<?php
	/**
	 *
	 * 基础模型
	 *
	 * @category Model
	 * @package Core
	 * @author harry
	 * @version v1.0 2011-01-17
	 */
	class Base
	{
		/**
		 * 配置变量
		 *
		 * @access protected
		 * @var stdclass
		 */
		protected $config;
		
		/**
		 * 构造函数
		 *
		 * @access public
		 */
		public function __construct()
		{
			self::init();
		}
		
		/**
		 * 构造函数
		 *
		 * @access public
		 */
		public function Base()
		{
			self::__construct();
		}
		
		/**
		 * 抛出异常
		 *
		 * @access protected
		 * @param string $error 异常信息
		 */
		protected function throwError($error='error!')
		{
			throw new Exception($error);
		}
		
		/**
		 * 处理错误
		 *
		 * @access protected
		 * @param Exception $e
		 */
		protected function dealError($e)
		{
			die($e->getMessage());
		}
		
		/**
		 * 初始化
		 *
		 * @access protected
		 */
		protected function init()
		{
			self::initConfig();
		}

		/**
		 * 初始化配置
		 *
		 * @access public
		 */
		protected function initConfig()
		{
			$this->config = & $GLOBALS['config'];
			$this->config->time = & $GLOBALS['time'];
			$this->config->dateTime = & $GLOBALS['dateTime'];
			$this->config->date = & $GLOBALS['date'];
			
			$this->request = & $GLOBALS['request'];
			$this->cookie = & $GLOBALS['cookie'];
			$this->session = & $GLOBALS['session'];
			$this->files = & $GLOBALS['files'];
			
			$this->initValidate();
			$this->initDB();
			$this->initTemplate();
		}

		/**
		 * 给single-quote,double-quote,backslash,nul前加转义字符
		 *
		 * @access public
		 * @param array $array
		 */
		public function addS(&$array)
		{
			foreach($array as $key=>$value){
				if(!is_array($value)){
					$array[$key]=trim(addslashes($value));
				}else{
					$this->addS($array[$key]);
				}
			}
		}
		
		/**
		 * 初始化DB
		 *
		 * @access protected
		 * @param int $fresh 是否重构 0代表否,1代表是
		 */
		protected function initDB($fresh=0)
		{
			if(!$GLOBALS['db'] || ($GLOBALS['db'] && $fresh==1))
			{
				include_once($this->config->libDir.'MyDB.php');
				$GLOBALS['db'] = &new MyDB($this->config->db);
			}
			$this->db = &$GLOBALS['db'];
		}

		/**
		 * 初始化模板
		 *
		 * @access protected
		 * @param int $fresh 是否重构 0代表否,1代表是
		 */
		protected function initTemplate($fresh=0)
		{
			if(!$GLOBALS['template'] || ($GLOBALS['template'] && $fresh==1))
			{
				include_once($this->config->libDir.'smarty'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'smarty.class.php');
 				$GLOBALS['template'] = new Smarty();
 				
	      $GLOBALS['template']->template_dir = array($this->config->templateDir);
	      $GLOBALS['template']->compile_dir = $this->config->templateCompileDir;
	      $GLOBALS['template']->cache_dir = $this->config->templateCacheDir;
	      
				$GLOBALS['template']->assign('webSite',$this->config->webSite);
				$GLOBALS['template']->assign('cssSite',$this->config->cssSite);
				$GLOBALS['template']->assign('jsSite',$this->config->jsSite);
				$GLOBALS['template']->assign('imageSite',$this->config->imageSite);
				$GLOBALS['template']->assign('flashSite',$this->config->flashSite);
				$GLOBALS['template']->assign('uploadSite',$this->config->uploadSite);
				$GLOBALS['template']->assign('time',$this->config->time);
				$GLOBALS['template']->assign('date',$this->config->date);
			}
			$this->template = &$GLOBALS['template'];
		}
		
		/**
		 * 截字
		 *
		 * @access public
		 * @param string $sourceStr 源字符
		 * @param int $cutLength 截取长度
		 * @return string
		 */
		public function subStr($sourceStr,$cutLength,$extraStr='...')
		{
			$i = 0;
			$n = 0;
			$strLength = strlen($sourceStr);
			while ($n<$cutLength && $i<=$strLength)
			{
				$tempStr = substr($sourceStr,$i,1);
				$ascNum = ord($tempStr);
				if ($ascNum>=224)
				{
					$returnStr=$returnStr.substr($sourceStr,$i,3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
					$i = $i+3; //实际Byte计为3
					$n++; //字串长度计1
				}elseif ($ascNum>=192) //如果ASCII位高与192，
				{
					$returnStr = $returnStr.substr($sourceStr,$i,2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
					$i = $i+2; //实际Byte计为2
					$n++; //字串长度计1
				}elseif ($ascNum>=65 && $ascNum<=90) //如果是大写字母，
				{
					$returnStr=$returnStr.substr($sourceStr,$i,1);
					$i = $i+1; //实际的Byte数仍计1个
					$n++; //但考虑整体美观，大写字母计成一个高位字符
				}else //其他情况下，包括小写字母和半角标点符号，
				{
					$returnStr=$returnStr.substr($sourceStr,$i,1);
					$i = $i+1; //实际的Byte数计1个
					//$n = $n+0.5; //小写字母和半角标点等与半个高位字符宽...
					$n++;
				}
			}
			if($strLength>$cutLength && $extraStr)
			{
				$returnStr = $returnStr . $extraStr;//超过长度时在尾处加上省略号
			}
			return $returnStr;
		}
		
		/**
		 * 计算字符串字的个数
		 *
		 * @access public
		 * @param string $sourceStr
		 * @return int
		 */
		public function strLen($sourceStr)
		{
			$i = 0;
			$n = 0;
			$strLength = strlen($sourceStr);
			while ($i<=$strLength)
			{
				$tempStr = substr($sourceStr,$i,1);
				$ascNum = ord($tempStr);
				if ($ascNum>=224)
				{
					$i = $i+3; //实际Byte计为3
				}elseif ($ascNum>=192) //如果ASCII位高与192，
				{
					$i = $i+2; //实际Byte计为2
				}elseif ($ascNum>=65 && $ascNum<=90) //如果是大写字母，
				{
					$i = $i+1; //实际的Byte数仍计1个
				}else //其他情况下，包括小写字母和半角标点符号，
				{
					$i = $i+1; //实际的Byte数计1个
				}
				$n++;
			}
			return $n;
		}
		 
		/**
		 * 分页
		 *
		 * @access public
		 * @param int $count 总数
		 * @param int $page 页号
		 * @param int $perpage 每页显示数
		 * @param string $url url
		 * @param string $mao 锚点
		 * @return string
		 */
		public function page($count,$page,$perpage,$url,$mao='',$pageName='page')
		{
			substr($url,-1)!='&' && $url = $url.'&';
			$numofpage = ceil($count/$perpage);
			if($page>$numofpage)
			{
				if($numofpage==1 && $page>1)
				{
					return '';
				}
				$page = $numofpage;
			}
			$total = $numofpage;
			//	$max && $numofpage > $max && $numofpage=$max;
			if($numofpage<=1 || !is_numeric($page))
			{
				return '';
			}else{
				$pages="<div class=\"pages\" style=\"font-size:14px;\"><a href=\"{$url}{$pageName}=1$mao\" style=\"font-weight:bold\">&laquo;</a>";
				$flag=0;
				for($i=$page-3;$i<=$page-1;$i++)
				{
					if($i<1) continue;
					$pages.="<a href=\"{$url}{$pageName}=$i$mao\">$i</a>";
				}
				$pages.="<b> $page </b>";
				if($page<$numofpage)
				{
					for($i=$page+1;$i<=$numofpage;$i++)
					{
						$pages.="<a href=\"{$url}{$pageName}=$i$mao\">$i</a>";
						$flag++;
						if($flag==4) break;
					}
				}
				$pages.="<!--<input type=\"text\" size=\"3\" onkeydown=\"javascript: if(event.keyCode==13){ location='{$url}{$pageName}='+this.value;return false;}\">--><a href=\"{$url}{$pageName}=$numofpage$mao\" style=\"font-weight:bold\">&raquo;</a> ( 页数: $page/$total )</div>";
				return $pages;
			}
		}
		
		/**
		 * 字符串异或
		 *
		 * @access public
		 * @param string $str 源字符
		 * @param string $key 钥匙
		 * @return string
		 */
		public function authEncode($str,$key='')
		{
			$key = md5($key);
			$keylength = strlen($key);
			$strlength = strlen($str);
			for($i=0;$i<$strlength;$i+=$keylength)
			{
				$tmp = ($i+$keylength)<$strlength ? $i+$keylength : $strlength;
				$codebyte = substr($str,$i,$tmp-$i)^$key;
				$coded.=$codebyte;
			}
			$coded = base64_encode($coded);
			return $key.$coded;
		}
        
	  /**
	   * 字符串异或
	   *
	   * @access public
	   * @param string $str 字符
	   * @return string
	   */
		public function authDecode($str)
		{
			$key = substr($str,0,32);
			$str = base64_decode(substr($str,32));
			$keylength = strlen($key);
			$strlength = strlen($str);
			for($i=0;$i<$strlength;$i+=$keylength)
			{
				$tmp = ($i+$keylength)<$strlength ? $i+$keylength : $strlength;
				$codebyte = substr($str,$i,$tmp-$i)^$key;
				$coded.=$codebyte;
			}
			return $coded;
		}
		
		/**
		 * json encode
		 *
		 * @access public
		 * @param string $val 源字符
		 * @return string
		 */
		public function jsonEncode($val)
		{
			include_once($this->config->libDir.'Json.class.php');
			$json = new Services_JSON();
			return $json->encode($val);
		}

		/**
		 * json decode
		 *
		 * @access public
		 *
		 * @param string $val 源字符
		 * @return string
		 */
		public function jsonDecode($val)
		{
			include_once($this->config->libDir.'Json.class.php');
			$json = new Services_JSON();
			return $json->decode($val);
		}
		
		/**
		 * 制作缩略图
		 *
		 * @access public
		 * @param string $sourceFile 源文件路径
		 * @param string $targetFile 目标文件路径
		 * @param int $targatWidth 目标图片宽度
		 * @param int $targetHeight 目标图片高度
		 * @param bool $isReserveSourceFile 是否保留源文件
		 * @return bool
		 */
		public function makeThumbImage($sourceFile, $targetFile, $targetWidth = 0, $targetHeight = 0, $isReserveSourceFile = false)
		{
			$data = getimagesize($sourceFile);
			$scale  = $data[0] / $data[1];
			$newscale  = $targetWidth / $targetHeight;
			
			if ($data[0] <= $targetWidth && $data[1] <= $targetHeight){
				$width = $data[0];
				$height = $data[1];
			}else{
				if ($scale <= $newscale ){
					$width = $targetHeight * $scale ;
					$height = $targetHeight;
				}else{
					$width = $targetWidth;
					$height = $targetWidth / $scale ;
				}
			}
			
			switch ($data[2]) 
			{
				case 1: //图片类型，1是GIF图
					$image = imagecreatefromgif($sourceFile);
					break;
				case 2: //图片类型，2是JPG图
					$image = imagecreatefromjpeg($sourceFile);
					break;
				case 3: //图片类型，3是PNG图
					$image = imagecreatefrompng($sourceFile);
					break;
				case 6:
					$image = imagecreatefromxbm($sourceFile);
					break;
			}
		
			$targetWidth = ceil($width);
			$targetHeight = ceil($height);
			
			$sourceWidth = ImageSX($image);
			$sourceHeight = ImageSY($image);
			
			$ni = imagecreatetruecolor($targetWidth,$targetHeight);
			imagecopyresampled($ni, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);
			
			/* 水印
			$text_color = imagecolorallocate ($ni, 255, 255, 255);
			
			$text = ($targetWidth > 250) ? 'www.xinpindao.com' : '';
			$textX = ($targetWidth > 250) ? ($targetWidth - 230) : ($targetWidth - 30);
			$textY = ($targetHeight > 90) ? ($targetHeight - 20) : ($targetHeight - 15);
			imagestring ($ni, 2, $textX, $textY, $text, $text_color);
			*/
			ImageJpeg($ni, $targetFile);
			
			//在显示图片时用，把注释取消可以直接在页面显示出图片。
			//ImageJpeg($ni);
			
			//删除原图
			if (!$isReserveSourceFile){
				unlink($sourceFile);
			}
			return true;
		}
		
		/**
		 * 创建目录
		 *
		 * @access public
		 * @param string $dir 目标目录路径
		 */
		public function createDir($dir)
		{
			for($i=0;$i<strlen($dir);$i++)
			{
				if($dir{$i}=='/')
				{
					$dir_1 = substr($dir,0,$i);
					if($dir_1!='' && !is_dir($dir_1))
					{
						mkdir($dir_1,0755);
						chmod($dir_1,0755);
					}
				}
			}
		}
		
		/**
		 * 上传图片
		 *
		 * @access public
		 * @param string $uploadRelativeDir 目标文件相对目录
		 * @param string $uploadName htmlfile对象名称
		 * @param string $uploadFileName 目标文件名称
		 * @return array
		 */
		public function uploadImage($uploadRelativeDir,$uploadName,$uploadFileName)
		{
			if(!$_FILES[$uploadName]['error'])
			{
				
				$uploadDir = $this->model->uploadDir.$uploadRelativeDir;
				
				if(!is_dir($uploadDir))
				{
					createDir($uploadDir);
				}
				
				if(array_key_exists($_FILES[$uploadName]['type'],$this->config->allowUploadImageType))
				{
					$fromFilePath = $_FILES[$uploadName]['tmp_name'];
					$destFilePath = $uploadDir.$uploadFileName;
					move_uploaded_file($fromFilePath,$destFilePath);
					return array('status'=>1,'filepath'=>$dir.$destFileName);
				}
				$this->errorMsg = '上传格式非法';
				return array('status'=>0,'msg'=>$this->errorMsg);
			}
			return array('status'=>0,'msg'=>$_FILES[$uploadName]['error']);
		}
		
		/**
		 * 获取系统提示信息
		 *
		 * @access public
		 * @param string $code
		 * @return string
		 */
		public function getSysMsg($code)
		{
			$sql = "select Content from sys_msg where Code='{$code}'";
			$content = $this->db->getOne($sql);
			return $content;
		}
		
		/**
		 * 初始化验证类
		 *
		 * @access public
		 */
		public function initValidate()
		{
			include_once($this->config->libDir.'validate.class.php');
		}
		
		/**
		 * curl 封装
		 *
		 * @access public
		 * @param string $baseurl
		 * @param int $requestType
		 * @param array $data
		 * @return string
		 */
		public function curlRequest($baseurl,$requestType = 1,$data = array())
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			$this_header = array(
					"content-type: application/x-www-form-urlencoded;charset:UTF-8"
			);
			curl_setopt($curl,CURLOPT_HTTPHEADER,$this_header);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, $baseurl); # this is where you are requesting POST-method form results (working with secure connection using cookies after auth)
			//使用post方法传递参数时所作设置
			if($requestType == 2){
				curl_setopt($curl, CURLOPT_POST, true);
				if(!empty($data)){
					//整理传递参数的格式
					if(is_array($data)){                          //当为数组时
						$strEntity = "";
						foreach($data as $k=>$v){
							$urlencodeV = urlencode($v);
							$strEntity .= "{$k}={$urlencodeV}&";
						}
						curl_setopt($curl, CURLOPT_POSTFIELDS, $strEntity); # form params that'll be used to get form results
					}else{                                                   //当为字符串时
						curl_setopt($curl, CURLOPT_POSTFIELDS, $data); # form params that'll be used to get form results
					}
				}
		
			}
			$strContent = curl_exec($curl);      //从msn中所取得的回复
			curl_close ($curl);
			return $strContent;
		}
	}
?>
