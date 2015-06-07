<?php
class  Sh404sefExtpluginCom_yoorecipe extends Sh404sefClassBaseextplugin {

	protected $_extName = 'com_yoorecipe';

	/**
	* Standard constructor don't change
	*/    
	public function __construct( $option, $config) {

		parent::__construct( $option, $config);
		$this->_pluginType = Sh404sefClassBaseextplugin::TYPE_SH404SEF_ROUTER;
	}

	/**
	* Adjust returned path to your own plugin. This method will be used to find the exact
	* and full path to your plugin main file. The location used below is just a sample.
	* Your plugin can be stored anywhere, and use as many files as you need. sh404SEF® only
	* needs to know about the main entry point.
	*
	* @params array $nonSefVars an array of key=>values representing the non-sef vars of the url
	*                we are trying to SEFy. You can adjust the plugin used depending on the
	*                request being made (or other elements). For instance, you could use
	*                a different plugin based on the currently installed version of the extension              
	*/    
	protected function _findSefPluginPath( $nonSefVars = array())
	{
		$this->_sefPluginPath =  JPATH_ROOT.'/plugins/sh404sefextplugins/sh404sefextplugincom_yoorecipe/yoorecipe/com_yoorecipe.php';
	}
}