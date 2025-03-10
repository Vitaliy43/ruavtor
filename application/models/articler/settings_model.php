<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model{
	
	public $symbol_escaped = '%';
	public $type_headings = array();
	public $brief_type_headings = array();
	public $last_type_heading;
	
	function __construct()
	{
		parent::__construct();
		$this->type_headings = $this->get_type_headings();
		$this->last_type_heading = $this->type_headings[count($this->type_headings) - 1]['id'];
		$this->brief_type_headings = $this->get_type_headings(true);
	}
	
	function get_setting($type, $key, $return = 'array', $property = null)
	{
		$this->db->select('*');
		$this->db->from('articler_settings');;
		$this->db->where('type',$type);
		$this->db->where('key',$key);
		$query = $this->db->get();
		if($return == 'array'){
			$result = $query->result_array();
			return $result[0];
		}
		elseif($return == 'property'){
			$result = $query->row();

			if($property == null)
				return $result->value;
			else
				return $result->$property;
		}
		
	}
	
	function get_type_heading($heading_id){
		
		$query = $this->db->get_where('headings', array('id' => $heading_id));
		return $query->row()->type_heading;
	}
	
	function get_setting_from_array($array, $key)
	{
		foreach($array as $elem){
			if($key == $elem['key'])
				return $elem['value'];
		}
	}
	
	function get_type_headings($briefly = false)
	{
		$query = $this->db->get('type_headings');
		$buffer = $query->result_array();
		if(!$briefly)
			return $buffer;
		foreach($buffer as $row){
			$arr[$row['id']] = $row['type'];
		}
		return $arr;
	}
	
	function get_all()
	{
		$query = $this->db->get('articler_settings');
		return $query->result_array();
	}
	
	function get_mail_template($key, $return = 'array', $property = null)
	{
		$this->db->select('*');
		$this->db->from('mail_templates');;
		$this->db->where('key',$key);
		$query = $this->db->get();
		if($return == 'array'){
			$result = $query->result_array();
			return $result[0];
		}
		elseif($return == 'property'){
			$result = $query->row();

			if($property == null)
				return $result->value;
			else
				return $result->$property;
		}
	}
	
	function set_setting($type, $key, $value)
	{
		$this->db->where('type',$type);
		$this->db->where('key',$key);
		if($type == 'mail_settings' || $key == 'interface_language'){

		}
		elseif($key == 'pay_for_visit' || $key == 'corrective_q'){
			$value = (float)$value;

		}
		else{
			$value = (int)$value;

		}
		$res = $this->db->update('articler_settings',array('value' => $value));
		return $res;
	}
	
	function validate_mail_template($key)
	{
		$this->db->select('elements');
		$this->db->from('mail_templates');
		$this->db->where('key', $key);
		$query = $this->db->get();
		$elements = explode(',',$query->row()->elements);
		$template = $this->input->post('template_'.$key);
		
		foreach($elements as $elem){
			
			if(stristr($template, '%'.$elem.'%') == '')
				return $elem;
		}
		
		return 1;
		
	}
	
	function set_mail_template($key, $text)
	{
		$this->db->where('key',$key);
		$res = $this->db->update('mail_templates',array('text' => $text));
		return $res;
	}
	
}


?>