<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DataTypes extends CI_Controller {			/* Heredamos de la clase CI_Controller */

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
	$this->title = "Adminsitración de Tipos de Dato";
  }

    /**
     * Función para cachar el index de la sección
     *
     * @return void
     */
  function index(){
    /*
     * Mandamos todo lo que llegue a la funcion
     * admin().
     **/
    redirect('/cms/config/DataTypes/admin');
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
			$crud->set_table('CAT_DATA_TYPES');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Tipo de Dato');		/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
		
			/* Campos del modelo */
			$crud->fields(
			  	'order',
			  	'name',
			  	'structure',
			  	'minLength',
			  	'maxLength',
			  	'mandatory',
			  	'prefix',
			  	'postfix',
			  	'uploadFileTypes',
			  	'description',
			  	'active',
			  	'displayName',
			  	'label',
			  	'dataTypeShowFormsId');
			/* Campos obligatorios */
			$crud->required_fields(
			  	'order',
			  	'name',
			  	'mandatory',
			  	'description',
			  	'active',
			  	'displayName',
			  	'label',
			  	'dataTypeShowFormsId');
			
			/* Campos a mostrar */
			$crud->columns(
			  	'order',
			  	'structure',
			  	'name',
			  	'prefix',
			  	'postfix',
			  	'postfix',
			  	'uploadFileTypes',
			  	'minLength',
			  	'maxLength',
			  	'description',
			  	'mandatory',
			  	'active',
			  	'displayName',
			  	'label',
			  	'dataTypeShowFormsId')        
			->display_as('dataTypesId','ID')
			->display_as('name','Nombre')
			->display_as('order','Orden')
			->display_as('mandatory','Obligatorio')
			->display_as('prefix','Prefijo')
			->display_as('postfix','Postfijo')
			->display_as('uploadFileTypes','Archivos permitidos')
			->display_as('minLength','Longitud Min.')
			->display_as('maxLength','Longitud Max.')
			->display_as('description','Descripción')
			->display_as('displayName','Nombre a mostrar')
			->display_as('dataTypeShowFormsId','Tipo de dato')
			->display_as('label','Etiqueta')
			->display_as('active','Activo');
								
								
			// (campoRef,tablaRef,campoMostrar)
			$crud->set_relation('dataTypeShowFormsId','CAT_DATA_TYPE_SHOW_FORMS','name');	

			$crud->set_relation_n_n('structure','DET_ENTRY_CONTENTS','CAT_ENTRY_STRUCTURES','entryStructuresId','entryStructuresId','name');	
			
			// Validación de campos
			$crud->set_rules('name', 'Nombre', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('label', 'Etiqueta', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('description', 'Descrupcion', 'trim|min_length[0]|max_length[120]|xss_clean');			
			$crud->set_rules('length','Longitud','integer');

			//Callbacks     
			$crud->callback_add_field('uploadFileTypes',array($this,'add_uploadFileTypes_callback'));
			$crud->callback_edit_field('uploadFileTypes',array($this,'add_uploadFileTypes_callback'));

			$crud->callback_add_field('length',array($this,'add_length_callback'));
			$crud->callback_edit_field('length',array($this,'edit_length_callback'));
				
			$crud->unset_texteditor('prefix');
			$crud->unset_texteditor('postfix');
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
	 * Función para daar tip al usuario de como llenar el campo
	 *
	 * @return void
	 */
	function add_uploadFileTypes_callback(){
	     return '<input type="text" value="" name="uploadFileTypes" style="width:262px"> separados por pipes (|), ejemplo: html|gif|jpeg|jpg|png';
	}
	
  	/**
	 * Función para daar tip al usuario de como llenar el campo
	 *
	 * @return void
	 */
	function edit_uploadFileTypes_callback($value){
	     return '<input type="text" value="'.$value.'" name="uploadFileTypes" style="width:262px"> separados por pipes (|), ejemplo: jpeg|jpg|png';
	}
	
  	/**
	 * Función para poner 0 (cero) como default para el campo length permisos sobre un usuario
	 *
	 * @return void
	 */
	function add_length_callback(){
	     return '<input type="text" value="0" name="length" style="width:62px"> Si no aplica, usar 0';
	}
  	/**
	 * Función para poner texto de ayuda enm edicon de campo length
	 *
	 * @return void
	 */
	function edit_length_callback($value){
	     return '<input type="text" value="'.$value.'" name="length" style="width:62px"> Si no aplica, usar 0';
	}
}


/* End of file UserGroups.php */
/* Location: ./application/controllers/access/UserGroups.php */
