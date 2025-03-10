<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('db_select')) :

 function db_select($table,$extract,$where="",$debug=null){
     if(!$where)
			$where=1;
	 if($extract<>"*"){
	 $buffer=explode(",",$extract);
	foreach($buffer as $element){
		$temp.="`".$element."`,";
	}
	$temp=substr($temp,0,-1);
	$extract=$temp;
	}

	$query="select $extract from $table where $where";

	if($debug)
       echo "query $query <br>";
    $res=mysql_query($query);
    if(mysql_num_rows($res)>0){
       $num_fields=mysql_num_fields($res);

 	for($i=0;$i<mysql_num_rows($res);$i++):
    for($k=0;$k<$num_fields;$k++){
    $field=mysql_field_name($res,$k);
    $result[$i][$field]=mysql_result($res,$i,$field);                                       

	}
	endfor;
 	}
    if(count($result) > 0)  
		return $result;                                                 
	return false;

   }                                   

endif;

if ( !function_exists('db_select_row')):

function db_select_row($table,$extract,$where="",$debug=null){
																
	$buffer=db_select($table,$extract,$where,$debug);
	if($buffer)							
		return $buffer[0];
	return false;
									
	}

endif;

if ( !function_exists('db_insert')):
function db_replace($table, $fields, $debug = false){
	
 	$query="REPLACE INTO $table VALUES(";
        foreach($fields as $key=>$value){

           if(!isset($value))$value="null";
			   
            if($value=="" and is_integer($value) or is_float($integer))$value="null";

              if(is_string($value) and $value<>"null") 
				$value="'".$value."'";
              	$query.="$value,";
              }

          $query=substr($query,0,-1);
          $query.=")";
		  $res = mysql_query($query);
		  if($debug)
		  	return $query;
			  
			  return $res;
	}
endif;

?>