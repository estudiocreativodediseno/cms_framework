<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Heredamos de la clase CI_Controller */
class Users extends CI_Controller {

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
    redirect('/config/access/users/admin');
  }

  /*
   *
   **/
  function admin(){
		try{
			
			
			$crud = new grocery_CRUD();				/* Creamos el objeto */
			$crud->set_theme('flexigrid');			/* Seleccionamos el tema */
			$crud->set_table('CAT_USERS');			/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Usuario');			/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
			
			/* Campos del modelo */
			$crud->fields(
			  'name',
			  'username',
			  'password',
			  'active',
			  'email',
			  'userTypesId',
			  'userGroupsId');
			/* Estos campos son obligatorios */
			$crud->required_fields(
			  'name',
			  'username',
			  'password',
			  'active',
			  'email',
			  'userTypesId',
			  'userGroupsId'
			);
			$crud->field_type('password', 'password');
			/* Aqui le indicamos que campos deseamos mostrar */
			$crud->columns(
			  'usersId',
			  'name',
			  'username',
			  'email',
			  'active',
			  'userTypesId',
			  'userGroupsId'
			)        
			->display_as('usersId','ID')
			->display_as('name','Nombre')
			->display_as('email','Email')
			->display_as('username','Usuario');
			
			
			//Cuando se requiere que un campo es un ardhivo adjunto
			//$crud->set_field_upload('email','uploads/files');
		
			//Haciendo la relacion entre tablas
			$crud->display_as('userTypesId','Tipo de Usuario'); 
			// (campoRef,tablaRef,campoMostrar)
			$crud->set_relation('userTypesId','CAT_USER_TYPES','name');
			
			$crud->display_as('userGroupsId','Grupo de Usuarios'); 
			$crud->set_relation('userGroupsId','CAT_USER_GROUPS','name');
			
			//validaciones de campos
			$crud->set_rules('username', 'Usuario', 'trim|required|min_length[5]|max_length[12]|xss_clean');
			$crud->set_rules('password', 'Password', 'trim|required|md5');
			$crud->set_rules('email', 'Email', 'trim|required|valid_email');
			
	
			//Callbacks
			$crud->callback_add_field('passconf',array($this,'add_field_confPassword'));
			$crud->callback_edit_field('passconf',array($this,'edit_field_confPassword'));
			$crud->callback_before_insert(array($this,'convertToMD5'));
				
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
  
  	function convertToMD5($post_array){
		$post_array['password'] = md5($post_array['password']);
    	return $post_array;
	}
	
  	function add_field_confPassword(){
    	return '<input type="password" maxlength="50" value="" name="passconf" style="width:400px"> (Confirmar password)';
	}
	
	function edit_field_callback_1($value, $primary_key){
    	return '<input type="password" maxlength="50" value="" name="passconf" style="width:400px"> (Confirmar password)';
		//+30 <input type="text" maxlength="50" value="'.$value.'" name="phone" style="width:462px">';
	}
}