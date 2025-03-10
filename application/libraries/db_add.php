<?php

class DB_add {
	
	static $connect;
	
	static function init(){
		$buffer = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/application/config/config.php');
		$arr = explode('$active_record = TRUE;',$buffer);
		$arr2 = explode('Base Site URL',$arr[1]);
		$arr3 = explode(';',$arr2[0]);
		foreach($arr3 as $row){
			$temp = explode(' = ',$row);
			$temp[1] = str_replace("'",'',$temp[1]);
			if(strpos($temp[0],'hostname')){
				$hostname = $temp[1];
			}
			elseif(strpos($temp[0],'username')){
				$username = $temp[1];
			}	
			elseif(strpos($temp[0],'password')){
				$password = $temp[1];
			}	
			elseif(strpos($temp[0],'database')){
				$database = $temp[1];
			}
		}
		
		if($hostname && $username && $password && $database){
			$res = mysql_connect($hostname,$username,$password);
			if($res)
				self::$connect = $res;
			mysql_select_db($database);
		}

	}
	
	static function close(){
		
		mysql_close(self::$connect);
	}
}

?>