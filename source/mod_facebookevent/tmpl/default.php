<?php
/**
* @package Module Facebook Group Events for Joomla! 3.x
* @version $Id: default.php 2014-10-20 Trefex $
* @author Christophe Trefois (Trefex)
* @copyright (C) 2014 - Trefex
* @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
	defined('_JEXEC') or die;

	use Facebook\FacebookSession;
	use Facebook\FacebookRequest;
	use Facebook\GraphObject;
	use Facebook\FacebookRequestException;
	use Facebook\FacebookRedirectLoginHelper;

	try {
		$request = new FacebookRequest($session, 'GET', '/'. $page_id . '/?fields=link');
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
	} catch ( Exception $e ) {
		echo 'Error: '.$e->getMessage();
	}

	$fbname = $graphObject->asArray();
	//print_r($fbname);
	$fbname = $fbname['link'];
	$toShow = '
		<div align="right">
		<a href="'. $fbname . '?sk=events">More events...</a>
		</div>
	';

	echo $toShow;
	?>
