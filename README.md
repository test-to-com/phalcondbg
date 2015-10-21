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

**IMPORTANT:** I use Ubuntu 14.04 as my development OS. I have not tried building this on any other Linux Distribution or Windows.

### Linux/Unix/Mac

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

If not, you will receive an error, that hopefully will explai what you need to do to rebuild.

**NOTE:** It's up to you to then install the extension into you version of PHP and add the PHALCON PHP Code to your applications namespace

### Windows

I have not tried to build the PHALCON PHP Code or Debug Extension in windows, but, since the extension uses ZEPHIR, it should be buildable on windows.
(TODO) I will try to work out a batch file that is capable of building this project in windows.
