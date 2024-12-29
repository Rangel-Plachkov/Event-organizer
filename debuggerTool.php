<?php
function dd()
{
    $debug = debug_backtrace();
    echo '<pre>';
    array_map(function($x) { var_dump($x); }, func_get_args());
    echo "\n\n==================PATH====================\n\n";
    var_dump(['file' => $debug[0]['file'], 'line' => $debug[0]['line']]);
    die;
}