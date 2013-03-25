<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Structure extends CI_Controller {			/* Heredamos de la clase CI_Controller */

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
	$this->title = "Adminsitración de Catálogo de Entradas (Estrucutura)";
	
			
  }

    /**
     *
     * @param integer $primary_key
     * @return void
     */
  function index(){
    /*
     * Mandamos todo lo que llegue a la funcion
     * admin().
     **/
    redirect('/cms/content/Structure/admin');
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
			//$crud->set_theme('datatables');			/* Seleccionamos el tema */
			$crud->set_table('CAT_ENTRIES');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Estrucutra de Entradas de Contenido');			/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
		
			
			/* Campos del modelo */
			$crud->fields(
			  	'name',
			  	'description',
			  	'active',
			  	'label',
			  	'displayName');
			/* Campos obligatorios */
			$crud->required_fields(
				  'name',
				  'description',
				  'active',
			  	'label',
			  	'displayName');
			
			/* Campos a mostrar */
			$crud->columns(
			  	'name',			  	
			  	'description',
			  	'active',
			  	'label',
			  	'displayName')        
			->display_as('name','Nombre')
			->display_as('description','Descripción')
			->display_as('label','Etiqueta')
			->display_as('displayName','Nombre a mostrar')
			->display_as('active','Activo');
								
			// Validación de campos
			$crud->set_rules('name', 'Nombre', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('description', 'Descrupcion', 'trim|min_length[0]|max_length[120]|xss_clean');			
	
	    	$crud->add_action('Datos', base_url().'images/icons/structure.png', 'Cms/Content/Strucuture/editData');
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
     * Función para obtener los componentes de estructura seleccionada
     *
     * @param integer $StructureId
     * @return void
     */
  function editData($StructureId){
		try{
			$crud = new grocery_CRUD();				/* Creamos el objeto */
			$crud->set_theme('flexigrid');			/* Seleccionamos el tema */
			//$crud->set_theme('datatables');			/* Seleccionamos el tema */
			$crud->set_table('CAT_ENTRIES');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Estrucutra de Entradas de Contenido');			/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
		
			
			/* Campos del modelo */
			$crud->fields(
			  	'name',
			  	'description',
			  	'active',
			  	'label',
			  	'displayName');
			/* Campos obligatorios */
			$crud->required_fields(
				  'name',
				  'description',
				  'active',
			  	'label',
			  	'displayName');
			
			/* Campos a mostrar */
			$crud->columns(
			  	'name',			  	
			  	'description',
			  	'active',
			  	'label',
			  	'displayName')        
			->display_as('name','Nombre')
			->display_as('description','Descripción')
			->display_as('label','Etiqueta')
			->display_as('displayName','Nombre a mostrar')
			->display_as('active','Activo');
								
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


/* End of file Structure.php */
/* Location: ./application/controllers/Cms/Content/Structure.php */
