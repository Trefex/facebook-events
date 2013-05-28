<?php
	defined('_JEXEC') or die;
	
	require_once 'api/facebook.php';
	
	$app_id = $params->get('AppKey');
    $secret = $params->get('AppSecret');
    $page_id = $params->get('GroupID');

	$fbConfig = array();
    $fbConfig['appId'] = $app_id;
    $fbConfig['secret'] = $secret;
    $fbConfig['fileUpload'] = false; // optional

    $facebook = new Facebook($fbConfig);

	$fql = 'SELECT 
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
		
		ORDER BY start_time ASC 
		LIMIT ' . $params->get('numEvents') . '
';


    $ret_obj = $facebook->api(array(
        'method' => 'fql.query',
        'query' => $fql,
    ));
	
    $html = '';                            

    foreach($ret_obj as $key)
    {
		
        $facebook_url = 'https://www.facebook.com/event.php?eid=' . $key['eid'];
		
        $start_time = date("F j, Y \@ g:i a", strtotime($key['start_time']));
		
		if(!is_null($key['end_time'])) {
			$end_time = date("F j, Y \@ g:i a", strtotime($key['end_time']));
		}
		
		$description = (strlen($key['description']) > 70) ? substr($key['description'],0,67).'...' : $key['description'];
		
		/*
		//<a href="'.$facebook_url.'"><img src="'.$key['pic_square'].'" /></a>
				
				<p class="time">'.$end_time.'</p>
		*/

        $html .= '
            <div class="event' . $params->get('moduleclass_sfx') . '">
                <span>
                    <a href="'.$facebook_url.'">'.$key['name'].'</a>
                    <p class="time">'.$start_time.'</p>
                    <p style="font-size:0.9em; font-style:italic;">' . $description . '</p>
                </span>
            </div>
        ';
    }

    echo $html;
	
	$layout = $params->get('layout', 'default');
	require JModuleHelper::getLayoutPath('mod_facebookevent', $layout);
?>