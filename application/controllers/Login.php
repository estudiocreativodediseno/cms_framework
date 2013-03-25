<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Login extends CI_Controller {
		
		var $data;
		function __construct(){
			parent::__construct();
		}
		
		function index(){
	        	//redirect('login/index');
			if(isset($this->session->userdata['username']))
				redirect('menu/main');
			$this->load->helper(array('form'));
			$this->load->view('login',$this->data);
		}
		
		function processLogin(){
			$username = $this->input->post('username');    
			$password  = $this->input->post('password');
	
			 // Load the model
			$this->load->model('CmsUser');
			$this->data =  $this->CmsUser->validate();
			if(! $this->data['correct']){
				$this->data = array('logged_in' => FALSE,
									'msg'		=> '<font color=red>Usuario y/o Password incorrectos.</font><br />');
				$this->index();
			}else{
				array_push($this->data, array('logged_in'  => TRUE));
				$this->session->set_userdata($this->data);
    			redirect('menu/main');
			}     
		}
		
		public function logout(){
			//Limpiamos los datos de session
			$this->session->unset_userdata('username');
			$this->session->unset_userdata('name');
			$this->session->unset_userdata('moduleId');
			$this->session->unset_userdata('moduleData');
		   
			//Y redireccionamos
			$this->index();
		}
		
	
	}

?>