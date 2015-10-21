Phalcon PHP and Debug Extension
===============================

This project is meant to create an "almost" fully debugable version of [PHALCON](https://phalconphp.com).

As you probable already know, PHALCON is implemented as a PHP Extension which makes it faster than other MVC frameworks. Unfortunately it also make it impossible for tools like [XDebug](http://xdebug.org/) to step through it's source.

This implied that, if you ran into a problem with PHALCON or needed to understand it's workings, you had to:

1. Refer to the PHALCON Documentation (on the site), or
2. Directly read the 'C' code for the extension

The conversion of PHALCON 2.x to [ZEP](http://zephir-lang.com/) made it easier to read the code. Unfortunately, in order to debug "into" PHALCON you had to mentally step through the ZEP code, taking into consideration the current Application state and changes made by PHALCON Code.

Not something I'm very good at, so the reason for this Project.

Using the [ZEP to PHP](https://github.com/test-to-com/zep-to-php) translator, I also wrote, I can now produce a PHP version of the PHALCON ZEP extension.

This PHP code can replace 99% of the PHALCON extension. The "1%" is also why I need, what I call, the "PHALCON Debug Extension". 

The PHALCON Debug Extension, compliments the PHALCON PHP code, by providing functions that are directly implemented, by the PHALCON Team, as "C" code (not ZEP).

This basically include 4 areas:

1. PHQL Statement Parser
2. The VOLT Template Parser
3. The Annotations Parser
4. The Extension Global Parameters

These 3 parsers, are fully implemented in 'C'.

The PHALCON Debug Extension replaces the original PHALCON Extension (i.e. you have to disable PHALCON extension in the php.ini) and the PHALCON PHP code has to be introduced into your applications namespace for debugging.

As of this moment, you can step through the PHALCON Code.

Build Instructions
------------------

**DISCLAIMER:** I use Ubuntu 14.04 as my development OS. I have not tried building this on any other Linux Distribution or Windows.

### Linux/Unix/Mac

#### Requirements
You will need, working:

* [ZEPHIR](http://zephir-lang.com/) last version tested was 0.8.x
* [ZEP to PHP](https://github.com/test-to-com/zep-to-php)

**NOTE:** Please follow these projects seperate install instruction (including their requirements). They have to be working for what follows to work!!!

This should basically work on any Linux Distribution.

```bash
git clone --depth 1 git@github.com:test-to-com/phalcondbg.git
cd cphalcondbg
./rebuild
```

If everything goes okay, you should get a message like:

```
*********************************************************************
PHALCON PHP and Debug Extension Built
You will find:
PHALCON PHP -> [..SOME PATH.../phalcondbg/build/php]
PHALCON Debug Extension -> [..SOME PATH.../phalcondbg/build/phalcondbg.so]

IMPORTANT:
- PHALCON PHP Requires the use of PHALCON DEBUG EXTENSION
- To use PHALCON PHP you have to disable the normal PHALCON Extension
*********************************************************************
                            THE END
*********************************************************************
```

If not, you will receive an error, that hopefully will explain what you need to do to rebuild.

**NOTE:** It's up to you to then install the extension into you version of PHP and add the PHALCON PHP Code to your applications namespace

### Windows

I have not tried to build the PHALCON PHP Code or Debug Extension in windows, but, since the extension uses ZEPHIR, it should be buildable on windows.

**TODO:** I will try to work out a batch file that is capable of building this project in Windows, for now, it's basically linux only.

Implementation Notes
--------------------

Why the ZEP patch files?

Well a series of patches was just to correct problems in PHALCON ZEP code, that are either automatically corrected by ZEPHIR, during it's conversion of ZEP to C, or small bugs in the actual ZEPHIR code.

Example:

1. http_response.zep.patch - removes a duplicate _use_ (use Phalcon\Mvc\ViewInterface). Create a duplicate _use_ in the PHP code and the corresponding error when run in PHP.
2. security_random.zep.patch - in the ZEP code a double '\' is used (return \\\\Sodium\\\\randombytes_buf(len);) Potentially a bug in ZEPHIR.

Another set of patches (mvc_model_query_lang.zep.patch and mvc_model_query.zep.patch) are to handle a hack by the PHALCON TEAM in the it's use of the PHQL Parser.

Why a hack?

Well to explain that, you have to know how these parsers are built and coded. 

PHALCON team uses [re2c](http://re2c.org/) to build the scanner and [lemon](http://www.hwaci.com/sw/lemon/) to build the parser.

The scanner produces a C include file (scanner.h) that contains IDENTIFIERS for each scan token (ex: #define PHQL_T_STARALL 352).

PHALCON uses a HACK/BUG/FEATURE in ZEPHIR that translates something like (taken from PHALCON mvc\model\query.zep):

```
protected final function _getCallArgument(array! argument) -> array
{
	if argument["type"] == PHQL_T_STARALL {
		return ["type": "all"];
	}
	return this->_getExpression(argument);
}
```

into a C code like:

```
PHP_METHOD(Phalcon_Mvc_Model_Query, _getCallArgument) {

	int ZEPHIR_LAST_CALL_STATUS;
	zval *argument_param = NULL, *_0;
	zval *argument = NULL;

	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &argument_param);

	argument = argument_param;


	zephir_array_fetch_string(&_0, argument, SL("type"), PH_NOISY | PH_READONLY, "phalcon/mvc/model/query.zep", 336 TSRMLS_CC);
	if (ZEPHIR_IS_LONG(_0, 352)) {
		zephir_create_array(return_value, 1, 0 TSRMLS_CC);
		add_assoc_stringl_ex(return_value, SS("type"), SL("all"), 1);
		RETURN_MM();
	}
	ZEPHIR_RETURN_CALL_METHOD(this_ptr, "_getexpression", NULL, 317, argument);
	zephir_check_call_status();
	RETURN_MM();

}
```

Notice that, PHQL_T_STARALL generated ZEPHIR_IS_LONG(_0, 352). How it generated this code? 

I don't know. But I assume that it is one of the following 2 options:

1. ZEPHIR directly references the "scanner.h" file (which would require some sort o C preprocessor function in ZEPHIR, which I didn't see) to extract the values, or
2. That this is handed coded modification of the ZEPHIR produced files.

In either case, ZEP to PHP translates this code (without the patch) to:

```
protected final function _getCallArgument($argument)
{
  if ($argument ['type'] == PHQL_T_STARALL) {
    return ['type' => 'all'];
  }
  return $this->_getExpression($argument) ;
}
```

Notice the simple use of PHQL_T_STARALL. Unfortunately, unless you 'define' PHQL_T_STARALL, some where in the PHP code, it will be interpreted by PHP as string and not produce the correct results.

My solution to the problem was, to import the constants defined in "scanner.h", translated by hand, into mvc/model/query/lang.zep and then replace PHQL_T_* by Lang::PHQL_T_*.

Why do I think that PHALCON TEAM solution is hack?

Because it bypasses ZEPHIR's compile time type checking, which was one of the stated goals of the ZEPHIR Languange.

**IMPORTANT:** I have NOT applied similar patches for Volt and Annotations Parser, BECAUSE I HAVEN'T TESTED THAT CODE. BUT, A SET OF SIMILAR PATCHES WILL PROBABLY BE REQUIRED in order to use Volt and Annotations Parsers without error.

The last important patch was to loader.zep.

This patch just simply modifies the autoloader so that it searches for missing classes, in files, with the class name in lowercase (rather than just simply using an exact match of the class name, case included).
