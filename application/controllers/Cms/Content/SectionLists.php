<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SectionLists extends CI_Controller {			/* Heredamos de la clase CI_Controller */

	var $title;
  function __construct(){
   
    parent::__construct();
	/* Validamos si el usuario está en sesión*/
   	if(!isset($this->session->userdata['username']))
		redirect('login/index');
		
	if(strlen($this->input->get('mod'))>0)
		$this->session->set_userdata('moduleId',$this->input->get('mod'));
	$permission = $this->CmsPermission->getUserModulePermission();

    //if(!empty($permission->moduleData))
		//redirect('forbidden/index');

    $this->load->database();				/* Cargamos la base de datos */
    $this->load->library('grocery_crud');	/* Cargamos la libreria*/   
    $this->load->helper('url'); 			/* Añadimos el helper al controlador */
	$this->title = "Adminsitración de Secciones";
	
			
  }

    /**
     * Función para obtener los permisos del grupo de usuario para todos los modulos
     *
     * @param integer $primary_key
     * @return void
     */
  function index(){
    /*
     * Mandamos todo lo que llegue a la funcion
     * admin().
     **/
    redirect('/cms/content/sectionLists/admin');
  }

    /**
     * Función para obtener los permisos del grupo de usuario para todos los modulos
     *
     * @param integer $primary_key
     * @return void
     */
  function admin(){
		try{
	
			
			$crud = new grocery_CRUD();					/* Creamos el objeto */
			$crud->set_theme('flexigrid');				/* Seleccionamos el tema */
			//$crud->set_theme('datatables');			/* Seleccionamos el tema */
			$crud->set_table('CAT_SECTION_LISTS');		/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$crud->set_subject('Menú');	/* Le asignamos un nombre */
			$crud->set_language('spanish');				/* Asignamos el idioma español */
			
			/* Campos del modelo */
			$crud->fields(
			  	'name',
			  	'description',
			  	'active',
			  	'displayName');
			/* Campos obligatorios */
			$crud->required_fields(
			  	'name',
			  	'description',
			  	'active',
			  	'displayName');
			
			/* Campos a mostrar */
			$crud->columns(
			  	'sectionListsId',
			  	'name',
			  	'description',
			  	'active',
			  	'displayName')        
			->display_as('sectionListsId','ID')
			->display_as('name','Nombre')
			->display_as('displayName','Nombre a mostrar')
			->display_as('description','Descripción')
			->display_as('active','Activo');
								
			// Validación de campos
			$crud->set_rules('name', 'Nombre', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('displayName', 'Nombre amostrar', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('description', 'Descripcion', 'trim|min_length[0]|max_length[120]|xss_clean');			
	
	    	$crud->add_action('Editar Secciones', base_url().'images/icons/menuedit.png', 'Cms/Content/SectionLists/editSection');
			//Callbacks
				
			/* Generamos la tabla */
			$data->output = $output = $crud->render();
			$data->title = $this->title;
			
			/* Cargamos en la vista*/
			$this->load->view('templates/heads', $data);
			$this->load->view('templates/header',$this->session->userdata);
			$this->load->view('templates/aside');
			$this->load->view('templates/admin', $output);
			$this->load->view('templates/footer');
	
		}catch(Exception $e){
			  /* Si	 algo sale mal cachamos el error y lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
  }
  
    /**
     * Función para obtener as secciones del menu a editar
     *
     * @param integer $sectionListId
     * @return void
     */
  function editSection($sectionListId, $parentSectionListId = NULL){
		try{
	
			
			$crud = new grocery_CRUD();					/* Creamos el objeto */
			$crud->set_theme('flexigrid');				/* Seleccionamos el tema */

			$crud->set_table('CAT_SECTIONS');					/* Seleccionmos el nombre de la tabla de nuestra base de datos*/
			$where = "CAT_SECTIONS.sectionListsId = '".$sectionListId."'";
			if(is_null($parentSectionListId))
				$where .= "AND CAT_SECTIONS.parentSectionsId IS NULL";
			else
				$where .= "AND CAT_SECTIONS.parentSectionsId = '".$parentSectionListId."'";
			$crud->where($where);	/* Solo mostramos las secciones del menu seleccionado */
			$crud->set_subject('Sección');						/* Le asignamos un nombre */
			$crud->set_language('spanish');						/* Asignamos el idioma español */
			
			
			// (campoRef,tablaRef,campoMostrar)
			$crud->set_relation('parentSectionsId','CAT_SECTIONS','displayName');		//Haciendo la relacion entre tablas
			$crud->display_as('parentSectionsId','Pertenece a');

			$crud->set_relation('sectionListsId','CAT_SECTION_LISTS','displayName');		
			$crud->display_as('parentSectionsId','Menú');
			
			$crud->set_relation('templatesId','CAT_TEMPLATES','name');		
			$crud->display_as('templatesId','Menú');
			
			$crud->set_relation_n_n('libraries', 'DET_LIBRARIES_TEMPLATES', 'CAT_LIBRARIES', 'sectionsId', 'librariesId', 'name','');//'sectionsId, CAT_LIBRARIES.order');
			$crud->display_as('libraries','Librerías');

			/* Campos del modelo */
			$crud->fields(
			  	'name',
			  	'description',
			  	'active',
			  	'sectionListsId',
			  	'templatesId',
			  	'libraries',
			  	'url',
			  	'parentSectionsId',
			  	'entryEstrucutresId',
			  	'displayName');
			/* Campos obligatorios */
			$crud->required_fields(
			  	'name',
			  	'description',
			  	'active',
			  	'displayName');
			
			/* Campos a mostrar */
			$crud->columns(
			  	'name',
			  	'description',
			  	'active',
			  	'sectionListsId',
			  	'templatesId',
			  	'libraries',
			  	'url',
			  	'parentSectionsId',
			  	'entryEstrucutresId',
			  	'displayName')        
			->display_as('sectionListsId','Menú')
			->display_as('name','Nombre')
			->display_as('displayName','Nombre a mostrar')
			->display_as('description','Descripción')
			->display_as('templatesId','Plantilla')
			->display_as('url','url sección')
			->display_as('parentSectionsId','Pertenece a')
			->display_as('entryEstrucutresId','Datos a mostrar')
			->display_as('active','Activo');

			// Validación de campos
			$crud->set_rules('name', 'Nombre', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('displayName', 'Nombre amostrar', 'trim|min_length[3]|max_length[120]|xss_clean');
			$crud->set_rules('description', 'Descripcion', 'trim|min_length[0]|max_length[120]|xss_clean');			
	
	
			
			if(!is_null($parentSectionListId))
	    		$crud->add_action('Regresar', base_url().'images/icons/undo.png','','',array($this, '_callback_add_action_parentsections'));
	    	$crud->add_action('Ver Subsecciones', base_url().'images/icons/menuedit.png', '', '', array($this, '_callback_add_action_subsections'));
	    	$crud->add_action('Editar Plantilla', base_url().'images/icons/html.png', 'Cms/Content/TemplateView/edit');
			
			//Callbacks
			$this->parameter = $parentSectionListId;
			$crud->callback_add_field('parentSectionsId',array($this,'_add_parentSectionsId_callback'));
			$crud->callback_add_field('sectionListsId',array($this,'_add_sectionListsId_callback'));
				
			/* Generamos la tabla */
			$data->output = $output = $crud->render();

			/* Cargamos en la vista*/
			$this->load->view('templates/heads', $data);
			$this->load->view('templates/header',$this->session->userdata);
			$this->load->view('templates/aside');
			$this->load->view('templates/admin', $output);
			$this->load->view('templates/footer');
	
		}catch(Exception $e){
			  /* Si	 algo sale mal cachamos el error y lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
  }

   
    /**
     * Liga para ir a las subsecciones 
     *
     * @param integer $primary_key
     * @param integer $row
     * @return void
     */
	function _callback_add_action_subsections($primary_key , $row){
		return site_url('Cms/Content/SectionLists/editSection').'/'.$row->sectionListsId.'/'.$row->sectionsId;
	}
   
    /**
     * Liga para ir a la sección padre 
     *
     * @param integer $primary_key
     * @param integer $row
     * @return void
     */
	function _callback_add_action_parentsections($primary_key , $row){
		$rs = $this->db->select('parentSectionsId')->from('CAT_SECTIONS')->where('sectionsId',$row->parentSectionsId)->get();	
			$returnSectionId = $rs->row()->parentSectionsId;	
			
		return site_url('Cms/Content/SectionLists/editSection').'/'.$row->sectionListsId.'/'.$returnSectionId;
	}
	
   
    /**
     * Devuelve el input para el valor de la sección padre
     *
     * @param integer $primary_key
     * @param integer $row
     * @return void
     */
	function _add_parentSectionsId_callback($value){
		$value = is_numeric($this->uri->segments[count($this->uri->segments)-2])?$this->uri->segments[count($this->uri->segments)-1]:'';
		$rs = $this->db->select('name')->from('CAT_SECTIONS')->where('sectionsId',$value)->get();
		$parentSectionName = $rs->num_rows()>0?$rs->row()->name:'';
		return 		'<input type="hidden" value="'.$value.'" name="parentSectionsId" id="parentSectionsId">'.$parentSectionName;
	}

   
    /**
     * Devuelve el input para el valor de la lista de secciones padre
     *
     * @param integer $primary_key
     * @param integer $row
     * @return void
     */
	function _add_sectionListsId_callback($value){
		$value = is_numeric($this->uri->segments[count($this->uri->segments)-2])?$this->uri->segments[count($this->uri->segments)-2]:$this->uri->segments[count($this->uri->segments)-1];
		$rs = $this->db->select('name')->from('CAT_SECTION_LISTS')->where('sectionListsId',$value)->get();
		$parentSectionListName = $rs->num_rows()>0?$rs->row()->name:'';
		return 	'<input type="hidden" value="'.$value.'" name="sectionListsId" id="sectionListsId">'.$parentSectionListName;
	}


}


/* End of file SectionLists.php */
/* Location: ./application/controllers/Cms/Content/SectionLists.php */
