<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ContentsPublished extends CI_Controller {			/* Heredamos de la clase CI_Controller */

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
    redirect('/cms/content/ContentsPublished/showList');
  }

    /**
     * Función para obtener todas las publicaciones del tipo de estructura solicitada
     *
     * @param integer $entryStrucutreId
     * @return void
     */
  function showList($entryStrucutreId=''){
		try{
			$moduleData= array();
			$rs_qry = $this->db->select('name, description, entryStructuresId')->from('CAT_ENTRY_STRUCTURES')->where("MD5(entryStructuresId) = '$entryStrucutreId'")->get();
			if($rs_qry->num_rows()>0)
				$moduleData =  $rs_qry->row(); 
				
			$crud = new grocery_CRUD();				/* Creamos el objeto */
			$crud->set_theme('flexigrid');			/* Seleccionamos el tema */
			//$crud->set_theme('datatables');			/* Seleccionamos el tema */
			$crud->set_table('CAT_ENTRY_CONTENTS');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->where('entryStructuresId',$moduleData->entryStructuresId);		
			$crud->set_subject('Publicación de '.(isset($moduleData->name)?$moduleData->name:''));	
			$crud->set_language('spanish');			/* Asignamos el idioma español */
		
			
			/* Campos del modelo */
			$crud->fields(
				'entryStructuresId',
			  	'name',
			  	'description',
			  	'active',
			  	'dateStart',
			  	'dateEnd',
				'permanently',
			  	'tagsId',
			  	'insertDate',
			  	'insertUserId',
			  	'updateDate',
			  	'updateUserId');
			/* Campos obligatorios */
			$crud->required_fields(
			  	'name',
				'permanently',
			  	'active');
			
			/* Campos a mostrar */
			$crud->columns(
				'entryStructuresId',
			  	'name',
			  	'description',
			  	'datas',
			  	'active',
			  	'dateStart',
			  	'dateEnd',
			  	'tagsId',
			  	'insertDate',
			  	'insertUserId',
			  	'updateDate',
			  	'updateUserId',
			  	'operations')        
			->display_as('entryStructuresId','Tipo de publicación')
			->display_as('name','Título')
			->display_as('description','Notas')
			->display_as('dateStart','Fecha inicio')
			->display_as('dateEnd','Fecha finalización')
			->display_as('permanently','Permanente')
			->display_as('active','Activo')
			->display_as('tagsId','Tags')
			->display_as('insertDate','Fecha creación')
			->display_as('insertUserId','Usuario Creación')
			->display_as('updateDate','Ult. Actualización')
			->display_as('updateUserId','Usuario Actualización');
								
			$crud->callback_column('datas',	array($this,'_callback_view_dataContent'));
			$crud->callback_column('operations',	array($this,'_callback_view_edit_operations'));

		    $crud->field_type('entryStructuresId', 'hidden', $moduleData->entryStructuresId);
		    $crud->field_type('insertUserId', 'hidden', $this->session->userdata('usersId'));
		    $crud->field_type('updateUserId', 'hidden', $this->session->userdata('usersId'));
		    $crud->field_type('updateDate', 'hidden', date('Y-m-d H:m:s'));
		    $crud->field_type('insertDate', 'hidden', date('Y-m-d H:m:s'));
			
			
			// Validación de campos
			$crud->set_rules('name', 'Título', 'trim|min_length[3]|max_length[120]|xss_clean');
	

	    	$crud->add_action('Editar publicación', base_url().'images/icons/editContent.png', 'Cms/Content/Publish/editContent');//, '', array($this,'editContent'));
			
			//Callbacks
			$crud->callback_before_insert(array($this,'clearDataInsert'));
			$crud->callback_before_update(array($this,'clearDataUpdate'));
			$crud->callback_before_delete(array($this,'deleteDataDetails'));
			$crud->callback_after_insert(array($this,'insertDataDetails'));
			$crud->callback_after_update(array($this,'insertDataDetails'));
			
			
			$crud->set_relation('insertUserId','CAT_USERS','username');
			$crud->set_relation('updateUserId','CAT_USERS','username');
			
			/* Generamos la tabla */
			$data->output = $output = $crud->render();
			$data->title = $this->title;
			
			/* Cargamos en la vista*/
			array_push($data->output->css_files,base_url().'resources/css/tinybox.css');
			array_push($data->output->js_files,base_url().'resources/js/tinybox.js');
			array_push($data->output->js_files,base_url().'resources/js/cms/content/publish.js');
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
	 * Función para no modificar el update de los capos de usarios de creacion de registro
	 *
	 * @return void
	 */
	function insertDataDetails($post_array, $primary_key){
		
		$rs_qry = $this->db->select('entryContentsId, entryStructuresId')->from('CAT_ENTRY_CONTENTS')->
									where("entryContentsId = '".$primary_key."'")->get();
		$rowContent = $rs_qry->row();
		
		if ($rs_qry->num_rows() == 0)
			return $post_array;
			
		$this->db->select("dataTypesId"); 
		$this->db->from('DET_ENTRY_STRUCTURE');		
		$res = $this->db->where("entryStructuresId = '".$rowContent->entryStructuresId."' AND dataTypesId IS NOT NULL")->get();
	
		if ($res->num_rows() == 0)
			return array();
		

		foreach ($res->result() as $row){
			$rs_qry = $this->db->select('entryContentsId, ')->from('DET_ENTRY_CONTENTS')->
							where("entryContentsId = '".$rowContent->entryContentsId."' AND dataTypesId = '".$row->dataTypesId."'")->get();
			if ($rs_qry->num_rows() == 0)
				$this->db->insert('DET_ENTRY_CONTENTS', 
								array(	'entryContentsId' 	=> 		$rowContent->entryContentsId,
										'entryStructuresId'	=> 		$rowContent->entryStructuresId, 
										'dataTypesId' 		=> 		$row->dataTypesId )); 
				
			}			
	}
	
	
  	/**
	 * Función para eliminar los datos de entrada de contenidos para esta publicación
	 *
	 * @return void
	 */
	function deleteDataDetails($primary_key){
		
		$rs_qry = $this->db->select('entryContentsId, entryStructuresId')->from('CAT_ENTRY_CONTENTS')->
									where("entryContentsId = '".$primary_key."'")->get();
		$rowContent = $rs_qry->row();
		
		if ($rs_qry->num_rows() == 0)
			return $post_array;
			
		$this->db->select("dataTypesId"); 
		$this->db->from('DET_ENTRY_STRUCTURE');		
		$res = $this->db->where("entryStructuresId = '".$rowContent->entryStructuresId."' AND dataTypesId IS NOT NULL")->get();
	
		if ($res->num_rows() == 0)
			return array();
		

		foreach ($res->result() as $row)
			$this->db->where("entryContentsId = '".$rowContent->entryContentsId."' AND dataTypesId = '".$row->dataTypesId."'")->delete('DET_ENTRY_CONTENTS');
					
	}
	
	
  	/**
	 * Mostrar los primeros cinco datos de la publicación
	 *
	 * @return void
	 */
	public function _callback_view_dataContent($value, $row){
		
		$this->db->select("*"); 
		$this->db->from('CAT_ENTRY_CONTENTS');
		$this->db->join('DET_ENTRY_CONTENTS', 'CAT_ENTRY_CONTENTS.entryContentsId  = DET_ENTRY_CONTENTS.entryContentsId ', 'INNER');
		$this->db->join('CAT_DATA_TYPES', 'DET_ENTRY_CONTENTS.dataTypesId  = CAT_DATA_TYPES.dataTypesId ', 'INNER');
		$rs_qry = $this->db->where("DET_ENTRY_CONTENTS.entrycontentsId 		= '".$row->entryContentsId."'  ")->limit(5)->get();
		
		$strucutres = '';
			foreach ($rs_qry->result() as $row)
						$strucutres .= '-'.$row->displayName.': <strong>'.(strlen($row->data)>15?substr($row->data,0,15).'...':$row->data).'</strong><br>';

		return $strucutres;// .= '</ul>';
		
		
		$this->db->select("COUNT(DISTINCT(DET_ENTRY_CONTENTS.id)) AS counter, CAT_ENTRY_STRUCTURES.name, DET_ENTRY_STRUCTURE.entryStructuresId, DET_ENTRY_STRUCTURE.subEntryStructureId"); 
		$this->db->from('CAT_ENTRY_STRUCTURES');
		$this->db->join('DET_ENTRY_STRUCTURE', 'DET_ENTRY_STRUCTURE.subEntryStructureId = CAT_ENTRY_STRUCTURES.entryStructuresId', 'INNER');
		$this->db->join('DET_ENTRY_CONTENTS', 'DET_ENTRY_CONTENTS.entryStructuresId = DET_ENTRY_STRUCTURE.entryStructuresId', 'LEFT');
		$rs_qry = $this->db->where("DET_ENTRY_STRUCTURE.entryStructuresId 	= '".$row->entryStructuresId."' AND 
									DET_ENTRY_CONTENTS.entrycontentsId 		= '".$row->entryContentsId."'  ")->get();
		
		$strucutres = '';
			foreach ($rs_qry->result() as $row)
						$strucutres .= '-'.$row->displayname.':'.$row->data.')<br>';

		return $strucutres;// .= '</ul>';
	}
	
	
	
	
  	/**
	 * Mostrar los primeros cinco datos de la publicación
	 *
	 * @return void
	 */
	public function _callback_view_edit_operations($value, $row){
		
		$result = '<div style="text-align:right; width:auto;">
					<span onclick="TINY.box.show({
													url:\''.base_url().'index.php/cms/content/publish/viewPublishData\',
													post:\'entryContentsId='.md5($row->entryContentsId).'\',
													width:800,
													height:600,
													opacity:80,
													topsplit:3
												})" class="action-button">
						<a href="#linkTnBox">
							<img src="'.base_url().'images/icons/editContent.png" alt="Ver publicación" title="Ver publicación">
						</a>
					</span>';
					
		
		$result .= '
					<span onclick="" class="action-button">
						<a href="'.base_url().'index.php/Cms/Content/Publish/editPublishData/'.md5($row->entryContentsId).'">
							<img src="'.base_url().'images/icons/textedit.png" alt="Editar publicación" title="Editar publicación">
						</a>
					</span>
				</div>';
		

		return $result;
	}

	
	
}


/* End of file Structure.php */
/* Location: ./application/controllers/Cms/Content/Structure.php */
