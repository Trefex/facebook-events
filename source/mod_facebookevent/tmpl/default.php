<?php
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
