<?php
/**
 * Analyse php.ini file
 * 
 * Usage:
 * 		--current=/..		current /etc of your zend server
 * 		--prev=/...			saved /etc of your previous zend server version
 * 		--diff=1 			if you want to have only the diff
 */
$etcdir_prev = $param['prev'];
$etcdir_current = $param['current'];
$diff = (bool)$param['diff'];

$prevConfig = getConfigArray($etcdir_prev . DIRECTORY_SEPARATOR . 'php.ini');
$currentConfig = getConfigArray($etcdir_current . DIRECTORY_SEPARATOR . 'php.ini');

$keys = array_merge(array_keys($prevConfig),array_keys($currentConfig));
$phpArray = array();
foreach ($keys as $key)
{
	if ( ! array_key_exists($key, $phpArray)) 
		$phpArray[$key] = array('prev' => '<no value>', 'current' => '<no value>');
	if ($prevConfig[$key]) $phpArray[$key]['prev'] = $prevConfig[$key];
	if ($currentConfig[$key]) $phpArray[$key]['current'] = $currentConfig[$key];
}

foreach ($phpArray as $key => $values){
	if ($diff && ($values['prev'] == $values['current'])) continue;
	echo sprintf('| %-\'-60.60s | %-20.20s | %-20.20s |',$key,$values['prev'],$values['current']);
	echo  "\n";
}