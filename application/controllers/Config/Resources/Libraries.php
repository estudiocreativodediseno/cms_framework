<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Libraries extends CI_Controller {			/* Heredamos de la clase CI_Controller */

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
	$this->title = "Adminsitración de Librerías";
	
			
  }

  function index(){
    /*
     * Mandamos todo lo que llegue a la funcion
     * admin().
     **/
    redirect('/config/resources/Libraries/css');
  }

    /**
     * Función para obtener todas las librerías de hoja de estilos (CSS)
     *
     * @param integer $primary_key
     * @return void
     */
  function css(){
		try{
	
			
			$crud = new grocery_CRUD();				/* Creamos el objeto */
			$crud->set_theme('flexigrid');			/* Seleccionamos el tema */

			$crud->set_table('CAT_LIBRARIES');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			//$crud->from('CAT_LIBRARIES');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->where('CAT_LIBRARIES.libraryTypesId','1');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Hoja de Estilo');		/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
			
			
			/* Hacemos la relacion con los permisos de usuarios*/
			//$crud->set_relation_n_n('templates', 'DET_LIBRARIES_TEMPLATES', 'CAT_TEMPLATES', 'librariesId', 'templatesId', 'name','librariesId');
			$crud->set_relation('libraryTypesId', 'CAT_LIBRARY_TYPES', 'description');
			
			/* Campos del modelo */
			$crud->fields(
			  	'order',
			  	'name',
			  	'description',
			  	//'templates',
			  	'libraryTypesId',
			  	'active',
			  	'displayName',
			  	'urlFile');
			/* Campos obligatorios */
			$crud->required_fields(
			  	'order',
			  	'name',
			  	'description',
			  	'libraryTypesId',
			  	'active',
			  	'urlFile');
			
			/* Campos a mostrar */
			$crud->columns(
			  	'order',
			  	'name',
			  	'description',
			  	'displayName',
			  	//'templates',
			  	'libraryTypesId',
			  	'active',
			  	'urlFile')        
			->display_as('order','Prioridad')
			->display_as('name','Nombre')
			->display_as('urlFile','Archivo')
			->display_as('templates','Template')
			->display_as('description','Descripción')
			->display_as('libraryTypesId','Tipo de librería')
			->display_as('displayName','Nombre a mostrar')
			->display_as('active','Activo');
								
			//Cuando se requiere que un campo es un ardhivo adjunto
			$crud->set_field_upload('urlFile','uploads/files/resources/css');
		
			// Validación de campos
			$crud->set_rules('name', 'Nombre', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('description', 'Descripcion', 'trim|min_length[0]|max_length[120]|xss_clean');			
	
	    	$crud->add_action('Ver', base_url().'images/icons/menuedit.png', 'Config/Resources/Libraries/css/view');
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
  
  
    /**
     * Función para obtener todas las librerías de javascript (JS)
     *
     * @param integer $primary_key
     * @return void
     */
  function javascript(){
		try{
	
			
			$crud = new grocery_CRUD();				/* Creamos el objeto */
			$crud->set_theme('flexigrid');			/* Seleccionamos el tema */

			$crud->set_table('CAT_LIBRARIES');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			//$crud->from('CAT_LIBRARIES');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->where('CAT_LIBRARIES.libraryTypesId','2');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Archivo Javascript');		/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
			
			
			/* Hacemos la relacion con los permisos de usuarios*/
			//$crud->set_relation_n_n('templates', 'DET_LIBRARIES_TEMPLATES', 'CAT_TEMPLATES', 'librariesId', 'templatesId', 'name','librariesId');
			$crud->set_relation('libraryTypesId', 'CAT_LIBRARY_TYPES', 'description');
			
			/* Campos del modelo */
			$crud->fields(
			  	'order',
			  	'name',
			  	'description',
			  	//'templates',
			  	'libraryTypesId',
			  	'active',
			  	'displayName',
			  	'urlFile');
			/* Campos obligatorios */
			$crud->required_fields(
			  	'order',
			  	'name',
			  	'description',
			  	'libraryTypesId',
			  	'active',
			  	'urlFile');
			
			/* Campos a mostrar */
			$crud->columns(
			  	'order',
			  	'name',
			  	'description',
			  	'displayName',
			  	//'templates',
			  	'libraryTypesId',
			  	'active',
			  	'urlFile')        
			->display_as('order','Prioridad')
			->display_as('name','Nombre')
			->display_as('urlFile','Archivo')
			->display_as('templates','Template')
			->display_as('description','Descripción')
			->display_as('libraryTypesId','Tipo de librería')
			->display_as('displayName','Nombre a mostrar')
			->display_as('active','Activo');
								
			//Cuando se requiere que un campo es un ardhivo adjunto
			$crud->set_field_upload('urlFile','uploads/files/resources/css');
		
			// Validación de campos
			$crud->set_rules('name', 'Nombre', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('description', 'Descripcion', 'trim|min_length[0]|max_length[120]|xss_clean');			
	
	    	$crud->add_action('Ver', base_url().'images/icons/menuedit.png', 'Config/Resources/Libraries/css/view');
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


/* End of file Libraries.php */
/* Location: ./application/controllers/Config/Resources/Libraries.php */
