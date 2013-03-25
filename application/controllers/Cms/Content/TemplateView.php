<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TemplateView extends CI_Controller {			/* Heredamos de la clase CI_Controller */

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
	$this->title = "Adminsitración de template";
	
  }

    /**
     * Función para obtener los permisos del grupo de usuario para todos los modulos
     *
     * @param integer $primary_key
     * @return void
     */
  function index(){
    redirect('/cms/content/templateView/edit');
  }
	
	function edit($sectionId=NULL){
		$data->output->js_files = array();
		$data->lines 			= array();
		
		$rs = $this->db->select('CAT_TEMPLATES.urlFile, ')->
					from('CAT_SECTIONS')->
					join('CAT_TEMPLATES','CAT_TEMPLATES.templatesId = CAT_SECTIONS.templatesId','INNER')->
					where('sectionsId',$sectionId)->get();
					
		if($rs->num_rows==0)
			echo "La sección seleccionada no cuenta con una plantilla asignada.";	
			
		$file_path = $this->config->item('upload_templates_folder');
		$file_section_path = $this->config->item('upload_section_templates_folder');
		
		$row = $rs->row();
		if(!file_exists($file_path.$row->urlFile))
			echo "El archivo ".$file_path.$row->urlFile." no existe";
			
		if(!file_exists($file_section_path.md5($sectionId).'.tpl'))
			//echo "El archivo ".$file_section_path.md5($sectionId).'.tpl'." no existe";
			copy($file_path.$row->urlFile, $file_section_path.md5($sectionId).'.tpl');
		
		$gestor = fopen($file_section_path.md5($sectionId).'.tpl', "r");
		while(!feof($gestor)) {
			$line = fgets($gestor);
			array_push($data->lines,' '.$line);
		}
		fclose($gestor);	
		$data->file 		= $file_section_path.md5($sectionId).'.tpl';
		$data->sectionId 	= $sectionId;


		$rs = $this->db->select('name, description, class, shortcutsId, isStructure, isField')->
					from('CAT_SHORTCUTS')->
					where('active',1)->get();
		$data->commands = array();
		foreach ($rs->result() as $row)		
			array_push($data->commands, array(	'name'			=>	$row->name,
												'shortcutsId'	=>	$row->shortcutsId,
												'class'			=>	$row->class,
												'isField'		=>	$row->isField,
												'isStructure'	=>	$row->isStructure,
												'description'	=>	$row->description	));
		
		$data->output->css_files = $data->output->js_files = array();
		array_push($data->output->css_files,base_url().'resources/css/tinybox.css');
		array_push($data->output->css_files,base_url().'resources/css/editor.css');
		array_push($data->output->css_files,base_url().'resources/css/chosen/chosen.css');
		array_push($data->output->js_files,base_url().'resources/js/tinybox.js');
			/* Cargamos en la vista*/
		array_push($data->output->js_files,base_url().'resources/js/jquery.min.js');
		array_push($data->output->js_files,base_url().'resources/js/jquery-ui.js');
		array_push($data->output->js_files,base_url().'resources/js/test/editHTML.js');
		array_push($data->output->js_files,base_url().'resources/js/chosen/chosen.jquery.js');
		array_push($data->output->js_files,base_url().'resources/js/jquery-litelighter.js');
		array_push($data->output->js_files,base_url().'resources/js/jquery-litelighter-extra.js');
		array_push($data->output->js_files,base_url().'resources/js/cms/content/templateView.js');
		array_push($data->output->js_files,base_url().'resources/js/ZeroClipboard.js');
		
		
		$data->versions = array();
		if ($handle = opendir($file_section_path)) {
			while (false !== ($entry = readdir($handle)))
				if (strpos($entry,md5($sectionId)) !== false) 
					array_push($data->versions,strlen(str_replace('.tpl','',str_replace(md5($sectionId),'',$entry)))>0?str_replace('.tpl','',str_replace(md5($sectionId),'',$entry)):'|  Actual  |');
			closedir($handle);
		}
		arsort($data->versions);
			/* Cargamos en la vista*/
			$this->load->view('templates/heads', $data);
			$this->load->view('templates/header',$this->session->userdata);
			$this->load->view('templates/aside');
			$this->load->view('cms/content/TemplateView', $data);
			$this->load->view('templates/footer');
	}
	
	
	function saveTemplate(){
		
		$data = json_decode($this->input->post('data'),true);
		$rs = $this->db->select('CAT_SECTIONS.sectionsId, templateUpdateDate ')->
					from('CAT_SECTIONS')->
					join('CAT_TEMPLATES','CAT_TEMPLATES.templatesId = CAT_SECTIONS.templatesId','INNER')->
					where('sectionsId',$data['sectionId'])->get();
		
						
		$row 		= $rs->row();
		
		$file_section_path = $this->config->item('upload_section_templates_folder');
		$outputFile = $file_section_path.md5($row->sectionsId).'.tpl';
		
		if(file_exists($outputFile))
			copy($outputFile, $file_section_path.md5($row->sectionsId).'['.$row->templateUpdateDate.'].tpl');
		
		
		$textFile = '';
		foreach ($data as $textLine) 
			if(isset($textLine['text']))
					$textFile .= str_replace("\t", "	",$textLine['text']);
				//if(strrpos($textLine['text'], '\n') === strlen($textLine['text'])-strlen('\n'))
					//echo $textFile .= str_replace("\t", "	",substr($textLine['text'],0,strlen($textLine['text'])-strlen('\n')));
						 
		if(strlen($textFile)>2)	$textFile = substr($textFile,0,strlen($textFile)-2);
		file_put_contents($data['file'], $textFile);
		file_get_contents($outputFile); 
		
		$updateData = array(  	'templateUpdateDate' 	=> date('Y-m-d h:i:s'),
								'templateUpdateUserId' 	=> $this->session->userdata['usersId']		);
		$this->db->where('sectionsId',		$data['sectionId']);
		$this->db->update('CAT_SECTIONS', 	$updateData); 

		$response->message = 'Información actualizada correctamente.';
		echo json_encode($response);
	}
	
	
	
	function publishTemplate(){
		
		$data = json_decode($this->input->post('data'),true);
		
		$response->message = '';
		$this->load->library('sectionCreator');	
		$generator = new sectionCreator();		
		$response->message .= $generator->generateViewCode($data['sectionId']);
		$response->message .= $generator->generateControllerCode($data['sectionId']);
		
		if(strlen($response->message)==0)
			$response->message = 'Sección publicada correctamente.';
			
		echo json_encode($response);
	}
	
	
	
	function getShortcut(){
		
		$data = json_decode($this->input->post('data'),true);
		$rs = $this->db->select('CAT_SHORTCUTS.code ')->
					from('CAT_SHORTCUTS')->
					where('shortcutsId',$data['shortcutId'])->get();
		$row 		= $rs->row();
		$response->message 		= $row->code;
		$response->lineCodes 	= explode("\n",$row->code);
		$response->lineCodes  = array_reverse($response->lineCodes);
		
		echo json_encode($response);
	}
	
	
	
	function getShortcutInfo(){
		
		$shortcutId = $this->input->post('shortcutId');
		//$shortcutId = $this->input->get('s');
		$rs = $this->db->select('code, isStructure, isField')->
					from('CAT_SHORTCUTS')->
					where('shortcutsId',$shortcutId)->get();
		$rowCode 		= $rs->row();
		
		$rs = $this->db->select('name, entryStructuresId')->
					from('CAT_ENTRY_STRUCTURES')->
					where('ACTIVE',1)->get();
		$output->structures =  array();
		foreach($rs->result() as $row)
			array_push($output->structures,array('structuresId'=>$row->entryStructuresId,'name'=>$row->name));
			

		$output->lines 			= explode("\n",$rowCode->code);
		$output->isStructure 	= $rowCode->isStructure;
		$output->isField 		= $rowCode->isField;
		
		$this->load->view('cms/content/viewShortcutDetails', $output);
	}
	
	function getDataTypes(){
		$structureId = $this->input->post('structureId');
		$rs = $this->db->select('MD5(CAT_DATA_TYPES.dataTypesId) as \'dataTypesId\',CAT_DATA_TYPES.displayName')->
					from('CAT_DATA_TYPES')->
					join('DET_ENTRY_STRUCTURE','DET_ENTRY_STRUCTURE.dataTypesId = CAT_DATA_TYPES.dataTypesId','INNER')->
					where('MD5(DET_ENTRY_STRUCTURE.entryStructuresId)',$structureId)->
					where('CAT_DATA_TYPES.active',1)->get();
		
		$output->structures =  array();
		foreach($rs->result() as $row)
			array_push($output->structures,array('id'=>$row->dataTypesId,'name'=>$row->displayName));
		echo json_encode($output->structures);
	}
		
		
	
	function getVersionViewCode(){
		
		
		$data->output->js_files = array();
		$data->lines 			= array();
		
		$rs = $this->db->select('CAT_TEMPLATES.urlFile, ')->
					from('CAT_SECTIONS')->
					join('CAT_TEMPLATES','CAT_TEMPLATES.templatesId = CAT_SECTIONS.templatesId','INNER')->
					where('sectionsId',$this->input->post('sectionId'))->get();
		$this->db->last_query();
		
		$version = $this->input->post('version');
		if (stripos($version,'actual') !== false) $version = '';
		
		$file_section_path = $this->config->item('upload_section_templates_folder');
		$data->urlFile = $urlFile = $file_section_path.md5($this->input->post('sectionId')).$version.'.tpl';

		if(!file_exists($urlFile))
			echo "Archivo no encontrado";
		
		$gestor = fopen($urlFile, "r");
		while(!feof($gestor)) {
			$line = fgets($gestor);
			array_push($data->lines,' '.$line);
		}
		fclose($gestor);	
		
		/* Cargamos en la vista*/
		$this->load->view('cms/content/viewVersionView', $data);
	}
		
	
}

/* End of file TemplateView.php */
/* Location: ./application/controllers/Cms/Content/TemplateView.php */