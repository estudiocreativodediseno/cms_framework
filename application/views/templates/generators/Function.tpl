
  	/**
     * Código generado automáticamente por CMS-FRAMEWORK
	 * %function_description%
	 *
	 %function_parameter_comments%
	 * @return void
	 */
     function %function_name%(%function_parameters%){
		try{
            %function_code%
	
			$data->output = $output;
			$data->title = '%title_section%';
			
			/* Se carga la vista del Controlador*/
			$this->load->view('%view_section_file%', $output);
		}catch(Exception $e){
			  /* En caso de error, lo mostramos */
			  show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
     }
     
     