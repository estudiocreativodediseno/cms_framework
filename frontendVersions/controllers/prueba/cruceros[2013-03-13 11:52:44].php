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
     * Código generado automáticamente por CMS-FRAMEWORK [2013-03-13 11:47:55]
	 * Cruceros - Catalogo de Cruceros
	 *
	 {%function_parameter_comments%}
	 * @return void
	 */
     function index(/******* Parametros *******/){
		try{
        
        	//inicia function_code
            {%function_code%}
        	//termina function_code
	
			$data->output = $output;
			$data->title = '{%title_section%}';
			
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
