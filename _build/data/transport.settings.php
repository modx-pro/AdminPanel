<?php

$settings = array();

$tmp = array(
    'show_users' => array(
        'xtype' => 'textfield',
        'value' => '',
        'area' => 'ap_main',
    ),
    'hide_templates' => array(
        'xtype' => 'textfield',
        'value' => '0',
        'area' => 'ap_main',
    ),
    'controllers' => array(
        'xtype' => 'textfield',
        'value' => 'default',
        'area' => 'ap_main',
    ),
    'controllers_path' => array(
        'xtype' => 'textfield',
        'value' => '[[+corePath]]controllers/',
        'area' => 'ap_main',
    ),

    'frontend_js' => array(
        'xtype' => 'textfield',
        'value' => '[[+assetsUrl]]js/default.js',
        'area' => 'ap_style',
    ),
    'frontend_css' => array(
        'xtype' => 'textfield',
        'value' => '[[+assetsUrl]]css/default.css',
        'area' => 'ap_style',
    ),
    'tpl_outer' => array(
        'xtype' => 'textfield',
        'value' => 'tpl.AdminPanel.outer',
        'area' => 'ap_style',
    ),
    'tpl_group' => array(
        'xtype' => 'textfield',
        'value' => 'tpl.AdminPanel.group',
        'area' => 'ap_style',
    ),
    'tpl_link' => array(
        'xtype' => 'textfield',
        'value' => 'tpl.AdminPanel.link',
        'area' => 'ap_style',
    ),
    'theme' => array(
        'xtype' => 'textfield',
        'value' => 'dark',
        'area' => 'ap_style',
    ),
    'inactive_opacity' => array(
        'xtype' => 'numberfield',
        'value' => '9',
        'area' => 'ap_style',
    ),
    'active_opacity' => array(
        'xtype' => 'numberfield',
        'value' => '10',
        'area' => 'ap_style',
    ),
);

foreach ($tmp as $k => $v) {
    /** @var modSystemSetting $setting */
    $setting = $modx->newObject('modSystemSetting');
    $setting->fromArray(array_merge(
        array(
            'key' => 'ap_' . $k,
            'namespace' => PKG_NAME_LOWER,
            'area' => 'ap_main',
        ), $v
    ), '', true, true);

    $settings[] = $setting;
}
unset($tmp);

return $settings;
