<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* --------------------------------------------------------------------------------------------------
*
* Model forms_Model
* 
* List of forms
*
* Generated by application module maker.
*
* @since 	23/07/2014
* @author 	leandro.w3c@gmail.com
*
* --------------------------------------------------------------------------------------------------
*/
class forms_Model extends CI_Model {
	// Definição de Atributos
	
	/**
	* __construct()
	*/
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	* example()
	* Model example method. When some controller invoke by the call 
	* $this->forms_Model->example(), this method will be triggered.
	* @return void
	*/
	public function example()
	{
		// How to manipulate queries
		/*
		$sql = "SELECT * FROM table";
		$data = $this->db->query($sql)->result_array();
		*/
	}
}