<?php
set_time_limit(0);
ini_set('memory_limit','2000M');
//exit;
session_start();
include_once( 'config.php' );
include_once( 'weibooauth.php' );



$o = new WeiboOAuth( WB_AKEY , WB_SKEY , $_SESSION['keys']['oauth_token'] , $_SESSION['keys']['oauth_token_secret']  );
//exit;
$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;

$_SESSION['last_key'] = $last_key;


$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
$test = $c->upload('卡机伤不起啊啊啊！彪悍女玩家网络暴走，咆哮捣毁整个页面！#核芯显卡拯救游戏世界#，流勒个畅！ http://minisite.youku.com/pgfx1/?f=duowan',WEBSITE."/duowan_share/images/share.png");
header('Location:../prize.php');
?>
