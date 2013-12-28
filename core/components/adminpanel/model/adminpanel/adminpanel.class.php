<?php

class AdminPanel {
	/* @var modX $modx */
	public $modx;
	protected $request;
	protected $html = array();

	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;

		$corePath = $this->modx->getOption('adminpanel_core_path', $config, $this->modx->getOption('core_path') . 'components/adminpanel/');
		$assetsUrl = $this->modx->getOption('adminpanel_assets_url', $config, $this->modx->getOption('assets_url') . 'components/adminpanel/');

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'controllersPath' => $this->getOption('ap_controllers_path', null, '[[+corePath]]controllers/', true),

			'tplOuter' => $this->getOption('ap_tpl_outer', null, 'tpl.AdminPanel.outer', true),
			'tplGroup' => $this->getOption('ap_tpl_group', null, 'tpl.AdminPanel.group', true),
			'tplLink' => $this->getOption('ap_tpl_link', null, 'tpl.AdminPanel.link', true),

			'inactive_opacity' => $this->getOption('ap_inactive_opacity', null, 9, true),
			'active_opacity' => $this->getOption('ap_aactive_opacity', null, 10, true),

			'controllers' => $this->getOption('ap_controllers', null, 'default'),
		), $config);

		$tmp = $this->makePlaceholders($this->config);
		$this->config['controllersPath'] = str_replace($tmp['pl'], $tmp['vl'], $this->config['controllersPath']);

		$tmp = array_map('trim', explode(',', $this->config['controllers']));
		$this->config['controllers'] = array();
		foreach ($tmp as $v) {
			$this->config['controllers'][] = preg_replace('/\.php$/', '', $v);
		}
		$this->config['controllers'] = array_unique($this->config['controllers']);

		foreach (array('inactive_opacity', 'active_opacity') as $v) {
			$tmp = & $this->config[$v];
			if ($tmp < 0 || $tmp > 10) {$tmp = 1;}
			else {$tmp = round($tmp / 10, 1);}
		}
		unset($tmp);

		$this->modx->lexicon->load('adminpanel:default');
	}


	/**
	 * @return string
	 */
	public function run() {
		$html = array();

		$config = $this->makePlaceholders($this->config);
		if ($css = $this->getOption('ap_frontend_css')) {
			$html['1-css'] = "\n".'<link href="'.str_replace($config['pl'], $config['vl'], $css).'" rel="stylesheet">';
		}
		if ($js = trim($this->getOption('ap_frontend_js'))) {
			$html['3-js'] = str_replace('				', '', '
				<script type="text/javascript">
				AdminPanelConfig = {
					cssUrl: "'.$this->config['cssUrl'].'"
					,jsUrl: "'.$this->config['jsUrl'].'"
					,inactive_opacity: "'.$this->config['inactive_opacity'].'"
					,active_opacity: "'.$this->config['active_opacity'].'"
				};
				</script>');
			if (!empty($js) && preg_match('/\.js/i', $js)) {
				$html['3-js'] .= str_replace('					', '', '
					<script type="text/javascript">
					if(typeof jQuery == "undefined") {
						document.write("<script src=\"'.$this->config['jsUrl'].'lib/jquery.min.js\" type=\"text/javascript\"><\/script>");
					}
					</script>');
				$html['3-js'] .= "\n".'<script type="text/javascript" src="'.str_replace($config['pl'], $config['vl'], $js).'"></script>';
			}
		}

		$actions = $this->getActions();
		$maxIterations = (integer) $this->modx->getOption('parser_max_iterations', null, 10);
		$this->modx->getParser()->processElementTags('', $actions, false, false, '[[', ']]', array(), $maxIterations);
		$this->modx->getParser()->processElementTags('', $actions, true, true, '[[', ']]', array(), $maxIterations);
		$html['2-html'] = $actions;

		ksort($html);
		return implode("\n", $html);
	}


	/**
	 * Loads actions and template menus
	 *
	 * @return string
	 */
	public function getActions() {
		$tmp = $this->modx->request->getAllActionIDs();

		$system = array();
		foreach ($tmp as $name => $id) {
			$name = str_replace(array('/',':'), '_', $name);
			$system[$name] = MODX_MANAGER_URL . 'index.php?a=' . $id;
			$system[$name.'_id'] = $id;
		}

		$controllers = $this->loadControllers(array(
			'actions' => $system
		));

		$groups = '';
		foreach ($controllers as $controller => $actions) {
			if (is_array($actions)) {
				if (is_array(current($actions))) {
					$links = '';
					foreach ($actions as $action) {
						if (is_array($action)) {
							$links .= $this->modx->getChunk($this->config['tplLink'], $action);
						}
					}
					$groups .= $this->modx->getChunk($this->config['tplGroup'], array(
						'title' => $controller,
						'links' => $links
					));
				}
				else {
					$groups .= $this->modx->getChunk($this->config['tplLink'], $actions);
				}
			}
			else {
				$groups .= $actions;
			}
		}

		$system['groups'] = $groups;
		$system['class_status'] = isset($_COOKIE['adminpanel_closed']) && !empty($_COOKIE['adminpanel_closed']) ? 'ap-closed' : 'ap-opened';
		$system['class_theme'] = strtolower($this->getOption('ap_theme', null, 'dark', true)) != 'dark' ? 'ap-light' : 'ap-dark';
		$system['inactive_opacity'] = $this->config['inactive_opacity'];
		$output = $this->modx->getChunk($this->config['tplOuter'], $system);

		return $output;
	}


	/**
	 * Method loads custom classes from specified directory
	 *
	 * @return array
	 */
	public function loadControllers($params = array()) {
		extract($params);
		$modx = & $this->modx;
		$controllers = array();
		foreach ($this->config['controllers'] as $file) {
			$file = $this->config['controllersPath'] . '/' . $file .'.php';
			if (is_file($file)) {
				include($file);
			}
		}

		return $controllers;
	}


	/** Method for transform array to placeholders
	 *
	 * @var array $array With keys and values
	 * @return array $array Two nested arrays With placeholders and values
	 */
	public function makePlaceholders(array $array = array(), $prefix = '') {
		$result = array(
			'pl' => array(),
			'vl' => array()
		);
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				$result = array_merge_recursive($result, $this->makePlaceholders($v, $k.'.'));
			}
			else {
				$result['pl'][$prefix.$k] = '[[+'.$prefix.$k.']]';
				$result['vl'][$prefix.$k] = $v;
			}
		}
		return $result;
	}


	/**
	 * Get an xPDO configuration option value by key with respect to user settings.
	 *
	 * {@inheritdoc}
	 */
	public function getOption($key, $options = null, $default = null, $skipEmpty = false) {
		$settings = $this->modx->user->getSettings();

		if (!is_array($key) && is_array($settings) && array_key_exists($key, $settings)) {
			return $settings[$key];
		}
		else {
			return $this->modx->getOption($key, $options, $default, $skipEmpty);
		}
	}

}
