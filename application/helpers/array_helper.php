<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
    if (!function_exists('my_print_r'))
    {
        function my_print_r($array = array())
        {
            echo "<pre>";
                print_r($array);
            echo "</pre>";
        }
    }
	
	/*
	Возвращает часть массива, элементы с одинаковым значением. С параметром assoc_key выбираются значения по указанному ключу.
	 */
	if (!function_exists('array_group'))
    {
        function array_group($array, $value, $assoc_key = false, $sort_by_assoc_key = false, $sort_type = 'ASC')
        {
			$new_arr = array();
            foreach($array as $k=>$v){
			  if(!$assoc_key){
			  	
			  
				if($v == $value){
					$new_arr[$k] = $v;
				}
			 }
			  else{
			  	
				 if($v[$assoc_key] == $value){
					$new_arr[$k] = $v;
				}
			  }
			  
			  
			}
			
			if($sort_by_assoc_key){
				
			}
			
			return $new_arr;
        }
    }
	
	///////////////////////////////////////////// Извлекает массив уникальных значений с выборкой по указаннму ключу /////////
	
	if (!function_exists('array_complex_unique'))
    {
        function array_complex_unique($array, $key)
        {
			$new_arr = array();
            foreach($array as $elem){	 
			 	$new_arr[] = $elem[$key];
			}	
			$new_arr = array_unique($new_arr);	
					
			return $new_arr;
        }
    }
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    if (!function_exists('is_true_array'))
    {
        function is_true_array($array)
        {
            if ($array == false)
                return false;

            if (sizeof($array) > 0)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
	
	//////////////////////////////////////////// Поиск в массиве либо по ключу, либо по значению /////////////////////////
		
	if (!function_exists('array_complex_search'))
		 {
		 	
		function array_complex_search($array, $search, $type){
				
			if($type == 'value'):
				foreach($array as $elem){
					
					if(strpos($elem, $search))
						return $elem;
					
			}
				
			else:
				$arr = array_keys($array);
				
				foreach($arr as $elem){
				
					if(strpos($elem,'/')){
						$buffer = explode('/',$elem);
						if($buffer[1] == $search)
							return $elem;
					}
					else{
						if($elem == $search)
							return $elem;
					}
					
								
				}
					
			endif;
				
			return false;
		}
			
			
	}
		
  }
?>
