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
     * Código generado automáticamente por CMS-FRAMEWORK [{2013-03-14 11:27:32}]
	 * {Cruceros - Catalogo de Cruceros}
	 *
	 * @return void
	 */
     function index(){
		try{
        
        	//inicia function_index_code
            {}
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
            {}
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
            {}
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
            {}
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
            {}
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
