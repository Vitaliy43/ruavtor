<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('image_get_sizes')) :
function image_get_sizes($filename,$max_height, $max_width){
 	
	$sizes = getimagesize($filename);
	$width = $sizes[0];
	$height = $sizes[1];
	$proportion = $height/$width;
	if($width > $height){
		$result['width'] = $max_width;
		$result['height'] = round($max_width * $proportion);
	}
	else{
		$result['height'] = $max_height;
		$result['width'] = round($max_height / $proportion);
	}
	
	return $result;
		
 }
 
 endif;
 

?>