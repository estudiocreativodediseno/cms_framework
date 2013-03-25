<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class CmsUser extends CI_Model{
	
		var $TBL_NAME = "CAT_USERS";
		function __construct(){
			parent::__construct();
		}
		
		public function validate(){
			// grab user input
			$username = $this->security->xss_clean($this->input->post('username'));
			$password = md5($this->security->xss_clean($this->input->post('password')));
			// Prep the query
			$this->db->where('username', $username);
			$this->db->where('password', $password);
			
			// Run the query
			$query = $this->db->get('CAT_USERS');
			// Let's check if there are any results
			if($query->num_rows == 1)	{
				// If there is a user, then create session data
				$row = $query->row();
				$data = array(
						'usersId'		=> $row->usersId,
						'username' 		=> $row->username,
						'name' 			=> $row->name,
						'correct' 		=> TRUE,
						'userTypesId'	=> $row->userTypesId,
						'userGroupsId' 	=> $row->userGroupsId,
						'moduleData' 	=> array(),
						'moduleId' 	=> ''
						);
				$this->session->set_userdata($data);
				return $data;
			}
			// If the previous process did not validate
			// then return false.
			return array('correct'=>FALSE);
		}
	
		function setup()
		{
			$this->setTable('CAT_USERS');
			$this->setFields( array(	'usersId',		'name',			'username',
										'password',		'email',		'banned',
										'banReason',	'lastIp',		'active',
										'userTypesId',	'userGroupsId',	'insertUserId',
										'insertDate',	'updateUserId',	'updateDate' ));
			
			$this->setUpdateFields( array(	'usersId',		'name',			'username',
											'password',		'email',		'banned',
											'banReason',	'lastIp',		'active',
											'userTypesId',	'userGroupsId',	'insertUserId',
											'insertDate',	'updateUserId',	'updateDate'));
		}
		public function newSelf( Core_Db_Db $dbConnection)
		{
			return new self( $dbConnection);
		}	
	}
?>