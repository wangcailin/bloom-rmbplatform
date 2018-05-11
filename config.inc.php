<?php
	$config = new stdClass();
	$config->listNum = 10;
	$config->confDir = ROOTDIR.'conf'.DIRECTORY_SEPARATOR;
	$config->modelDir = ROOTDIR.'model'.DIRECTORY_SEPARATOR;
	$config->controllerDir = ROOTDIR.'controller'.DIRECTORY_SEPARATOR;
	$config->viewDir = ROOTDIR.'view'.DIRECTORY_SEPARATOR;
	$config->cacheDir = ROOTDIR.'cache'.DIRECTORY_SEPARATOR;
	$config->templateDir = $config->viewDir.'templates'.DIRECTORY_SEPARATOR;
	$config->templateCompileDir = $config->viewDir.'templates_c'.DIRECTORY_SEPARATOR;
	$config->templateCacheDir = $config->viewDir.'templates'.DIRECTORY_SEPARATOR;
	$config->htmlDir = $config->viewDir.'html'.DIRECTORY_SEPARATOR;
	$config->uploadDir = ROOTDIR.'attachment'.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR;
	$config->libDir = ROOTDIR.'lib'.DIRECTORY_SEPARATOR;
	$config->webSite = 'http://'.$_SERVER['HTTP_HOST']."/rmbplatform";
	$config->domain = '';
	$config->cookieTime = 0;
	$config->cookieExpireTime = 0;
	$config->cookiePath = '/';
	$config->cookieSecure = 0;
	$config->cssSite = $config->webSite.'/view/templates/css/';
	$config->jsSite = $config->webSite.'/view/templates/js/';
	$config->imageSite = $config->webSite.'/view/templates/images/';
	$config->flashSite = $config->webSite.'/view/templates/flash/';
	$config->attachmentSite = $config->webSite.'/attachment/';
	$config->uploadSite = $config->webSite.'/attachment/upload/';
	$config->db = array('host'=>'localhost','user'=>'root','pass'=>'sdfdsf$@321367ioer','charset'=>'utf8','ispconnect'=>0,'dbname'=>'bloom');
	$config->model = 'event';
	$config->action = 'index';
	
	//允许上传的图片类型
	$config->allowUploadImageType = array('image/jpg'=>'.jpg', 'image/jpeg'=>'.jpg','image/png'=>'.png','image/pjpeg'=>'.jpg','image/gif'=>'.gif','image/bmp'=>'.bmp','image/x-png'=>'.png', 'application/octet-stream'=>'');
?>