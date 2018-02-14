<?php
$GLOBALS['plugins'][]['NGXConfigurator'] = array(
    'name'=>'NGXConfigurator',
    'author'=>'Vertig0ne',
    'category'=>'Web Server',
    'link'=>'https://github.com/',
    'idPrefix'=>'NGXC',
    'configPrefix'=>'NGXC',
    'version'=>'0.0.1',
    'image'=>'plugins/images/tabs/nginx.png',
    'settings'=>true,
    'homepage'=>false
);

function _ngxcGetAllTabs() {
	return allTabs();
}

function _ngxcGetGroups($groups) {
        $data = array();
	$g = 0;
        foreach ($groups["groups"] as $group) {
                $data[$g] = array(
                        "name" => $group["group"],
                        "value" => $group["group_id"]
                );
                $g++;
        }
	return $data;
}

function _ngxcGetTabs() {
	$tabs = _ngxcGetAllTabs();
	$groups = _ngxcGetGroups($tabs);
        $i = 0;
	$data = array();
        foreach ($tabs["tabs"] as $tab) {
                if ($tab['name'] != "Homepage" && $tab['name'] != "Settings") {
                        $name = strtoupper(str_replace(' ','_',$tab['name']));
                        $data[$i] = array(
                                'type' => 'select',
                                'name' => 'NGXC_'.$name.'_AUTH',
                                'label' => $tab['name'],
                                'value' => $GLOBALS['NGXC_'.$name.'_AUTH'] ?: $tab['group_id'],
                                'options' => $groups
                                );
                        $i++;
                }
        }
	return $data;
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
	$tabs = _ngxcGetTabs();
	foreach ($tabs as $tab) {
		$name = str_replace('NGXC_', '', $tab['name']);
		$file_contents .= "set $" .$name." ".$tab['value'].";\n";
	}
        file_put_contents($GLOBALS['dbLocation'].'/auth.conf', $file_contents);
}
