<?php

namespace CoffeeScript;

class yy_Index extends yy_Base
{
  public $children = array('index');

  function constructor($index=NULL)
  {
    $this->index = $index;

    return $this;
  }

  function compile($options=NULL, $level = NULL)
  {
    return ($this->proto ? '.prototype' : '').'['.$this->index->compile($options, LEVEL_PAREN).']';
  }

  function is_complex()
  {
    return $this->index->is_complex();
  }
}

?>
