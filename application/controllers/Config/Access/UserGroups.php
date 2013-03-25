<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserGroups extends CI_Controller {			/* Heredamos de la clase CI_Controller */

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
	$this->title = "Adminsitración de Grupos de Usuario";
	
			
  }

    /**
     * Función para obtener los permisos del grupo de usuario para todos los modulos
     *
     * @param integer $primary_key
     * @return void
     */
  function index(){
    /*
     * Mandamos todo lo que llegue a la funcion
     * admin().
     **/
    redirect('/config/access/userGroups/admin');
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
			$crud->set_table('CAT_USER_GROUPS');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Grupo de Usuario');			/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
		
			/* Hacemola relacion con los permisos de usuarios*/
			$crud->set_relation_n_n('permissions', 'DET_PERMISSIONS', 'CAT_MODULES', 'userGroupsId', 'modulesId', 'displayName','');
			$crud->set_relation_n_n('contents', 'DET_PERMISSIONS', 'CAT_ENTRY_STRUCTURES', 'userGroupsId', 'entryStructuresId', 'name','');
			
			/* Campos del modelo */
			$crud->fields(
			  	'name',
			  	'permissions',
			  	'description',
			  	'contents',
			  	'active');
			/* Campos obligatorios */
			$crud->required_fields(
			  'name',
			  'description',
			  'active');
			
			/* Campos a mostrar */
			$crud->columns(
			  	'userGroupsId',
			  	'name',			  	
				'permissions',
				'contents',
			  	'description',
			  	'active')        
			->display_as('userGroupsId','ID')
			->display_as('name','Nombre')
			->display_as('permissions','Permisos')
			->display_as('contents','Contenidos')
			->display_as('description','Descripción')
			->display_as('active','Activo');
								
			// Validación de campos
			$crud->set_rules('name', 'Nombre', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('description', 'Descrupcion', 'trim|min_length[0]|max_length[120]|xss_clean');			
	
	    	$crud->add_action('Funciones', base_url().'assets/images/functions.png', 'config/access/userGroups/editFunctions');
	    	$crud->add_action('Permisos', base_url().'assets/images/grayLock.png', 'config/access/userGroups/editPermissions');
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
     * Función para obtener los permisos del grupo de usuario para todos los modulos
     *
     * @param integer $userGroupId
     * @return void
     */
  function editPermissions($userGroupId){

		$this->db->select("CAT_MODULES.modulesId, CAT_MODULES.displayName, CAT_MODULES.description, CAT_MODULES.parentModuleId, DET_PERMISSIONS.userGroupsId"); 
		$this->db->order_by("CAT_MODULES.order, CAT_MODULES.parentModuleId", "DESC"); 		
		/*$this->db->from('CAT_MODULES');
		$this->db->join('DET_PERMISSIONS', 'DET_PERMISSIONS.modulesId = CAT_MODULES.modulesId', 'left');
		$this->db->join('CAT_USER_GROUPS', "DET_PERMISSIONS.userGroupsId = CAT_USER_GROUPS.userGroupsId AND CAT_USER_GROUPS.userGroupsId = '$userGroupId'", 'left');
		*/
		
		$this->db->from('CAT_USER_GROUPS');
		$this->db->join('DET_PERMISSIONS', 'DET_PERMISSIONS.userGroupsId = CAT_USER_GROUPS.userGroupsId', 'right');
		$this->db->join('CAT_MODULES', "DET_PERMISSIONS.modulesId = CAT_MODULES.modulesId AND CAT_USER_GROUPS.userGroupsId = '$userGroupId'", 'right');
		$res->rs_array = $this->db->where('parentModuleId IS NULL AND CAT_MODULES.active = 1')->get();
		
		foreach ($res->rs_array->result() as $row){
			$modules[$row->modulesId] = array(	'moduleId'		=>		$row->modulesId,
												'displayName'	=>		$row->displayName,
												'description'	=>		$row->description,
												'parentModuleId'=>		$row->parentModuleId,
												'userGroupId'	=>		$row->userGroupsId );
			$modules[$row->modulesId]['modules'] =	$this->getSubModules($row->modulesId,$userGroupId);
		}
		
		$resUserG = $this->db->select('CAT_USER_GROUPS.name')->where("userGroupsId = '$userGroupId'")->from('CAT_USER_GROUPS')->get();

		if($res->rs_array->num_rows() > 0)
   			$res->groupName = $resUserG->row()->name; 
			
		$res->modules = $modules;
	
		/* Cargamos en la vista*/
		$data->title = $this->title;
		$data->groupId = $userGroupId;
		$data->output->css_files = array();
		$data->output->js_files = array(	'jquery.min'	=>base_url()."resources/js/jquery.min.js",
											'functions'		=>base_url()."resources/js/access/userGroups.js");
			
		$this->load->view('templates/heads', $data);
		$this->load->view('templates/header',$this->session->userdata);
		$this->load->view('templates/aside');
		$this->load->view('access/UserGroups/editPermissions', $res);
		$this->load->view('templates/footer');
  }
  
    /**
     * Función para obtener los permisos del grupo de usuario en los submodulos de parteModuleId
     *
     * @param integer $userGroupId
     * @param integer $parentModuleId
     * @return array()
     */
  	function getSubModules($parentModuleId,$userGroupId){
	
		$this->db->select("CAT_MODULES.modulesId, CAT_MODULES.displayName, CAT_MODULES.description, CAT_MODULES.parentModuleId, DET_PERMISSIONS.userGroupsId"); 
		$this->db->order_by("CAT_MODULES.order, CAT_MODULES.parentModuleId", "DESC"); 		
		/*$this->db->from('CAT_MODULES');
		$this->db->join('DET_PERMISSIONS', 'DET_PERMISSIONS.modulesId = CAT_MODULES.modulesId', 'left');
		$this->db->join('CAT_USER_GROUPS', "DET_PERMISSIONS.userGroupsId = CAT_USER_GROUPS.userGroupsId AND CAT_USER_GROUPS.userGroupsId = '$userGroupId'", 'left');
		*/
		
		$this->db->from('CAT_USER_GROUPS');
		$this->db->join('DET_PERMISSIONS', 'DET_PERMISSIONS.userGroupsId = CAT_USER_GROUPS.userGroupsId', 'right');
		$this->db->join('CAT_MODULES', "DET_PERMISSIONS.modulesId = CAT_MODULES.modulesId AND CAT_USER_GROUPS.userGroupsId = '$userGroupId'", 'right');
		
		$rs_array = $this->db->where("parentModuleId = '".$parentModuleId."' AND CAT_MODULES.active = 1")->get();
		
		foreach ($rs_array->result() as $row){
			$modules_ret[$row->modulesId] = array(	'moduleId'		=>		$row->modulesId,
												'displayName'		=>		$row->displayName,
												'description'		=>		$row->description,
												'parentModuleId'	=>		$row->parentModuleId,
												'userGroupId'		=>		$row->userGroupsId);
			$modules_ret[$row->modulesId]['modules'] =	$this->getSubModules($row->modulesId,$userGroupId);
		}
		//echo $this->db->last_query();
		if(isset($modules_ret))		return $modules_ret;
		else						return array();;
		
		
	}
  
  
    /**
     * Función para obtener los permisos del grupo de usuario para todos los modulos
     *
     * @param integer $primary_key
     * @return void
  function editPermissions($primary_key){

		$this->db->select("CAT_MODULES.modulesId, CAT_MODULES.displayName, CAT_MODULES.description, DET_PERMISSIONS.userGroupsId, CAT_MODULES.parentModuleId"); 
		$this->db->order_by("CAT_MODULES.order, CAT_MODULES.parentModuleId", "desc"); 
		$this->db->from('CAT_MODULES');
		$this->db->join('DET_PERMISSIONS', 'DET_PERMISSIONS.modulesId = CAT_MODULES.modulesId', 'left');
		$this->db->join('CAT_USER_GROUPS', "DET_PERMISSIONS.userGroupsId = CAT_USER_GROUPS.userGroupsId AND CAT_USER_GROUPS.userGroupsId = '$primary_key'", 'left');
		$res->rs_array = $this->db->where("CAT_MODULES.active = '1'", 'left')->get();
		//$res->rs_array = $this->db->where('CAT_USER_GROUPS.userGroupsId', $primary_key)
		
		$resUserG = $this->db->select('CAT_USER_GROUPS.name')->where("userGroupsId = '$primary_key'")->from('CAT_USER_GROUPS')->get();

		if($res->rs_array->num_rows() > 0)
   			$res->groupName = $resUserG->row()->name; 
		
		$res->query = $this->db->last_query();

		$data->title = $this->title;
		$data->groupId = $primary_key;
		$data->output->css_files = array();
		$data->output->js_files = array(	'jquery.min'	=>base_url()."resources/js/jquery.min.js",
											'functions'		=>base_url()."resources/js/access/userGroups.js");
			
		$this->load->view('templates/heads', $data);
		$this->load->view('templates/header',$this->session->userdata);
		$this->load->view('templates/aside');
		$this->load->view('access/UserGroups/editPermissions', $res);
		$this->load->view('templates/footer');
  }
  
     */
	 
	 
	/**
	 * Función para actualizar permisos sobre un usuario
	 *
	 * @param POST integer $groupId
	 * @param POST integer $moduleId
	 * @return json
	 */
	function editModulePermission(){
	
		$data->message = "Cambio realizado";
		if($this->input->post('type') == 'add'){
			$this->db->set('userGroupsId', 	$this->input->post('groupId'));
			$this->db->set('modulesId', 	$this->input->post('moduleId'));
			$this->db->insert('DET_PERMISSIONS'); 
			$data->image 	= base_url().'/images/icons/lockOff.png';
			$data->onClick 	= 'editPermission(\'remove\',\''.$this->input->post('groupId').'\',\''.$this->input->post('moduleId') .'\');';
			$data->editType = 'Denegar';
		}else{
			$this->db->delete('DET_PERMISSIONS', array(	'userGroupsId'		=>		$this->input->post('groupId'),
														'modulesId'			=>		$this->input->post('moduleId') ) 
							 );
							 
			$data->image 	= base_url().'/images/icons/lockOn.png';
			$data->onClick 	= 'editPermission(\'add\',\''.$this->input->post('groupId').'\',\''.$this->input->post('moduleId') .'\');';
			$data->editType = 'Permitir';
		}
		
		$data->moduleId = $this->input->post('moduleId') ;
		$data->groupId 	= $this->input->post('groupId');
		$data->query 	= $this->db->last_query();
		echo json_encode($data); 
		//$this->load->view('jsonResponse', $data);
		
	}
  
    /**
     * Función para obtener los permisos del grupo de usuario para todos los modulos
     *
     * @param integer $userGroupId
     * @return void
	 */
	function editFunctions($userGroupId){
	  
		$this->db->select("CAT_FUNCTIONS.functionsId, CAT_FUNCTIONS.displayName, CAT_FUNCTIONS.description, DET_PERMISSIONS.userGroupsId, CAT_FUNCTIONS.name"); 
		$this->db->order_by("displayName", "desc"); 
		$this->db->from('CAT_FUNCTIONS');
		$this->db->join('DET_PERMISSIONS', "DET_PERMISSIONS.moduleFunctionsId = CAT_FUNCTIONS.FunctionsId AND DET_PERMISSIONS.userGroupsId = '$userGroupId'", 'left');
		$res->rs_array = $this->db->where("CAT_FUNCTIONS.active = '1'", 'left')->get();
		//$res->rs_array = $this->db->where('CAT_USER_GROUPS.userGroupsId', $primary_key)
		
		$resUserG = $this->db->select('CAT_USER_GROUPS.name')->where("userGroupsId = '$userGroupId'")->from('CAT_USER_GROUPS')->get();

		if($res->rs_array->num_rows() > 0)
   			$res->groupName = $resUserG->row()->name; 
		
		$res->query = $this->db->last_query();

		$data->title = $this->title;
		$data->groupId = $userGroupId;
		$data->output->css_files = array();
		$data->output->js_files = array(	'jquery.min'	=>base_url()."resources/js/jquery.min.js",
											'functions'		=>base_url()."resources/js/access/userGroups.js");
			
		$this->load->view('templates/heads', $data);
		$this->load->view('templates/header',$this->session->userdata);
		$this->load->view('templates/aside');
		$this->load->view('access/UserGroups/editFunctions', $res);
		$this->load->view('templates/footer');
	}
	
  	/**
	 * Función para actualizar permisos sobre un usuario
	 *
	 * @param POST integer $functionId
	 * @param POST integer $moduleId
	 * @return json
	 */
	function editFunctionAllowed(){
	
		$data->message = "Cambio realizado";
		if($this->input->post('type') == 'add'){
			$this->db->set('userGroupsId', 	$this->input->post('groupId'));
			$this->db->set('moduleFunctionsId', 	$this->input->post('functionId'));
			$this->db->insert('DET_PERMISSIONS'); 
			$data->image 	= base_url().'/images/icons/ok.png';
			$data->onClick 	= 'editFunctionAllowed(\'remove\',\''.$this->input->post('groupId').'\',\''.$this->input->post('functionId') .'\');';
			$data->editType = 'Denegar';
		}else{
			$this->db->delete('DET_PERMISSIONS', array(	'userGroupsId'		=>		$this->input->post('groupId'),
														'moduleFunctionsId'			=>		$this->input->post('functionId') ) 
							 );
							 
			$data->image 	= base_url().'/images/icons/cancel.png';
			$data->onClick 	= 'editFunctionAllowed(\'add\',\''.$this->input->post('groupId').'\',\''.$this->input->post('functionId') .'\');';
			$data->editType = 'Permitir';
		}
		
		$data->functionId = $this->input->post('functionId') ;
		$data->groupId 	= $this->input->post('groupId');
		$data->query 	= $this->db->last_query();
		echo json_encode($data); 
		//$this->load->view('jsonResponse', $data);
		
	}
}


/* End of file UserGroups.php */
/* Location: ./application/controllers/access/UserGroups.php */
