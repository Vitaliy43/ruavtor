<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( !function_exists('time_change_show_data')) :
function time_change_show_data($data){
 	
	$buffer = explode(' ',$data);
	$date = $buffer[0];
	$time = $buffer[1];
	$buffer2 = explode('-',$date);
	$year = $buffer2[0];
	$month = $buffer2[1];
	$day = $buffer2[2];
	$new_date=$day.'.'.$month.'.'.$year;
	$new_data=$new_date.' '.$time;
	
	return $new_data;
		
 }
 
 endif;
 
 if ( !function_exists('time_db_to_mktime')) :
function time_db_to_mktime($data){
 	
	$buffer = explode(' ',$data);
	$date = $buffer[0];
	$time = $buffer[1];
	$buffer1 = explode(':', $time);
	$hour = $buffer1[0];
	$minute = $buffer1[1];
	$second = $buffer1[2];
	$buffer2 = explode('-',$date);
	$year = $buffer2[0];
	$month = $buffer2[1];
	$day = $buffer2[2];
	$day = cut_zero($day);
	$month = cut_zero($month);
	return mktime($hour,$minute,$second,$month,$day,$year);
		
 }
 
 endif;
 
 if ( !function_exists('time_expire_period')) :

 ////////////////////////////////////////// Проверяем, вышел ли период блокировки ///////////////////////
 function time_expire_period($data1,$data2,$period){
 	
	if($data2 - $data1 > $period)
		return true;
	return false;
 }
 
 endif;
 
 function last_day($month){
 	$month = cut_zero($month);
 	$days_month=array(1=>31,2=>28,3=>31,4=>30,5=>31,6=>30,7=>31,8=>31,9=>30,10=>31,11=>30,12=>31);
	$leap=date('L');
	if($leap)$days_month[2]=29;
	return $days_month[$month];

 }
 
 if ( !function_exists('prev_day')) :

 ////////////////////////////////////////// Выдаем предыдущий от указанного день ///////////////////////
 function prev_day(){
 	
	$day=date('j');
	$month=date('n');
	$year=date('Y');
	$leap=date('L');
	$days_month=array(1=>31,2=>28,3=>31,4=>30,5=>31,6=>30,7=>31,8=>31,9=>30,10=>31,11=>30,12=>31);
	
	$prev_day=$day-1;
	
	if($leap)$days_month[2]=29;
	
	if($prev_day=='0'){
		
		
		if($month<>0){
			$prev_day=$days_month[$month-1];
			$prev_year=$year;
			$prev_month=$month-1;
				
		}
		else{
			$prev_month=12;
			$prev_day=31;
			$prev_year=$year-1;	
			
		}
	
	
	}
	
	else{
			
		$prev_day=$day-1;
		$prev_year=$year;
		$prev_month=$month;	
			
	}
	
	$data=$prev_year.'-'.add_zero($prev_month).'-'.add_zero($prev_day);
	return $data;
 }
 
 endif;
 
 if ( !function_exists('extract_date')) :

 ////////////////////////////////////////// Извлекаем дату, отсекая время создания статьи ///////////////////////
 function extract_date($data){
 	
	$data = time_change_show_data($data);
	$buffer = explode(' ',$data);
	$date = $buffer[0];
	return $date;
	
 }
 
 endif;
 
 
 
  if ( !function_exists('compare_date')) :

 ////////////////////////////////////////// Сравниваем даты по какому-либо параметру ///////////////////////
 function compare_date($data1, $data2, $parameter){
 	
	$date_arr1 = array();
	$date_arr2 = array();
	
	$buffer = explode(' ',$data1);
	$date = $buffer[0];
	$time = $buffer[1];
	
	$buffer2 = explode('-', $date);
	$date_arr1['year'] = $buffer2[0];
	$date_arr1['month'] = $buffer2[1];
	$date_arr1['day'] = $buffer2[2];
	
	$buffer3 = explode(':', $time);
	$date_arr1['hour'] = $buffer3[0];
	$date_arr1['minute'] = $buffer3[1];
	$date_arr1['second'] = $buffer3[2];
	
	$buffer4 = explode(' ', $data2);
	$date = $buffer4[0];
	$time = $buffer4[1];
	
	$buffer5 = explode('-', $date);
	$date_arr2['year'] = $buffer5[0];
	$date_arr2['month'] = $buffer5[1];
	$date_arr2['day'] = $buffer5[2];
	
	$buffer6 = explode(':', $time);
	$date_arr2['hour'] = $buffer6[0];
	$date_arr2['minute'] = $buffer6[1];
	$date_arr2['second'] = $buffer6[2];
	
	if($date_arr1[$parameter] == $date_arr2[$parameter])
		return true;
	return false;
 }
 
 endif;
 
  if(!function_exists('add_zero')) :
 
  function add_zero($num){

         if(strlen($num)==1) $num='0'.$num;
         return $num;

        }
  endif;

  if(!function_exists('cut_zero')) :
        function cut_zero($num){

        if(strlen($num)==2 and substr($num,0,1)=='0') $num=substr($num,-1);
        return $num;

        }
  endif;
  
   function time_from_url($time){

   		if(strlen($time) == 10){
			$day = substr($time,0,2);
			$deduct = 0;
		}
		else{
			$day = substr($time,0,1);
			$day = add_zero($day);
			$deduct = 1;

		}
		
		$month = substr($time,2 - $deduct,2);
		$year = '20'.substr($time,4 - $deduct,2);
		return $year.'-'.$month.'-'.$day.' 00:00:00';
   }
  
   if(!function_exists('time_text_data')) :
   	
   	function time_text_data($date_from_db, $reverse = false, $language = 'russian'){
		if(!$language)
			$language = 'russian';
		$show_months = get_months_langs($language,'show_months');
		$months_number = get_months_langs($language,'months_number');
		
		if(!$reverse){
			

			$day = date('d', $date_from_db);
			$month = date('M', $date_from_db);
			$year = date('Y', $date_from_db);
			if($language == 'russian')
				$new_date = $day.' '.$show_months[$month].' '.$year;
			else
				$new_date = ucfirst($show_months[$month]).' '.$day.', '.$year;
			
			
		}	
			
		else {
			
		
			$buffer = explode(' ', $date_from_db);
			$day = $buffer[0];
			$month = $buffer[1];
			$year = $buffer[2];
			$number_month = add_zero($months_number[$month]);
			$new_date = $year.'-'.$number_month.'-'.add_zero($day).' 00:00:00';
		}
		
		return $new_date;
	}
   
   
   endif;
   
   	function time_convert_date_to_text($date_from_db, $language = 'russian'){
		if(!$language)
			$language = 'russian';
			$months_number = get_months_langs($language,'months_number');
			$arr = array_combine(array_values($months_number),array_keys($months_number));
			$buffer = explode(' ',$date_from_db);
			$date = $buffer[0];
			$time = $buffer[1];
			$buffer2 = explode('-',$date);
			$year = $buffer2[0];
			$month = $buffer2[1];
			$day = $buffer2[2];
			$month = cut_zero($month);
			if($language == 'russian')
				return $day.' '.$arr[$month].' '.$year;
			return ucfirst($arr[$month]).' '.$day.', '.$year;
	}
   
  
  
    if(!function_exists('dates_in_prev_month')) :
        function dates_in_prev_month($month, $year, $type = 'begin'){
		
			 $time_days_month = array(1=>31,2=>28,3=>31,4=>30,5=>31,6=>30,7=>31,8=>31,9=>30,10=>31,11=>30,12=>31);

			if($year%4){
				$v = 1;
			}
			else{
				$v = 0;
			}
		
			$time_days_month[2] += $v;
       		$prev_month = $month - 1;
			if($prev_month == 0){
				$prev_month = 12;
				$year -= 1;
				
			}
	   		
			$last_day = $time_days_month[$prev_month];
			if($type == 'begin')
				return $year.'-'.add_zero($prev_month).'-'.add_zero(1);
			else
				return $year.'-'.add_zero($prev_month).'-'.$last_day;
        }
  endif;
  
  function data_to_db($data,$add_time=false)
	{
		
		if(strstr($data,'.')<>''):
		
		$buffer=explode('.',$data);
		if($add_time)
			$new_data="$buffer[2]-$buffer[1]-$buffer[0] ".date('H:i:s');
		else
			$new_data="$buffer[2]-$buffer[1]-$buffer[0] 00:00:00";

		
		else:
		
		$new_data=$data;
		
		endif;
		
		return $new_data;
		
		
	}
	
	function get_months_langs($language,$index){
		$arr['russian'] = array(
		'months_number' => array('января' => 1, 'февраля' => 2, 'марта' => 3, 'апреля' => 4, 'мая' => 5, 'июня' => 6, 'июля' => 7, 'августа' => 8, 'сентября' =>  9, 'октября' => 10, 'ноября' => 11, 'декабря' => 12),
		'show_months' => array('Jan' => 'января', 'Feb' => 'февраля', 'Mar' => 'марта', 'Apr' => 'апреля', 'May' => 'мая', 'Jun' => 'июня', 'Jul' => 'июля', 'Aug' => 'августа', 'Sep' => 'сентября', 'Oct' => 'октября', 'Nov' => 'ноября', 'Dec' => 'декабря')
		);
		
		$arr['english'] = array(
		'months_number' => array('january' => 1, 'february' => 2, 'march' => 3, 'april' => 4, 'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8, 'september' =>  9, 'october' => 10, 'november' => 11, 'december' => 12),
		'show_months' => array('Jan' => 'january', 'Feb' => 'february', 'Mar' => 'march', 'Apr' => 'april', 'May' => 'may', 'Jun' => 'june', 'Jul' => 'july', 'Aug' => 'august', 'Sep' => 'september', 'Oct' => 'october', 'Nov' => 'november', 'Dec' => 'december')
		);
		
		
		return $arr[$language][$index];
	}


?>