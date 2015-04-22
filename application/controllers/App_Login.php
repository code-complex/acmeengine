<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * --------------------------------------------------------------------------------------------------
 * App_Login
 *
 * Application login. Handle all application login/logout.
 *
 * @since	14/04/2015
 * --------------------------------------------------------------------------------------------------
 */
class App_Login extends ACME_Controller {

	/**
	 * Define if controller is external or not.
	 *
	 * 		TRUE:
	 * 			=> Session validation is skipped
	 * 			=> Database layer is not loaded
	 * 			=> Module/controller is not loaded from database
	 *
	 * 		FALSE:
	 * 			=> Session is validated
	 * 			=> Database layer is loaded
	 * 			=> Module/controller is attempted to loaded
	 *
	 * @var boolean
	 */
	protected $external = true;

	/**
	 * Class constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		// Check if ACME Framework is already installed
		if ( ! $this->acme_installed )
			redirect('app-installer');
	}

	/**
	 * Default action, call login page.
	 *
	 * @return void
	 */
	public function index()
	{
		$this->login();
	}

	/**
	 * Login page.
	 *
	 * @return void
	 */
	public function login()
	{
		// Get email for errors
		$args['email_user'] = $this->session->userdata('email_user');
		$this->session->unset_userdata('email_user');

		// Get email error message
		$args['email_msg_error'] = $this->session->userdata('email_msg_error');
		$this->session->unset_userdata('email_msg_error');

		// Boolean defining if error is in email input
		$args['bool_email_error'] = $this->session->userdata('bool_email_error');
		$this->session->unset_userdata('bool_email_error');

		// Load view
		$this->template->load_view( $this->controller . '/login', $args, false, false);
	}

	/**
	 * Process login page/form. After validate login, redirect user to his home.
	 *
	 * @return void
	 */
	public function login_process()
	{
		$email = $this->input->post('email');
		$pass = $this->input->post('pass');

		// Try to get the user by email and pass
		$user = $this->access->validate_login($email, $pass);

		// Case user doesnt exist, redirect to login page again
		if( ! $user ) {

			// Error message
			$this->session->set_userdata('email_user', $email);
			$this->session->set_userdata('bool_email_error', true);

			if ( $email == '' )
				$this->session->set_userdata('email_msg_error', lang('Enter your email address'));
			elseif ( $email != '' )
				$this->session->set_userdata('email_msg_error', lang('The given email address or password are incorrect'));

			redirect('app-login');

		} else {
			// Check url default for user, if not exist try to redirect to default dashboard
			$url_default = (get_value($user, 'url_default') != '') ? $this->tag->tag_replace(get_value($user, 'url_default')) : URL_ROOT . '/app_dashboard/';

			// Put some user data in session
			$session['id_user'] = get_value($user, 'id_user');
			$session['user_group'] = get_value($user, 'user_group');
			$session['user_name'] = get_value($user, 'user_name');
			$session['email'] = get_value($user, 'email');
			$session['user_img'] = tag_replace(get_value($user, 'url_img'));
			$session['language'] = get_value($user, 'lang_default');
			$session['url_default'] = $url_default;
			$session['login_access'] = true;

			// Set the data above
			$this->session->set_userdata($session);

			// Log the login
			$this->logger->db_log('login', 'login');

			// Redirect to user's home
			redirect($url_default);
		}
	}

	/**
	 * Application exit. Clear all session data and redirect to login.
	 *
	 * @return void
	 */
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('app-login');
	}

	/**
	 * For 404 pages.
	 *
	 * @return void
	 */
	public function not_found()
	{
		$this->error->show_404();
	}

	/**
	 * Requirement new pass form for users.
	 *
	 * @param boolean process
	 * @return void
	 */
	public function forgot_password($process = false)
	{
		if( ! $process)
			$this->template->load_view($this->controller . '/forgot-password', array(), false, false);
		else {

			// collect email and validation
			$email = $this->input->post('email');
			$validate = $this->input->post('validate_human');

			// check consistency
			if($email == '' || $validate == '')
				redirect($this->controller_name . '/forgot-password');

			// try to find user data
			$user = $this->db->get_where('acm_user', array('email' => $email))->row_array(0);

			// user doesnt exist
			if( count($user) <= 0 )
				$this->template->load_view($this->controller . '/forgot-password', array('error' => lang('Ops! There is no users registered with this email address')), false, false);

			else {

				$args['user'] = $user;
				$args['id_user'] = get_value($user, 'id_user');
				$args['key_access'] = md5(uniqid());

				// collect email body msg
				$body_msg = $this->template->load_view('App_User/email-reset-password', $args, true, false);

				// now try to send email
				$this->load->library('email');
				$this->email->clear();
			    $this->email->to(get_value($user, 'email'));
			    $this->email->from(EMAIL_FROM, APP_NAME);
			    $this->email->subject(lang('Reset password'));
			    $this->email->message($body_msg);

			    if( ! @$this->email->send() )
					$this->template->load_view($this->controller . '/forgot-password', array('error' => lang('Ops! It was not possible to send the email message')), false, false);
				else {

					// log asking for reset pass
					$data['id_user'] = $args['id_user'];
					$data['key_access'] = $args['key_access'];

					$this->logger->db_log(lang('Reset password request'), 'reset_password', '', $data);

					// load page success!
					$this->template->load_view($this->controller . '/forgot-password', array('success' => lang('OK! One message containing all steps to reset your password was forwarded to ') . $email), false, false);

				}
			}
		}
	}

	/**
	 * Form to reset user password. The parameters (iduser, key_access) must be valid in
	 * order to get access to this form. The second parameter says to process this same form.
	 *
	 * @param int id_user
	 * @param string key_access
	 * @param boolean process
	 * @return void
	 */
	public function reset_password($id_user = 0, $key_access = '', $process = false)
	{
		if($id_user == '' || $key_access == '')
			redirect($this->controller_name);

		// Var to check if exist any valid log
		$valid = false;
		$id_log = 0;

		// Collect all reset_passwords logs
		$data = $this->db->get_where('acm_log', array('action' => 'reset_password'))->result_array();

		// Loop all logs, trying to find a valid reset password log
		foreach ($data as $log) {

			$reset = json_decode(get_value($log, 'additional_data'), true);

			if(get_value($reset, 'id_user') == $id_user && get_value($reset, 'key_access') == $key_access) {
				$valid = true;
				$id_log = get_value($log, 'id_log');
				break;
			}
		}

		// Well, this is not a valid token or this reset already used
		if( ! $valid)
			redirect( $this->controller_name );

		// This is not a process action
		if( ! $process)
			$this->template->load_view($this->controller . '/reset-password', array('id_user' => $id_user, 'key_access' => $key_access), false, false);
		else {

			// Get user's new pass
			$password = $this->input->post('password');
			$p_repeat = $this->input->post('password_repeat');

			// Bad passwords
			if($password == '' || $p_repeat == '' || $password != $p_repeat)
				redirect($this->controller_name);

			// Update user data
			$this->db->update('acm_user', array('password' => md5($password)), array('id_user' => $id_user));

			// Delete log
			$this->db->delete('acm_log', array('id_log' => $id_log));

			// Load view (update ok)
			$this->template->load_view($this->controller . '/reset-password', array('id_user' => $id_user, 'key_access' => $key_access, 'success' => lang('OK! Password successfully changed')), false, false);
		}
	}

	/**
	 * Verify if session is valid and active (user logged). Print JSON return as response.
	 *
	 * @return void
	 */
	public function check_session()
	{
		$json = array('check_session' => $this->access->check_session());
		echo json_encode($json);
	}

	/**
	 * Change current language on session.
	 *
	 * @param string language 	// en_US, pt_BR, es_ES ...
	 * @return string json
	 */
	public function change_language($language = '')
	{
		$this->session->set_userdata('language', $language);
		echo json_encode(array('return' => true));
	}

	/**
	 * This is a very useful action. It catch all lang() function calls from
	 * every file on entire project and build a language file containing all
	 * translatable indexes.
	 *
	 * BE CAREFUL: THIS FUNCTION ERASE ALL CONTENT OF LANGUAGE FILES.
	 *
	 * @return object
	 */
	/*
	 *
	public function build_translation_file()
	{
		set_time_limit(0);

		$this->load->helper('file');

		$lang_calls = array();
		$lang_indexes = array();

		// First foreach all project files
		foreach(get_filenames('application', true) as $key => $file) {

			// get content of current file
			$content = read_file($file);

			// match any call of lang()
			if(preg_match_all('/lang[ ]*[(][ ]*[\'"][^\'")]*[\'"][ ]*[)]/i', $content, $matches))
			{

				// For every match, put inside array of indexes and indexes per file
				foreach($matches[0] as $key => $match) {

					// Translatable array
					$lang_indexes[] = $match;

					// List every call per file
					$lang_calls[str_replace(getcwd() . '/', '', $file)][] = $match;

				}

			}
		}

		// Remove duplicate keys
		$lang_indexes = array_unique($lang_indexes);

		// Order indexes
		natsort($lang_indexes);

		// Content of new file
		$before = '';
		$content = "<?php\n\n// Application language indexes";

		// Now put in the new file the array of translatable indexes
		foreach ($lang_indexes as $key => $match) {

			$match = trim(preg_replace('/lang[ ]?[(][ ]?/i', '', $match), ') ');
			$content .= "\n" . '$lang[' . $match . '] = ' . $match . ';';

		}

		// Now insert on translate file all calls grouped by file, just for reading
		foreach ($lang_calls as $file => $matches) {

			if($file != $before)
				$content .= "\n\n// File " . $file;

			foreach ($matches as $key => $match) {

				$match = trim(preg_replace('/lang[ ]?[(][ ]?/i', '', $match), ') ');

				$content .= "\n" . '// -> $lang[' . $match . '] = ' . $match . ';';
			}
		}

		// create files
		$languages = array('pt_BR', 'en_US');

		foreach($languages as $language)
			file_put_contents('application/language/' . $language . '/app_lang.php', $content);
	}
	*/
}
