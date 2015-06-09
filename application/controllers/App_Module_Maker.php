<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * --------------------------------------------------------------------------------------------------
 * Controller App_Module_Maker
 *
 * Application module maker. Build other modules with this module.
 *
 * @since 	15/10/2012
 * --------------------------------------------------------------------------------------------------
 */
class App_Module_Maker extends ACME_Controller {

	/**
	 * Class constructor.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Module index. Redirect for new module page.
	 *
	 * @return void
	 */
	public function index()
	{
		$this->validate_permission('ENTER');

		// Redirect to new module
		redirect('app-module-maker/new-module');
	}

	/**
	 * Module index. Show page for creating a new module.
	 *
	 * @param boolean process
	 * @return void
	 */
	public function new_module($process = false)
	{
		$this->validate_permission('CREATE_MODULE');

		$path_permissions = $this->_check_permissions();

		$timezone = $this->_check_timezone();

		if( ! $process)
		{
			// Load all groups
			$groups = $this->db->select('id_user_group AS id, name')->from('acm_user_group')->order_by('name')->get()->result_array();

			// Build group options
			$args['groups'] = json_encode(array('results' => array_change_key_case_recursive($groups, CASE_LOWER)));

			// Check permissions for all paths
			$args['path_permissions'] = $path_permissions;

			// Timezone
			$args['timezone'] = $timezone;

			// Load view
			$this->template->load_view( $this->controller . '/new-module', $args);
		} else {

			// Set time process to zero
			set_time_limit(0);

			// Controller used as a key
			$controller = $this->input->post('controller');

			// Check if module already exist
			if ($this->_module_exists($controller))
				redirect('app-module-maker/new-module');

			// Get table name
			$table = $this->input->post('table_name');

			// Start building a new module!
			$module['def_file'] = json_encode($this->input->post());
			$module['table_name'] = $table;
			$module['controller'] = $controller;
			$module['label'] = $this->input->post('label');
			$module['description'] = $this->input->post('description');
			$module['sql_list'] = $this->input->post('sql_list');
			$module['url_img'] = $this->input->post('url_img');

			// $this->db->trans_start();

			// Insert a module
			$this->db->insert('acm_module', $module);

			// Get the module from db now
			$module = $this->db->from('acm_module')->where(array('controller' => $controller))->get()->row_array(0);

			// Catch id_module now
			$id_module = get_value($module, 'id_module');

			// Check if table exists
			$table_exist = false;

			foreach ($this->db->list_tables() as $idx => $db_table)
				if ( strtolower($table) == strtolower($db_table) )
				{
					$table_exist = true;
					break;
				}

			// Create forms, menus, actions and permissions
			// Only if table name was set
			$forms = $this->input->post('forms') == '' ? array() : $this->input->post('forms');
			if( $table_exist )
				foreach ($forms as $operation)
				{
					$form = array();
					$form['id_module'] = $id_module;
					$form['operation'] = $operation;

					// Create form
					$this->db->insert('acm_module_form', $form);

					// Get form from db
					$form = $this->db->from('acm_module_form')->where(array('id_module' => $id_module, 'operation' => $operation))
									 ->get()
									 ->row_array(0);

					// Now form id
					$id_form = get_value($form, 'id_module_form');

					// Table fields meta-data
					$fields = $this->db->field_data($table);

					// Loop fields until find correct column
					foreach ($fields as $field)
					{
						// Build field to insert on acm_module_form_field
						$field_data = $this->form->build_field($field, $table);
						$field_data['id_module_form'] = $id_form;

						// Loop fields to insert
						foreach($field_data as $index => $value)
						{
							$escape = ($value != 'NULL') ? true : false;
							$this->db->set($index, $value, $escape);
						}

						// Insert it!
						$this->db->insert('acm_module_form_field');
					}

					// And also for each form we have to create menus, permissions and actions
					$data['id_module'] = $id_module;
					$data['label'] = lang(ucwords($operation));
					$data['link'] = '{URL_ROOT}/' . str_replace('_', '-', strtolower( $controller )) . '/form/' . $operation;

					// If operation is update, delete or view then add first column (usually is ID)
					if ($operation != 'insert')
						$data['link'] .= '/{0}';

					// Now permission
					$permission['id_module'] = $id_module;
					$permission['label'] = lang(ucwords($operation) . ' form');
					$permission['permission'] = strtoupper($operation);

					// Adjust table name
					$table_ins = $operation == 'insert' ? 'acm_module_menu' : 'acm_module_action';

					// Do it!
					$this->db->insert($table_ins, $data);
					$this->db->insert('acm_module_permission', $permission);
				}

			// Insert one more permission for entering
			$permission['id_module'] = $id_module;
			$permission['permission'] = 'ENTER';
			$permission['label'] = lang('Module entrance');

			$this->db->insert('acm_module_permission', $permission);

			// Automatically gives all permissions for the user creator
			$permissions = $this->db->get_where('acm_module_permission', array('id_module' => $id_module))->result_array(0);

			foreach($permissions as $module_permission)
			{
				$usr_permission['id_user'] = $this->session->userdata('id_user');
				$usr_permission['id_module_permission'] = get_value($module_permission, 'id_module_permission');
				$this->db->insert('acm_user_permission', $usr_permission);
			}

			// Now create menus for application for all groups matched
			$groups = $this->input->post('menu_groups') == '' ? array() : $this->input->post('menu_groups');
			$menu['link'] = '{URL_ROOT}/' . str_replace('_', '-', strtolower( $controller ));
			foreach ($groups as $group)
			{
				$menu['id_user_group'] = $group;
				$menu['label'] = ucwords( $this->input->post('label') );

				$this->db->insert('acm_menu', $menu);
			}

			// And finally, create all files needed
			// Controller
			$file_controller = file_get_contents('application/core/engine-files/Maker_Template_Controller.php');
			$file_controller = str_replace('<CLASS_NAME>', $controller, $file_controller);
			$file_controller = str_replace('<DESCRIPTION>', $this->input->post('description'), $file_controller);
			$file_controller = str_replace('<CREATION_DATE>', date('d/m/Y'), $file_controller);
			$file_controller = str_replace('<AUTHOR>', $this->session->userdata('email'), $file_controller);
			file_put_contents('application/controllers/' . $controller . '.php', $file_controller);

			// Model
			$file_model = file_get_contents('application/core/engine-files/Maker_Template_Model.php');
			$file_model = str_replace('<CLASS_NAME>', $controller, $file_model);
			$file_model = str_replace('<DESCRIPTION>', $this->input->post('description'), $file_model);
			$file_model = str_replace('<CREATION_DATE>', date('d/m/Y'), $file_model);
			$file_model = str_replace('<AUTHOR>', $this->session->userdata('email'), $file_model);
			file_put_contents('application/models/' . $controller . '_Model.php', $file_model);

			// View
			@mkdir('application/views/' . TEMPLATE . '/' . $controller);

			// Complete all transaction
			// $this->db->trans_complete();

			// Build args for view
			$args['module'] = $module;
			$args['link'] = $menu['link'];

			// Load view
			$this->template->load_view( $this->controller . '/new-module-success', $args);
		}
	}

	/**
	 * Check if a module with the given controller already exist. The controller
	 * name must be forwarded by POST. Print a json as return.
	 *
	 * @return void
	 */
	public function check_controller()
	{
		// The module with given controller name already exist?
		echo json_encode(array('return' => $this->_module_exists($this->input->post('controller'))));
	}

	/**
	 * Check if a module exists according with give name. Returns boolean.
	 *
	 * @param string controller
	 * @return boolean
	 */
	private function _module_exists($controller = '')
	{
		// Build query according with driver
        switch (strtolower(DB_DRIVER))
        {
            // MySQL driver + PostgreSQL driver
            case 'mysql':
            case 'mysqli':
            case 'postgre':
            case 'oci8':
                $sql = "SELECT COUNT(*) AS COUNT_MODULE FROM acm_module WHERE LOWER(controller) = LOWER('" . $controller . "')";
            break;
        }

        // Module exists?
        return get_value($this->db->query($sql)->row_array(0), 'count_module') >= 1 ? true : false;
	}

	/**
	 * Check permissions for needed paths and files:
	 * 		=> application/controllers
	 * 		=> application/models
	 * 		=> application/views
	 * 		=> application/core/engine-files/Maker_Template_Controller.php
	 * 		=> application/core/engine-files/Maker_Template_Model.php
	 *
	 * Returns true or false in case of doesnt has permissions for write.
	 *
	 * @return boolean has-permissions
	 */
	private function _check_permissions()
	{
		if ( is_writable('application/controllers')
			 && is_writable('application/models')
			 && is_writable('application/views')
			 && is_readable('application/core/engine-files/Maker_Template_Model.php')
			 && is_readable('application/core/engine-files/Maker_Template_Controller.php')
		   )
			return true;
		else
			return false;
	}

	/**
	 * Check value for php.ini setting date.timezone.
	 *
	 * @return boolean is_set
	 */
	private function _check_timezone()
	{
		if ( ini_get('date.timezone') != false )
			return true;
		else
			return false;
	}
}