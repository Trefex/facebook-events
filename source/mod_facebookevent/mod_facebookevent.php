<?php
	defined('_JEXEC') or die;
	ini_set('display_errors', 'On');
	error_reporting(E_ALL);
	
	//require_once 'api/facebook.php';
	require_once 'facebook-php-sdk/autoload.php';
	use Facebook\FacebookSession;
	use Facebook\FacebookRequest;
	use Facebook\GraphObject;
	use Facebook\FacebookRequestException;
	use Facebook\FacebookRedirectLoginHelper;
	
	date_default_timezone_set('Europe/Brussels');

	$app_id = $params->get('AppKey');
    $secret = $params->get('AppSecret');
    $page_id = $params->get('GroupID');
	$days = $params->get('daysPast');

//	$fbConfig = array();
//    $fbConfig['appId'] = $app_id;
//    $fbConfig['secret'] = $secret;
//    $fbConfig['fileUpload'] = false; // optional

//    $facebook = new Facebook($fbConfig);

	FacebookSession::setDefaultApplication($app_id, $secret);
	$session = FacebookSession::newAppSession();
	
	$daystime = strtotime("-".$days." days");
	
	//$session = new FacebookSession($access_token);
	
	try {
		// validate the access_token to make sure it's still valid
		$request = new FacebookRequest($session, 'GET', '/275000555887801/events?fields=description,name,end_time,id,start_time&since='.$daystime);
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
	} catch ( Exception $e ) {
		// catch any exceptions and set the sesson null
		$session = null;
		echo 'No session: '.$e->getMessage();
	}
	
	//print_r($graphObject->asArray());

	//$start_time = mktime() - ($days * 24 * 60 * 60);
	/*$fql = 'SELECT 
			eid, 
			name, 
			pic_square, 
			creator,
			start_time,
			description,
			end_time
		FROM event 
		WHERE eid IN 
			(SELECT eid 
				FROM event_member 
				WHERE uid="'.$page_id.'"
			) 
		
		AND start_time >= '. $start_time . '
		ORDER BY start_time ASC 
		LIMIT ' . $params->get('numEvents') . '
';*/


    /*$ret_obj = $facebook->api(array(
        'method' => 'fql.query',
        'query' => $fql,
    ));
	*/
	
//	$ret_obj = facebook->api('/275000555887801/events?since=1411939985');
	$html = '';                       
//echo $ret_obj;	
	
	//echo 'hello';
	//print_r($graphObject);
	$data2 = $graphObject->asArray();
	//echo '<pre>' . print_r( $data2, 1 ) . '</pre>';
	//print_r($data2['data'][0]->id);
	//print_r();
	
	
	if(empty($data2['data'])) {
		$html .= '<p>There are currently no events planned.</p>';
	} else {
	$i = 0;
	//foreach($data as $key)
	foreach($data2['data'] as $toto)
		{
			//print_r($data2['data'][$i]->id);
			$facebook_url = 'https://www.facebook.com/event.php?eid=' . $data2['data'][$i]->id;
		
			//echo $facebook_url;
			
			$start_time = date("F j, Y \@ g:i a", strtotime($data2['data'][$i]->start_time));
			//echo $start_time;
			
			/*if(!is_null($data2['data'][$i]->end_time)) {
				$end_time = date("F j, Y \@ g:i a", strtotime($data2['data'][$i]->end_time));
			}*/
			
			$desc = $data2['data'][$i]->description;
			$description = (strlen($desc) > 70) ? substr($desc,0,67).'...' : $desc;
			
			/*
			//<a href="'.$facebook_url.'"><img src="'.$key['pic_square'].'" /></a>
					
					<p class="time">'.$end_time.'</p>
			*/

			$html .= '
				<div class="event' . $params->get('moduleclass_sfx') . '">
					<span>
						<a href="'.$facebook_url.'">'.$data2['data'][$i]->name.'</a>
						<p class="time">'.$start_time.'</p>
						<p style="font-size:0.9em; font-style:italic;">' . $description . '</p>
					</span>
				</div>
			';
			$i++;
		}
	}

    echo $html;
	
	$layout = $params->get('layout', 'default');
	require JModuleHelper::getLayoutPath('mod_facebookevent', $layout);
?>