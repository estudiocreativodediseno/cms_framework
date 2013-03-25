// JavaScript Document

    /**
     * Función para editar elpermisos del grupo de usuario en permisos de funciones
     *
     * @param String type
     * @param integer groupId
     * @param integer moduleId
     * @return void 
	 */
	function editPermission(type,groupId,moduleId){
		$.post($('#base-url').val()+"index.php/access/userGroups/editModulePermission", 
				{	
					'groupId': 	groupId,
					'moduleId': moduleId,
					'type': 	type
				}, 
				function(data){
					var response = $.parseJSON(data);
					alert(response.message);
					$('#module-lock-image-'+response.groupId+'-'+response.moduleId).html('<img src="'+response.image+'">');
					$('#module-link-function-'+response.groupId+'-'+response.moduleId).html(' [<a href="#edit" onclick="'+response.onClick+'">'+response.editType+'</a>]');
				}
		);
	}
	
	
    /**
     * Función para editar el permisos del grupo de usuario en permisos de funciones
     *
     * @param String type
     * @param integer groupId
     * @param integer functionId
     * @return void
	 */
	function editFunctionAllowed(type,groupId,functionId){
		$.post($('#base-url').val()+"index.php/access/userGroups/editFunctionAllowed", 
				{	
					'groupId': 		groupId,
					'functionId': 	functionId,
					'type': 		type
				}, 
				function(data){
					var response = $.parseJSON(data);
					alert(response.message);
					$('#module-lock-image-'+response.groupId+'-'+response.functionId).html('<img src="'+response.image+'">');
					$('#module-link-function-'+response.groupId+'-'+response.functionId).html(' [<a href="#edit" onclick="'+response.onClick+'">'+response.editType+'</a>]');
				}
		);
	}