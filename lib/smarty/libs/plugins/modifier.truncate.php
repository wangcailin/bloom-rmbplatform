<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifier
 */
 
/**
 * Smarty truncate modifier plugin
 * 
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *               optionally splitting in the middle of a word, and
 *               appending the $etc string or inserting $etc into the middle.
 * 
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php truncate (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com> 
 * @param string $string input string
 * @param integer $length lenght of truncated text
 * @param string $etc end string
 * @param boolean $break_words truncate at word boundary
 * @param boolean $middle truncate in the middle of text
 * @return string truncated string
 */
function smarty_modifier_truncate($string, $length = 80, $etc = '...',
    $break_words = false, $middle = false)
{
    if ($length == 0)
        return '';
	
	$i = 0;
	$n = 0;
	$strLength = strlen($string);
	while ($n<$length && $i<$strLength)
	{
		$tempStr = substr($string,$i,1);
		$ascNum = ord($tempStr);
		if ($ascNum>=224)
		{
			$returnStr=$returnStr.substr($string,$i,3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
			$i = $i+3; //实际Byte计为3
			$n++; //字串长度计1
		}elseif ($ascNum>=192) //如果ASCII位高与192，
		{
			$returnStr = $returnStr.substr($string,$i,2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
			$i = $i+2; //实际Byte计为2
			$n++; //字串长度计1
		}elseif ($ascNum>=65 && $ascNum<=90) //如果是大写字母，
		{
			$returnStr=$returnStr.substr($string,$i,1);
			$i = $i+1; //实际的Byte数仍计1个
			$n++; //但考虑整体美观，大写字母计成一个高位字符
		}else //其他情况下，包括小写字母和半角标点符号，
		{
			$returnStr=$returnStr.substr($string,$i,1);
			$i = $i+1; //实际的Byte数计1个
			//$n = $n+0.5; //小写字母和半角标点等与半个高位字符宽...
			$n++;
		}
	}
	if($strLength>$i && $etc)
	{
		$returnStr = $returnStr . $etc;//超过长度时在尾处加上省略号
	}
	return $returnStr;
} 

?>