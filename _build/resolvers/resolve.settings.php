<?php

if ($object->xpdo) {
	/* @var modX $modx */
	$modx =& $object->xpdo;

	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
			break;

		case xPDOTransport::ACTION_UPGRADE:
			if ($setting = $modx->getObject('modSystemSetting', array('key' => 'ap_theme', 'xtype' => 'combo-ap-theme'))) {
				$setting->set('xtype', 'textfield');
				$setting->save();
			}
			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;
