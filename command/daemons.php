<?php
/**
 * Usage:
 * 		--current=/..		current /etc of your zend server
 * 		--prev=/...			saved /etc of your previous zend server version
 * 		--diff=1 			if you want to have only the diff
 * 		--extension=name	if you want information about a specific extension
 */
$etcdir_prev = $param['prev'];
$etcdir_current = $param['current'];
$diff = (bool)$param['diff'];
$ext =  $param['extension'] . '.ini';
//merge directory list
$extensionArray = array();
foreach (scandir($etcdir_current) as $file){
	if ($file == '.' || $file == '..') continue;
	if (substr($file,-4) != '.ini') continue;
	if ($file == 'php.ini') continue;
	$filePath = $etcdir_current . DIRECTORY_SEPARATOR . $file;
	if ( ! array_key_exists($file, $extensionArray)) $extensionArray[$file] = array();
	foreach (getConfigArray($filePath) as $key => $value) {
		if ( ! array_key_exists($key, $extensionArray[$file]))  $extensionArray[$file][$key] = array('prev' => '<no value>');
		$extensionArray[$file][$key]['current'] = $value;
	}
}
foreach (scandir($etcdir_prev) as $file){
	if ($file == '.' || $file == '..') continue;
	if (substr($file,-4) != '.ini') continue;
	if ($file == 'php.ini') continue;
	$filePath = $etcdir_prev . DIRECTORY_SEPARATOR . $file;
	if ( ! array_key_exists($file, $extensionArray)) $extensionArray[$file] = array();
	foreach (getConfigArray($filePath) as $key => $value) {
		if ( ! array_key_exists($key, $extensionArray[$file]))  $extensionArray[$file][$key] = array('current' => '<no value>');
		$extensionArray[$file][$key]['prev'] = $value;
	}
}
foreach ($extensionArray as $extension => $directives){
	if (($param['extension'] != '') && ($ext != $extension)) continue;
	echo "\n" . $extension ."\n";
	foreach ($directives as $key => $values){
		if ($diff && ($values['prev'] == $values['current'])) continue;
		echo sprintf('| %-\'-60.60s | %-20.20s | %-20.20s |',$key,$values['prev'],$values['current']);
		echo  "\n";
	}
}
