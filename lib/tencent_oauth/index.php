<?php
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
require_once('config.php');
require_once('oauth.php');
require_once('opent.php');

$o = new MBOpenTOAuth( MB_AKEY , MB_SKEY  );
$keys = $o->getRequestToken(WEBSITE.'/duowan_share/tencent/callback.php');//这里的*********************填上你的回调URL
$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false,'');

$_SESSION['keys'] = $keys;
header("Location:{$aurl}");
exit;
?>