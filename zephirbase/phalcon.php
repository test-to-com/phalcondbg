<?php
/*
  +--------------------------------------------------------------------------+
  | PHALCON PHP Debug Extension                                              |
  +--------------------------------------------------------------------------+
  | Copyright (c) 2015 pf at sourcenotes.org                                 |
  +--------------------------------------------------------------------------+
  | This source file is subject the MIT license, that is bundled with        |
  | this package in the file LICENSE, and is available through the           |
  | world-wide-web at the following url:                                     |
  | https://opensource.org/licenses/MIT                                      |
  +--------------------------------------------------------------------------+
 */
require_once 'zephir_globals.php';

// Define interesting PHALCON Constants
define('PHP_PHALCON_NAME', "phalcon");
define('PHP_PHALCON_VERSION', "2.0.8");
define('PHP_PHALCON_EXTNAME', "phalcon");
define('PHP_PHALCON_AUTHOR', "Phalcon Team and contributors");
define('PHP_PHALCON_ZEPVERSION', "0.8.0a");
define('PHP_PHALCON_DESCRIPTION', "Web framework delivered as a C-extension for PHP");

// Initialize Extension Globals
_globals_init([
  'db.escape_identifiers' => true,
  'db.force_casting' => false,
  'orm.events' => true,
  'orm.virtual_foreign_keys' => true,
  'orm.column_renaming' => true,
  'orm.not_null_validations' => true,
  'orm.exception_on_failed_save' => false,
  'orm.enable_literals' => true,
  'orm.late_state_binding' => false,
  'orm.enable_implicit_joins' => true,
  'orm.cast_on_hydrate' => false,
  'orm.ignore_unknown_columns' => false
]);

