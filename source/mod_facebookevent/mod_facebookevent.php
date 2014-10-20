<?php
/**
 * @package Module Facebook Group Events for Joomla! 3.x
 * @version $Id: mod_facebookevent.php 2014-10-20 Trefex $
 * @author Christophe Trefois (Trefex)
 * @copyright (C) 2014 - Trefex
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
	defined('_JEXEC') or die;
	//ini_set('display_errors', 'On');
	//error_reporting(E_ALL);

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

	FacebookSession::setDefaultApplication($app_id, $secret);
	$session = FacebookSession::newAppSession();

	$daystime = strtotime("-".$days." days");

	try {
		$request = new FacebookRequest($session, 'GET', '/'. $page_id . '/events?fields=description,name,end_time,id,start_time&since='.$daystime);
		$response = $request->execute();
		$graphObject = $response->getGraphObject();
	} catch ( Exception $e ) {
		echo 'Problem with request: '.$e->getMessage();
	}

	//print_r($graphObject->asArray());

	$html = '';
	$data2 = $graphObject->asArray();

	if(empty($data2['data'])) {
		$html .= '<p>There are currently no events planned.</p>';
	} else {
		$i = 0;
		foreach($data2['data'] as $toto)
			{
				$facebook_url = 'https://www.facebook.com/event.php?eid=' . $data2['data'][$i]->id;
				$start_time = date("F j, Y \@ g:i a", strtotime($data2['data'][$i]->start_time));

				/*if(!is_null($data2['data'][$i]->end_time)) {
					$end_time = date("F j, Y \@ g:i a", strtotime($data2['data'][$i]->end_time));
				}*/

				$desc = $data2['data'][$i]->description;
				$description = (strlen($desc) > 70) ? substr($desc,0,67).'...' : $desc;

				/*
				<a href="'.$facebook_url.'"><img src="'.$key['pic_square'].'" /></a>
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
