<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Heredamos de la clase CI_Controller */
class Dashboard extends CI_Controller {

	var $title;
  function __construct(){
   
    parent::__construct();

	/* Validamos si el usuario está en sesión*/
   	if(!($this->session->userdata['username']))
		redirect('login/index');	
		  
	if(strlen($this->input->get('mod'))>0)
		$this->session->set_userdata('moduleId',$this->input->get('mod'));
	$permission = $this->CmsPermission->getUserModulePermission();
	
    $this->load->database();				/* Cargamos la base de datos */
    $this->load->library('grocery_crud');	/* Cargamos la libreria*/   
    $this->load->helper('url'); 			/* Añadimos el helper al controlador */
	
	//$this->parts['head'] = $this->load->view("templates/heads.php", null, true);
	//$this->scripts = array("JQuery/jquery-1.4.2.min", "JQuery/form", "Core", "Frontend");
	//$this->styles = array("style");
	$this->title = "Adminsitración de Usuarios";
		
  }

  function index(){
    /*
     * Mandamos todo lo que llegue a la funcion
     * administracion().
     **/
    redirect('menu/dashboard/show');
  }

  /*
   *
   **/
  function show(){
		try{
			
			
			$crud = new grocery_CRUD();				/* Creamos el objeto */
			$crud->set_theme('flexigrid');			/* Seleccionamos el tema */
			$crud->set_table('CAT_USERS');			/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Usuario');			/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
			
			/* Generamos la tabla */
			$data->output = $output = $crud->render();
			$data->title = $this->title;
			
			/* Cargamos en la vista*/
			$this->load->view('templates/heads', $data);
			$this->load->view('templates/header',$this->session->userdata);
			$this->load->view('templates/aside');
			$this->load->view('menu/dashboard', $output);
			$this->load->view('templates/footer');
	
		}catch(Exception $e){
			  /* Si	 algo sale mal cachamos el error y lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
  }
 
}