<?php
	$currDir = dirname(__FILE__);
	require("{$currDir}/incCommon.php");

	$GLOBALS['page_title'] = $Translation['app documentation'];
	include("{$currDir}/incHeader.php");

	$app_title = 'OIM';
	
	include("{$currDir}/incFooter.php");
