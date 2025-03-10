<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

	function media_url($url = '')
	{
		$CI =& get_instance();
		$config = $CI->config;

		if (is_array($url))
		{
			$uri = implode('/', $url);
		}

		$index_page = $config->slash_item('index_page');
		if($index_page === '/')
			$index_page = '';

		$return = $config->slash_item('static_base_url').$index_page.preg_replace("|^/*(.+?)/*$|", "\\1", $url);
		return $return;
	}

    function whereami(){
        $CI =& get_instance();
        if($CI->uri->segment(1))
            return 'inside';
        else
            return 'mainpage';
    }
	
function my_file_get_contents($link)
	{
	$uagent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8";

		$ch = curl_init($link);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвращает веб-страницу
curl_setopt($ch, CURLOPT_HEADER, 0); // не возвращает заголовки
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // переходит по редиректам
curl_setopt($ch, CURLOPT_ENCODING, ""); // обрабатывает все кодировки
curl_setopt($ch, CURLOPT_USERAGENT, $uagent); // useragent
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // таймаут соединения
curl_setopt($ch, CURLOPT_TIMEOUT, 120); // таймаут ответа
curl_setopt($ch, CURLOPT_MAXREDIRS, 10); // останавливаться после 10-ого редиректа
    	$screen = curl_exec($ch); 
    	curl_close($ch);  
		return $screen; 
	}
	
	function check_http_status($url)
  {
  $user_agent = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0)';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_VERBOSE, false);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//  curl_setopt($ch, CURLOPT_SSLVERSION, 3);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  $page = curl_exec($ch);

  $err = curl_error($ch);
  if (!empty($err))
    return $err;

  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  return $httpcode;

  }
    
