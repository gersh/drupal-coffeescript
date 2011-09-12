# Introduction
Cofeescript is an improved version of Javascript. This module allows to use Coffeescript in your modules, and include Coffeescript files in your Drupal pages. 

Cofeescript is an improved version of Javascript. This module allows to use Coffeescript in your modules, and include Coffeescript files in your Drupal pages. This uses a PHP interpreter to compile the CoffeeScript into javascript.

# Usage

coffeescript_add_cs($data,$type)

If $type is 'file', then data is the coffeescript file. If $type is 'inline', it will compile the iniline code.

# About Coffeescript

The CoffeeScript syntax aims to simplify Javascript. See http://jashkenas.github.com/coffee-script/. For example

	var a=3;

becomes
	a = 3

You can write functions as (x)->x instead of function(x) { return(x); }

# Author
This was written by Gershon Bialer based upon https://github.com/alxlit/coffeescript-php.


