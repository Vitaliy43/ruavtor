<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Image CMS
 */

class Admin extends MY_Controller {

	private $mod_name = 'user_manager';
	protected $author_fields = array(
	'username' => 'Логин',
	'email' => 'E-Mail',
	'last_login' => 'Последний вход',
	'created' => 'Создан'
	);

	function __construct()
	{
		parent::__construct();

		// only admin access
        $this->load->library('DX_Auth');

		$this->load->library('Form_validation');
	}


	/*
	 * Index function of user_manager admin
	 * Select roles and displau main template
	 */
	function index()
	{
		$this->set_tpl_roles();
		$this->display_tpl('main');
	}

	/*
	 * Assign template roles
	 */
	function set_tpl_roles()
	{
		// roles
		$query = $this->db->get('roles');
		$this->template->assign('roles',$query->result_array());
		// roles
	}

	/*
	 * Generate table with user with pagination
	 * Ajax usage
	 * Calling from javascript from main.tpl
	 */
	function genre_user_table($sort = null)
	{
        cp_check_perm('user_view_data');

		$this->load->model('dx_auth/users', 'users');
		$this->load->library('pagination');
		$uriSegments = $this->uri->segment_array();
		$is_number = preg_match('#\d+#', $this->uri->segment(6), $matches);

		if($is_number){
			$offset = (int) $this->uri->segment(6);
			$sort = null;
		}
		else{
			$offset = (int) $this->uri->segment(7);		
			$sort = $this->uri->segment(6);
		}
			
		$row_count = 50;
		

		// Get all users
		if(!$sort){
			$users = $this->users->get_all($offset, $row_count, 'username', 'ASC');

		}
		elseif($sort == 'last_login' || $sort == 'created'){
		
			$users = $this->users->get_all($offset, $row_count, $sort);

		}
		else{
			$users = $this->users->get_all($offset, $row_count, $sort, 'ASC');

		}


		// Begin pagination
		if($is_number){
			$config['base_url'] = site_url('admin/components/cp/user_manager/genre_user_table');
			$config['uri_segment'] = 6;
		}
		else{
			$config['base_url'] = site_url('admin/components/cp/user_manager/genre_user_table/'.$uriSegments[6]);
			$config['uri_segment'] = 7;

		}
		
		$config['container'] = 'users_ajax_table';
		$config['total_rows'] =  $this->users->get_all()->num_rows();
		$config['per_page'] = $row_count;
		$this->pagination->initialize($config);
		$this->pagination->container = 'users_ajax_table';
		$this->template->assign('paginator',$this->pagination->create_links_ajax());
		// End pagination

		$users = $users->result_array();

		for ($i = 0, $users_c = count($users); $i < $users_c; $i++) {
			if($users[$i]['banned'] == 1)
			$users[$i]['banned'] = 'Да';
			else
			$users[$i]['banned'] = 'Нет';
		}
		
		if($sort)
			$select_sort = form_dropdown('sort',$this->author_fields,$sort,'id="users_sort" onchange="users_ajax_sort();"');
		else
			$select_sort = form_dropdown('sort',$this->author_fields,'username','id="users_sort" onchange="users_ajax_sort();"');

		$this->template->assign('select_sort',$select_sort);
		$this->template->assign('users',$users);
		$this->template->assign('cur_page',$offset);

		echo $this->fetch_tpl('users_table');
	}

	/*
	 * Register new user
	 */
	function create_user()
	{
		$this->load->model('dx_auth/users', 'user2');
		$val = $this->form_validation;

                $val->set_rules('username', lang('amt_user_login'), 'trim|required|xss_clean|alpha_dash');
                $val->set_rules('password', lang('amt_password'), 'trim|min_length['.$this->config->item('DX_login_min_length').']|max_length['.$this->config->item('DX_login_max_length').']|required|xss_clean');
                $val->set_rules('password_conf', lang('amt_new_pass_confirm'), 'matches[password]|required');
		$val->set_rules('email', lang('amt_email'), 'trim|required|xss_clean|valid_email');

        ($hook = get_hook('users_create_set_val_rules')) ? eval($hook) : NULL;

		$user  = $this->input->post('username');
		$email = $this->input->post('email');
        $role  = $this->input->post('role'); 

		// check user
		if($this->user2->check_username($user)->num_rows() > 0)
		{
			showMessage(lang('amt_login_exists'),false,'r');
			exit;
		}

		// check user mail
		if($this->user2->check_email($email)->num_rows() > 0)
		{
			showMessage(lang('amt_email_exists'),false,'r');
			exit;
		}

            if (!check_perm('user_create') AND !check_perm('user_create_all_roles'))
            {
                cp_check_perm('user_create');
            }

            if (!check_perm('user_create_all_roles'))
            {
                $role = $this->dx_auth->get_role_id();
            }

			if ($val->run() AND $this->dx_auth->register($val->set_value('username'), $val->set_value('password'), $val->set_value('email')))
			{
                ($hook = get_hook('users_user_created')) ? eval($hook) : NULL;

				//set user role
				$user_info = $this->user2->get_user_by_username($user)->row_array();
				$this->user2->set_role($user_info['id'], $role);

                $this->lib_admin->log(lang('amt_create_user').$val->set_value('username'));

				showMessage(lang('amt_user_created'));
			}else{
				showMessage (validation_errors(),false,'r');
			}

	}

	/*
	 * Ban, unban or delete users
	 */
	function actions($action, $return_to_page = 0)
	{
		$this->load->model('dx_auth/users', 'users');

		foreach ($_POST as $k => $value)
		{
			// If checkbox found
			if (substr($k, 0, 9) == 'checkbox_')
			{
				switch ($action)
				{
					case 1: // ban
                        cp_check_perm('user_edit');
                        ($hook = get_hook('users_ban')) ? eval($hook) : NULL;
						$this->users->ban_user($value);
                        $this->lib_admin->log(lang('amt_banned_user').$value);
					break;

					case 2: //unban
                        cp_check_perm('user_edit');
                        ($hook = get_hook('users_unban')) ? eval($hook) : NULL;
						$this->users->unban_user($value);
                        $this->lib_admin->log(lang('amt_unbanned_user').$value);
					break;

					case 3: //delete
                        cp_check_perm('user_delete');
                        ($hook = get_hook('users_delete')) ? eval($hook) : NULL;
						$this->users->delete_user($value);
                        $this->lib_admin->log(lang('amt_deleted_user').$value); 
					break;
				}
			}
		}

		updateDiv('users_ajax_table',site_url('admin/components/cp/user_manager/genre_user_table/'.$return_to_page));

	}

	/*
	 * Search users
	 */
	function search()
	{
        cp_check_perm('user_view_data');

		$s_data = $this->input->post('s_data');
		$role = $this->input->post('role');
		$page = (int) $this->uri->segment(8);

		$this->db->select("users.*", FALSE);
		$this->db->select("roles.name AS role_name", FALSE);
		$this->db->select("roles.alt_name AS role_alt_name", FALSE);
		$this->db->join("roles", "roles.id = users.role_id");
		$this->db->like('username',$s_data);
		$this->db->or_like('email',$s_data);
		$this->db->order_by('created','desc');

		$query = $this->db->get('users');

		if($query->num_rows() == 0)
		{
			showMessage(lang('amt_users_not_found'),false,'r');
		}else{
			$users = $query->result_array();

			for ($i = 0, $users_c = count($users); $i < $users_c; $i++) {
				if($users[$i]['banned'] == 1)
				$users[$i]['banned'] = 'Да';
				else
				$users[$i]['banned'] = 'Нет';

				if($role != 0)
				{
					if($users[$i]['role_id'] != $role)
					{
						unset($users[$i]);
					}
				}
			}

			// recount users
			if(count($users) == 0) {
				showMessage(lang('amt_users_not_found'),false,'r');
				exit;
			}

			$this->template->assign('users',$users);
			$rezult_table = $this->fetch_tpl('users_table_search');

			echo $rezult_table;
		}
	}
	
	function update_settings(){
		
		cp_check_perm('roles_edit'); 
		$this->load->model('articler/settings_model');

		if(isset($_POST['update'])){
			if(isset($_POST['register_ban'])){
				$this->settings_model->set_setting('user_settings','ban_after_registration',1);
			}
			else{
				$this->settings_model->set_setting('user_settings','ban_after_registration',0);
			}
		}
		showMessage('Настройки обновлены');

	}

	/*
	 * Show edit_users form
	 */
	function edit_user($user_id)
	{
        cp_check_perm('user_edit');

		$this->load->model('dx_auth/users', 'users');

		$user = $this->users->get_user_by_id($user_id);

		if($user->num_rows() == 0)
		{
			exit(lang('amt_users_not_found'));
		}else{
			$this->template->add_array($user->row_array());
			$this->set_tpl_roles();
			$this->display_tpl('edit_user');
		}
	}

	/*
	 * Update used data
	 */
	function update_user($user_id)
	{
        cp_check_perm('user_edit');

		$this->load->model('dx_auth/users', 'user2');

		$val = $this->form_validation;

		$val->set_rules('username', lang('amt_user_login'), 'trim|required|xss_clean');
                $val->set_rules('new_pass', lang('amt_password'), 'trim|max_length['.$this->config->item('DX_login_max_length').']|xss_clean');
                $val->set_rules('new_pass_conf', lang('amt_new_pass_confirm'), 'matches[new_pass]');

		$val->set_rules('email', lang('amt_email'), 'trim|required|xss_clean|valid_email');

		$user_data = $this->user2->get_user_field($user_id,array('username','email'))->row_array();
                
                if(strlen($this->input->post('new_pass')) !== 0)
                    {
                    $val->set_rules('new_pass', lang('amt_password'), 'trim|min_length['.$this->config->item('DX_login_min_length').']|max_length['.$this->config->item('DX_login_max_length').']|required|xss_clean');
                    $val->set_rules('new_pass_conf', lang('amt_new_pass_confirm'), 'matches[new_pass]|required');
                    }

		if($user_data['username'] != $this->input->post('username'))
		{
			if($this->user2->check_username($this->input->post('username'))->num_rows() > 0)
			{
				showMessage(lang('amt_login_exists'),false,'r');
				exit;
			}
		}

		if($user_data['email'] != $this->input->post('email'))
		{
			if($this->user2->check_email($this->input->post('email'))->num_rows() > 0)
			{
				showMessage(lang('amt_email_exists'),false,'r');
				exit;
			}
		}

			if ($val->run())
			{
				$data = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'role_id' => $this->input->post('role_id'),
				'banned' => $this->input->post('banned'),
				'ban_reason' => $_POST['ban_reason']
				);

					//change password
					if($this->input->post('new_pass'))
					{
						$new_pass = crypt($this->dx_auth->_encode($this->input->post('new_pass')));
						$data['password'] = $new_pass;
					}

                ($hook = get_hook('users_user_update')) ? eval($hook) : NULL;

				$this->db->where('id',$user_id);
				$this->db->update('users',$data);

                $this->lib_admin->log(lang('amt_updated_user').$data['username']);

				showMessage(lang('amt_changes_saved'));

			}else{
				showMessage (validation_errors(),false,'r');
			}
	}

    /*************************************
     * Groups
     ************************************/

	function groups_index()
	{
		$query = $this->db->get('roles');
		$this->template->assign('roles',$query->result_array());
		$this->display_tpl('groups');
	}

	function create()
	{
        cp_check_perm('roles_create');

		$this->form_validation->set_rules('name', lang('amt_identif'), 'required|trim|max_length[150]|min_length[2]|alpha_dash');
		$this->form_validation->set_rules('alt_name', lang('amt_tname'), 'required|trim|max_length[150]|min_length[2]');
		$this->form_validation->set_rules('desc', lang('amt_description'), 'trim|max_length[300]|min_length[2]');

		if ($this->form_validation->run($this) == FALSE)
		{
			showMessage (validation_errors(),false,'r');
		}
        else
        {
			$data = array(
					'name'     => $this->input->post('name'),
					'alt_name' => $this->input->post('alt_name'),
					'desc'     => $this->lib_admin->db_post('desc')
						);

            ($hook = get_hook('users_create_role')) ? eval($hook) : NULL;

			$this->db->insert('roles',$data);

            $this->lib_admin->log(lang('amt_created_group').$data['name']);

			showMessage(lang('amt_group_created'));
            $this->update_groups_block();
		}
	}

	function edit($id)
	{
        cp_check_perm('roles_edit'); 

		$this->db->where('id',$id);
		$query = $this->db->get('roles',1);

		if($query->num_rows() > 0)
		{
			$this->template->add_array($query->row_array());
		}

		$this->display_tpl('groups_edit');
	}
	
	function settings(){
		
		cp_check_perm('roles_edit');
		$this->load->model('articler/settings_model');
		$this->load->model('articler/articler_model');
		$this->template->assign('ban',$this->articler_model->ban_after_register);
		$this->display_tpl('settings');
	}

	function save($id)
	{
        cp_check_perm('roles_edit'); 

		$this->form_validation->set_rules('alt_name', lang('amt_name'), 'required|trim|max_length[150]|min_length[2]');
		$this->form_validation->set_rules('name', lang('amt_identif'), 'required|trim|max_length[150]|min_length[2]|alpha');
		$this->form_validation->set_rules('desc', lang('amt_description'), 'trim|max_length[500]|min_length[2]');

		if ($this->form_validation->run($this) == FALSE)
		{

			showMessage (validation_errors(),false,'r');

		}else{

			$data = array(
						'name' => $this->input->post('name'),
						'alt_name' => $this->input->post('alt_name'),
						'desc' => $this->lib_admin->db_post('desc')
						);

			switch($id)
			{
				case 1:
				$data['name'] = 'user';
				break;

				case 2:
				$data['name'] = 'admin';
				break;
			}

            ($hook = get_hook('users_update_role')) ? eval($hook) : NULL;

			$this->db->limit(1);
			$this->db->where('id',intval($id));
			$this->db->update('roles',$data);

            $this->lib_admin->log(lang('amt_changed_group').$id);

			showMessage(lang('amt_group_saved'));
            $this->update_groups_block();
            closeWindow('group_edit_window');
		}
	}

	function delete($id)
    {
        cp_check_perm('roles_delete');

		switch($id)
		{
			case 1:
			case 2:
    			showMessage(lang('amt_error_deleting'),false,'r');
	    		exit;
			break;
		}

        ($hook = get_hook('users_delete_role')) ? eval($hook) : NULL;

		$this->db->limit(1);
		$this->db->where('id',intval($id));
		$this->db->delete('roles');

        $this->lib_admin->log(lang('amt_deleted_group').$id); 

		$this->update_groups_block();
	}

    function update_groups_block()
    {
        updateDiv('groups_block', site_url('admin/components/cp/user_manager/groups_index')); 
    }

    function update_role_perms()
    {
        cp_check_perm('roles_edit');

        $this->load->model('dx_auth/permissions', 'permissions');
        //$permission_data = $this->permissions->get_permission_data($this->input->post('role'));
        $permission_data = array();

        $all_perms = $this->get_permissions_table();

        foreach ($all_perms as $k => $v)
        {
            if (isset($_POST[$k]) AND $_POST[$k] == 1)
            {
                $permission_data[$k] = 1;
            }
        }
        
        if (count($permission_data) > 0)
        {
            $this->permissions->set_permission_data($this->input->post('role_id'), $permission_data);
        }
        else
        {
            $this->db->where('role_id', $this->input->post('role_id'));
            $this->db->delete('permissions');
        }

        showMessage(lang('amt_changes_saved'));
    }

    function show_edit_prems_tpl($selected_role = 1)
    {
        $this->load->model('dx_auth/permissions', 'permissions');
        $permissions = $this->permissions->get_permission_data($selected_role);

        $all_perms = $this->get_permissions_table();

        // Explode all perms to groups by prefix
        $groups = array();
        foreach ($all_perms as $k => $v)
        {
            $tmp = explode('_', $k);
            $groups[$tmp[0]][$k] = $v;
        }

        foreach ($groups as $key => $row) 
        {
            $count[$key]  = count($row);
        }

        array_multisort($count, SORT_ASC, $groups); 
    
        $this->template->add_array(array(
            'selected_role' => $selected_role,
            'roles'         => $this->db->get('roles')->result_array(),
            'all_perms'     => $all_perms,
            'permissions'   => $permissions,
            'groups'        => $groups,
            'group_names'   => $this->get_group_names(),
        ));

        $this->display_tpl('edit_perms');
    }

    function get_permissions_table()
    {
        return get_permissions_array();
    }

    function get_group_names()
    {
        return get_perms_groups();
    }

    ////////////////////////////////////////// 

    // Template functions
	private function display_tpl($file)
	{
        $file =  realpath(dirname(__FILE__)).'/templates/'.$file.'.tpl';  
		$this->template->display('file:'.$file);
	}

	private function fetch_tpl($file)
	{
        $file =  realpath(dirname(__FILE__)).'/templates/'.$file.'.tpl';  
		return $this->template->fetch('file:'.$file);
	}

}

/* End of file admin.php */
