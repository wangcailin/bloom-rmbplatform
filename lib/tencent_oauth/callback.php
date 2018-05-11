<?php
	function real_ip()
	{
		static $realip = NULL;		
		if ($realip !== NULL){return $realip;}		
			if (isset($_SERVER)){
				if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
					$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);		
					/* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
					foreach ($arr AS $ip){
						$ip = trim($ip);		
						if ($ip != 'unknown'){
							$realip = $ip;		
							break;
						}
					}
				}
				elseif (isset($_SERVER['HTTP_CLIENT_IP'])){
					$realip = $_SERVER['HTTP_CLIENT_IP'];
				}
			else{
				if (isset($_SERVER['REMOTE_ADDR']))				{
					$realip = $_SERVER['REMOTE_ADDR'];
				}
				else{
					$realip = '0.0.0.0';
				}
			}
		}
		else{
			if (getenv('HTTP_X_FORWARDED_FOR')){
				$realip = getenv('HTTP_X_FORWARDED_FOR');
			}
			elseif (getenv('HTTP_CLIENT_IP')){
				$realip = getenv('HTTP_CLIENT_IP');
			}
			else{
				$realip = getenv('REMOTE_ADDR');
			}
		}	
		preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
		$realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
		return $realip;
	}
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
@require_once('config.php');
@require_once('oauth.php');
@require_once('opent.php');

$o = new MBOpenTOAuth( MB_AKEY , MB_SKEY , $_SESSION['keys']['oauth_token'] , $_SESSION['keys']['oauth_token_secret']  );
$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;//获取ACCESSTOKEN
$_SESSION['last_key'] = $last_key;
//print_r($_SESSION);

@require_once('config.php');
@require_once('oauth.php');
@require_once('opent.php');
@require_once('api_client.php');

$c = new MBApiClient( MB_AKEY , MB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']);
$c->postOne(array('c'=>'卡机伤不起啊啊啊！彪悍女玩家网络暴走，咆哮捣毁整个页面！#核芯显卡拯救游戏世界#，流勒个畅！ http://minisite.youku.com/pgfx1/?f=duowan','p'=>'../images/share.png','ip'=>real_ip()));
header('Location:../prize.php');
exit();
?>