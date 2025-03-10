<?php

class Articler_Users extends Users
{
	public $statuses = array('publicist','journalist','expert');
	
	function Users()
	{
		parent::Users();
	}
	
	function change_email($user_id, $new_email)
	{
		$this->db->set('email', $new_email);
		$this->db->where('id', $user_id);
		return $this->db->update($this->_table);
	}
	
	function get_email($user_id)
	{
		$this->db->select('email');
		$this->db->from($this->_table);
		$this->db->where('id',$user_id);
		$query = $this->db->get();
		return $query->row()->email;
	}
	
	function check_password($password, $user_id)
	{	
		$buffer = $this->get_user_by_id($user_id);
		$user = $buffer->row();
		$stored_hash = $user->password;
		if (crypt($password, $stored_hash) === $stored_hash){
		
				return true;
		}
	
		return false;
	}
	
	function get_password($user_id)
	{
		$buffer = $this->get_user_by_id($user_id);
		$user = $buffer->row();
		return $user->password;
	}
	
	function get_email_by_user_id($user_id){
		
		$query = $this->db->get_where('users', array('id' => $user_id));
		return $query->row()->email;
	}
	
	function isset_user_id($user_id){
		
		$query = $this->db->get_where('users', array('id' => $user_id));
		if($query->row()){
			return true;
		}
		return false;
	}
	
	function get_authors($limit = null, $order = null, $statistic_table){
		
		if($limit == null)
			$limit = '';
		else
			$limit = 'LIMIT '.$limit;
		$buffer_order = $order;
			
		if($order == null)
			$order = 'ORDER BY users.username';
		else
			$order = 'ORDER BY '.$order;
			
		if($buffer_order == 'author_rating' || $buffer_order == 'num_articles' || $buffer_order == 'sum_statistic')
			$order .= ' DESC';
			
//		$sql = "SELECT users.*,authors.name,authors.family,ratings.rating 'author_rating',articler_score.score,ratings_activity.rating 'rating_activity',COUNT(articles.id) 'num_articles' FROM users,authors,articles,articler_score,ratings,ratings_activity WHERE articles.user_id = users.id AND authors.user_id = users.id AND ratings.user_id = users.id AND ratings_activity.user_id = users.id AND articler_score.user_id = users.id AND articles.activity = 2 GROUP BY users.id $order $limit ";
		$sql = "SELECT users.*,authors.name,authors.family,ratings.rating 'author_rating',articler_score.score,ratings_activity.rating 'rating_activity',COUNT(articles.id) 'num_articles',SUM({$statistic_table}.num_all) AS 'sum_statistic' FROM users JOIN authors ON users.id = authors.user_id LEFT JOIN articles ON users.id = articles.user_id LEFT JOIN articler_score ON users.id = articler_score.user_id  LEFT JOIN ratings ON users.id = ratings.user_id LEFT JOIN ratings_activity ON users.id = ratings_activity.user_id 
		LEFT JOIN {$statistic_table} ON articles.id = {$statistic_table}.article_id
		WHERE articles.activity = 2 GROUP BY users.id $order $limit ";
		$query = $this->db->query($sql);
        return $query->result_array();
	}
	
	function get_author($id, $statistic_table){
		
//		$sql = "SELECT users.*,authors.corrective_q,ratings.rating 'author_rating',articler_score.score,ratings_activity.rating 'rating_activity' FROM users,articler_score,ratings,ratings_activity WHERE ratings.user_id = users.id AND ratings_activity.user_id = users.id AND articler_score.user_id = users.id AND author.user_id = users.id AND users.id = $id";
		$sql = "SELECT users.*,ratings.rating 'author_rating',articler_score.score,ratings_activity.rating 'rating_activity',SUM({$statistic_table}.num_all) AS 'sum_statistic' FROM users LEFT JOIN articler_score ON users.id = articler_score.user_id LEFT JOIN ratings ON users.id = ratings.user_id LEFT JOIN ratings_activity ON users.id = ratings_activity.user_id LEFT JOIN authors ON users.id = authors.user_id
		LEFT JOIN articles ON articles.user_id = users.id
		LEFT JOIN {$statistic_table} ON articles.id = {$statistic_table}.article_id
		WHERE users.id = $id";
		$query = $this->db->query($sql);
        $result = $query->result_array();
//		if($result[0]['corrective_q'] != 1){
//			$result[0]['correct_sum_statistic'] = round($result[0]['corrective_q'] * $result[0]['sum_statistic']);
//		}
		$query_payments = $this->db->get_where('articler_payments', array('user_id' => $id));
		if($query_payments->num_rows() != 0){
			$result[0]['payments'] = $query_payments->row()->payment;
		}
		$query_payouts = $this->db->get_where('articler_payouts', array('user_id' => $id));
		if($query_payouts->num_rows() != 0){
			$result[0]['payouts'] = $query_payouts->row()->payout;
		}
		
		return $result[0];
		
	}
	
	function get_user_id_by_author($author){
		
		$author = trim($author);
		$author = strip_tags($author);
		$author = str_replace('Рубрика:','',$author);
		$author = str_replace('Рубрика','',$author);
		if(mb_strlen($author) > 50)
			$author = '';
		list($name, $family) = split(' ',$author);
		$author = $name.' '.$family;
		
			$query = $this->db->get_where('authors', array('fullname' => $author));
			if($query->row())
				return $query->row();
			
		return false;
	}
	
	function get_balance($user_id){
		
		$this->db->select_sum('payment');
		$query = $this->db->get_where('articler_payments', array('user_id' => $user_id));
		$sum_payment = $query->row()->payment;
		
		$this->db->select_sum('payout');
		$query = $this->db->get_where('articler_payouts', array('user_id' => $user_id, 'status' => 1));
		$sum_payout = $query->row()->payout;
		
		return $sum_payment - $sum_payout;
	}
	
	function update_author($user_id){
		
		$author_rating = $this->input->post('author_rating');
		$rating_activity = $this->input->post('rating_activity');
		$score = $this->input->post('score');
		$payments = $this->input->post('payments');
		$payouts = $this->input->post('payouts');
		$corrective_q = $this->input->post('corrective_q');
		
			$query = $this->db->get_where('articler_score',array('user_id' => $user_id));
			$current_rating = $query->row()->score;
			if(isset($_POST['score'])){
				$this->db->set('score',$score);
            	$this->db->where('user_id', $user_id);
				$res = $this->db->update('articler_score');
				if(!$res)
					return false;
			}
			
			if(isset($_POST['corrective_q'])){
				$this->db->set('corrective_q',$corrective_q);
            	$this->db->where('user_id', $user_id);
				$res = $this->db->update('authors');
				if(!$res)
					return false;
			}
				
			if(isset($_POST['payments'])){
				$query_payment = $this->db->get_where('articler_payments', array('user_id' => $user_id));
				$this->db->set('payment',$payments);
				$this->db->where('id', $query_payment->row()->id);
				$res = $this->db->update('articler_payments');
				$new_balance = $this->get_balance($user_id);
				$query_payment = $this->db->get_where('articler_payments', array('user_id' => $user_id));
				$this->db->where('id', $query_payment->row()->id);
				$this->db->set('balance', $new_balance);
				$res = $this->db->update('articler_payments');

				$this->db->set('score',$new_balance);
            	$this->db->where('user_id', $user_id);
				$res = $this->db->update('articler_score');
			}
			
			if(isset($_POST['payouts'])){
				$query_payout = $this->db->get_where('articler_payouts', array('user_id' => $user_id));
				$this->db->set('payout',$payouts);
				$this->db->where('id', $query_payout->row()->id);
				$res = $this->db->update('articler_payouts');
				$new_balance = $this->get_balance($user_id);
				$query_payout = $this->db->get_where('articler_payouts', array('user_id' => $user_id));
				$this->db->where('id', $query_payout->row()->id);
				$this->db->set('balance', $new_balance);
				$res = $this->db->update('articler_payouts');

				$this->db->set('score',$new_balance);
            	$this->db->where('user_id', $user_id);
				$res = $this->db->update('articler_score');
			}
		
			$query = $this->db->get_where('ratings',array('user_id' => $user_id));
			$current_rating = $query->row()->rating;

			$this->db->set('rating',$author_rating);
            $this->db->where('user_id', $user_id);
			$res = $this->db->update('ratings');
			if(!$res)
				return false;
			
			$change_rating = $author_rating - $current_rating;
		
			$data = array(
			'id' => null,
			'user_id' => $user_id,
			'change_rating' => $change_rating,
			'type' => 'author',
			'data' => date("Y-m-d H:i:s")
			);
		
			$res = $this->db->insert('change_rating',$data);
			if(!$res)
				return false;
				
			$query = $this->db->get_where('ratings_activity',array('user_id' => $user_id));
			$current_rating = $query->row()->rating;
			
			$this->db->set('rating',$rating_activity);
            $this->db->where('user_id', $user_id);
			$res = $this->db->update('ratings_activity');
			if(!$res)
				return false;
		
		$change_rating = $rating_activity - $current_rating;
		
		$data = array(
		'id' => null,
		'user_id' => $user_id,
		'change_rating' => $change_rating,
		'type' => 'activity',
		'data' => date("Y-m-d H:i:s")
		);
		
		$res = $this->db->insert('change_rating',$data);
		if(!$res)
			return false;
			
			return true;
	}
	
	function is_setted_author_parameters($user_id){
		
		$query = $this->db->get_where('articler_score', array('user_id' => $user_id));
		if(count($query->result_array()) > 0)
			return false;
		return true;
	}
	
		///////////////////////// Назначение или смена кошелька//////////////////////////////////
	
	function update_purse($user_id, $purse){
	
			$this->db->where('user_id', $user_id);
			$res = $this->db->update('articler_score', array('purse' => $purse));
	
		return $res;
	}
	
	
	function set_author_parameters($user_id){
		
		$data = array(
		'id' => null,
		'user_id' => $user_id,
		'score' => 0
		);
		$this->db->insert('articler_score',$data);
		
		$data = array(
		'user_id' => $user_id,
		'rating' => 0,
		'data_modified' => date("Y-m-d H:i:s")
		);
		$this->db->insert('ratings',$data);
		
		$data = array(
		'user_id' => $user_id,
		'rating' => 0,
		'data_modified' => date("Y-m-d H:i:s"),
		'type_last_change' => ''
		);
		$this->db->insert('ratings_activity',$data);
	}
	
	function get_moderator_email(){
	
		$sql = "SELECT email FROM users WHERE role_id = (SELECT id FROM roles WHERE name = 'moderator')";
		$query = $this->db->query($sql);
		if($query->row()->email)
			return $query->row()->email;
		return false;
	}
	
	function create_refer($user_id,$refer_id,$data_create){
		$refer_id = (int)$refer_id;
		if(!$refer_id)
			return false;
		$data = array(
		'id' => null,
		'user_id' => $user_id,
		'refer_id' => $refer_id,
		'data' => $data_create
		);
		if(isset($_COOKIE['refer_link']))
			$data['url'] = $_COOKIE['refer_link'];
		$res = $this->db->insert('user_refers',$data);
		return $res;
	}
}

?>