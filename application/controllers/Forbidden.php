<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Forbidden extends CI_Controller {
		
		var $data;
		var $title;
		function __construct(){
			parent::__construct();
				
			$this->load->database();				/* Cargamos la base de datos */
			$this->load->library('grocery_crud');	/* Cargamos la libreria*/   
			$this->load->helper('url'); 			/* Añadimos el helper al controlador */
			$this->title = "Adminsitración de Grupos de Usuario";
		}
		
		function index(){
			//redirect('login/index');
			$this->load->helper(array('form'));
							
			$crud = new grocery_CRUD();				/* Creamos el objeto */

			$data->title = $this->title;
			/* Cargamos en la vista*/
			$this->load->view('templates/heads', $data);
			$this->load->view('templates/header',$this->session->userdata);
			$this->load->view('templates/aside');
			$this->load->view('templates/forbidden');
			$this->load->view('templates/footer');
		}
	
	}

?>