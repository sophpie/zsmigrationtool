<?php
function getConfigArray($file, $directive = null)
{
	$iniFile = fopen($file, 'r');
	//Delete commented lines
	$conf = array();
	while ($line = fgets($iniFile)){
		if (substr($line, 0,1) == ';') continue;
		if ($line == '' || $line== "\n") continue;
		if ($line == '[') continue;
		preg_match('@([^=]*)=([^=]*)@', $line,$elem);
		$conf[trim($elem[1])] = trim($elem[2]);
	}
	return $conf;
}

function compare($init,$current, $dir = null)
{
	$conf1 = getConfigArray($init);
	$conf2 = getConfigArray($current);
	$keys = array_merge(array_keys($conf1),array_keys($conf2));
	$csvline = 'directive;init;current' . "\n";
	$result = '';
	if ($dir) $result = $dir . DIRECTORY_SEPARATOR;
	$result .= basename($init,'.ini') . '_ini.csv';
	$isEmpty = true;
	foreach ($keys as $key)
	{
		if ($conf1[$key] == $conf2[$key]) continue;
		if ( ! $conf1[$key] ) $conf1[$key] = '<not exixts>';
		if ( ! $conf2[$key] ) $conf2[$key] = '<not exixts>';
		$csvline .= $key .';' . $conf1[$key] . ';' . $conf2[$key] . "\n";
		$isEmpty = false;
	}
	if ( ! $isEmpty) file_put_contents($result,$csvline);
}