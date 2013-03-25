<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class editHTML  extends CI_Controller {			/* Heredamos de la clase CI_Controller */

  function __construct(){
   
    parent::__construct();
   
    $this->load->database();				/* Cargamos la base de datos */
    $this->load->helper('url'); 			/* Añadimos el helper al controlador */

  }
  

	function index(){
		$data->output->js_files = array();
					
			/* Cargamos en la vista*/
		array_push($data->output->js_files,base_url().'resources/js/jquery.min.js');
		array_push($data->output->js_files,base_url().'resources/js/test/editHTML.js');
		$this->load->view('test/editHTML', $data);
	}
}

/* End of file editHTML.php */
/* Location: ./application/controllers/test/editHTML.php */