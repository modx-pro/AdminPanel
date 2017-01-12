<?php
/** @var modX $modx */
/** @var array $actions Array with system actions */
/** @var array $controllers Array with building menu */

//echo '<pre>'; print_r($actions); print_r($controllers); die;

if (!is_array($controllers)) {
    return;
}

// miniShop2
if (isset($actions['minishop2_controllers_mgr_orders']) || isset($actions['minishop2_mgr_orders'])) {
    $controllers['ms2'][] = array(
        'link' => !empty($actions['minishop2_controllers_mgr_orders'])
            ? $actions['minishop2_controllers_mgr_orders']
            : $actions['minishop2_mgr_orders'],
        'title' => 'ms2_orders',
        'class' => '',
        'target' => '_top',
    );
}

if (isset($actions['minishop2_controllers_mgr_settings']) || isset($actions['minishop2_mgr_settings'])) {
    $controllers['ms2'][] = array(
        'link' => !empty($actions['minishop2_controllers_mgr_settings'])
            ? $actions['minishop2_controllers_mgr_settings']
            : $actions['minishop2_mgr_settings'],
        'title' => 'ms2_settings',
        'class' => '',
        'target' => '_top',
    );
}

// Tickets
if (isset($actions['tickets_index']) || isset($actions['tickets_mgr_index'])) {
    $controllers['tickets'][] = array(
        'link' => !empty($actions['tickets_index'])
            ? $actions['tickets_index']
            : $actions['tickets_mgr_index'],
        'title' => 'tickets_comments',
        'class' => '',
        'target' => '_top',
    );
}

// mSearch2
/*
if (isset($actions['msearch2_index']) || isset($actions['msearch2_mgr_index'])) {
    $controllers['msearch2'] = array(
        'link' => !empty($actions['msearch2_index'])
            ? $actions['msearch2_index']
            : $actions['msearch2_mgr_index'],
        'title' => 'msearch2',
        'class' => '',
        'target' => '_top',
    );
}
*/