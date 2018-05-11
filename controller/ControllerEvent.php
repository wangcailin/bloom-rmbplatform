<?php
	/**
	 *
	 * 用户控制器
	 *
	 * @category Controller
	 * @package Controller
	 * @author harry
	 * @version v1.0 2014-06-23
	 */
	class ControllerEvent extends Base
	{
		/**
		 * 构造函数
		 *
		 * @access public
		 */
		public function __construct()
		{
			parent::Base();

			include_once($this->config->modelDir.'ModelEvent.php');
			$this->model = new ModelEvent();

			self::initConfig();
			if(!isset($_SESSION)){ //判断session是否开启
				session_start(); //开启就session
			} 
			
			include_once("../jssdk3.php");
			$this->test = new JSSDK("wxe91b175d5b5f020c", "b650c8edd1158faf1cce07df21f32fca");

			if($_GET['bloom_openid']){
				
				setcookie('bloom_openid',$_GET['bloom_openid'],$this->config->time+86400*12,$this->config->cookiePath,$this->config->domain,$this->config->cookieSecure);
				header("Location:".str_replace('&bloom_openid='.$_GET['bloom_openid'],'',str_replace('?bloom_openid='.$_GET['bloom_openid'],'',$this->curPageURL())));
				die();
			}
			$this->openid = $_COOKIE['bloom_openid'];
			
			if($_GET['sourceOpenid']){
				setcookie('sourceOpenid',$_GET['sourceOpenid'],$this->config->time+86400*12,$this->config->cookiePath,$this->config->domain,$this->config->cookieSecure);
				header("Location:".str_replace('&sourceOpenid='.$_GET['sourceOpenid'],'',str_replace('?sourceOpenid='.$_GET['sourceOpenid'],'',$this->curPageURL())));
				die();
			}
			$this->sourceOpenid=$_COOKIE['sourceOpenid'];
			
			if(!$this->openid){
				setcookie('state',$this->curPageURL(),$this->config->time+86400*30,$this->config->cookiePath,$this->config->domain,$this->config->cookieSecure);
				header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx2704d18d6f9757d5&redirect_uri=".urlencode("http://wk.blue-dot.cn/getOpenid4.php")."&response_type=code&scope=snsapi_base&state=".urlencode($this->curPageURL())."#wechat_redirect");
				die("请从微信入口进入活动");
			}
			if(!$this->sourceOpenid){
				$this->sourceOpenid=$this->openid;
			}
            $signPackage = $this->test->GetSignPackage($_GET["requrl"]);
            $this->template->assign('signPackage',$signPackage);
            $this->template->assign('openid',$this->openid);
			
		}
		
		/**
		 * 构造函数
		 *
		 * @access public
		 */
		public function ControllerEvent()
		{
			self::__construct();
		}
		
		/**
		 * 首页
		 *
		 * @access public
		 */
		public function index()
		{
			$this->template->display('index.html');
		}

		public function video()
        {
            $this->template->display('video.html');
        }

        public function register()
        {
            $this->template->display('register.html');
        }
        public function registerSubmit()
        {
            $data['firstname']      = $_POST['username_for'];
            $data['lastname']       = $_POST['username'];
            $data['company']        = $_POST['company'];
            $data['job']            = $_POST['position'];
            $data['city']           = $_POST['province'];
            $data['country']        = $_POST['country'];
            $data['email']          = $_POST['email'];
            $data['tel']            = $_POST['phone'];
            $data['service1']       = $_POST['service1'];
            $data['service2']       = $_POST['service2'];
            $data['beijing']        = $_POST['beijing'];
            $data['shanghai']       = $_POST['shanghai'];
            $data['time']           = time();
            if ($this->model->insert($data, 'bloom_rmbplatform')){
                echo '1';
            }
        }


		function curPageURL() 
		{
			$pageURL = 'http';
		
			if ($_SERVER["HTTPS"] == "on") 
			{
				$pageURL .= "s";
			}
			$pageURL .= "://";
		
			if ($_SERVER["SERVER_PORT"] != "80") 
			{
				$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
			} 
			else 
			{
				$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
			}
			return $pageURL;
		}
		
	}
?>