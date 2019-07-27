<?php
	require_once(str_replace('//','/',dirname(__FILE__).'/') .'../../../admin/config.php');

	$backupPath = dirname(DIR_APPLICATION) . '/backup';
			
	if (!is_dir($backupPath)) {
		mkdir($backupPath);
	}
		
	require_once(DIR_SYSTEM . 'external/bk_zip.php');

	$localPath =  dirname(DIR_APPLICATION);
	$zipFile = $backupPath . '/' . date('dmY') .'_'.mt_rand(). '_backup.zip';

	$path_filter = array(
		'admin',
		'system',
		'download',
		'image',
		'vqmod'
	);

	$exclude = array();

	foreach ($path_filter as $path) {
		HZip::zipDir("$localPath/$path", $zipFile, $exclude);
	}

?>