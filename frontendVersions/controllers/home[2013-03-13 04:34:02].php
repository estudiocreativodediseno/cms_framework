<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class %section_name% extends CI_Controller {			

    %global_vars%
    
    function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('url'); 
        $this->title = "%title_page%";
    }
    

  	/**
     * Código generado automáticamente por CMS-FRAMEWORK
	 * %function_description%
	 *
	 %function_parameter_comments%
	 * @return void
	 */
     function index(%function_parameters%){
		try{
        
        	//inicia function_code
            %function_code%
        	//termina function_code
	
			$data->output = $output;
			$data->title = '%title_section%';
			
			/* Se carga la vista del Controlador*/
			$this->load->view('%view_section_file%', $output);
		}catch(Exception $e){
			  /* En caso de error, lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
     }
     
     %extra_functions%
     
}


/* End of file %section_name%.php */
/* Location: ./application/controllers/%url_section%.php */
