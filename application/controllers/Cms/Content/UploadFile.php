<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UploadFile  extends CI_Controller {			/* Heredamos de la clase CI_Controller */

  function __construct(){
   
    parent::__construct();
   
    $this->load->database();				/* Cargamos la base de datos */
    $this->load->library('grocery_crud');	/* Cargamos la libreria*/   
    $this->load->helper('url'); 			/* Añadimos el helper al controlador */
	$this->title = "Adminsitración de Estructura de Datos";
  }

    /**
     * Función para subir archivo y actualizar BD
     *
     * @return void
     */
	function index(){
	
		$rs_qry = $this->db->
					select('CAT_DATA_TYPES.prefix, CAT_DATA_TYPES.postfix')->
					from('DET_ENTRY_CONTENTS')->
					join('CAT_DATA_TYPES', 'CAT_DATA_TYPES.dataTypesId = DET_ENTRY_CONTENTS.dataTypesId', 'INNER')->
					join('CAT_DATA_TYPE_SHOW_FORMS', 'CAT_DATA_TYPE_SHOW_FORMS.dataTypeShowFormsId = CAT_DATA_TYPES.dataTypeShowFormsId', 'INNER')->
					where('DET_ENTRY_CONTENTS.id = '.$this->input->post('id'))->get();
		$row = $rs_qry->row();
		$targetFolder = '/'.$this->config->item('upload_complete_folder_url'); 
		
				
		if (!empty($_FILES)) {
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder.$row->prefix;
			$targetFile = rtrim($targetPath,'/').'/'.md5($this->input->post('id'));
			
			$fileParts = pathinfo($_FILES['Filedata']['name']);
			
				move_uploaded_file($tempFile,$targetFile.'.'.strtolower($fileParts['extension']));
				
				$this->db->where('id = '.$this->input->post('id'))->
					update('DET_ENTRY_CONTENTS', array(
															'data'=>md5($this->input->post('id')).'.'.strtolower($fileParts['extension']),
															'updateDate'		=>date('Y-m-d h:m:s')
													   ));
				
				echo base_url().'/'.$this->config->item('upload_folder_url').$row->prefix.md5($this->input->post('id')).'.'.strtolower($fileParts['extension']).'?'.date('h_m_s');
				
		}
	}
	
	
    /**
     * Función para subir archivo y actualizar BD con Ajax
     *
     * @return void
     */
	function ajax(){
		
	   $destination_path = getcwd().DIRECTORY_SEPARATOR;
	
	   $result = 0;
	   
		$fileParts = pathinfo($_FILES['file']['name']);
	   echo $target_path = $destination_path.$this->config->item('upload_folder_url').$this->input->post('prefix').$this->input->post('idContent').'.'.strtolower($fileParts['extension']);
	
		$response->file = $_FILES['file']['name'];
		$response->newFile = base_url().$this->config->item('upload_folder_url').$this->input->post('prefix').$this->input->post('idContent').'.'.strtolower($fileParts['extension']);
		$response->id =  $this->input->post('idContent');
		
	   mkdir(dirname($target_path), 0755, true); 
	   if(@move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
		  $response->message = 'El archivo '.$response->file.' se cargó correctamente.';
	   }
	   
	   sleep(1);
	   $this->db->where("md5(id) = '".$this->input->post('idContent')."'")->
					update('DET_ENTRY_CONTENTS', array(		'data'			=>$this->input->post('idContent').'.'.strtolower($fileParts['extension']),
															'updateDate'	=>date('Y-m-d h:m:s')
													   ));
			   
		?>
			<script language="javascript" type="text/javascript">
				var response = <?php echo json_encode($response); ?>;
				window.top.window.stopUpload('<?php echo $result; ?>',response);
            </script>   
		<?php
	}
  	
}
/* End of file UploadFile.php */
/* Location: ./application/controllers/Cms/content/UploadFile.php */
