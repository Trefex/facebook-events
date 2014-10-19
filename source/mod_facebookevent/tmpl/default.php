<?php
	defined('_JEXEC') or die;
	use Facebook\FacebookSession;
	use Facebook\FacebookRequest;
	use Facebook\GraphObject;
	use Facebook\FacebookRequestException;
	use Facebook\FacebookRedirectLoginHelper;
	//$facebookName = $facebook->api('/' . $params->get('GroupID'), 'GET');
	try {
		// validate the access_token to make sure it's still valid
		$request = new FacebookRequest($session, 'GET', '/275000555887801/?fields=link');
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
	} catch ( Exception $e ) {
		// catch any exceptions and set the sesson null
		$session = null;
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