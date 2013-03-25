<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Publish extends CI_Controller {			/* Heredamos de la clase CI_Controller */


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
    redirect('/cms/content/Publish/editContent');
  }
  
  
  function editContent($entryContentId=null){
		try{
			

			$moduleData= array();
			$rs_qry = $this->db->select('name, description, entryContentsId, entryStructuresId')->
									from('CAT_ENTRY_CONTENTS')->
										where("entryContentsId = '$entryContentId'")->get();

			if($rs_qry->num_rows()>0)
				$moduleData =  $rs_qry->row(); 
				
			$crud = new grocery_CRUD();				/* Creamos el objeto */
			$crud->set_theme('flexigrid');			/* Seleccionamos el tema */

			$crud->set_table('DET_ENTRY_CONTENTS');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			//$crud->where("DET_ENTRY_CONTENTS.entryContentsId 		= 	".$entryContentId);		
						
						
			//$crud->where("DET_ENTRY_CONTENTS.entryContentsId 		= 	".$entryContentId. " AND ".
				//		"DET_ENTRY_CONTENTS.entryStructuresId 	= 	".$moduleData->entryStructuresId);		
			$crud->or_where(" DET_ENTRY_CONTENTS.entryContentsId 		= 	".$entryContentId." GROUP BY  entryContentsId ",NULL, FALSE);	
			
			//echo $this->db->last_query();	

			$crud->set_subject('Publicación de '.(isset($moduleData->name)?$moduleData->name:''));	
			$crud->set_language('spanish');			/* Asignamos el idioma español */

			$crud->set_relation('entryStructuresId','CAT_ENTRY_STRUCTURES','name');
			$crud->set_relation('dataTypesId','CAT_DATA_TYPES','name');
			//$crud->set_relation_n_n('dataValue','DET_ENTRY_CONTENTS','CAT_DATA_TYPES','entryStructuresId','dataTypesId','name');

			
			/* Campos del modelo */
			$crud->fields(
				'entryStructuresId',
			  	'data',
			  	'entryContentsId',
			  	'dataTypesId',
			  	'entriesId');
				
			/* Campos obligatorios */
			$crud->required_fields(
				'entryStructuresId',
			  	'data',
			  	'entryContentsId',
			  	'dataTypesId',
			  	'entriesId');
			
			/* Campos a mostrar */
			$crud->columns(
				'entryStructuresId',
			  	'datas',
			  	'operations')        
			->display_as('entryStructuresId','Tipo de publicación');
						
			$crud->callback_column('datas',	array($this,'_callback_view_dataContent'));
			$crud->callback_column('operations',	array($this,'_callback_view_edit_operations'));
								
		    //$crud->field_type('entryStructuresId', 'hidden', $moduleData->entryStructuresId);
		   /*
		    $crud->field_type('insertUserId', 'hidden', $this->session->userdata('usersId'));
		    $crud->field_type('updateUserId', 'hidden', $this->session->userdata('usersId'));
		    $crud->field_type('updateDate', 'hidden', date('Y-m-d H:m:s'));
		    $crud->field_type('insertDate', 'hidden', date('Y-m-d H:m:s'));
			*/
				
			//Callbacks
			//$crud->unset_operations();	
			$crud->unset_print();	
			$crud->unset_edit();	
			$crud->unset_delete();	
			$crud->unset_export();	
			//$crud->callback_add_field('state',array($this,'_callback_add_entryStructuresId'));

			//$crud->set_relation('insertUserId','CAT_USERS','username');
			//$crud->set_relation('updateUserId','CAT_USERS','username');
			
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
	 * Mostrar los datos de la publicación
	 *
	 * @return void
	 */
	public function viewPublishData(){
		
		$this->db->select("*"); 
		$this->db->from('CAT_ENTRY_CONTENTS');
		$this->db->join('DET_ENTRY_CONTENTS', 'CAT_ENTRY_CONTENTS.entryContentsId  = DET_ENTRY_CONTENTS.entryContentsId ', 'INNER');
		$this->db->join('CAT_DATA_TYPES', 'DET_ENTRY_CONTENTS.dataTypesId  	= CAT_DATA_TYPES.dataTypesId ', 'INNER');
		$rs_qry = $this->db->where("MD5(DET_ENTRY_CONTENTS.entrycontentsId) = '".$this->input->post('entryContentsId')."'  ")->get();
		
		$output->data = array();
		foreach ($rs_qry->result() as $row)
			array_push($output->data,$row);


		$this->load->view('cms/content/viewPublishData', $output);
		
	}
	
	
  	/**
	 * Mostrar los datos de la publicación
	 *
	 * @return void
	 */
	public function editPublishData($entryContentsId){
		
		$this->db->select("CAT_ENTRY_CONTENTS.name as publishName, CAT_ENTRY_CONTENTS.description as publishDescription, 
							CAT_ENTRY_CONTENTS.permanently, CAT_ENTRY_CONTENTS.insertDate,
							CAT_ENTRY_CONTENTS.updateDate, CAT_ENTRY_CONTENTS.dateEnd, CAT_ENTRY_CONTENTS.dateStart, CAT_DATA_TYPES.*, CAT_DATA_TYPE_SHOW_FORMS.showLike, 
							DET_ENTRY_CONTENTS.data, DET_ENTRY_CONTENTS.id, DET_ENTRY_CONTENTS.entryContentsId , DET_ENTRY_CONTENTS.dataTypesId "); 
		$this->db->from('CAT_ENTRY_CONTENTS');
		$this->db->join('DET_ENTRY_CONTENTS', 'CAT_ENTRY_CONTENTS.entryContentsId  = DET_ENTRY_CONTENTS.entryContentsId ', 'INNER');
		$this->db->join('CAT_DATA_TYPES', 'DET_ENTRY_CONTENTS.dataTypesId  	= CAT_DATA_TYPES.dataTypesId ', 'INNER');
		$this->db->join('CAT_DATA_TYPE_SHOW_FORMS', 'CAT_DATA_TYPE_SHOW_FORMS.dataTypeShowFormsId  	= CAT_DATA_TYPES.dataTypeShowFormsId ', 'INNER');
		$this->db->order_by('order');
		$rs_qry = $this->db->where("DET_ENTRY_CONTENTS.dataTypesId IS NOT NULL AND MD5(DET_ENTRY_CONTENTS.entrycontentsId)	= '".$entryContentsId."'  ")->get();
		
		$output->publicacion = $rs_qry->row();
		$output->data = array();
		foreach ($rs_qry->result() as $row)
			array_push($output->data,$row);

		$this->load->view('templates/headsTemplate',$output);
		$this->load->view('templates/header',$this->session->userdata);
		$this->load->view('templates/aside');
		$this->load->view('cms/content/editPublishData', $output);
		$this->load->view('templates/footer');
	}
	
	
  	/**
	 * Mostrar los datos de la publicación
	 *
	 * @return void
	 */
	public function savePublishData(){

		$data = json_decode($this->input->post('data'),true);

		foreach ($data as $dataRow) 
			if(isset($dataRow['dataId']))
				$this->db->where('id', $dataRow['dataId'])
						 ->update('DET_ENTRY_CONTENTS', array('data'=>$dataRow['dataValue'])); 
		$response->message = 'Información actualizada correctamente.';
		echo json_encode($response);
		
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

  	/**
	 * Mostrar los primeros cinco datos de la publicación
	 *
	 * @return void
	 */
	function _callback_add_entryStructuresId($p1='', $p2=''){
		return '
		<div id="entryStructuresId_input_box" class="form-input-box">
					<select style="width: 300px; display: none;" data-placeholder="Select Tipo de publicación" class="chosen-select chzn-done"
						 name="entryStructuresId" id="field-entryStructuresId">
						 <option value="8">Categorías</option>
					</select>
					<div id="field_entryStructuresId_chzn" class="chzn-container chzn-container-single" style="width: 300px;">
						<a tabindex="-1" class="chzn-single chzn-default" href="javascript:void(0)">
							<span>Select Tipo de publicación</span>
					<div><b></b></div></a><div style="left: -9000px; width: 298px; top: 24px;" class="chzn-drop"><div class="chzn-search"><input type="text" autocomplete="off" style="width: 278px;"></div><ul class="chzn-results"><li style="" class="active-result" id="field_entryStructuresId_chzn_o_1">Categorías</li><li style="" class="active-result" id="field_entryStructuresId_chzn_o_2">Galería de producto</li><li style="" class="active-result" id="field_entryStructuresId_chzn_o_3">Marcas</li><li style="" class="active-result" id="field_entryStructuresId_chzn_o_4">Persona</li><li style="" class="active-result" id="field_entryStructuresId_chzn_o_5">Productos</li></ul></div></div>				</div>';
	}
}



/* End of file Publish.php */
/* Location: ./application/controllers/Cms/Content/Publish.php */