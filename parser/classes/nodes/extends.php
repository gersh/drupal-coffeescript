<?php

namespace CoffeeScript;

class yy_Extends extends yy_Base
{
  public $children = array('child', 'parent');

  function constructor($child=NULL, $parent=NULL)
  {
    $this->child = $child;
    $this->parent = $parent;

    return $this;
  }

  function compile($options=NULL,$blah=NULL)
  {
    utility('hasProp');

    $tmp = yy('Call', yy('Value', yy('Literal', utility('extends'))), array($this->child, $this->parent));

    return $tmp->compile($options);
  }
}

?>
