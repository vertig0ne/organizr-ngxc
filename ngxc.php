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

###############
## INTERNAL FUNCTIONS
###############

function _ngxcTypeOptions() {
        $_ngxcTypes = array(
                'none' => 'None',
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
	$data = array();
        foreach ($tabs["tabs"] as $tab) {
                if ($tab['name'] != "Homepage" && $tab['name'] != "Settings") {
                        $name = strtoupper(str_replace(' ','_',$tab['name']));
                        $data[$tab['name']] = array(
                                array(
                                        'type' => 'select',
                                        'name' => 'NGXC_'.$name.'_TYPE',
                                        'label' => 'Type of Proxy',
                                        'value' => $GLOBALS['NGXC_'.$name.'_TYPE'] ?: 'None',
                                        'options' => $types
                                ),
                                array(
                                        'type' => 'input',
                                        'name' => 'NGXC_'.$name.'_URL',
                                        'label' => 'Proxy URL',
                                        'value' => $GLOBALS['NGXC_'.$name.'_URL'] ?: '',
                                )
                        );
                }
        }
	return $data;
}

function _ngxcWriteTabConfig($tab) {
        $name = strtoupper(str_replace(' ','_',$tab["name"]));
        $nameLower = strtolower(str_replace(' ','_',$tab["name"]));
        $type = $GLOBALS['NGXC_'.$name.'_TYPE'];
        $path = $tab["url"];
        $url = $GLOBALS['NGXC_'.$name.'_URL'];
        switch($type) {
                case "sonarr":
                case "radarr":
                        _ngxcWriteTabSonarrConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "lidarr":
                        _ngxcWriteTabLidarrConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "lidarr":
                        _ngxcWriteTabAirSonicConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "airsonic":
                        _ngxcWriteTabAirSonicConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "calibre-web":
                        _ngxcWriteTabCalibreWebConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "deluge":
                        _ngxcWriteTabDelugeConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "guacamole":
                        _ngxcWriteTabGuacamoleConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "jackett":
                        _ngxcWriteTabJackettConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "mylar":
                        _ngxcWriteTabMylarConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "netdata":
                        _ngxcWriteTabNetdataConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "nowshowing":
                        _ngxcWriteTabNowshowingConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "nzbget":
                        _ngxcWriteTabNzbGetConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "nzbhydra":
                        _ngxcWriteTabNzbHydraConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "ombi":
                        _ngxcWriteTabOmbiConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "plex":
                        _ngxcWriteTabPlexConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "qbittorrent":
                        _ngxcWriteTabQbittorrentConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "tautulli":
                        _ngxcWriteTabTautulliConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "transmission":
                        _ngxcWriteTabTransmissionConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "ubooquity":
                        _ngxcWriteTabUbooquityConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
                case "rutorrent":
                        _ngxcWriteTabRutorrentConfig($url, $path, $nameLower, $tab["group_id"]);
                        break;
        }
}

###############
## CONFIGURATION WRITERS
###############

function _ngxcWriteTabSonarrConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_pass $url/;
                proxy_set_header X-Real-IP \$remote_addr; 
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_set_header X-Forwarded-Proto \$scheme;
                proxy_http_version 1.1;
                proxy_no_cache \$cookie_session;

                proxy_set_header Accept-Encoding \"\";
                sub_filter '<//head>' '<link rel=\"stylesheet\" type=\"text/css\" href=\"//rawgit.com/iFelix18/Darkerr/master/darkerr.css\"></head>';
                sub_filter_once on;

                location ".$path."api {
                        auth_request off;
                        proxy_pass $url/api;
                }
        }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabLidarrConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_pass $url/;
                proxy_set_header X-Real-IP \$remote_addr; 
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_set_header X-Forwarded-Proto \$scheme;
                proxy_http_version 1.1;
                proxy_no_cache \$cookie_session;
                
                location ".$path."api {
                        auth_request off;
                        proxy_pass $url\api;
                }
        }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabAirSonicConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_set_header X-Real-IP         \$remote_addr;
                proxy_set_header X-Forwarded-For   \$proxy_add_x_forwarded_for;
                proxy_set_header X-Forwarded-Proto https;
                proxy_set_header X-Forwarded-Host  \$http_host;
                proxy_set_header Host              \$http_host;
                proxy_max_temp_file_size           0;
                proxy_pass                         $url/;
                proxy_redirect                     http:// https://;
        }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabCalibreWebConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_bind              \$server_addr;
                proxy_pass              $url/;
                proxy_set_header        Host            \$http_host;
                proxy_set_header        X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_set_header        X-Scheme        \$scheme;
                proxy_set_header        X-Script-Name   $path;
        }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabDelugeConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_pass $url/;
                proxy_set_header X-Deluge-Base \"$path\";
                proxy_set_header Host \$host;
                proxy_set_header X-Real-IP \$remote_addr;
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_set_header X-Forwarded-Proto https;
                proxy_redirect  http://  \$scheme://;
                proxy_http_version 1.1;
                proxy_set_header Connection \"\";
                proxy_cache_bypass \$cookie_session;
                proxy_no_cache \$cookie_session;
                proxy_buffers 32 4k;
                add_header X-Frame-Options SAMEORIGIN;
        }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabGuacamoleConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_pass $url/;
                proxy_buffering off;
                proxy_set_header Upgrade \$http_upgrade;
                proxy_set_header Connection \$http_connection;
                proxy_set_header X-Real-IP \$remote_addr;
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_set_header X-Forwarded-Proto \$scheme;
                proxy_http_version 1.1;
                proxy_no_cache \$cookie_session;
        }";

      file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabJackettConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_pass $url/;
                proxy_set_header Host \$host;
                proxy_set_header X-Real-IP \$remote_addr;
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_set_header X-Forwarded-Proto https;
                proxy_redirect  http://  \$scheme://;
                proxy_http_version 1.1;
                proxy_set_header Connection \"\";
                proxy_cache_bypass \$cookie_session;
                proxy_no_cache \$cookie_session;
                proxy_buffers 32 4k;
              }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabMylarConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_pass $url/;
                proxy_set_header Host \$host;
                proxy_set_header X-Real-IP \$remote_addr;
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_set_header X-Forwarded-Proto \$scheme;
        }";

      file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabNetdataConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_redirect off;
                proxy_set_header Host \$host;
                proxy_set_header X-Forwarded-Host \$host;
                proxy_set_header X-Forwarded-Server \$host;
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_http_version 1.1;
                proxy_pass_request_headers on;
                proxy_set_header Connection \"keep-alive\";
                proxy_store off;
                proxy_pass $url/;
        }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabNowshowingConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_set_header Host \$host;
                proxy_set_header X-Forwarded-Host \$host;
                proxy_set_header X-Forwarded-Server \$host;
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_http_version 1.1;
                proxy_pass_request_headers on;
                proxy_set_header Connection \"keep-alive\";
                proxy_pass $url/;
        }";

      file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabNzbGetConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_pass $url/;
                proxy_set_header X-Real-IP \$remote_addr;
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_set_header X-Forwarded-Proto \$scheme;
                proxy_http_version 1.1;
                proxy_no_cache \$cookie_session;
                proxy_set_header Accept-Encoding \"\";
                sub_filter '</head>' '<link rel=\"stylesheet\" type=\"text/css\" href=\"https://rawgit.com/ydkmlt84/DarkerNZBget/develop/nzbget_custom_darkblue.css\"></head>';
                sub_filter_once on;
        }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabNzbHydraConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_pass $url/;
                proxy_set_header X-Real-IP \$remote_addr;
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_set_header X-Forwarded-Proto \$scheme;
                proxy_http_version 1.1;
                proxy_no_cache \$cookie_session;
        }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabOmbiConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_pass  $url/;
                proxy_cache_bypass \$http_upgrade;
                proxy_set_header Connection keep-alive;
                proxy_set_header Upgrade \$http_upgrade;
                proxy_set_header X-Forwarded-Host \$server_name;
                proxy_set_header X-Forwarded-Ssl on;
                proxy_set_header X-Real-IP \$remote_addr;
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_set_header X-Forwarded-Proto \$scheme;
                proxy_http_version 1.1;
                proxy_no_cache \$cookie_session;
        }
        location /dist/ {
                return 301 $path\$request_uri;
        }";
        
      file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabPlexConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                return 301 /web;
        }
        location ~ ^/(\?(?:.*)(X-Plex-Device=)|web|video|photo|library|web|status|system|updater|clients|:|playQueues)(.*) {
                proxy_pass $url;
                proxy_redirect  $url /;
                proxy_set_header X-Real-IP \$remote_addr;
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_redirect off;
                proxy_set_header Host \$host;
                proxy_http_version 1.1;
                proxy_set_header Upgrade \$http_upgrade;
                proxy_set_header Connection \"upgrade\";
                proxy_read_timeout 36000s;
                proxy_pass_request_headers on;
        }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabQbittorrentConfig($url, $path, $name, $group) {
        $data = "
        location ~ $path(?<url>.*) {
                auth_request /auth-$group;
                proxy_pass  $path/\$url;
                proxy_set_header X-Forwarded-Host \$host;
                proxy_hide_header Referer;
                proxy_hide_header Origin;
                proxy_set_header Referer '';
                proxy_set_header Origin '';
                add_header X-Frame-Options \"SAMEORIGIN\";
        }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabRutorrentConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_pass $url/;
                proxy_set_header Host \$server_name;
                proxy_set_header X-Real-IP \$remote_addr;
                proxy_set_header X-Forwarded-Host \$server_name;
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_redirect off;
        }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabTautulliConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_pass $url/;
                proxy_set_header X-Forwarded-Host \$server_name;
                proxy_set_header X-Real-IP \$remote_addr;
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_set_header X-Forwarded-Proto \$scheme;
                proxy_http_version 1.1;
                proxy_no_cache \$cookie_session;
                location $path\api/ {
                        auth_request off;
                        proxy_pass $url/api/;
                }
        }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabTransmissionConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_set_header X-Real-IP \$remote_addr;
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
                proxy_set_header Host \$http_host;
                proxy_set_header X-NginX-Proxy true;
                proxy_http_version 1.1;
                proxy_set_header Connection \"\";
                proxy_pass_header X-Transmission-Session-Id;
                add_header Front-End-Https   on;
                location ".$path."rpc {
                  proxy_pass $url/rpc;
                }
                location ".$path."web {
                  proxy_pass $url/web;
                }
                location ".$path."upload {
                  proxy_pass $url/upload;
                }
                location /transmission {
                  return 301 https://\$server_name".$path."web;
                }
        }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

function _ngxcWriteTabUbooquityConfig($url, $path, $name, $group) {
        $data = "
        location $path {
                auth_request /auth-$group;
                proxy_pass $url/;
                proxy_set_header Host \$host;
                proxy_set_header X-Real-IP \$remote_addr;
                proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        }";

        file_put_contents($GLOBALS['dbLocation'].'proxy'.'/'.$name.'.conf', $data);
}

###############
## PUBLIC FUNCTIONS
###############

function NGXCGetSettings() {
	$data = _ngxcGetTabs();
        $data['Actions'] = array(
		array(
			'type' => 'button',
                        'label' => 'Write Config',
                        'class' => 'ngxc-write-config',
                        'icon' => 'fa fa-save',
                        'text' => 'Write Config'
               )
        );
	return $data;
}

function NGXCWriteConfig() {
        if (!file_exists($GLOBALS['dbLocation'].'proxy')) {
                mkdir($GLOBALS['dbLocation'].'proxy', 0777, true);
        }
	$tabs = _ngxcGetAllTabs();
	foreach ($tabs["tabs"] as $tab) {
                _ngxcWriteTabConfig($tab);
        }
        $file_contents = "location ~ /auth-(.*) {
                internal;
                rewrite ^ /api/?v1/auth&group=$1;
        }\n";
        $file_contents .= "include ".$GLOBALS['dbLocation']."proxy/*.conf;\n";

        file_put_contents($GLOBALS['dbLocation'].'ngxc.conf', $file_contents);
}