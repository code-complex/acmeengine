<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* --------------------------------------------------------------------------------------------------
*
* Controller teste_pg2
* 
* 
*
* Generated by application module maker.
*
* @since 	04/08/2014
* @author 	leandro.w3c@gmail.com
*
* --------------------------------------------------------------------------------------------------
*/
class teste_pg2 extends ACME_Module_Controller {
	// Class attributes
	
	/**
	* __construct()
	*/
	public function __construct()
	{
		parent::__construct(__CLASS__);
	}
	
	/**
	* index()
	* Controller default method. Show a html table list with results 
	* of sql_list of module.
	* @return void
	*/
	public function index()
	{
		parent::index();
	}
	
	/**
	* example()
	* Example method of this controller. When an URL like teste_pg2/example 
	* will be called, this method is triggered.
	* @return void
	*/
	public function example()
	{
		$this->validate_permission('EXAMPLE');

		$this->template->load_page('controller_name/script', $array_of_arguments);
	}
}
