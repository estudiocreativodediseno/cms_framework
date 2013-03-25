<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>CMS - Adminsitración de contenidos</title>
        
    
    
                <?php
					if(isset($data)){
						$this->load->library('formDataTypes');	
						$form = new FormDataTypes();		
						$field_ids = '';
						foreach ($data as $row){
							$form->getFormElement($row->entryContentsId, $row->dataTypesId); 		
							if(strpos($row->showLike,'upload') === false)
								$field_ids .= (strlen($field_ids)>0?',':'').$row->id;
                    	}
						$form_validation =  $form->getFormValidation($row->entryContentsId, $row->dataTypesId); 
					}
                ?>   
                
                
            <link rel="icon" href="<?php echo base_url(); ?>resources/img/favicon.ico" type="image/x-icon" />
            <link rel="shortcut icon" href="<?php echo base_url(); ?>resources/img/favicon.ico" type="image/x-icon" />
            <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>resources/css/base.css" />
            <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Droid+Sans" />
            
            <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/grocery_crud/css/jquery_plugins/uniform/uniform.default.css" />
            <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/grocery_crud/css/jquery_plugins/chosen/chosen.css" />
            <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/grocery_crud/css/ui/simple/jquery-ui-1.9.0.custom.min.css" />

            <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/grocery_crud/themes/flexigrid/css/flexigrid.css" />
            <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>resources/css/tables.css" />
            <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>resources/css/uniform.default.min.css" />
            <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>resources/css/jquery.qtip.min.css" />

		<?php
            if(isset($form)){
                foreach ($form->css as $css){
        ?>
            <link type="text/css" rel="stylesheet" href="<?php echo $css; ?>" />
		<?php
                }
            }
        ?>   
                
			<script src="<?php echo base_url(); ?>assets/grocery_crud/js/jquery-1.8.2.min.js"></script>
            <script src="<?php echo base_url(); ?>assets/grocery_crud/themes/flexigrid/js/cookies.js"></script>
            
            <script src="<?php echo base_url(); ?>assets/grocery_crud/themes/flexigrid/js/jquery.form.js"></script>
            <script src="<?php echo base_url(); ?>assets/grocery_crud/js/jquery_plugins/jquery.numeric.min.js"></script>
            <script src="<?php echo base_url(); ?>assets/grocery_crud/themes/flexigrid/js/jquery.printElement.min.js"></script>
            <script src="<?php echo base_url(); ?>assets/grocery_crud/js/jquery_plugins/jquery.uniform.min.js"></script>
            
			<script type="text/javascript" src="<?php echo base_url(); ?>resources/js/libraries/jquery.collapse.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/libraries/jquery.easing.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/app-chrome.js"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/tools-manager.js"></script>
            
            <script src="<?php echo base_url(); ?>resources/js/jquery.qtip.min.js"></script>
            <script src="<?php echo base_url(); ?>resources/js/jquery.qtip.grow.js"></script>
            <script src="<?php echo base_url(); ?>resources/js/init.js"></script>
            <script src="<?php echo base_url(); ?>resources/js/initUniForm.js"></script>
            <script src="<?php echo base_url(); ?>resources/js/initQtip.js"></script>

            <script src="<?php echo base_url(); ?>resources/js/cms/content/publish.js"></script>
            
            
		<?php
            if(isset($form)){
                foreach ($form->js as $js){
        ?>
            <script src="<?php echo $js; ?>"></script>
		<?php
                }
            }
        ?>  
        
		<script language="javascript">
				<?php  if(isset($form))	echo $form->inline_js;  ?>  
            	var js_date_format = 'yy-mm-dd';
				var id_fields = '<?php echo $field_ids; ?>';
        </script>
        
        
		<script language="javascript">
				<?php  
							echo $form_validation;
				?>
        </script>
        
        <script id="template-download" type="text/x-tmpl"><?php  if(isset($form))	echo $form->inline_x_tmpl;  ?>  </script>
    
		<script language="javascript" type="text/javascript">
        <!--
		
        function startUpload(id){
			$('#upload_process_'+id).slideDown('slow');
			$("#uploader_button_"+id).attr("disabled", "disabled");
			$('#upload_image_'+id).slideUp();
			return true;
        }
        
        function stopUpload(success, response){
			
			$('#upload_process_'+response.id).slideUp('slow');
			createGrowl('Operación terminada', response.message, false);
											
			$('#uploaded_image_'+response.id).attr('src', response.newFile+'?'+new Date().getTime()).load(function(){
				$('a#fancy_image_'+response.id).attr('href', response.newFile+'?'+new Date().getTime());
				$('#upload_image_'+response.id).slideDown();			
			});             
        }
        //-->
        </script>  
	</head>