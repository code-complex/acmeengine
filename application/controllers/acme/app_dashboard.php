<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* --------------------------------------------------------------------------------------------------
*
* Controller App_Dashboard
* 
* Dashboard padr�o da aplica��o. Por padr�o, tela de entrada dos usu�rios do grupo ROOT.
*
* @since	15/10/2012
*
* --------------------------------------------------------------------------------------------------
*/
class App_Dashboard extends ACME_Module_Controller {

	/**
	* __construct()
	* Construtor de classe.
	* @return object
	*/
	public function __construct()
	{
		parent::__construct(__CLASS__);
	}
	
	/**
	* index()
	* Tela de dashboard.
	* @return void
	*/
	public function index()
	{
		// Valida permiss�o de visualiza��o de dashboard
		$this->validate_permission('VIEW_DASHBOARD');
		
		// M�dulos da app
		$args['modules'] = $this->db->from('acm_module')->order_by('label')->get()->result_array();

		// Carrega view
		$this->template->load_page('_acme/app_dashboard/index', $args);
	}
}
