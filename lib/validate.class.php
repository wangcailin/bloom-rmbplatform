<?php
/**
 * 数据验证抽象类
 * @version 1.0
 * @package lib
 * @author 曹禹
 */
abstract class validate{
	//电子邮件
	public  function email($str){
	 return is_string($str)&&preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/',$str);
	}
	// url 地址
	public  function url($str){
	 return is_string($str)&&preg_match("/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/",$str);
	}
	// 电话号码
	public  function phone($str){
	$preg_array_pho='/^((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}$/';
	return preg_match($preg_array_pho,$str);
	
	}
	// ip 地址
	public  function ip($ip){ 
	     return preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])" . "(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $ip);
	} 
	//日期
	public  function dateate($var){ 
	return preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/",$var);
	} 
	//颜色
	public  function color($var){ 
	return preg_match("/^#?([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$/",$var);
	}
	//用户名 
	public  function userName($var){ 
	return preg_match("/^[a-zA-Z0-9_\.\-]{3,15}$/",$var);
	} 
	//是否为图片名
	public  function pic($var){ 
	return preg_match("/^[a-zA-Z0-9\-\.]+\.(jpg|jpeg|gif|png)$/",$var);
	}
	
	
	//验证是否为指定长度的字母/数字组合
	function fixtext($str,$arr)
	{
	
	Return (preg_match("/^[a-zA-Z0-9]{".$arr[0].",".$arr[1]."}$/",$str))?true:false;
	}
	
	//验证是否为指定长度数字
	function fixnumtext($str,$arr)
	{
	return (preg_match("/^[0-9]{".$arr[0].",".$arr[1]."}$/i",$str))?true:false;
	}
	//验证是否为指定长度汉字
	function fixzh($str,$arr)
	{
	// preg_match("/^[\xa0-\xff]{1,4}$/", $string);
	return (preg_match("/^([\x81-\xfe][\x40-\xfe]){".$arr[1].",".$arr[2]."}$/",$str))?true:false;
	}
	//验证身份证号码
	function idcard($str)
	{
	return (preg_match('/(^([\d]{15}|[\d]{18}|[\d]{17}x)$)/',$str))?true:false;
	}
	
	//验证是否为指定长度汉字
	function isnum($str)
	{
		// preg_match("/^[\xa0-\xff]{1,4}$/", $string);
		return $str==$str*1;
	}
	
	//验证邮编
	function zip($str)
	{
	return (preg_match("/^[1-9]d{5}$/",$str))?true:false;
	}
	
	//手机
	function mobile($str)
	{
		$exp = "/^13[0-9]{1}[0-9]{8}$|15[012356789]{1}[0-9]{8}$|18[012356789]{1}[0-9]{8}$|14[57]{1}[0-9]$/";
		if(preg_match($exp,$mobilephone))
		{
			return true;
		}else{
			return false;
		}
	}

}