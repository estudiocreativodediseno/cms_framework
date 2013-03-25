<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * <Descripción del modelo>
 *  
 */
class CmsPermission extends CI_Model {
    function __construct() {
        parent::__construct();
    }
   
    /**
     * Función que devuelve si el usuario tiene accesos al modulo
     *
     * @param integer $userId
     * @param integer $moduleId
     * @return array
     */
    function getAllowedUserModules(){
		 if(!isset($this->session->userdata['username']))
			return array();
		$userGroupId = $this->session->userdata['userGroupsId'];

		$this->db->select(	"CAT_MODULES.modulesId, 	CAT_MODULES.displayName, 	CAT_MODULES.name, CAT_MODULES.description, ".
							"CAT_MODULES.description, 	CAT_MODULES.parentModuleId, DET_PERMISSIONS.userGroupsId, CAT_MODULES.urlSection"); 
		$this->db->order_by("CAT_MODULES.order, CAT_MODULES.parentModuleId", "DESC"); 		
		
		$this->db->from('CAT_USER_GROUPS');
		$this->db->join('DET_PERMISSIONS', 'DET_PERMISSIONS.userGroupsId = CAT_USER_GROUPS.userGroupsId', 'inner');
		$this->db->join('CAT_MODULES', "DET_PERMISSIONS.modulesId = CAT_MODULES.modulesId AND CAT_USER_GROUPS.userGroupsId = '$userGroupId'", 'inner');
		$res->rs_array = $this->db->where('parentModuleId IS NULL AND CAT_MODULES.active = 1')->get();

		$modules = array();
		foreach ($res->rs_array->result() as $row){
			$modules[$row->modulesId] = array(	'moduleId'		=>		$row->modulesId,
												'displayName'	=>		$row->displayName,
												'description'	=>		$row->description,
												'parentModuleId'=>		$row->parentModuleId,
												'urlSection'	=>		$row->urlSection,
												'userGroupId'	=>		$row->userGroupsId );
			$modules[$row->modulesId]['modules'] =	$this->getSubModules($row->modulesId,$userGroupId);
		}	
		$res->modules = $modules;

		/* Cargamos en la vista*/
		$res->title = $this->title;
		return $res;
  }
  
  
   
    /**
     * Función que devuelve los submodulos contenidos en el menu visitado para mostrar en dashboard y que tiene acceso
     *
     * @param integer $userId
     * @param integer $moduleId
     * @return array
     */
    function getAllowedSubmodules(){
		 if(!isset($this->session->userdata['username']))
			return array();

		$userGroupId = $this->session->userdata['userGroupsId'];
		$moduleId =  $this->session->userdata['moduleId']; 
		
		$this->db->select(	"CAT_MODULES.modulesId, 	CAT_MODULES.displayName, 	CAT_MODULES.name, CAT_MODULES.description, CAT_MODULES.urlIconImage, ".
							"CAT_MODULES.description, 	CAT_MODULES.parentModuleId, DET_PERMISSIONS.userGroupsId, CAT_MODULES.urlSection"); 
		$this->db->order_by("CAT_MODULES.order, CAT_MODULES.parentModuleId", "DESC"); 		
		
		$this->db->from('CAT_USER_GROUPS');
		$this->db->join('DET_PERMISSIONS', 'DET_PERMISSIONS.userGroupsId = CAT_USER_GROUPS.userGroupsId', 'inner');
		$this->db->join('CAT_MODULES', "DET_PERMISSIONS.modulesId = CAT_MODULES.modulesId AND CAT_USER_GROUPS.userGroupsId = '$userGroupId'", 'inner');
		$res->rs_array = $this->db->where("MD5(parentModuleId) = '".$moduleId."' AND CAT_MODULES.active = 1")->get();

		$submodules = array();
		foreach ($res->rs_array->result() as $row){
			$submodules[$row->modulesId] = array(	'moduleId'		=>		$row->modulesId,
												'displayName'		=>		$row->displayName,
												'description'		=>		$row->description,
												'parentModuleId'	=>		$row->parentModuleId,
												'urlSection'		=>		$row->urlSection,
												'urlIconImage'		=>		$row->urlIconImage,
												'userGroupId'		=>		$row->userGroupsId );
												
		}	
		$res->submodules = $submodules;

		/* Cargamos en la vista*/
		$res->title = $this->title;
		return $res;
  }
  
  
    /**
     * Función que devuelve si el usuario tiene accesos al modulo
     *
     * @param session  	integer $userGroupId
     * @param session 	integer $moduleId
	 * @return boolean
     */
    function getUserModulePermission(){
		 if(!isset($this->session->userdata['username']))
			return array();
		$userGroupId = $this->session->userdata['userGroupsId'];
		$moduleId =  $this->session->userdata['moduleId']; 

		$this->db->select(	"CAT_MODULES.modulesId, 	CAT_MODULES.displayName, 	CAT_MODULES.name, CAT_MODULES.description, ".
							"CAT_MODULES.urlIconImage, 	CAT_MODULES.parentModuleId, DET_PERMISSIONS.userGroupsId, CAT_MODULES.urlSection"); 
		$this->db->from('CAT_MODULES');
		$this->db->join('DET_PERMISSIONS', "DET_PERMISSIONS.modulesId = CAT_MODULES.modulesId AND DET_PERMISSIONS.userGroupsId = '$userGroupId'", 'inner');
		$rs_query = $this->db->where("md5(CAT_MODULES.modulesId) = '".$moduleId."' AND CAT_MODULES.active = 1")->get();

		$res->moduleData = array();
		if ($rs_query->num_rows() > 0)
			$res->moduleData = $rs_query->row(); 
			/*
			$modules[$row->modulesId] = array(	'moduleId'		=>		$row->modulesId,
												'displayName'	=>		$row->displayName,
												'urlIconImage'	=>		$row->urlIconImage,
												'description'	=>		$row->description,
												'parentModuleId'=>		$row->parentModuleId,
												'urlSection'	=>		$row->urlSection,
												'userGroupId'	=>		$row->userGroupsId );
			*/
		else
			$res->forbidden = 1;
			
			
		$this->session->set_userdata('moduleData',$res->moduleData);
		//echo $this->db->last_query();		
		//echo json_encode($this->session->userdata);	
		//print_r($res);	
		/* Cargamos en la vista*/
		$res->title = $this->title;
		return $res;
  }
  
  
    /**
     * Función que devuelve los contenidos que el usuario tiene acceso
     *
     * @param session  	integer $userGroupId
     * @param 			integer $parentStructure
	 * @return String
     */
    function getHtmlAllowedUserContents($parentStructure=null){
		 if(!isset($this->session->userdata['username']))
			return '';
		$userGroupId = $this->session->userdata['userGroupsId'];


		$rs_query =								
		$this->db->query("SELECT CAT_ENTRY_STRUCTURES.entryStructuresId, 	CAT_ENTRY_STRUCTURES.name, 		
							CAT_ENTRY_STRUCTURES.description, 			DET_ENTRY_STRUCTURE.subEntryStructureId 
							FROM CAT_ENTRY_STRUCTURES
								INNER JOIN 		DET_PERMISSIONS
									ON	(DET_PERMISSIONS.entryStructuresId  		= 		CAT_ENTRY_STRUCTURES.entryStructuresId  	AND 
										 DET_PERMISSIONS.userGroupsId 				= 		'".$userGroupId."')
								LEFT JOIN		DET_ENTRY_STRUCTURE
									ON 	(DET_ENTRY_STRUCTURE.entryStructuresId  	= 		CAT_ENTRY_STRUCTURES.entryStructuresId)
							WHERE	
								".(!is_null($parentStructure)?"CAT_ENTRY_STRUCTURES.entryStructuresId = '".$parentStructure."' 				AND ":"")."
								".(is_null($parentStructure)?"CAT_ENTRY_STRUCTURES.showInMenu 	AND":"CAT_ENTRY_STRUCTURES.showInSubMenu = 1 AND ")."
								CAT_ENTRY_STRUCTURES.active 	= 	1				
								
							ORDER BY CAT_ENTRY_STRUCTURES.order, DET_ENTRY_STRUCTURE.subEntryStructureId DESC");
		$result = '<ul>'; 
		
		//echo $this->db->last_query().'<hr>';
		$aux = '';
		foreach ($rs_query->result() as $row){
			if($aux != $row->entryStructuresId)
				$result .= '<li><a href="'.base_url().'index.php/cms/content/ContentsPublished/showList/'.md5($row->entryStructuresId).'?mod='.md5(49).'" 
									alt="'.$row->description.'" title="'.$row->description.'">'.$row->name.'</a></li>';
			if($row->subEntryStructureId>'0')
				$result .=	$this->getHtmlAllowedUserContents($row->subEntryStructureId);
			$aux = $row->entryStructuresId;
		}
		return $result .= '</ul>'; 
  }
  
  
  
  
    /**
     * Función que devuelve los contenidos que el usuario tiene acceso
     *
     * @param session  	integer $userGroupId
     * @param 			integer $parentStructure
	 * @return array
     */
    function getAllowedUserContents($parentStructure=null){
		 if(!isset($this->session->userdata['username']))
			return '';
		$userGroupId = $this->session->userdata['userGroupsId'];


		$rs_query =								
		$this->db->query("SELECT CAT_ENTRY_STRUCTURES.entryStructuresId, 	CAT_ENTRY_STRUCTURES.name, 		
							CAT_ENTRY_STRUCTURES.description, 			DET_ENTRY_STRUCTURE.subEntryStructureId 
							FROM CAT_ENTRY_STRUCTURES
								INNER JOIN 		DET_PERMISSIONS
									ON	(DET_PERMISSIONS.entryStructuresId  		= 		CAT_ENTRY_STRUCTURES.entryStructuresId  	AND 
										 DET_PERMISSIONS.userGroupsId 				= 		'".$userGroupId."')
								LEFT JOIN		DET_ENTRY_STRUCTURE
									ON 	(DET_ENTRY_STRUCTURE.entryStructuresId  	= 		CAT_ENTRY_STRUCTURES.entryStructuresId)
							WHERE	
								".(!is_null($parentStructure)?"CAT_ENTRY_STRUCTURES.entryStructuresId = '".$parentStructure."' 				AND ":"")."
								".(is_null($parentStructure)?"CAT_ENTRY_STRUCTURES.showInMenu 	AND":"CAT_ENTRY_STRUCTURES.showInSubMenu = 1 AND ")."
								CAT_ENTRY_STRUCTURES.active 	= 	1				
								
							ORDER BY CAT_ENTRY_STRUCTURES.order, DET_ENTRY_STRUCTURE.subEntryStructureId DESC");
		
		foreach ($rs_query->result() as $row){
			if($aux != $row->entryStructuresId)
				$result .= '<li><a href="'.$row->description.'" alt="'.$row->description.'" title="'.$row->description.'">'.$row->name.'</a></li>';
			if($row->subEntryStructureId>'0')
				$result .=	$this->getHtmlAllowedUserContents($row->subEntryStructureId);
			$aux = $row->entryStructuresId;
		}
  }
  
  
    /**
     * Función para obtener los permisos del grupo de usuario en los submodulos de parteModuleId
     *
     * @param integer $userGroupId
     * @param integer $parentModuleId
     * @return array()
     */
  	function getSubModules($parentModuleId,$userGroupId){

		$this->db->select(	"CAT_MODULES.modulesId, 	CAT_MODULES.displayName, 	CAT_MODULES.name, CAT_MODULES.description, ".
							"CAT_MODULES.description, 	CAT_MODULES.parentModuleId, DET_PERMISSIONS.userGroupsId, CAT_MODULES.urlSection"); 
		$this->db->order_by("CAT_MODULES.order, CAT_MODULES.parentModuleId", "DESC"); 		
		
		$this->db->from('CAT_USER_GROUPS');
		$this->db->join('DET_PERMISSIONS', 'DET_PERMISSIONS.userGroupsId = CAT_USER_GROUPS.userGroupsId', 'inner');
		$this->db->join('CAT_MODULES', "DET_PERMISSIONS.modulesId = CAT_MODULES.modulesId AND CAT_USER_GROUPS.userGroupsId = '$userGroupId'", 'inner');
		
		$rs_array = $this->db->where("parentModuleId = '".$parentModuleId."' AND CAT_MODULES.active = 1")->get();
		
		foreach ($rs_array->result() as $row){
			$modules_ret[$row->modulesId] = array(	'moduleId'		=>		$row->modulesId,
												'displayName'		=>		$row->displayName,
												'description'		=>		$row->description,
												'urlSection'		=>		$row->urlSection,
												'parentModuleId'	=>		$row->parentModuleId,
												'userGroupId'		=>		$row->userGroupsId);
			$modules_ret[$row->modulesId]['modules'] =	$this->getSubModules($row->modulesId,$userGroupId);
		}
		//echo $this->db->last_query();
		if(isset($modules_ret))		return $modules_ret;
		else						return array();
		
		
	}
   
   
    /**
     * Función que chequea el nombre de usuario y contraseña sean correctos y devuelve
     * TRUE o FALSE según el caso.
     *
     * @param integer $id
     * @param string $contrasena
     * @return boolean
     */
    function checkPassword($id, $contrasena){
        $this->db->select()->from('Usuarios')->where('idUsuario', $id)->where('contrasena', $contrasena);
       
        $resultado = $this->db->get();
       
        if($resultado->num_rows()==1){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }


    /**
     * Función para cambiar la contraseña de un usuario en la BBDD
     *
     * @param int $id
     * @param string $contrasena
     * @return mixed Devuelve la cadena ejecutada o FALSE si falló
     */
    function changePassword($id,$contrasena){
        $set = array(
            'contrasena' => md5($contrasena)
        );
       
        $where = array(
            'idUsuario' => $id
        );
        return $this->db->update('Usuarios', $set, $where);
    }
}

/* End of file usuario_model.php */
/* Location: ./application/models/usuarios_model.php */
