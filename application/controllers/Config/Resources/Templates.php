<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Templates extends CI_Controller {			/* Heredamos de la clase CI_Controller */

	var $title;
  function __construct(){
   
    parent::__construct();
	/* Validamos si el usuario está en sesión*/
   	if(!isset($this->session->userdata['username']))
		redirect('login/index');
		
	if(strlen($this->input->get('mod'))>0)
		$this->session->set_userdata('moduleId',$this->input->get('mod'));
	$permission = $this->CmsPermission->getUserModulePermission();

    //if(!empty($permission->moduleData))
		//redirect('forbidden/index');

    $this->load->database();				/* Cargamos la base de datos */
    $this->load->library('grocery_crud');	/* Cargamos la libreria*/   
    $this->load->helper('url'); 			/* Añadimos el helper al controlador */
	$this->title = "Adminsitración de Plantillas";
	
			
  }

  function index(){
    /*
     * Mandamos todo lo que llegue a la funcion
     * admin().
     **/
    redirect('/config/resources/Templates/admin');
  }

    /**
     * Función para obtener los permisos del grupo de usuario para todos los modulos
     *
     * @param integer $primary_key
     * @return void
     */
  function admin(){
		try{
	
			
			$crud = new grocery_CRUD();				/* Creamos el objeto */
			$crud->set_theme('flexigrid');			/* Seleccionamos el tema */

			$crud->set_table('CAT_TEMPLATES');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Plantilla');		/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
			
			
			/* Hacemola relacion con los permisos de usuarios*/
			//$crud->set_relation_n_n('libraries', 'DET_LIBRARIES_TEMPLATES', 'CAT_LIBRARIES', 'librariesId', 'templatesId', 'name','');
			
			
			/* Hacemos la relacion con los permisos de usuarios*/
			$crud->set_relation_n_n('libraries', 'DET_LIBRARIES_TEMPLATES', 'CAT_LIBRARIES', 'templatesId', 'librariesId', 'displayName','');
			
			/* Campos del modelo */
			$crud->fields(
			  	'name',
			  	'description',
			  	'libraries',
			  	'active',
			  	'urlFile');
			/* Campos obligatorios */
			$crud->required_fields(
			  	'name',
			  	'description',
			  	'active',
			  	'urlFile');
			
			/* Campos a mostrar */
			$crud->columns(
			  	'name',
			  	'description',
			  	'libraries',
			  	'active',
			  	'urlFile')        
			->display_as('name','Nombre')
			->display_as('urlFile','Archivo')
			->display_as('libraries','Librerías')
			->display_as('description','Descripción')
			->display_as('active','Activo');
								
			//Cuando se requiere que un campo es un ardhivo adjunto
			$crud->set_field_upload('urlFile','uploads/files/templates');
		
			// Validación de campos
			$crud->set_rules('name', 'Nombre', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('description', 'Descripcion', 'trim|min_length[0]|max_length[120]|xss_clean');			
	
	    	$crud->add_action('Ver', base_url().'images/icons/menuedit.png', 'Config/Resoirces/Templates/view');
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


/* End of file Templates.php */
/* Location: ./application/controllers/Config/Resources/Templates.php */
