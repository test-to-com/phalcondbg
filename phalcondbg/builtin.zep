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

function phalcon_phql_parse_phql(var phql) {
  return phql_parse_phql(phql);
}
   
function phalcon_volt_parse_view(string viewCode, string currentPath) {
  return phvolt_parse_view(viewCode, currentPath);
}

function phalcon_annotations_parse(string comment, string filePath, int line) {
  return phannot_parse_annotations(comment,filePath,line);
}

namespace PhalconDBG;

%{
// Include Missing Headers
#include "kernel/operators.h"
#include "kernel/memory.h"
#include "kernel/fcall.h"
}%

interface BuiltIN {
  
}