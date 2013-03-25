<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class {Cruceros} extends CI_Controller {			

    {/******* Variables globales *******/}
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('url'); 
        $this->title = "{Cruceros - Catalogo de Cruceros}";
    }
    

  	/**
     * Código generado automáticamente por CMS-FRAMEWORK [{2013-03-14 10:57:00}]
	 * {Cruceros - Catalogo de Cruceros}
	 *
	 * @return void
	 */
     function index(){
		try{
        
        	//inicia function_index_code
            {    ﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="format-detection" content="telephone=no" />
        
            <link rel="stylesheet" type="text/css" href="css/base.css" />
            <link rel="stylesheet" type="text/css" href="css/showcase.css" />
    
            <script type="text/javascript" src="js/jquery.min.js"></script>
             <script type="text/javascript" src="js/jquery.bxSlider.min.js"></script>
    
            <script type="text/javascript" src="js/showcase.js"></script>
            <script type="text/javascript" src="js/tools-manager.js"></script>
            <script type="text/javascript" src="Cru/includes/javascripts/boletin.js"></script>
            <script type="text/javascript" src="js/analytics.js"></script>
                
    </head>
    
    <body>
    <div class="wrap">
          
          <!-- Catalogo de Productos -->
          <div class="catalog-showcase">
            <ul>
                
						$rs_contents = $this->CI->db->select('CAT_ENTRY_CONTENTS.*')->
										from('CAT_ENTRY_CONTENTS')->
										where('MD5(entryStructuresId)',$structure)->get();
						
						$this->db->flush_cache();
						
						foreach ($rs_contents->result() as $row){
							$this->db->select("DET_ENTRY_CONTENTS.data, "); 
							$this->db->from("CAT_ENTRY_CONTENTS");
							$this->db->join("DET_ENTRY_CONTENTS", "CAT_ENTRY_CONTENTS.entryContentsId  = DET_ENTRY_CONTENTS.entryContentsId ", "INNER");
							$this->db->join("CAT_DATA_TYPES", "DET_ENTRY_CONTENTS.dataTypesId  	= CAT_DATA_TYPES.dataTypesId ", "INNER");
							$rs_qry = $this->db->where("MD5(DET_ENTRY_CONTENTS.entrycontentsId) = '".$this->input->post('entryContentsId')."'  ")->get();

							$dataRow = array();
							foreach ($rs_qry->result() as $rowData)
								array_push($dataRow,array( 
															"data"=>$rowData->data,
															"dataTypeId"=>$rowData->dataTypeId,
															"dataTypeIdEncrypt"=>MD5($rowData->dataTypeId),
															"data"=>$rowData->data));
							
							array_push($output->cruceros_elements,$dataRow);
						}
			             <li class="catalog-item" id="%element%">
                    <div class="product-banner" rel="1">
                      <h2 class="product-tag">
                          %element%<br />
                          <span>%element%</span>
                      </h2>
                      <a rel="1" href="#" class="product-shutter">
                            <img src="%element%" alt="" />
                      </a> 
                      <!-- Promocion -->
                      <input type="hidden" value="">
                      <input type="hidden" value="">
                    </div>
                    <div class="tab">
                      <p class="product-destiny promotion">Desde <span>%element%</span></p>
                      <a href="#" rel="1" class="btn detail-btn">Ver &rsaquo;</a> </div>
                  </li>
                  %endloop%           </ul>
          </div>
          <div class="paginator-bar">
            <ul class="paginator">
              <li class="arrow-btn prev">
              <li> <a class="arrow-btn prev" href="#page">&lt;</a> </li>
              <li>Página <span>1</span> de <span>1</span></li>
              <li class="arrow-btn next">
              <li> <a class="arrow-btn next" href="#page">&gt;</a> </li>
            </ul>
          </div>
        </div>
        
      </div>
      <!-- end board --> 
    </div>
    </div>
    </body>
    </html>  }
        	//termina function_index_code
	
			$data->output = $output;
			$data->title = '{Cruceros - Catalogo de Cruceros}';
			
			/* Se carga la vista del Controlador*/
			$this->load->view('{/prueba/cruceros}', $output);
		}catch(Exception $e){
			  /* En caso de error, lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
     }
     

  	/**
     * Código generado automáticamente por CMS-FRAMEWORK
	 * Búsqueda por id: {Cruceros - Catalogo de Cruceros}
	 *
	 * @return void
	 */
     function id($valueId){
		try{
        
        	//inicia function_id_code
            {    ﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="format-detection" content="telephone=no" />
        
            <link rel="stylesheet" type="text/css" href="css/base.css" />
            <link rel="stylesheet" type="text/css" href="css/showcase.css" />
    
            <script type="text/javascript" src="js/jquery.min.js"></script>
             <script type="text/javascript" src="js/jquery.bxSlider.min.js"></script>
    
            <script type="text/javascript" src="js/showcase.js"></script>
            <script type="text/javascript" src="js/tools-manager.js"></script>
            <script type="text/javascript" src="Cru/includes/javascripts/boletin.js"></script>
            <script type="text/javascript" src="js/analytics.js"></script>
                
    </head>
    
    <body>
    <div class="wrap">
          
          <!-- Catalogo de Productos -->
          <div class="catalog-showcase">
            <ul>
                <?php foreach($cruceros_elements as $cruceros_element): ?>             <li class="catalog-item" id="%element%">
                    <div class="product-banner" rel="1">
                      <h2 class="product-tag">
                          %element%<br />
                          <span>%element%</span>
                      </h2>
                      <a rel="1" href="#" class="product-shutter">
                            <img src="%element%" alt="" />
                      </a> 
                      <!-- Promocion -->
                      <input type="hidden" value="">
                      <input type="hidden" value="">
                    </div>
                    <div class="tab">
                      <p class="product-destiny promotion">Desde <span>%element%</span></p>
                      <a href="#" rel="1" class="btn detail-btn">Ver &rsaquo;</a> </div>
                  </li>
                  %endloop%           </ul>
          </div>
          <div class="paginator-bar">
            <ul class="paginator">
              <li class="arrow-btn prev">
              <li> <a class="arrow-btn prev" href="#page">&lt;</a> </li>
              <li>Página <span>1</span> de <span>1</span></li>
              <li class="arrow-btn next">
              <li> <a class="arrow-btn next" href="#page">&gt;</a> </li>
            </ul>
          </div>
        </div>
        
      </div>
      <!-- end board --> 
    </div>
    </div>
    </body>
    </html>  }
        	//termina function_id_code
	
			$data->output = $output;
			$data->title = '{Cruceros - Catalogo de Cruceros}';
			
			/* Se carga la vista del Controlador*/
			$this->load->view('{/prueba/cruceros}', $output);
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
            {    ﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="format-detection" content="telephone=no" />
        
            <link rel="stylesheet" type="text/css" href="css/base.css" />
            <link rel="stylesheet" type="text/css" href="css/showcase.css" />
    
            <script type="text/javascript" src="js/jquery.min.js"></script>
             <script type="text/javascript" src="js/jquery.bxSlider.min.js"></script>
    
            <script type="text/javascript" src="js/showcase.js"></script>
            <script type="text/javascript" src="js/tools-manager.js"></script>
            <script type="text/javascript" src="Cru/includes/javascripts/boletin.js"></script>
            <script type="text/javascript" src="js/analytics.js"></script>
                
    </head>
    
    <body>
    <div class="wrap">
          
          <!-- Catalogo de Productos -->
          <div class="catalog-showcase">
            <ul>
                %startloop%             <li class="catalog-item" id="%element%">
                    <div class="product-banner" rel="1">
                      <h2 class="product-tag">
                          %element%<br />
                          <span>%element%</span>
                      </h2>
                      <a rel="1" href="#" class="product-shutter">
                            <img src="%element%" alt="" />
                      </a> 
                      <!-- Promocion -->
                      <input type="hidden" value="">
                      <input type="hidden" value="">
                    </div>
                    <div class="tab">
                      <p class="product-destiny promotion">Desde <span>%element%</span></p>
                      <a href="#" rel="1" class="btn detail-btn">Ver &rsaquo;</a> </div>
                  </li>
                  %endloop%           </ul>
          </div>
          <div class="paginator-bar">
            <ul class="paginator">
              <li class="arrow-btn prev">
              <li> <a class="arrow-btn prev" href="#page">&lt;</a> </li>
              <li>Página <span>1</span> de <span>1</span></li>
              <li class="arrow-btn next">
              <li> <a class="arrow-btn next" href="#page">&gt;</a> </li>
            </ul>
          </div>
        </div>
        
      </div>
      <!-- end board --> 
    </div>
    </div>
    </body>
    </html>  }
        	//termina function_page_code
	
			$data->output = $output;
			$data->title = '{Cruceros - Catalogo de Cruceros}';
			
			/* Se carga la vista del Controlador*/
			$this->load->view('{/prueba/cruceros}', $output);
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
            {    ﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="format-detection" content="telephone=no" />
        
            <link rel="stylesheet" type="text/css" href="css/base.css" />
            <link rel="stylesheet" type="text/css" href="css/showcase.css" />
    
            <script type="text/javascript" src="js/jquery.min.js"></script>
             <script type="text/javascript" src="js/jquery.bxSlider.min.js"></script>
    
            <script type="text/javascript" src="js/showcase.js"></script>
            <script type="text/javascript" src="js/tools-manager.js"></script>
            <script type="text/javascript" src="Cru/includes/javascripts/boletin.js"></script>
            <script type="text/javascript" src="js/analytics.js"></script>
                
    </head>
    
    <body>
    <div class="wrap">
          
          <!-- Catalogo de Productos -->
          <div class="catalog-showcase">
            <ul>
                %startloop%             <li class="catalog-item" id="%element%">
                    <div class="product-banner" rel="1">
                      <h2 class="product-tag">
                          %element%<br />
                          <span>%element%</span>
                      </h2>
                      <a rel="1" href="#" class="product-shutter">
                            <img src="%element%" alt="" />
                      </a> 
                      <!-- Promocion -->
                      <input type="hidden" value="">
                      <input type="hidden" value="">
                    </div>
                    <div class="tab">
                      <p class="product-destiny promotion">Desde <span>%element%</span></p>
                      <a href="#" rel="1" class="btn detail-btn">Ver &rsaquo;</a> </div>
                  </li>
                  %endloop%           </ul>
          </div>
          <div class="paginator-bar">
            <ul class="paginator">
              <li class="arrow-btn prev">
              <li> <a class="arrow-btn prev" href="#page">&lt;</a> </li>
              <li>Página <span>1</span> de <span>1</span></li>
              <li class="arrow-btn next">
              <li> <a class="arrow-btn next" href="#page">&gt;</a> </li>
            </ul>
          </div>
        </div>
        
      </div>
      <!-- end board --> 
    </div>
    </div>
    </body>
    </html>  }
        	//termina function_find_code
	
			$data->output = $output;
			$data->title = '{Cruceros - Catalogo de Cruceros}';
			
			/* Se carga la vista del Controlador*/
			$this->load->view('{/prueba/cruceros}', $output);
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
            {    ﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="format-detection" content="telephone=no" />
        
            <link rel="stylesheet" type="text/css" href="css/base.css" />
            <link rel="stylesheet" type="text/css" href="css/showcase.css" />
    
            <script type="text/javascript" src="js/jquery.min.js"></script>
             <script type="text/javascript" src="js/jquery.bxSlider.min.js"></script>
    
            <script type="text/javascript" src="js/showcase.js"></script>
            <script type="text/javascript" src="js/tools-manager.js"></script>
            <script type="text/javascript" src="Cru/includes/javascripts/boletin.js"></script>
            <script type="text/javascript" src="js/analytics.js"></script>
                
    </head>
    
    <body>
    <div class="wrap">
          
          <!-- Catalogo de Productos -->
          <div class="catalog-showcase">
            <ul>
                %startloop%             <li class="catalog-item" id="%element%">
                    <div class="product-banner" rel="1">
                      <h2 class="product-tag">
                          %element%<br />
                          <span>%element%</span>
                      </h2>
                      <a rel="1" href="#" class="product-shutter">
                            <img src="%element%" alt="" />
                      </a> 
                      <!-- Promocion -->
                      <input type="hidden" value="">
                      <input type="hidden" value="">
                    </div>
                    <div class="tab">
                      <p class="product-destiny promotion">Desde <span>%element%</span></p>
                      <a href="#" rel="1" class="btn detail-btn">Ver &rsaquo;</a> </div>
                  </li>
                  %endloop%           </ul>
          </div>
          <div class="paginator-bar">
            <ul class="paginator">
              <li class="arrow-btn prev">
              <li> <a class="arrow-btn prev" href="#page">&lt;</a> </li>
              <li>Página <span>1</span> de <span>1</span></li>
              <li class="arrow-btn next">
              <li> <a class="arrow-btn next" href="#page">&gt;</a> </li>
            </ul>
          </div>
        </div>
        
      </div>
      <!-- end board --> 
    </div>
    </div>
    </body>
    </html>  }
        	//termina function_like_code
	
			$data->output = $output;
			$data->title = '{Cruceros - Catalogo de Cruceros}';
			
			/* Se carga la vista del Controlador*/
			$this->load->view('{/prueba/cruceros}', $output);
		}catch(Exception $e){
			  /* En caso de error, lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
     }
     
     {/******* Funciones extras *******/}
     
}


/* End of file {Cruceros}.php */
/* Location: ./application/controllers{/prueba/cruceros}.php */
