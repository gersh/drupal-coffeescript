<?php

namespace CoffeeScript;

class yy_Range
{
  public $children = array('from', 'to');

  function constructor($from = NULL, $to = NULL, $tag = NULL)
  {
    $this->from = $from;
    $this->to = $to;
    $this->exclusive = $tag === 'exclusive';
    $this->equals = $this->exclusive ? '' : '=';

    return $this;
  }

  function compile_array($options)
  {
    if ($this->from_num && $this->to_num && abs($this->from_num - $this->to_num) <= 20)
    {
      $range = range((int) $this->from_num, (int) $this->to_num);

      if ($this->exclusive)
      {
        array_pop($range);
      }

      return '['.implode(', ', $range).']';
    }

    $idt = $this->tab + TAB;
    $i = $options['scope']->free_variable('i');
    $result = $options['scope']->free_variable('result');
    $pre = "\n{$idt}{$result} = [];";

    if ($this->from_num && $this->to_num)
    {
      $options['index'] = $i;
      $body = $this->compile_simple($options);
    }
    else
    {
      $vars = "{$i} = {$this->from}".($this->to !== $this->to_var ? ", {$this->to}" : '');
      $cond = "{$this->from_var} <= {$this->to_var}";
      $body = "var {$vars}; {$cond} ? {$i} <{$this->equals} {$this->to_var} : {$i} >{$this->equals} {$this->to_var}; {$cond} ? {$i}++ : {$i}--";
    }

    $post = "{ {$result}.push({$i}); }\n{$idt}return {$result};\n{$options['indent']}";

    return "(function() {{$pre}\n{$idt}for ({$body}){$post}}.apply(this, arguments)";
  }

  function compile_node($options)
  {
    $this->compile_variables($options);

    if ( ! ($options['index']))
    {
      return $this->compile_array($options);
    }

    if ($this->from_num && $this->to_num)
    {
      return $this->compile_simple($options);
    }

    $idx = del($options, 'index');
    $step = del($options, 'step');
    $vars = "{$idx} = {$this->from}".($this->to !== $this->to_var ? ", {$this->to}" : '');
    $cond = "{$this->from_var} <= {$this->to_var}";
    $compare = "{$cond} ? {$idx} <{$this->equals} {$this->to_var} : {$idx} >{$this->equals} {$this->to_var}";
    $incr = $step ? "{$idx} += ".($step->compile($options)) : "{$cond} ? {$idx}++ : {$idx}==";

    return "{$vars}; {$compare}; {$incr}";
  }

  function compile_simple($options)
  {
    list($from, $to) = array((int) $this->from_num, (int) $this->to_num);

    $idx = del($options, 'index');
    $step = del($options, 'step');

    if ($step)
    {
      $step .= "{$idx} += ".($step->compile($options));
    }

    if ($from <= $to)
    {
      return "{$idx} = {$from}; {$idx} <{$this->equals} {$to}; ".($step ? $step : "{$idx}++");
    }
    else
    {
      return "{$idx} = {$from}; {$idx} >{$this->equals} {$to}; ".($step ? $step : "{$idx}--");
    }
  }

  function compile_variables($options)
  {
    $options = array_merge($options, array('top' => TRUE));

    list($this->from, $this->from_var) = $this->from->cache($options, LEVEL_LIST);
    list($this->to, $this->to_var) = $this->to->cache($options, LEVEL_LIST);

    preg_match_all(SIMPLENUM, $this->from_var, $this->from_num);
    preg_match_all(SIMPLENUM, $this->to_var, $this->to_num);

    $parts = array();

    if ($this->from !== $this->from_var)
    {
      $parts[] = $this->from;
    }

    if ($this->to !== $this->to_var)
    {
      $parts[] = $this->to;
    }

    return $parts;
  }
}

?>
