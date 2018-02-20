<?php
$GLOBALS['plugins'][]['ngxc'] = array(
    'name'=>'NGXConfigurator',
    'author'=>'Vertig0ne',
    'category'=>'Web Server',
    'link'=>'https://github.com/vertig0ne/organizr-ngxc',
    'idPrefix'=>'ngxc',
    'configPrefix'=>'ngxc',
    'version'=>'0.0.2',
    'image'=>'plugins/images/ngxc.png',
    'settings'=>true,
    'homepage'=>false
);

$_ngxcTypes = array(
        'airsonic' => 'AirSonic',
        'calibre-web' => 'Calibre-Web',
        'deluge' => 'Deluge',
        'guacamole' => 'Guacamole',
        'jackett' => 'Jackett',
        'lidarr' => 'Lidarr',
        'mylar' => 'Mylar',
        'netdata' => 'NetData',
        'nowshowing' => 'NowShowing',
        'nzbget' => 'NZBGet',
        'nzbhydra' => 'NZBHydra',
        'ombi' => 'Ombi',
        'plex' => 'Plex',
        'qbittorrent' => 'qbittorrent',
        'radarr' => 'Radarr',
        'rutorrent' => 'rUtorrent',
        'sonarr' => 'Sonarr',
        'tautulli' => 'Tautulli',
        'ubooquity' => 'Ubooquity'
);

function _ngxcTypeOptions() {
        $data = array();
        $t = 0;
        foreach ($_ngxcTypes as $key => $value) {
                $data[$t] = array(
                        "name" => $value,
                        "value" => $key
                );
                $t++;
        }
        return $data;
}

function _ngxcGetAllTabs() {
	return allTabs();
}

function _ngxcGetTabs() {
	$tabs = _ngxcGetAllTabs();
	$types = _ngxcTypeOptions();
        $t = 0;
	$data = array();
        foreach ($tabs["tabs"] as $tab) {
                if ($tab['name'] != "Homepage" && $tab['name'] != "Settings") {
                        $name = strtoupper(str_replace(' ','_',$tab['name']));
                        $data[$t] = array(
                                array(
                                        'type' => 'select',
                                        'name' => 'NGXC_'.$name.'_TYPE',
                                        'label' => 'Type of Proxy',
                                        'value' => $GLOBALS['NGXC_'.$name.'_TYPE'] ?: '',
                                        'options' => $types
                                ),
                                array(
                                        'type' => 'input',
                                        'name' => 'NGXC_'.$name.'_URL',
                                        'label' => 'Proxy URL',
                                        'value' => $GLOBALS['NGXC_'.$name.'_URL'] ?: '',
                                )
                        );
                        $t++;
                }
        }
	return $data;
}

function _ngxcWriteTabConfig($tab) {
        $name = strtoupper(str_replace(' ','_',$tab['name']));
        $nameLower = strtolower(str_replace(' ','_',$tab['name']));
        $type = $GLOBALS['NGXC_'.$name.'_TYPE'];
        $path = $tab['path'];
        $url = $GLOBALS['NGXC_'.$name.'_URL'];
        switch($type) {
                case 'sonarr':
                        _ngxcWriteTabSonarrConfig($url, $path, $name, $tab["group_id"]);
                break;
        }
}

function _ngxcWriteTabSonarrConfig($url, $path, $name, $group) {
        $data = "location ".$path." {
            auth_request /auth-".$group.";
            proxy_pass ".$url.";
            
            proxy_set_header X-Real-IP $remote_addr; 
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto $scheme;
            proxy_http_version 1.1;
            proxy_no_cache $cookie_session;
            
            proxy_set_header Accept-Encoding \"\";
            sub_filter
                '<//head>'
                '<link rel=\"stylesheet\" type=\"text//css\" href=\"//rawgit.com/iFelix18/Darkerr/master/darkerr.css\"></head>';
            sub_filter_once on;
            
            location ".$path."api {
                auth_request off;
                proxy_pass ".$url."/api;
            }
        }";

        file_put_contents($GLOBALS['dbLocation'].DIRECTORY_SEPERATOR.'proxy'.DIRECTORY_SEPERATOR.$name.'.conf', $data);
}

function NGXCGetSettings() {
	$data = array();
	$data['Tabs'] = _ngxcGetTabs();
        $data['Actions'] = array(
		array(
			'type' => 'button',
                        'label' => 'Write Config',
                        'class' => 'NGXC-WriteConfig',
                        'icon' => 'fa fa-save',
                        'text' => 'Write Config'
               )
        );
	return $data;
}

function NGXCWriteConfig() {
	$file_contents = "";
	$tabs = _ngxcGetAllTabs();
	foreach ($tabs as $tab) {
                _ngxcWriteTabConfig($tab);
		$name = str_replace('NGXC_', '', $tab['name']);
		$file_contents .= "set $" .$name." ".$tab['value'].";\n";
        }
        $file_contents .= "\ninclude " .$GLOBALS['dbLocation']."/proxy/*.conf;\n";

        file_put_contents($GLOBALS['dbLocation'].DIRECTORY_SEPERATOR.'ngxc.conf', $file_contents);
}