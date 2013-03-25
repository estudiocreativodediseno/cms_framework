<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class showTypesField extends CI_Controller {			/* Heredamos de la clase CI_Controller */

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
    redirect('/cms/config/showTypesField/admin');
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
			$crud->set_table('CAT_DATA_TYPE_SHOW_FORMS');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Forma de ver campo');			/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
		
			/* Campos del modelo */
			$crud->fields(
			  	'name',
			  	'description',
			  	'defaultValue',
			  	'showLike',
			  	'active',
			  	'ruleValidation',  
			  	'errorMessage',  
				'callbackAfterDelete',
				'callbackAfterInsert',
				'callbackAfterUpdate',
				'callbackAfterUpload',
				'callbackBeforeDelete',
				'callbackBeforeInsert',
				'callbackBeforeUpdate',
				'callbackBeforeUpload',
				'callbackColumn',
				'callbackDelete',
				'callbackEditField',
				'callbackField',
				'callbackInsert',
				'callbackUpdate',
				'callbackUpload');
			/* Campos obligatorios */
			$crud->required_fields(
			  	'name',
			  	'description',
			  	'active',
			  	'displayName',
			  	'label');
			
			/* Campos a mostrar */
			$crud->columns(
			  	'name',
			  	'description',
			  	'defaultValue',
			  	'showLike',
			  	'ruleValidation', 
				'callbacks', 
				'active')        
			->display_as('length','Longitud')
			->display_as('description','Descripción')
			->display_as('errorMessage','Mensaje de error')
			->display_as('active','Activo');
											
											
			$crud->unset_texteditor('errorMessage');

			// Validación de campos
			$crud->set_rules('name', 'Nombre', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('description', 'Descrupcion', 'trim|min_length[0]|max_length[120]|xss_clean');			

			//Callbacks     
			$crud->callback_column('callbacks',array($this,'column_callbacks_field'));
			$crud->callback_add_field('ruleValidation',array($this,'add_edit_ruleValidation_field'));
			$crud->callback_edit_field('ruleValidation',array($this,'add_edit_ruleValidation_field'));
			$crud->callback_add_field('showLike',array($this,'add_edit_showLike_field'));
			$crud->callback_edit_field('showLike',array($this,'add_edit_showLike_field'));
				
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
	 * Funciones para cambiar la forma de visualizar campos
	 *
	 * @return String
	 */
	function add_edit_showLike_field($value=''){
	     return '
		 							<select value="$value" name="showLike">
										<option value="text"'.		($value=='text'?' selected="selected"':'').			'>text</option>
										<option value="upload"'.	($value=='upload'?' selected="selected"':'').		'>upload</option>
										<option value="hidden"'.	($value=='hidden'?' selected="selected"':'').		'>hidden</option>
										<option value="invisible"'.	($value=='invisible'?' selected="selected"':'').	'>invisible</option>
										<option value="password"'.	($value=='password'?' selected="selected"':'').		'>password</option>
										<option value="enum"'.		($value=='enum'?' selected="selected"':'').			'>enum</option>
										<option value="set"'.		($value=='set'?' selected="selected"':'').			'>set</option>
										<option value="dropdown"'.	($value=='dropdown'?' selected="selected"':'').		'>dropdown</option>
										<option value="multiselect"'.($value=='multiselect'?' selected="selected"':'').	'>multiselect</option>
										<option value="integer"'.	($value=='integer'?' selected="selected"':'').		'>integer</option>
										<option value="true_false"'.($value=='true_false'?' selected="selected"':'').	'>true_false</option>
										<option value="string"'.	($value=='string'?' selected="selected"':'').		'>string</option>
										<option value="date"'.		($value=='date'?' selected="selected"':'').			'>date</option>
										<option value="datetime"'.	($value=='datetime'?' selected="selected"':'').		'>datetime</option>
										<option value="readonly"'.	($value=='readonly'?' selected="selected"':'').		'>readonly</option>
									</select>
		 		';
	}
	function add_edit_ruleValidation_field($value){
	     return '<input type="text" value="'.$value.'" name="ruleValidation" > <br>
		 					<code>set_rules(campo, nombreCampo, <strong>ruleValidation</strong>);</code><br>
		 					<a target="_blank" href="http://ellislab.com/codeigniter/user-guide/libraries/form_validation.html#cascadingrules">Rules</a>, 
		 					<a target="_blank" href="http://ellislab.com/codeigniter/user-guide/libraries/form_validation.html#rulereference">Rule Reference</a>, 
		 					<a target="_blank" href="http://ellislab.com/codeigniter/user-guide/libraries/form_validation.html#preppingreference">Prepping Reference</a><br>';
	}
	
	function column_callbacks_field($value, $row){
		 $callbacks = '';
		 $callbacks .= (strlen($row->callbackAfterDelete)>0		?'callbackAfterDelete:<strong>'.$row->callbackAfterDelete.'</strong><br>':'');
		 $callbacks .= (strlen($row->callbackAfterInsert)>0		?'callbackAfterInsert:<strong>'.$row->callbackAfterInsert.'</strong><br>':'');
		 $callbacks .= (strlen($row->callbackAfterUpdate)>0		?'callbackAfterUpdate:<strong>'.$row->callbackAfterUpdate.'</strong><br>':'');
		 $callbacks .= (strlen($row->callbackAfterUpload)>0		?'callbackAfterUpload:<strong>'.$row->callbackAfterUpload.'</strong><br>':'');
		 $callbacks .= (strlen($row->callbackBeforeDelete)>0	?'callbackBeforeDelete:<strong>'.$row->callbackBeforeDelete.'</strong><br>':'');
		 $callbacks .= (strlen($row->callbackBeforeInsert)>0	?'callbackBeforeInsert:<strong>'.$row->callbackBeforeInsert.'</strong><br>':'');
		 $callbacks .= (strlen($row->callbackBeforeUpdate)>0	?'callbackBeforeUpdate:<strong>'.$row->callbackBeforeUpdate.'</strong><br>':'');
		 $callbacks .= (strlen($row->callbackBeforeUpload)>0	?'callbackBeforeUpload:<strong>'.$row->callbackBeforeUpload.'</strong><br>':'');
		 $callbacks .= (strlen($row->callbackColumn)>0			?'callbackColumn:<strong>'.$row->callbackColumn.'</strong><br>':'');
		 $callbacks .= (strlen($row->callbackDelete)>0			?'callbackDelete:<strong>'.$row->callbackDelete.'</strong><br>':'');
		 $callbacks .= (strlen($row->callbackEditField)>0		?'callbackEditField:<strong>'.$row->callbackEditField.'</strong><br>':'');
		 $callbacks .= (strlen($row->callbackField)>0			?'callbackField:<strong>'.$row->callbackField.'</strong><br>':'');
		 $callbacks .= (strlen($row->callbackInsert)>0			?'callbackInsert:<strong>'.$row->callbackInsert.'</strong><br>':'');
		 $callbacks .= (strlen($row->callbackUpdate)>0			?'callbackUpdate:<strong>'.$row->callbackUpdate.'</strong><br>':'');
		 $callbacks .= (strlen($row->callbackUpload)>0			?'callbackUpload:<strong>'.$row->callbackUpload.'</strong><br>':'');
		return $callbacks;
	}
}


/* End of file UserGroups.php */
/* Location: ./application/controllers/access/UserGroups.php */
