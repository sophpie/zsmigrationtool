<?php
require_once 'phar://zsmigrationtool.phar/lib/functions.php';
error_reporting(E_ERROR | E_PARSE);

//Parsing script parameters
$param = array();
$c = 1;
$script = array_shift($argv);
$command = array_shift($argv);
foreach ($argv as $arg)
{
	preg_match('@--([a-zA-Z_-\s]*)=([^=-]*)@', $arg, $temp);
	if ($temp[1] == "" || $temp[2] == "") continue;
	$param[trim($temp[1])] = trim($temp[2]);
}
//Dispatch to command file
$commandFile =  'phar://zsmigrationtool.phar/command/' . $command .'.php';
if ( ! file_exists($commandFile)){
	echo 'Unknown command ' . $command ."\n";
	echo 'Use <php zsmigrationtool.phar help> to get help';
}
else require_once $commandFile;
__HALT_COMPILER();