<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Functions extends CI_Controller {			/* Heredamos de la clase CI_Controller */

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
    redirect('/config/access/functions/admin');
  }

  /*
   *
   **/
  function admin(){
		try{
	
			
			$crud = new grocery_CRUD();				/* Creamos el objeto */
			$crud->set_theme('flexigrid');			/* Seleccionamos el tema */
			$crud->set_table('CAT_FUNCTIONS');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Función');			/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
		
			/* Hacemola relacion con los permisos de usuarios*/
			$crud->set_relation_n_n('permissions', 'DET_PERMISSIONS', 'CAT_USER_GROUPS', 'moduleFunctionsId', 'userGroupsId', 'name','');
			
			/* Campos del modelo */
			$crud->fields(
				'name',
				'description',
				'active',
				'permissions',
				'displayName');
			/* Campos obligatorios */
			$crud->required_fields(
				'name',
				'description',
				'active',
				'displayName');
			
			/* Campos a mostrar */
			$crud->columns(
				'functionsId',
				'name',
				'description',
				'permissions',
				'active',
				'displayName')        
			->display_as('functionsId','ID')
			->display_as('name','Nombre')
			->display_as('description','Descripción')
			->display_as('permissions','Asignar a ')
			->display_as('displayname','Nombre a mostrar')
			->display_as('active','Activo');
			
			
				
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