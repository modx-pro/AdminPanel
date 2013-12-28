<?php
/* @var modX $modx */
/* @var array $actions Array with system actions */
/* @var array $controllers Array with building menu */

//echo '<pre>'; print_r($actions); print_r($controllers); die;

if (!is_array($controllers)) {return;}

// miniShop2
if (isset($actions['minishop2_controllers_mgr_orders'])) {
	$controllers['ms2'][] = array(
		'link' => $actions['minishop2_controllers_mgr_orders'],
		'title' => 'ms2_orders',
		'class' => '',
		'target' => '_top',
	);
}

if (isset($actions['minishop2_controllers_mgr_settings'])) {
	$controllers['ms2'][] = array(
		'link' => $actions['minishop2_controllers_mgr_settings'],
		'title' => 'ms2_settings',
		'class' => '',
		'target' => '_top',
	);
}

// Tickets
if (isset($actions['tickets_index'])) {
	$controllers['tickets'][] = array(
		'link' => $actions['tickets_index'],
		'title' => 'tickets_comments',
		'class' => '',
		'target' => '_top',
	);
}
