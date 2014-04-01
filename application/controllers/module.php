<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* --------------------------------------------------------------------------------------------------
*
* Controller Module
* 
* Dashboard padr�o da aplica��o. Por padr�o, tela de entrada dos usu�rios do grupo ROOT.
*
* @since	15/10/2012
*
* --------------------------------------------------------------------------------------------------
*/
class Module extends ACME_Module_Controller {

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
		parent::index();
	}
}
