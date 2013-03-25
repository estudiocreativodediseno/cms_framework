<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class StructuresData  extends CI_Controller {			/* Heredamos de la clase CI_Controller */

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
	$this->title = "Adminsitración de Estructura de Datos";
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
    redirect('/cms/config/StructuresData/admin');
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
			$crud->set_table('CAT_ENTRY_STRUCTURES');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Estructura de Datos');			/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
		
			/* Campos del modelo */
			$crud->fields(
			  	'order',
			  	'name',
			  	'description',
			  	//'datas',
			  	'permissions',
			  	'active',
			  	'showInMenu',
			  	'showInSubMenu',
			  	'insertDate',
			  	'insertUserId',
			  	'updateDate',
			  	'updateUserId',
			  	//'structures',
			  	'auxStructures',
			  	'auxDatas');
			/* Campos obligatorios */
			$crud->required_fields(
			  	'name',
			  	'showInMenu',
			  	'description',
			  	'active');
			
			
			$crud->set_relation_n_n('permissions', 'DET_PERMISSIONS', 'CAT_USER_GROUPS', 'entryStructuresId', 'userGroupsId', 'name','');
			//$crud->set_relation_n_n('datas', 'DET_ENTRY_STRUCTURE', 'CAT_DATA_TYPES', 'entryStructuresId', 'dataTypesId', 'displayName');
			//$crud->set_relation_n_n('structures', 'DET_ENTRY_STRUCTURE', 'CAT_DATA_TYPES', 'entryStructuresId', 'dataTypesId', 'displayName');
			
			//No soportó la referencia a si misma por medio de una tabla detalle
			//$crud->set_relation_n_n('structures', 'DET_ENTRY_STRUCTURE', 'CAT_ENTRY_STRUCTURES', 'entryStructuresId', 'subEntryStructureId', 'name');
			/* Campos a mostrar */
			$crud->columns(
			  	'order',
			  	'name',
			  	'description',
			  	'active',
			  	'showInMenu',
			  	'showInSubMenu',
			  	//'datas',
			  	'auxDatas',
			  	'auxStructures',
			  	'permissions',
			  	'insertDate',
			  	'insertUserId',
			  	'updateDate',
			  	'updateUserId')        
			->display_as('order','Orden')
			->display_as('name','Nombre')
			->display_as('description','Descripción')
			->display_as('displayName','Nombre a mostrar')
			->display_as('showInMenu','Mostrar en menú')
			->display_as('showInSubMenu','Mostrar en submenú')
			->display_as('insertDate','Fecha creación')
			->display_as('datas','Datos')
			->display_as('auxStructures','Estructuras')
			->display_as('auxDatas','Datos')
			->display_as('insertUserId','Usuario Creación')
			->display_as('updateDate','Ult. Actualización')
			->display_as('updateUserId','Usuario Actualización')
			->display_as('active','Activo');
			
			$crud->unset_texteditor('description');
			$crud->set_relation('insertUserId','CAT_USERS','username');
			$crud->set_relation('updateUserId','CAT_USERS','username');
								
		    $crud->field_type('datas', 'hidden');
		    $crud->field_type('structures', 'hidden');
		    $crud->field_type('auxStructures', 'invisible', '0');
		    $crud->field_type('auxDatas', 'invisible', '0');
		    $crud->field_type('insertUserId', 'hidden', $this->session->userdata('usersId'));
		    $crud->field_type('updateUserId', 'hidden', $this->session->userdata('usersId'));
		    $crud->field_type('updateDate', 'hidden', date('Y-m-d H:m:s'));
		    $crud->field_type('insertDate', 'hidden', date('Y-m-d H:m:s'));
			
			$crud->callback_column('auxStructures',	array($this,'_callback_view_structures'));
			$crud->callback_column('auxDatas',		array($this,'_callback_view_datas'));
			$crud->callback_before_insert(array($this,'clearDataInsert'));
			$crud->callback_before_update(array($this,'clearDataUpdate'));
			$crud->callback_before_delete(array($this,'_callback_delete_structure'));
			//$crud->unset_delete();
			
			// Validación de campos
			$crud->set_rules('name', 'Nombre', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('label', 'Etiqueta', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('description', 'Descrupcion', 'trim|min_length[0]|max_length[120]|xss_clean');			
			$crud->set_rules('length','Longitud','integer');

			$crud->add_action('Agregar otra estructura dentro de esta', base_url().'images/icons/package.png', 'cms/config/StructuresData/structures/edit');
			$crud->add_action('Editar estructura', base_url().'images/icons/packages.png', 'cms/config/StructuresData/datas/edit');			
			//$crud->add_action('Eliminar', base_url().'/assets/grocery_crud/themes/flexigrid/css/images/delete.png', 'cms/config/StructuresData/structures/edit');
			//$crud->add_action('Eliminar',  base_url().'/assets/grocery_crud/themes/flexigrid/css/images/delete.png', '','',array($this,'_delete_structure'));

			
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
     * Función para obtener los datos asignados a la estrucutura de entrada a editar
     *
     * @param integer $pk
     * @return void
     */
  function datas($pk){
		try{
	
		
			$crud = new grocery_CRUD();				/* Creamos el objeto */
			$crud->set_theme('flexigrid');			/* Seleccionamos el tema */
			$crud->set_table('CAT_ENTRY_STRUCTURES');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->where('entryStructuresId',$pk);		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Estructura de Datos');			/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
		
			/* Campos del modelo */
			$crud->fields(
			  	'entryStructuresId',
			  	'name',
			  	'description',
			  	'datas');

			$crud->set_relation_n_n('datas', 'DET_ENTRY_STRUCTURE', 'CAT_DATA_TYPES', 'entryStructuresId', 'dataTypesId', 'name');
			
			/* Campos a mostrar */
			$crud->columns(
			  	'name',
			  	'description',
				'datas')        
			->display_as('name','Nombre (No modificable)')
			->display_as('description','Descripción (No modificable)')
			->display_as('datas','Datos');
			
		    $crud->field_type('entryStructuresId', 'hidden');
			
			$crud->set_relation('insertUserId','CAT_USERS','username');
			$crud->set_relation('updateUserId','CAT_USERS','username');
			
			
			$crud->callback_before_update(array($this,'_callback_update_structure'));
			//$crud->callback_before_update(array($this,'assignNameDescription'));

		    $crud->unset_add();
		    $crud->unset_delete();
		    $crud->unset_print();
		    $crud->unset_export();
		 	$crud->unset_back_to_list();
		 	
			/* Generamos la tabla */
			$data->output = $output = $crud->render();
			$data->title = $this->title;
			
			/* Cargamos en la vista*/
			$this->load->view('templates/heads', $data);
			$this->load->view('templates/header',$this->session->userdata);
			$this->load->view('templates/aside');
			$this->load->view('cms/config/structuresData/datas', $output);
			$this->load->view('templates/footer');
	
		}catch(Exception $e){
			  /* Si	 algo sale mal cachamos el error y lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
  }
  
  
    /**
     * Función para obtener los datos asignados a la estrucutura de entrada a editar
     *
     * @param String $pk
     * @param integer $pk
     * @return void
     */
  function structures($action='', $pk=''){
		try{
	
			$crud = new grocery_CRUD();				/* Creamos el objeto */
			$crud->set_theme('flexigrid');			/* Seleccionamos el tema */
			$crud->set_table('CAT_ENTRY_STRUCTURES');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->where('entryStructuresId',$pk);		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Estructura de Datos');			/* Le asignamos un nombre */
			$crud->set_language('spanish');			/* Asignamos el idioma español */
		
			/* Campos del modelo */
			$crud->fields(
			  	'name',
			  	'description',
			  	'structures');
				
			$parents=$this->getParentStructures($pk);
			$parents .= (strlen($parents)>0?',':'').$pk;
			//echo "pk:".$pk;
			//$crud->set_relation('entryStructuresId', 'DET_ENTRY_STRUCTURE',  'name');
			//$crud->set_relation_n_n('structures', 'DET_ENTRY_STRUCTURE', 'CAT_ENTRY_STRUCTURES', 'entryStructuresId', 'subEntryStructureId', 'name', "","entryStructuresId <> '".$pk."'");
			$crud->set_relation_n_n('structures', 'DET_ENTRY_STRUCTURE', 'CAT_ENTRY_STRUCTURES', 'entryStructuresId', 'subEntryStructureId', 'name', "","entryStructuresId NOT IN (".$parents.")");

		
			/* Campos a mostrar */
			$crud->columns(
			  	'name',
			  	'description',
				'structures')        
			->display_as('name','Nombre (No modificable)')
			->display_as('description','Descripción (No modificable)')
			->display_as('structures','Estructuras');
			
			$crud->callback_before_update(array($this,'assignNameDescription'));

		    $crud->unset_add();
		    $crud->unset_delete();
		    $crud->unset_print();
		    $crud->unset_export();
		 	$crud-> unset_back_to_list();
			/* Generamos la tabla */
			$data->output = $output = $crud->render();
			$data->title = $this->title;
			
			/* Cargamos en la vista*/
			$this->load->view('templates/heads', $data);
			$this->load->view('templates/header',$this->session->userdata);
			$this->load->view('templates/aside');
			$this->load->view('cms/config/structuresData/datas', $output);
			$this->load->view('templates/footer');
	
		}catch(Exception $e){
			  /* Si	 algo sale mal cachamos el error y lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
  }
  
 			
  	/**
	 * Devulelve los modulos de los cuales pertenece la estructura
	 *
	 * @param $pk
	 * @return void
	 */
	function getParentStructures($pk,$separator=''){
		$response = '';
		//$result = $this->db->select('entryStructuresId')->from('DET_ENTRY_STRUCTURE')->where("entryStructuresId = '".$pk."' AND subEntryStructureId IS NOT NULL")->get();
		$result = $this->db->select('entryStructuresId')->from('DET_ENTRY_STRUCTURE')->where("subEntryStructureId = '".$pk."' ")->get();
		foreach ($result->result() as $row)
			//$response .= (strlen($response)>0?',':'').$row->entryStructuresId.$this->getParentStructures($row->entryStructuresId,',');
			//$response .= (strlen($response)>0?',':'').$row->entryStructuresId.$this->getParentStructures($row->entryStructuresId,',');
			$response .= (strlen($response)>0?',':'').$row->entryStructuresId.$this->getParentStructures($row->entryStructuresId,',');
					
		return (strlen($response)>0?$separator:'').$response;
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
		$this->db->from('CAT_ENTRY_STRUCTURES');

		$res = $this->db->where('entryStructuresId', $primary_key)->get();

		if ($res->num_rows() > 0){
			$rs_qry = $res->row(); 		
			$post_array['insertDate']	= $rs_qry->insertDate;
			$post_array['insertUserId']	= $rs_qry->insertUserId;
		}
		
		return $post_array;
	}
	
	
  	/**
	 * Función para no modificar nombre y descripcion
	 *
	 * @return void
	 */
	function assignNameDescription($post_array, $primary_key){
		
		$this->db->select("name, description"); 
		$this->db->from('CAT_ENTRY_STRUCTURES');

		$res = $this->db->where('entryStructuresId', $primary_key)->get();

		if ($res->num_rows() > 0){
			$rs_qry = $res->row(); 		
			$post_array['name']	= $rs_qry->name;
			$post_array['description']	= $rs_qry->description;
		}
		
		return $post_array;
	}
	
  	/**
	 * Agregarle funcionalidad a la columna de estructuras
	 *
	 * @return void
	 */
	public function _callback_view_structures($value, $row){
		
		$this->db->select("CAT_ENTRY_STRUCTURES.name, DET_ENTRY_STRUCTURE.entryStructuresId, DET_ENTRY_STRUCTURE.subEntryStructureId"); 
		$this->db->from('CAT_ENTRY_STRUCTURES');
		$this->db->join('DET_ENTRY_STRUCTURE', 'DET_ENTRY_STRUCTURE.subEntryStructureId = CAT_ENTRY_STRUCTURES.entryStructuresId', 'INNER');
		$rs_qry = $this->db->where('DET_ENTRY_STRUCTURE.entryStructuresId', $row->entryStructuresId)->get();
		
		//$strucutres = '<ul>';
		$strucutres = '';
		//if($rs_qry->num_rows > 0)	
			foreach ($rs_qry->result() as $row)
						$strucutres .= '-'.$row->name.'<br>';
						//$strucutres .= '<li>2'.$row->name.'</li>';
		

	   	//$strucutres .= "<li><a href='".site_url('admin/sub_webpages/'.$value)."' >Editar</a></li>";
		return $strucutres;// .= '</ul>';
	}


  	/**
	 * Agregarle funcionalidad a la columna de datos
	 *
	 * @return void
	 */
	public function _callback_view_datas($value, $row){

		$this->db->select("CAT_DATA_TYPES.name, CAT_DATA_TYPES.displayName, DET_ENTRY_STRUCTURE.entryStructuresId, DET_ENTRY_STRUCTURE.subEntryStructureId"); 
		$this->db->from('CAT_DATA_TYPES');
		$this->db->join('DET_ENTRY_STRUCTURE', 'DET_ENTRY_STRUCTURE.dataTypesId = CAT_DATA_TYPES.dataTypesId', 'INNER');
		$rs_qry = $this->db->where('DET_ENTRY_STRUCTURE.entryStructuresId', $row->entryStructuresId)->get();
		
		$strucutres = '';
		foreach ($rs_qry->result() as $row)
			$strucutres .= '-'.$row->name.'<br>';
		return $strucutres;
	}
	
	
  	/**
	 * Eliminar una estructura, es necesario borrar sus relaciones en DET_ENTRY_STRUCUTRE
	 *
	 * @return void
	 */
	public function _delete_structure($value, $row){

		$this->db->select("CAT_DATA_TYPES.name, CAT_DATA_TYPES.displayName, DET_ENTRY_STRUCTURE.entryStructuresId, DET_ENTRY_STRUCTURE.subEntryStructureId"); 
		$this->db->from('CAT_DATA_TYPES');
		$this->db->join('DET_ENTRY_STRUCTURE', 'DET_ENTRY_STRUCTURE.dataTypesId = CAT_DATA_TYPES.dataTypesId', 'INNER');
		$rs_qry = $this->db->where('DET_ENTRY_STRUCTURE.entryStructuresId', $row->entryStructuresId)->get();
		
		$strucutres = '';
		foreach ($rs_qry->result() as $row)
			$strucutres .= '-'.$row->name.'<br>';
		return $strucutres;
	}
	
  	/**
	 * Eliminar una estructura, es necesario borrar sus relaciones en DET_ENTRY_STRUCUTRE
	 *
	 * @return void
	 */
	public function _callback_delete_structure($value, $row=null){

		$this->db->where('DET_ENTRY_STRUCTURE.entryStructuresId', $value);
		$this->db->delete('DET_ENTRY_STRUCTURE');
		$this->db->where('DET_ENTRY_STRUCTURE.subEntryStructureId', $value);
		$this->db->delete('DET_ENTRY_STRUCTURE');
	}
	
  	/**
	 * Al actualizar una estructura, es necesario crear sus datos en DET_ENTRY_CONTENTS
	 *
	 * @return void
	 */
	public function _callback_update_structure($post_array, $primary_key){
		$contents = array();
		$this->db->where('entryStructuresId', $post_array['entryStructuresId']);
		$rs = $this->db->from('CAT_ENTRY_CONTENTS')->get();
		
		foreach($rs->result() as $row){
			foreach($post_array['datas'] as $dataId)
				if($this->db->where('entryContentsId ='.$row->entryContentsId.' AND dataTypesId ='.$dataId)->from('DET_ENTRY_CONTENTS')->get()->num_rows() == 0)
					$this->db->insert("DET_ENTRY_CONTENTS", array('data'=>'','entryContentsId'=>$row->entryContentsId,'dataTypesId'=>$dataId));
			array_push($contents, $row->entryContentsId);
		}
				
		$this->db->flush_cache();
		$this->db->from('DET_ENTRY_CONTENTS');
		$this->db->where('dataTypesId IS NOT NULL');

		$rs = $this->db->where_in('entryContentsId', $contents);
		$rs = $this->db->where_not_in('dataTypesId', $post_array['datas'])->get();
		$this->db->insert('test',array('data'=>$this->db->last_query(),'extra'=>json_encode($contents)));
		$this->db->flush_cache();
		foreach($rs->result() as $row)		
			$this->db->where('id',$row->id)->delete('DET_ENTRY_CONTENTS');
		
	}

  	
}
/* End of file StructureDatas.php */
/* Location: ./application/controllers/Cms/config/StructureDatas.php */
