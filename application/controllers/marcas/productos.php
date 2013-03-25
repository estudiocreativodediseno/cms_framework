<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productos extends CI_Controller {			

    /******* Variables globales *******/
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('url'); 
        $this->title = "Productos - Encuentra todos nuestros productos";
    }
    

  	/**
     * Código generado automáticamente por CMS-FRAMEWORK [2013-03-13 10:34:54]
	 * Productos - Encuentra todos nuestros productos
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
			$this->load->view('/marcas/productos', $output);
		}catch(Exception $e){
			  /* En caso de error, lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
     }
     
     /******* Funciones extras *******/
     
}


/* End of file Productos.php */
/* Location: ./application/controllers/marcas/productos.php */
