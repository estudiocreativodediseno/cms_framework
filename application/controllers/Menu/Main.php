<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Heredamos de la clase CI_Controller */
class Main extends CI_Controller {

	var $title;
  function __construct(){
   
    parent::__construct();
 
	/* Validamos si el usuario está en sesión*/
   	if(!isset($this->session->userdata['username']))
		redirect('login/index');
		
	$permission = $this->CmsPermission->getUserModulePermission();
    
	$this->session->set_userdata('moduleData',array('moduleId' => 0));

	//print_r($this->session->userdata['moduleData']);
    /* Añadimos el helper al controlador */
	
    $this->load->database();				/* Cargamos la base de datos */
    $this->load->library('grocery_crud');	/* Cargamos la libreria*/   
    $this->load->helper('url'); 			/* Añadimos el helper al controlador */

	$this->title = "Menú Principal";
		
  }

  function index(){
    redirect('menu/main/proccess');
  }

  /*
   *
   **/
  function proccess(){
		try{
	
			$data->title = $this->title;
			
			$data->output->js_files = array(	'jquery.min'	=>base_url()."resources/js/jquery.min.js");
			/* Cargamos en la vista*/
			$this->load->view('templates/heads', $data);
			$this->load->view('templates/header',$this->session->userdata);
			$this->load->view('templates/aside');
			$this->load->view('menu/main', $data);
			$this->load->view('templates/footer');
	
		}catch(Exception $e){
			  /* Si	 algo sale mal cachamos el error y lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
  }
  
  
}