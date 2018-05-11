<?php
session_start();
include_once( 'config.php' );
include_once( 'weibooauth.php' );

print_r($_SESSION);
//$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
$c = new WeiboClient( WB_AKEY , WB_SKEY , 'cffd79846824aba7b5f5c381bea6f4b0' , 'd662a64bba71caaedba910e2717dc522'  );
//print_r($c->delete('11643333759'));
//exit;
$ms  = $c->user_timeline(); // done
print_r($ms);
$me = $c->verify_credentials();
print_r($me);
echo "<br>";
$test = $c->friends(1,1,1792000967);
print_r($test);
exit;
?>
<h2><?=$me['name']?> 你好~ 要换头像么?</h2>
<form action="weibolist.php" >
<input type="text" name="avatar" style="width:300px" value="头像url" />
&nbsp;<input type="submit" />
</form>
<h2>发送新微博</h2>
<form action="weibolist.php" >
<input type="text" name="text" style="width:300px" />
&nbsp;<input type="submit" />
</form>

<h2>发送图片微博</h2>
<form action="weibolist.php" >
<input type="text" name="text" style="width:300px" value="文字内容" />
<input type="text" name="pic" style="width:300px" value="图片url" />
&nbsp;<input type="submit" />
</form>
<?php

if( isset($_REQUEST['text']) || isset($_REQUEST['avatar']) )
{

if( isset($_REQUEST['pic']) )
	$rr = $c ->upload( $_REQUEST['text'] , $_REQUEST['pic'] );
elseif( isset($_REQUEST['avatar']  ) )
	$rr = $c->update_avatar( $_REQUEST['avatar'] );
else
	$rr = $c->update( $_REQUEST['text'] );	

	echo "<p>发送完成</p>" ; 

}

?>

<?php if( is_array( $ms ) ): ?>
<?php foreach( $ms as $item ): ?>
<?php print_r($item);?>
<div style="padding:10px;margin:5px;border:1px solid #ccc">
<?=$item['text'];?>
</div>
<?php endforeach; ?>
<?php endif; ?>



