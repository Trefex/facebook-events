<?php
	defined('_JEXEC') or die;
	
	$facebookName = $facebook->api('/' . $params->get('GroupID'), 'GET');
	
	$toShow = '
		<div align="right">
		<a href="'. $facebookName['link'] . '?id=' . $params->get('GroupID') . '&sk=events">More events...</a>
		</div>
	';
	
	echo $toShow;
	?>