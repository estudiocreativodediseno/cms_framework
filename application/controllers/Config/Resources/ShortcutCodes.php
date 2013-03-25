<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ShortcutCodes extends CI_Controller {			/* Heredamos de la clase CI_Controller */

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
	$this->title = "Adminsitración de Atajos Framework";
	
			
  }

  function index(){
    /*
     * Mandamos todo lo que llegue a la funcion
     * admin().
     **/
    redirect('/config/resources/ShortcutCodes/admin');
  }

    /**
     *
     * @param integer $primary_key
     * @return void
     */
  function admin(){
		try{
	
			
			$crud = new grocery_CRUD();				/* Creamos el objeto */
			$crud->set_theme('flexigrid');			/* Seleccionamos el tema */

			$crud->set_table('CAT_SHORTCUTS');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Atajos');		/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
			
			
			
			/* Campos del modelo */
			$crud->fields(
			  	'name',
			  	'description',
			  	'order',
			  	'active',
			  	'code',
			  	'isStructure',
			  	'isField',
			  	'insertDate',
			  	'updateDate',
			  	'insertUserId',
			  	'updateUserId',
			  	'class');
			/* Campos obligatorios */
			$crud->required_fields(
			  	'name',
			  	'description',
			  	'isStructure',
			  	'isField',
			  	'order',
			  	'active');
			
			/* Campos a mostrar */
			$crud->columns(
			  	'name',
			  	'description',
			  	'order',
			  	'active',
			  	'code',
			  	'isStructure',
			  	'isField',
			  	'insertDate',
			  	'insertUserId',
			  	'updateDate',
			  	'updateUserId',
			  	'class')        
			->display_as('name','Nombre')
			->display_as('order','Orden')
			->display_as('code','Código')
			->display_as('isStructure','Aplica a Estructure')
			->display_as('isField','Aplica a Campo')
			->display_as('description','Descripción')
			->display_as('insertDate','Fecha Creación')
			->display_as('insertUserId','Usuario')
			->display_as('updateDate','Ult. Actualización')
			->display_as('updateUserId','Usuario')
			->display_as('class','Class')
			->display_as('active','Activo');
								
								
								
								
		    $crud->field_type('insertUserId', 'hidden', $this->session->userdata('usersId'));
		    $crud->field_type('updateUserId', 'hidden', $this->session->userdata('usersId'));
		    $crud->field_type('updateDate', 'hidden', date('Y-m-d H:i:s'));
		    $crud->field_type('insertDate', 'hidden', date('Y-m-d H:i:s'));
			
			$crud->unset_texteditor('name','full_text');
			$crud->unset_texteditor('code','full_text');
			$crud->unset_texteditor('description','full_text');
			$crud->unset_texteditor('class');

			// Validación de campos
			$crud->set_rules('name', 'Nombre', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('description', 'Descripcion', 'trim|min_length[0]|max_length[120]|xss_clean');			
				
			//Callbacks
			$crud->callback_before_insert(array($this,'clearDataInsert'));
			$crud->callback_before_update(array($this,'clearDataUpdate'));
			$crud->callback_add_field('code',array($this,'add_code_callback'));
			$crud->callback_edit_field('code',array($this,'add_code_callback'));

			$crud->set_relation('insertUserId','CAT_USERS','username');
			$crud->set_relation('updateUserId','CAT_USERS','username');
				
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
	 * Función para poner en blanco los campos de update al insert 
	 *
	 * @return void
	 */
	function clearDataInsert($post_array){
	     $post_array['updateDate']='';
	     $post_array['updateUserId']='';
		 return $post_array;
	}
	
  	/**
	 * Función para no modificar el update de los capos de usarios de creacion de registro
	 *
	 * @return void
	 */
	function clearDataUpdate($post_array, $primary_key){
		
		$this->db->select("insertDate, insertUserId"); 
		$this->db->from('CAT_SHORTCUTS');
		$res = $this->db->where('shortcutsId', $primary_key)->get();

		if ($res->num_rows() > 0){
			$rs_qry = $res->row(); 		
			$post_array['insertDate']	= $rs_qry->insertDate;
			$post_array['insertUserId']	= $rs_qry->insertUserId;
		}
		
		return $post_array;
	}
  	/**
	 * Función agregar información al campo de codigo
	 *
	 * @return void
	 */
	function add_code_callback($value){
		
		return '<textarea name="code" id="field-code">'.$value.'</textarea><br>
					Usar <font color=green><code>'.$this->config->item('structure_tag').'</code></font> para llenado automático de Estructura<br>
					Usar <font color=green><code>'.$this->config->item('field_tag').'</code></font> para llenado automático de Campo';
	}
	
}


/* End of file ShortcutCodes.php */
/* Location: ./application/controllers/Config/Resources/ShortcutCodes.php */
