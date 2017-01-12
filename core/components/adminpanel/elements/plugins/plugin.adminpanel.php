<?php
/** @var array $scriptProperties */
switch ($modx->event->name) {

    case 'OnWebPagePrerender':
        $users = trim($modx->getOption('ap_show_users'));
        $users_arr = array_map('trim', explode(',', $users));
        $templates = trim($modx->getOption('ap_hide_templates', null, '0'));
        $templates_arr = array_map('trim', explode(',', $templates));
        $register = ($users == '' && $modx->user->hasSessionContext('mgr')) ||
            ($modx->user->id > 0 && in_array($modx->user->id, $users_arr));

        if ($register) {
            if ($templates == '' || !in_array($modx->resource->template, $templates_arr)) {
                $path = $modx->getOption('ap_core_path', null, MODX_CORE_PATH . 'components/adminpanel/');
                $AdminPanel = $modx->getService('adminpanel', 'AdminPanel', $path . 'model/adminpanel/',
                    $scriptProperties);

                if ($AdminPanel instanceof AdminPanel) {
                    $html = $AdminPanel->run();

                    if (strpos($modx->resource->_output, '</body>') !== false) {
                        $modx->resource->_output =
                            preg_replace("#(</body>)#i", $html . "\n\\1", $modx->resource->_output, true);
                    } else {
                        $modx->resource->_output .= $html;
                    }
                }
            }
        }
        break;

    /*
    case 'OnSiteSettingsRender':
        $modx->controller->addLexiconTopic('adminpanel:setting');
        $modx->controller->addHtml("<script>
            Ext.onReady(function() {
                MODx.combo.apTheme = function(config) {
                    config = config || {};
                    Ext.applyIf(config,{
                        store: new Ext.data.SimpleStore({
                            fields: ['d','v']
                            ,data: [[_('ap_theme_dark'),'dark'],[_('ap_theme_light'),'light']]
                        })
                        ,displayField: 'd'
                        ,valueField: 'v'
                        ,name: 'value'
                        ,hiddenName: 'value'
                        ,mode: 'local'
                    });
                MODx.combo.apTheme.superclass.constructor.call(this,config);
            };
            Ext.extend(MODx.combo.apTheme,MODx.combo.ComboBox);
            Ext.reg('combo-ap-theme',MODx.combo.apTheme);
        });
        </script>");
        break;
    */
}