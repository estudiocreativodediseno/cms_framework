<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cruceros extends CI_Controller {			

    /******* Variables globales *******/
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('url'); 
        $this->title = "Cruceros - Catalogo de Cruceros";
    }
    

  	/**
     * Código generado automáticamente por CMS-FRAMEWORK [2013-03-15 12:23:21]
	 * Cruceros - Catalogo de Cruceros
	 *
	 * @return void
	 */
     function index(){
		try{
        
        	//inicia function_index_code
              
						$rs_contents = $this->db->select('CAT_ENTRY_CONTENTS.*')->
										from('CAT_ENTRY_CONTENTS')->
										where('MD5(entryStructuresId)','c20ad4d76fe97759aa27a0c99bff6710')->get();
						
						$this->db->flush_cache();
						$output->cruceros_elements = array();
						foreach ($rs_contents->result() as $row){
							$this->db->select("DET_ENTRY_CONTENTS.data, CAT_DATA_TYPES.dataTypesId"); 
							$this->db->from("CAT_ENTRY_CONTENTS");
							$this->db->join("DET_ENTRY_CONTENTS", "CAT_ENTRY_CONTENTS.entryContentsId  = DET_ENTRY_CONTENTS.entryContentsId ", "INNER");
							$this->db->join("CAT_DATA_TYPES", "DET_ENTRY_CONTENTS.dataTypesId  	= CAT_DATA_TYPES.dataTypesId ", "INNER");
							$rs_qry = $this->db->where("MD5(DET_ENTRY_CONTENTS.entrycontentsId) = '".md5($row->entryContentsId)."'  ")->get();

							$dataRow = array();
							foreach ($rs_qry->result() as $rowData)
								array_push($dataRow,array( 
															"data"=>$rowData->data,
															"dataTypeId"=>$rowData->dataTypesId,
															"dataTypeIdEncrypt"=>MD5($rowData->dataTypesId),
															"data"=>$rowData->data));
							
							array_push($output->cruceros_elements,$dataRow);
						}
									$fieldTags->entryContentsId = "d645920e395fedad7bbbed0eca3fe2e0";
  						$fieldTags->nombre = "d645920e395fedad7bbbed0eca3fe2e0";
  						$fieldTags->lugar = "d645920e395fedad7bbbed0eca3fe2e0";
  						$fieldTags->imagen = "d645920e395fedad7bbbed0eca3fe2e0";
  						$fieldTags->precio = "d645920e395fedad7bbbed0eca3fe2e0";
  /*No se reconoció la instrucción: endloop*/
			
        	//termina function_index_code
	
			$output->fieldTags = $fieldTags;
			$data->title = 'Cruceros - Catalogo de Cruceros';
			
			/* Se carga la vista del Controlador*/
			$this->load->view('/prueba/cruceros', $output);
		}catch(Exception $e){
			  /* En caso de error, lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
     }
     

  	/**
     * Código generado automáticamente por CMS-FRAMEWORK
	 * Búsqueda por id: Cruceros - Catalogo de Cruceros
	 *
	 * @return void
	 */
     function id($valueId){
		try{
        
        	//inicia function_id_code
              /*No se reconoció la instrucción: startloop*/
			/*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: endloop*/
			
        	//termina function_id_code
	
			
			$output->fieldTags = $fieldTags;
			$data->title = 'Cruceros - Catalogo de Cruceros';
			
			/* Se carga la vista del Controlador*/
			$this->load->view('/prueba/cruceros', $output);
		}catch(Exception $e){
			  /* En caso de error, lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
     }
     
  	/**
     * Código generado automáticamente por CMS-FRAMEWORK
	 * Búsqueda por página de resultados
	 *
	 * @param	page		Página
	 * @return 	void
	 */
     function page($page){
		try{
        
        	//inicia function_page_code
              /*No se reconoció la instrucción: startloop*/
			/*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: endloop*/
			
        	//termina function_page_code
	
			
			$output->fieldTags = $fieldTags;
			$data->title = 'Cruceros - Catalogo de Cruceros';
			
			/* Se carga la vista del Controlador*/
			$this->load->view('/prueba/cruceros', $output);
		}catch(Exception $e){
			  /* En caso de error, lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
     }
     
     
  	/**
     * Código generado automáticamente por CMS-FRAMEWORK
	 * Búsqueda por coincidencia exacta de campo
	 *
	 * @param 	field   	Campos par aplicar la búsqueda
	 * @param 	q			Criterio de búsqueda
	 * @param 	page		Página
	 * @return 	void		
	 */
     function find($field, $q, $page=1){
		try{
        
        	//inicia function_find_code
              /*No se reconoció la instrucción: startloop*/
			/*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: endloop*/
			
        	//termina function_find_code
	
			
			$output->fieldTags = $fieldTags;
			$data->title = 'Cruceros - Catalogo de Cruceros';
			
			/* Se carga la vista del Controlador*/
			$this->load->view('/prueba/cruceros', $output);
		}catch(Exception $e){
			  /* En caso de error, lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
     }
     
     
  	/**
     * Código generado automáticamente por CMS-FRAMEWORK
	 * Búsqueda por coincidencia similar (uso de Like %q%
	 *
	 * @param 	field   	Campos par aplicar la búsqueda
	 * @param 	q			Criterio de búsqueda
	 * @param 	page		Página
	 * @return 	void		
	 */
     function like($field, $q, $page=1){
		try{
        
        	//inicia function_like_code
              /*No se reconoció la instrucción: startloop*/
			/*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: element*/
			  /*No se reconoció la instrucción: endloop*/
			
        	//termina function_like_code
	
			
			$output->fieldTags = $fieldTags;
			$data->title = 'Cruceros - Catalogo de Cruceros';
			
			/* Se carga la vista del Controlador*/
			$this->load->view('/prueba/cruceros', $output);
		}catch(Exception $e){
			  /* En caso de error, lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
     }
     
     /******* Funciones extras *******/
     
}


/* End of file Cruceros.php */
/* Location: ./application/controllers/prueba/cruceros.php */
