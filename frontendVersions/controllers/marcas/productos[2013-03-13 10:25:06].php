<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {			

    %global_vars%
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('url'); 
        $this->title = "Home - Plantilla de la página principal";
    }
    

  	/**
     * Código generado automáticamente por CMS-FRAMEWORK
	 * {%function_description%}
	 *
	 {%function_parameter_comments%}
	 * @return void
	 */
     function index(/*Parametros*/){
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
     
     /****** Funciones extras *******/
     
}


/* End of file Home.php */
/* Location: ./application/controllers/marcas/productos.php */
