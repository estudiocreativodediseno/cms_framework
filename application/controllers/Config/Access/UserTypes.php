<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserTypes extends CI_Controller {			/* Heredamos de la clase CI_Controller */

	var $title;
  function __construct(){
   
    parent::__construct();
	/* Validamos si el usuario está en sesión*/
   	if(!isset($this->session->userdata['username']))
		redirect('login/index');
		
	if(strlen($this->input->get('mod'))>0)
		$this->session->set_userdata('moduleId',$this->input->get('mod'));
	$permission = $this->CmsPermission->getUserModulePermission();
    
    $this->load->database();				/* Cargamos la base de datos */
    $this->load->library('grocery_crud');	/* Cargamos la libreria*/   
    $this->load->helper('url'); 			/* Añadimos el helper al controlador */
	$this->title = "Adminsitración de Tipos de Usuario";
  }

  function index(){
    /*
     * Mandamos todo lo que llegue a la funcion
     * admin().
     **/
    redirect('/config/access/userTypes/admin');
  }

  /*
   *
   **/
  function admin(){
		try{
	
			
			$crud = new grocery_CRUD();				/* Creamos el objeto */
			$crud->set_theme('flexigrid');			/* Seleccionamos el tema */
			$crud->set_table('CAT_USER_TYPES');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Tipo de Usuario');			/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
		
			/* Campos del modelo */
			$crud->fields(
			  	'userTypesId',
			  	'name',
			  	'description',
			  	'active');
			/* Campos obligatorios */
			$crud->required_fields(
			  'name',
			  'description',
			  'active');
			
			/* Campos a mostrar */
			$crud->columns(
			  	'userTypesId',
			  	'name',
			  	'description',
			  	'active')        
			->display_as('userTypesId','ID')
			->display_as('name','Nombre')
			->display_as('description','Descripción')
			->display_as('active','Activo');
			
			$crud->change_field_type('description', 'text');

								
			// Validación de campos
			$crud->set_rules('name', 'Nombre', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('description', 'Descrupcion', 'trim|min_length[0]|max_length[120]|xss_clean');			
	
			//Callbacks
				
			/* Generamos la tabla */
			$data->output = $output = $crud->render();
			$data->title = $this->title;
			
			/* Cargamos en la vista*/
			$this->load->view('templates/heads', $data);
			$this->load->view('templates/header',$this->session->userdata);
			$this->load->view('templates/aside');
			$this->load->view('templates/admin', $output);
			$this->load->view('templates/footer');
	
		}catch(Exception $e){
			  /* Si	 algo sale mal cachamos el error y lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
  }
  
}