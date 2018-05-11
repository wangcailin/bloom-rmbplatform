<?php
	/**
	 * 获取微信用户信息通过HTML
	 *
	 * @param string $appID
	 * @param string #appSecret
	 * @return Array
	 */
	function getWeixinUserInfoByHTML($appID,$appSecret)
	{
		if(!$_REQUEST['code'] || ($_REQUEST['code'] && $_REQUEST['code']==$_COOKIE['code']))
		{
			$result = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appID}&secret={$appSecret}&code={$code}&grant_type=authorization_code");
			$redirectURL = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$scope = 'snsapi_base';
			$state = 'blue';
			header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appID}&redirect_uri={$redirectURL}&response_type=code&scope={$scope}&state={$state}#wechat_redirect");
			exit;
		}else{
			$code = $_REQUEST['code'];
			setcookie('code',$code,0,'/','',0);
			$result = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appID}&secret={$appSecret}&code={$code}&grant_type=authorization_code");
			$result = json_decode($result,true);
			$accessToken = $result['access_token'];
			$openid = $result['openid'];
		}
		file_put_contents('log_wechat.txt',"https://api.weixin.qq.com/sns/userinfo?access_token=".urlencode($accessToken)."&openid=".urlencode($result['openid']).'&lang=zh_CN'."\r\n",FILE_APPEND);
		$userInfoResult = file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=".urlencode($accessToken)."&openid=".urlencode($result['openid']).'&lang=zh_CN');
		$userInfoResult = json_decode($userInfoResult,true);
		file_put_contents('log_wechat.txt',var_export($userInfoResult,true)."\r\n",FILE_APPEND);
		if($userInfoResult['errcode'])
		{
			if($userInfoResult['errcode']=='40001')
			{
				$scope = 'snsapi_base';
			}elseif($userInfoResult['errcode']=='40003')
			{
				$scope = 'snsapi_userinfo';
			}else{
				$scope = 'snsapi_userinfo';
			}
			$redirectURL = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$state = 'blue';
			setcookie('wechatopenid',$openid,$time-86400,'/','',0);
			header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appID}&redirect_uri={$redirectURL}&response_type=code&scope={$scope}&state={$state}#wechat_redirect");
			exit;
		}
		return $userInfoResult;
	}
	
	/**
	 * 更新accessToken
	 *
	 * @param string $appID
	 * @param string $appSecret
	 * @param MyDB $db
	 * @return array
	 */
	function updateWeixinAccessToken($appID,$appSecret,$db)
	{
		$time = time();
		$sql = "select AccessToken from wechat_access_token limit 0,1";
		$accessTokenResult = $db->getOne($sql);
		$accessToken = null;
		if(!$accessTokenResult)
		{
			$sql = "insert into wechat_access_token(AccessToken) values('')";
			$db->update($sql);
		}elseif($accessTokenResult['ExpireTime']>=$time)
		{
			$accessToken = $accessTokenResult;
		}
		
		if(!$accessToken)
		{
			$accessToken = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appID}&secret={$appSecret}");
			$accessTokenResult = json_decode($accessToken,true);
			$accessToken = $accessTokenResult['access_token'];
			if($accessToken)
			{
				$expireTime = $time+$accessTokenResult['expires_in']-600;
				$sql = "update wechat_access_token set AccessToken='{$accessToken}',ExpireTime={$expireTime}";
				$db->update($sql);
				return array('status'=>1,'accessToken'=>$accessToken);
			}
			return array('status'=>-1);
		}else{
			$checkAccessTokenResult = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".urlencode($accessToken)."&openid=OPENID&lang=zh_CN");
			$checkAccessTokenResult = json_decode($checkAccessTokenResult,true);
			if($checkAccessTokenResult['errcode']=='40001' || $checkAccessTokenResult['errcode']=='41001')
			{
				$accessToken = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appID}&secret={$appSecret}");
				$accessTokenResult = json_decode($accessToken,true);
				$accessToken = $accessTokenResult['access_token'];
				if($accessToken)
				{
					$expireTime = $time+$accessTokenResult['expires_in']-600;
					$sql = "update wechat_access_token set AccessToken='{$accessToken}',ExpireTime={$expireTime}";
					$db->update($sql);
					return array('status'=>1,'accessToken'=>$accessToken);
				}
			}
			return array('status'=>1,'accessToken'=>$accessToken);
		}
	}	
	
	/**
	 * 验证用户是否关注微信公众账号通过HTML
	 *
	 * @param string $appID
	 * @param string $appSecret
	 * @return bool
	 */
	function checkIsSubWechatByHTML($appID,$appSecret)
	{
		$time = time();
		if(!$_REQUEST['code'] || ($_REQUEST['code'] && $_REQUEST['code']==$_COOKIE['code']))
		{
			$redirectURL = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$scope = 'snsapi_base';
			$state = 'blue';
			header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appID}&redirect_uri={$redirectURL}&response_type=code&scope={$scope}&state={$state}#wechat_redirect");
			exit;
		}else{
			$code = $_REQUEST['code'];
			setcookie('code',$code,0,'/','',0);
			$result = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appID}&secret={$appSecret}&code={$code}&grant_type=authorization_code");
			$result = json_decode($result,true);
			$accessToken = $result['access_token'];
			$openid = $result['openid'];
		}
		$accessToken = updateWeixinAccessToken();
		$accessToken = $accessToken['accessToken'];
		$userInfoResult = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$accessToken}&openid={$openid}&lang=zh_CN");
		$userInfoResult = json_decode($userInfoResult,true);
		file_put_contents('log_wechat.txt',var_export($userInfoResult,true)."\r\n",FILE_APPEND);
		if($userInfoResult['subscribe']==1)
		{
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 通过openID获取微信用户信息
	 *
	 * @param string $openID
	 * @return array
	 */
	function getWechatUserInfoByOpenID($openid)
	{
		$accessToken = updateWeixinAccessToken();
		$accessToken = $accessToken['accessToken'];
		$userInfoResult = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$accessToken}&openid={$openid}&lang=zh_CN");
		$userInfoResult = json_decode($userInfoResult,true);
		return $userInfoResult;
	}
?>