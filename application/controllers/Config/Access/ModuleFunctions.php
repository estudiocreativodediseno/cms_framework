<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModuleFunctions extends CI_Controller {			/* Heredamos de la clase CI_Controller */

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
	$this->title = "Adminsitración de Funciones";
  }

  function index(){
    /*
     * Mandamos todo lo que llegue a la funcion
     * admin().
     **/
    redirect('/config/access/moduleFunctions/admin');
  }

  /*
   *
   **/
  function admin(){
		try{
	
			
			$crud = new grocery_CRUD();				/* Creamos el objeto */
			$crud->set_theme('flexigrid');			/* Seleccionamos el tema */
			$crud->set_table('CAT_MODULE_FUNCTIONS');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Función');			/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
		
			/* Campos del modelo */
			$crud->fields(
			  	'name',
			  	'description',
			  	'active',
			  	'displayName',
				'parentModuleId',
				'urlIconImage',
				'order');
			/* Campos obligatorios */
			$crud->required_fields(
			  'name',
			  'description',
			  'active',
			  'displayName',
			  'order');
			
			/* Campos a mostrar */
			$crud->columns(
				'modulesId',
				'order',
				'name',
				'description',
				'active',
				'displayName',
				'parentModuleId',
				'urlIconImage')        
			->display_as('modulesId','ID')
			->display_as('order','Orden')
			->display_as('name','Nombre')
			->display_as('description','Descripción')
			->display_as('displayname','Nombre a mostrar')
			->display_as('parentModuelId','Pertenece a')
			->display_as('urlIconImage','Icono')
			->display_as('active','Activo');
			
			
			//Cuando se requiere que un campo es un ardhivo adjunto
			$crud->set_field_upload('urlIconImage','uploads/files/images');
		
			// Validación de campos
			$crud->set_rules('name', 'Nombre', 'trim|min_length[3]|max_length[50]|xss_clean');
			$crud->set_rules('description', 'Descrupcion', 'trim|min_length[5]|max_length[120]|xss_clean');
			$crud->set_rules('displayname', 'Nombre a mostrar', 'trim|min_length[5]|max_length[50]|xss_clean');
			$crud->set_rules('urlIconImage', 'Nombre de imágen', 'trim|min_length[5]|max_length[200]|xss_clean');
			$crud->set_rules('quantityInStock','Quantity In Stock','integer');
			
	
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