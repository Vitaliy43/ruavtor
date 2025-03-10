<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('mb_ucfirst'))
{
	function mb_ucfirst($string)
	{
		$first_symbol=mb_substr($string,0,1);
		$rest=mb_substr($string,1);
		return mb_strtoupper($first_symbol).$rest;
	}
}

if ( ! function_exists('generate_password'))
{
function generate_password($number, $param = 1)
{
	$arr = array('a','b','c','d','e','f',
	'g','h','i','j','k','l',
	'm','n','o','p','r','s',
	't','u','v','x','y','z',
	'A','B','C','D','E','F',
	'G','H','I','J','K','L',
	'M','N','O','P','R','S',
	'T','U','V','X','Y','Z',
	'1','2','3','4','5','6',
	'7','8','9','0','.',',',
	'(',')','[',']','!','?',
	'&','^','%','@','*','$',
	'<','>','/','|','+','-',
	'{','}','`','~');
	// Генерируем пароль
	$pass = "";
	for($i = 0; $i < $number; $i++)
	{
	if ($param>count($arr)-1)$param=count($arr) - 1;
	if ($param==1) $param=48;
	if ($param==2) $param=58;
	if ($param==3) $param=count($arr) - 1;
	// Вычисляем случайный индекс массива
	$index = rand(0, $param);
	$pass .= $arr[$index];
	}
	return $pass;
}

}

if ( ! function_exists('win2utf'))
{

function win2utf($str)
{
    static $table = array(
    "\xA8" => "\xD0\x81",
    "\xB8" => "\xD1\x91",
    "\xA1" => "\xD0\x8E",
    "\xA2" => "\xD1\x9E",
    "\xAA" => "\xD0\x84",
    "\xAF" => "\xD0\x87",
    "\xB2" => "\xD0\x86",
    "\xB3" => "\xD1\x96",
    "\xBA" => "\xD1\x94",
    "\xBF" => "\xD1\x97",
    "\x8C" => "\xD3\x90",
    "\x8D" => "\xD3\x96",
    "\x8E" => "\xD2\xAA",
    "\x8F" => "\xD3\xB2",
    "\x9C" => "\xD3\x91",
    "\x9D" => "\xD3\x97",
    "\x9E" => "\xD2\xAB",
    "\x9F" => "\xD3\xB3",
    );
    return preg_replace('#[\x80-\xFF]#se',
    ' "$0" >= "\xF0" ? "\xD1".chr(ord("$0")-0x70) :
                       ("$0" >= "\xC0" ? "\xD0".chr(ord("$0")-0x30) :
                        (isset($table["$0"]) ? $table["$0"] : "")
                       )',
    $str
    );
}
}

function splitterWord($word,$maxlength){

	$step = $maxlength;	
	if(mb_strlen($word) > $maxlength){
		return mb_substr($word,0,$maxlength).'...';
	}
	return $word;
	
}

function cutImage($src,$text){
	$res = preg_match_all('/<img(?:\\s[^<>]*)?>/i',$text,$matches);
	if(!$res)
		return $text;
	foreach($matches[0] as $match){
		if(strpos($match,$src))
			$text = str_ireplace($match,'',$text);
		
	}
	return $text;
}


?>