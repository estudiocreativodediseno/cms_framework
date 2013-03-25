<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class sectionCreator {
	
	
		var $CI;
		var $SECTION_ID;
		
		/**
		* Constructor de la librería
		*
		* return void
		*/
		public function __construct(){
			$this->CI =& get_instance();
			$this->CI->load->helper('url');
			$this->CI->load->library('session');
			$this->CI->load->database();
		}
		
		/**
		* Función que genera el còdigo HTML de la vista de la sección
		*
		* @param sectionId     id de sección a generar Vista
		* return void
		*/
		public function generateViewCode($sectionId){
			
			$this->SECTION_ID = $sectionId;
			$rs = $this->CI->db->select('CAT_SECTIONS.sectionsId, templateUpdateDate, url, frontTemplateUpdateDate')->
						from('CAT_SECTIONS')->
						join('CAT_TEMPLATES','CAT_TEMPLATES.templatesId = CAT_SECTIONS.templatesId','INNER')->
						where('sectionsId',$sectionId)->get();
			$row 		= $rs->row();
			
			$file_section_path 		= $this->CI->config->item('upload_section_templates_folder');
			$frontend_section_path 	= $this->CI->config->item('frontend_section_folder');
			$frontend_versions_path 	= $this->CI->config->item('frontend_versions_folder');
				
			$nameParts =  explode('/',$row->url);
			$controllerName = $nameParts[count($nameParts)-1];
			if(count($nameParts)>1)
				unset($nameParts[count($nameParts)-1]);
				
			if(!file_exists($file_section_path.md5($sectionId).'.tpl'))
				echo "El archivo ".$file_section_path.md5($sectionId).'.tpl'." no existe";
			

			$folder = implode('/',$nameParts);
			if(!file_exists($frontend_versions_path.'views'.$folder))
				mkdir($frontend_versions_path.'views'.$folder, 0755, true);
			if(!file_exists($frontend_section_path.'views'.$folder))
				mkdir($frontend_section_path.'views'.$folder, 0755, true);
				
			$outputFile = $frontend_section_path.'views'.$row->url.'.php';
			if(file_exists($outputFile))
				copy($outputFile, $frontend_versions_path.'views'.$row->url.'['.$row->frontTemplateUpdateDate.'].php');
				
			$data->lines = array();
			$gestor = fopen($file_section_path.md5($sectionId).'.tpl', "r");
			$textFile = '';
			while(!feof($gestor)) {
				$line = fgets($gestor);
				$line = $this->checkTemplateSintax($line);
				$textFile .= $line;
			}
			fclose($gestor);
							 
			//if(strlen($textFile)>2)	$textFile = substr($textFile,0,strlen($textFile)-2);
			file_put_contents($outputFile, $textFile);
			file_get_contents($outputFile); 
			
			$updateData = array(  	'frontTemplateUpdateDate' 	=> date('Y-m-d h:i:s'),
									'frontTemplateUpdateUserId'	=> $this->CI->session->userdata['usersId']		);
			$this->CI->db->where('sectionsId',		$sectionId);
			$this->CI->db->update('CAT_SECTIONS', 	$updateData); 
	
			return '';
		}
		
		/**
		* Función que genera el còdigo php del controlador de la sección
		*
		* @param sectionId     id de sección a generar Vista
		* return void
		*/
		public function generateControllerCode($sectionId){
			
			$rs = $this->CI->db->select('CAT_SECTIONS.*')->
						from('CAT_SECTIONS')->
						join('CAT_TEMPLATES','CAT_TEMPLATES.templatesId = CAT_SECTIONS.templatesId','INNER')->
						where('sectionsId',$sectionId)->get();
			$row 		= $rs->row();
			
			$nameParts =  explode('/',$row->url);
			$controllerName = $nameParts[count($nameParts)-1];
			if(count($nameParts)>1)
				unset($nameParts[count($nameParts)-1]);
				
			$file_section_path 		= $this->CI->config->item('upload_section_templates_folder');
			$frontend_section_path 	= $this->CI->config->item('frontend_section_folder');
			$generators_folder 		= $this->CI->config->item('template_generators_folder');
			$frontend_versions_path 	= $this->CI->config->item('frontend_versions_folder');
				
			if(!file_exists($generators_folder.'Controller.tpl'))
				echo "El archivo ".$generators_folder.'Controller.tpl'." no existe";
			
			$folder = implode('/',$nameParts);
			if(!file_exists($frontend_versions_path.'controllers'.$folder))
				mkdir($frontend_versions_path.'controllers'.$folder, 0755, true);
			if(!file_exists($frontend_section_path.'controllers'.$folder))
				mkdir($frontend_section_path.'controllers'.$folder, 0755, true);				
					
			$outputFile = $frontend_section_path.'controllers'.$row->url.'.php';
			if(file_exists($outputFile))
				copy($outputFile, $frontend_versions_path.'controllers'.$row->url.'['.$row->frontTemplateUpdateDate.'].php');
			
			$data->lines = array();
			$gestor = fopen($file_section_path.md5($sectionId).'.tpl', "r");
			$textFile = $indexCode = $idCode = $findCode = $likeCode = $pageCode = '';
			while(!feof($gestor)) {
				$line = fgets($gestor);
				
				$statements = '  ';
				$posOpen = strpos($line,  $this->CI->config->item('open_framework_tag'));
				$posClose = strpos($line,  $this->CI->config->item('close_framework_tag'));
				while($posClose*$posOpen>0){
					$statement 		= substr($line,$posOpen,$posClose-$posOpen+2);	
					$statements .= $statement;
					$line 	= str_replace($statement,'',$line);
					$posOpen = strpos($line,  $this->CI->config->item('open_framework_tag'));
					$posClose = strpos($line,  $this->CI->config->item('close_framework_tag'));
				}
					
				$posOpen = strpos($statements,  $this->CI->config->item('open_framework_tag'));
				$posClose = strpos($statements,  $this->CI->config->item('close_framework_tag'));
				
				if($posClose*$posOpen>0){

					$indexCode 	.= $this->checkControllerSintax($statements,'_index');
					$idCode 	.= $this->checkControllerSintax($statements,'_id');
					$findCode 	.= $this->checkControllerSintax($statements,'_find');
					$likeCode 	.= $this->checkControllerSintax($statements,'_like');
					$pageCode 	.= $this->checkControllerSintax($statements,'_page');
				}
			}
			fclose($gestor);
			
			
			$gestor = fopen($generators_folder.'Controller.tpl', "r");
			while(!feof($gestor)) {
				$line = fgets($gestor);
				$textFile 	.= $line;
			}
			fclose($gestor);
			
			$urlParts 		= explode('/',$row->url);
			$sectionName 	= $urlParts[count($urlParts)-1];
			
			
			$openTag 	= $this->CI->config->item('open_framework_tag');
			$closeTag 	= $this->CI->config->item('close_framework_tag');
			$creationDate = date('Y-m-d h:i:s');
			$textFile = str_replace($openTag.'url_section'.$closeTag,$row->url,$textFile);
			$textFile = str_replace($openTag.'section_name'.$closeTag,ucfirst(strtolower($sectionName)),$textFile);
			$textFile = str_replace($openTag.'function_parameters'.$closeTag,'/******* Parametros *******/',$textFile);
			$textFile = str_replace($openTag.'title_page'.$closeTag,$row->name.' - '.$row->description,$textFile);
			$textFile = str_replace($openTag.'title_section'.$closeTag,$row->name.' - '.$row->description,$textFile);
			$textFile = str_replace($openTag.'function_description'.$closeTag,$row->name.' - '.$row->description,$textFile);
			$textFile = str_replace($openTag.'view_section_file'.$closeTag,$row->url,$textFile);
			$textFile = str_replace($openTag.'global_vars'.$closeTag,'/******* Variables globales *******/',$textFile);
			$textFile = str_replace($openTag.'extra_functions'.$closeTag,'/******* Funciones extras *******/',$textFile);
			$textFile = str_replace($openTag.'creation_date'.$closeTag,$creationDate,$textFile);
			
			$textFile = str_replace($openTag.'function_index_code'.$closeTag,$indexCode,$textFile);
			$textFile = str_replace($openTag.'function_id_code'.$closeTag,$idCode,$textFile);
			$textFile = str_replace($openTag.'function_find_code'.$closeTag,$findCode,$textFile);
			$textFile = str_replace($openTag.'function_like_code'.$closeTag,$likeCode,$textFile);
			$textFile = str_replace($openTag.'function_page_code'.$closeTag,$pageCode,$textFile);

			file_put_contents($outputFile, $textFile);
			file_get_contents($outputFile); 
			
			$updateData = array(  	'frontTemplateUpdateDate' 	=> $creationDate,
									'frontTemplateUpdateUserId'	=> $this->CI->session->userdata['usersId']		);
			$this->CI->db->where('sectionsId',		$sectionId);
			$this->CI->db->update('CAT_SECTIONS', 	$updateData); 
			
			return '';
			
		}
		
		
		/**
		* Función verifica si la linea necesita generar codigo PHP adicional en el controlador
		*
		* @param line     Linea de codigo del template a procesar
		* return void
		*/
		function checkTemplateSintax($line){
			$posOpen 	= strpos($line,  $this->CI->config->item('open_framework_tag'));
			$posClose 	= strpos($line,  $this->CI->config->item('close_framework_tag'));
			if($posClose*$posOpen>0){
				$statement 		= substr($line,$posOpen,$posClose-$posOpen+2);			
				$posSpace	 	= strpos($statement,' ');
				$posDot	 	= strpos($statement,'.');
				$posSpace = $posDot<$posSpace ? ($posDot>0?$posDot:$posSpace) : ($posSpace>0?$posSpace:$posDot) ;
				$instruction 	= substr($line,$posOpen+2,($posSpace>0?$posSpace:$posClose-$posOpen)-2);
				$instruction 	= str_replace('-','',$instruction);

				if (method_exists($this, 'get_'.$instruction.'_view_code'))
					$line = str_replace($statement,$this->{'get_'.str_replace('-','',$instruction).'_view_code'}($statement),$line) ;
				else
					$line = str_replace($statement,'<!-- Etiqueta '.$instruction.' no definida -->',$line);
				return $this->checkTemplateSintax($line);
			}

			return $line;
		}
		
		
		/**
		* Función verifica si la linea necesita generar codigo PHP adicional en el controlador
		*
		* @param line     Linea de codigo del template a procesar
		* return void
		*/
		function checkControllerSintax($line,$function=''){
			
			$posOpen = strpos($line,  $this->CI->config->item('open_framework_tag'));
			$posClose = strpos($line,  $this->CI->config->item('close_framework_tag'));
			
			if($posClose*$posOpen>0){
				$statement 		= substr($line,$posOpen,$posClose-$posOpen+2);			
				$posSpace	 	= strpos($statement,' ');
				$posDot	 	= strpos($statement,'.');
				$posSpace = $posDot<$posSpace ? ($posDot>0?$posDot:$posSpace) : ($posSpace>0?$posSpace:$posDot) ;
				$instruction 	= substr($line,$posOpen+2,($posSpace>0?$posSpace:$posClose-$posOpen)-2);
				$instruction 	= str_replace('-','',$instruction);

				if (method_exists($this, 'get_'.$instruction.'_controller'.$function.'_code'))
					$line = str_replace($statement,$this->{'get_'.str_replace('-','',$instruction).'_controller'.$function.'_code'}($statement),$line) ;
				else
					$line = str_replace($statement,'/*No se reconoció la instrucción: '.$instruction.'*/
			',$line);

				return $this->checkControllerSintax($line,$function);
			}
            
			return $line;
		}
		
		
		
		
		/*************************************************************************************
			 A partir de aqui, son las funciones específicas por sentencia framework
		**************************************************************************************/
			
			
			
			
		/*************************************************************************************
			 start-loop
		**************************************************************************************/
			
		/*	start-loop: index Function	*/
		function get_startloop_controller_index_code($statement){
			
			$posStart 	= strpos($statement,'structure=')+strlen('structure=')+1;
			$posSpace	= strpos($statement,' ',$posStart);
			$posEnd	 	= strpos($statement,'%',2);
			$posEnd 	= $posEnd<$posSpace ? ($posEnd>0?$posEnd:$posSpace) : ($posSpace>0?$posSpace:$posEnd) - 1;
			
			$structure 	= substr($statement, $posStart, $posEnd-$posStart);
			$rs = $this->CI->db->select('CAT_ENTRY_STRUCTURES.*')->
						from('CAT_ENTRY_STRUCTURES')->
						where('MD5(entryStructuresId)',$structure)->get();
			$row = $rs->row();
			
			$code = '
						$rs_contents = $this->db->select(\'CAT_ENTRY_CONTENTS.*\')->
										from(\'CAT_ENTRY_CONTENTS\')->
										where(\'MD5(entryStructuresId)\',\''.$structure.'\')->get();
						
						$this->db->flush_cache();
						$output->'.strtolower($row->name).'_elements = array();
						foreach ($rs_contents->result() as $row){
							$this->db->select("DET_ENTRY_CONTENTS.data, CAT_DATA_TYPES.dataTypesId, CAT_DATA_TYPES.prefix, CAT_DATA_TYPES.postfix"); 
							$this->db->from("CAT_ENTRY_CONTENTS");
							$this->db->join("DET_ENTRY_CONTENTS", "CAT_ENTRY_CONTENTS.entryContentsId  = DET_ENTRY_CONTENTS.entryContentsId ", "INNER");
							$this->db->join("CAT_DATA_TYPES", "DET_ENTRY_CONTENTS.dataTypesId  	= CAT_DATA_TYPES.dataTypesId ", "INNER");
							$rs_qry = $this->db->where("MD5(DET_ENTRY_CONTENTS.entrycontentsId) = \'".md5($row->entryContentsId)."\'  ")->get();

							$dataRow = array();
							foreach ($rs_qry->result() as $rowData)
								$dataRow[MD5($rowData->dataTypesId)] = array(
																"data"=>$rowData->prefix.$rowData->data.$rowData->postfix,
																"dataTypeId"=>$rowData->dataTypesId);
								
							$dataRow["id"] = $row->entryContentsId;
							array_push($output->'.strtolower($row->name).'_elements,$dataRow);
						}
			';
			return $code;
		}
		
		/*	start-loop: id Function	*/
		function _get_startloop_controller_id_code($statement){
			
			$posStart 	= strpos($statement,'structure=')+strlen('structure=')+1;
			$posSpace	= strpos($statement,' ',$posStart);
			$posEnd	 	= strpos($statement,'%',2);
			$posEnd 	= $posEnd<$posSpace ? ($posEnd>0?$posEnd:$posSpace) : ($posSpace>0?$posSpace:$posEnd) - 1;
			
			$structure 	= substr($statement, $posStart, $posEnd-$posStart);
			$rs = $this->CI->db->select('CAT_ENTRY_STRUCTURES.*')->
						from('CAT_ENTRY_STRUCTURES')->
						where('MD5(entryStructuresId)',$structure)->get();
			$row = $rs->row();
			$code = '
						$this->db->select("*"); 
						$this->db->from("CAT_ENTRY_CONTENTS");
						$this->db->join("DET_ENTRY_CONTENTS", "CAT_ENTRY_CONTENTS.entryContentsId  = DET_ENTRY_CONTENTS.entryContentsId ", "INNER");
						$this->db->join("CAT_DATA_TYPES", "DET_ENTRY_CONTENTS.dataTypesId  	= CAT_DATA_TYPES.dataTypesId ", "INNER");
						$rs_qry = $this->db->where("MD5(DET_ENTRY_CONTENTS.entrycontentsId) = \'".MD5($valueId)."\'  ")->get();
			';
			return '<?php foreach($'.strtolower($row->name).'_elements as $'.strtolower($row->name).'_element): ?>';
		}
		/*  start-loop: View Code Generator */
		function get_startloop_view_code($statement){
			
			$posStart 	= strpos($statement,'structure=')+strlen('structure=')+1;
			$posSpace	= strpos($statement,' ',$posStart);
			$posEnd	 	= strpos($statement,'%',2);
			$posEnd 	= $posEnd<$posSpace ? ($posEnd>0?$posEnd:$posSpace) : ($posSpace>0?$posSpace:$posEnd) - 1;
			
			$structure 	= substr($statement, $posStart, $posEnd-$posStart);
			$rs = $this->CI->db->select('CAT_ENTRY_STRUCTURES.*')->
						from('CAT_ENTRY_STRUCTURES')->
						where('MD5(entryStructuresId)',$structure)->get();
			$row = $rs->row();
			return '<?php foreach($'.strtolower($row->name).'_elements as $'.strtolower($row->name).'_element): ?>';
		}
		
		/*************************************************************************************
			 end-loop
		**************************************************************************************/
			
		/*  end-loop: View Code Generator */
		function get_endloop_view_code($statement){
			return '<?php endforeach; ?>';
		}
		
		/*************************************************************************************
			 url-base
		**************************************************************************************/
			
		/*  end-loop: View Code Generator */
		function get_urlbase_view_code($statement){
			return '<?php echo base_url(); ?>';
		}
		
		/*************************************************************************************
			 url-uploads-folder
		**************************************************************************************/
			
		/*  end-loop: View Code Generator */
		function get_urluploadsfolder_view_code($statement){
			return '<?php echo base_url()."'.$this->CI->config->item('upload_folder_url').'"; ?>';
		}
		
		
		/*************************************************************************************
			 element
		**************************************************************************************/

		/*  element: index Function */
		function get_element_controller_index_code($statement){
			
			$posStart 	= strpos($statement,'structureId=')+strlen('structureId=')+1;
			$posSpace	= strpos($statement,' ',$posStart);
			$posEnd	 	= strpos($statement,'%',2);
			$posEnd 	= $posEnd<$posSpace ? ($posEnd>0?$posEnd:$posSpace) : ($posSpace>0?$posSpace:$posEnd) - 1;
			
			$structure 	= substr($statement, $posStart, $posEnd-$posStart);
			$rs = $this->CI->db->select('CAT_ENTRY_STRUCTURES.*')->
						from('CAT_ENTRY_STRUCTURES')->
						where('MD5(entryStructuresId)',$structure)->get();
			$row = $rs->row();
			$structure 	= strtolower($row->name);
			
			$posStart 	= strpos($statement,'fieldId=')+strlen('fieldId=')+1;
			$posSpace	= strpos($statement,' ',$posStart);
			$posEnd	 	= strpos($statement,'%',2);
			$posEnd 	= $posEnd<$posSpace ? ($posEnd>0?$posEnd:$posSpace) : ($posSpace>0?$posSpace:$posEnd) - 1;
			
			$dataField 	= substr($statement, $posStart, $posEnd-$posStart);
			$rs = $this->CI->db->select('dataTypesId, name')->
						from('CAT_DATA_TYPES')->
						where('MD5(dataTypesId)',$dataField)->get();
			$row = $rs->row();

			$dataField 	= strtolower($row->name);
	
			$posStart 	= strpos($statement,'.')+1;
			$posSpace	= strpos($statement,' ');
			$posEnd	 	= strpos($statement,'%',2);
			$posEnd 	= $posEnd<$posSpace ? ($posEnd>0?$posEnd:$posSpace) : ($posSpace>0?$posSpace:$posEnd) ;			
			$dataName 	= substr($statement, $posStart, $posEnd-$posStart);
			return '						$fieldTags->'.$dataName.' = "'.md5($row->dataTypesId).'";
';
			
		}

		/*  element: View Code Generator */
		function get_element_view_code($statement){
			
			$posStart 	= strpos($statement,'structureId=')+strlen('structureId=')+1;
			$posSpace	= strpos($statement,' ',$posStart);
			$posEnd	 	= strpos($statement,'%',2);
			$posEnd 	= $posEnd<$posSpace ? ($posEnd>0?$posEnd:$posSpace) : ($posSpace>0?$posSpace:$posEnd) - 1;
			
			$structure 	= substr($statement, $posStart, $posEnd-$posStart);
			$rs = $this->CI->db->select('CAT_ENTRY_STRUCTURES.*')->
						from('CAT_ENTRY_STRUCTURES')->
						where('MD5(entryStructuresId)',$structure)->get();
			$row = $rs->row();
			$structure 	= strtolower($row->name);
			
			$posStart 	= strpos($statement,'fieldId=')+strlen('fieldId=')+1;
			$posSpace	= strpos($statement,' ',$posStart);
			$posEnd	 	= strpos($statement,'%',2);
			$posEnd 	= $posEnd<$posSpace ? ($posEnd>0?$posEnd:$posSpace) : ($posSpace>0?$posSpace:$posEnd) - 1;
			
			$dataField 	= substr($statement, $posStart, $posEnd-$posStart);
			$rs = $this->CI->db->select('CAT_DATA_TYPES.name')->
						from('CAT_DATA_TYPES')->
						where('MD5(dataTypesId)',$dataField)->get();
			$row = $rs->row();

			$dataField 	= strtolower($row->name);
	
			$posStart 	= strpos($statement,'.')+1;
			$posSpace	= strpos($statement,' ');
			$posEnd	 	= strpos($statement,'%',2);
			$posEnd 	= $posEnd<$posSpace ? ($posEnd>0?$posEnd:$posSpace) : ($posSpace>0?$posSpace:$posEnd) ;			
			$dataName 	= substr($statement, $posStart, $posEnd-$posStart);
			return '<?php echo $'.$structure.'_element[$fieldTags->'.$dataName.']["data"]; ?>';
		}
		
		
		/*************************************************************************************
			 elementId
		**************************************************************************************/

		/*  elementId: View Code Generator */
		function get_elementid_view_code($statement){
			
			$posStart 	= strpos($statement,'structureId=')+strlen('structureId=')+1;
			$posSpace	= strpos($statement,' ',$posStart);
			$posEnd	 	= strpos($statement,'%',2);
			$posEnd 	= $posEnd<$posSpace ? ($posEnd>0?$posEnd:$posSpace) : ($posSpace>0?$posSpace:$posEnd) - 1;
			
			$structure 	= substr($statement, $posStart, $posEnd-$posStart);
			$rs = $this->CI->db->select('CAT_ENTRY_STRUCTURES.*')->
						from('CAT_ENTRY_STRUCTURES')->
						where('MD5(entryStructuresId)',$structure)->get();
			$row = $rs->row();
			$structure 	= strtolower($row->name);
			
			return '<?php echo $'.$structure.'_element["id"]; ?>';
		}
		
		
		/*************************************************************************************
			 insert-libraries
		**************************************************************************************/

		/*  element: View Code Generator */
		function get_insertlibraries_view_code($statement){
			$rs = $this->CI->db->select('CAT_LIBRARIES.urlFile, CAT_LIBRARIES.displayName, CAT_LIBRARY_TYPES.extensionFile, CAT_LIBRARY_TYPES.url_path')->
						from('CAT_SECTIONS')->
						join('CAT_TEMPLATES','CAT_TEMPLATES.templatesId = CAT_SECTIONS.templatesId','INNER')->
						join('DET_LIBRARIES_TEMPLATES','DET_LIBRARIES_TEMPLATES.templatesId = CAT_TEMPLATES.templatesId','INNER')->
						join('CAT_LIBRARIES','CAT_LIBRARIES.librariesId = DET_LIBRARIES_TEMPLATES.librariesId','INNER')->
						join('CAT_LIBRARY_TYPES','CAT_LIBRARY_TYPES.libraryTypesId = CAT_LIBRARIES.libraryTypesId','INNER')->
						where('CAT_SECTIONS.sectionsId',$this->SECTION_ID)->
						where('CAT_LIBRARIES.active',1)->
						order_by('order')->get();
			$libraries = '';
			foreach($rs->result() as $row):
				if($row->extensionFile == 'css')
					$libraries .= '				<link rel="stylesheet" type="text/css" href="'.base_url().$row->url_path.$row->urlFile.'" />
';
				else if($row->extensionFile == 'js')
					$libraries .= '				<script type="text/javascript" src="'.base_url().$row->url_path.$row->urlFile.'"></script>
';
			endforeach;
			
			return $libraries;
		}
	}

/* End of file sectionCreator.php */
/* Location: ./application/libraries/sectionCreator.php */