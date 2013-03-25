<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class FormDataTypes {//  extends CI_Controller {
	
		protected $states = array(
			0	=> 'unknown',
			1	=> 'list',
			2	=> 'add',
			3	=> 'edit',
			4	=> 'delete',
			5	=> 'insert',
			6	=> 'update',
			7	=> 'ajax_list',
			8   => 'ajax_list_info',
			9	=> 'insert_validation',
			10	=> 'update_validation',
			11	=> 'upload_file',
			12	=> 'delete_file',
			13	=> 'ajax_relation',
			14	=> 'ajax_relation_n_n',
			15	=> 'success',
			16  => 'export',
			17  => 'print'
		);
		
		protected $default_true_false_text = array('No', 'Si');
			
		protected $language				= null;
		protected $lang_strings			= array();
		protected $subjet			= "";
	
		var $ui_date_format = "yyyy-mm-dd";
		var $php_date_format 		= "Y-m-d";
		var $js 		= array();
		var $css 		= array();
		var $CI;
		
		public $default_css_path 	= '';
		public $default_js_path 	= '';
		public $inline_js			= '';
		public $x_tmpl				= '';
		
		protected $default_language_path	= 'assets/grocery_crud/languages';
		protected $default_config_path		= 'assets/grocery_crud/config';
		protected $default_assets_path		= 'assets/grocery_crud';
		
		public function __construct(){
			$this->CI =& get_instance();
			$this->CI->load->helper('url');
			$this->CI->load->library('session');
			$this->CI->load->database();
			
			
			$this->default_css_path = base_url().'resources/css';
			$this->default_js_path 	= base_url().'resources/js';
			
			$this->_initialize_variables();
			$this->_initialize_helpers();
			$this->_load_language();
			
		}
		
		public function getFormElement($entryContentId, $dataTypeId, $excludeUpload=false, $onlyUpload=false){
			$rs_qry = $this->CI->db->
					select('CAT_DATA_TYPE_SHOW_FORMS.*, CAT_DATA_TYPES.name as nameField,  CAT_DATA_TYPES.prefix, CAT_DATA_TYPES.postfix, DET_ENTRY_CONTENTS.id as dataId,
							CAT_DATA_TYPES.description as descriptionField, CAT_DATA_TYPES.displayName as displayNameField, CAT_DATA_TYPES.label as labelField,
							CAT_DATA_TYPES.minLength, CAT_DATA_TYPES.maxLength, CAT_DATA_TYPES.mandatory, CAT_DATA_TYPES.uploadFileTypes, DET_ENTRY_CONTENTS.data,
							DET_ENTRY_CONTENTS.updateDate')->
					from('DET_ENTRY_CONTENTS')->
					join('CAT_DATA_TYPES', 'CAT_DATA_TYPES.dataTypesId = DET_ENTRY_CONTENTS.dataTypesId', 'INNER')->
					join('CAT_DATA_TYPE_SHOW_FORMS', 'CAT_DATA_TYPE_SHOW_FORMS.dataTypeShowFormsId = CAT_DATA_TYPES.dataTypeShowFormsId', 'INNER')->
					where('DET_ENTRY_CONTENTS.entryContentsId = '.$entryContentId.' AND DET_ENTRY_CONTENTS.dataTypesId = '.$dataTypeId)->get();
					
			if($rs_qry->num_rows()>0){
				$row = $rs_qry->row();
				$field_info->dataId		= $row->dataId;
				$field_info->data 		= $row->data;
				$field_info->data_type 	= $row->showLike;
				$field_info->name	 	= $row->nameField;
				$field_info->prefix 	= $row->prefix;
				$field_info->postfix 	= $row->postfix;
				$field_info->minLength 	= $row->minLength;
				$field_info->maxLength 	= $row->maxLength;
				$field_info->mandatory 	= $row->mandatory;	
				$field_info->updateDate	= $row->updateDate;	
				$field_info->default 	= $row->defaultValue;	
				$field_info->upload_file_types 	= $row->uploadFileTypes;	
				$field_info->extras		= '';	
				if($excludeUpload)
					if(strpos($field_info->data_type,'upload') !== false)// || $field_info->data_type=="uploadfile")
						return "";
						
				if($onlyUpload)
					if (strpos($field_info->data_type,'upload') === false) //if($field_info->data_type!="upload")
						return "";
				//if($onlyUpload)
					//if($field_info->data_type!="uploadfile")
						//return "";
				return  $row->prefix.' '.$this->get_field_input($field_info)->input.' '.$row->postfix;
			}
				return '';
		}
		
		
		/**
		 * 
		 * Función para crear la validación de formularios de captura
		 * @param int $entryContentId
		 * @param int $dataTypeId
		 */
		public function getFormValidation($entryContentId, $dataTypeId){
			
		if (!in_array($this->default_js_path.'/jquery-migrate-1.0.0.js',$this->js))
			array_push($this->js,$this->default_js_path.'/jquery-migrate-1.0.0.js');
		if (!in_array($this->default_js_path.'/jquery.validate.min.js',$this->js))
			array_push($this->js,$this->default_js_path.'/jquery.validate.min.js');
		if (!in_array($this->default_js_path.'/jquery.validate.messages_es',$this->js))
			array_push($this->js,$this->default_js_path.'/jquery.validate.messages_es.js');
		
			$rs_qry = $this->CI->db->
					select('CAT_DATA_TYPE_SHOW_FORMS.*, CAT_DATA_TYPES.name as nameField,  CAT_DATA_TYPES.prefix, CAT_DATA_TYPES.postfix, DET_ENTRY_CONTENTS.id as dataId,
							CAT_DATA_TYPES.description as descriptionField, CAT_DATA_TYPES.displayName as displayNameField, CAT_DATA_TYPES.label as labelField,
							CAT_DATA_TYPES.minLength, CAT_DATA_TYPES.maxLength, CAT_DATA_TYPES.mandatory, CAT_DATA_TYPES.uploadFileTypes, DET_ENTRY_CONTENTS.data')->
					from('DET_ENTRY_CONTENTS')->
					join('CAT_DATA_TYPES', 'CAT_DATA_TYPES.dataTypesId = DET_ENTRY_CONTENTS.dataTypesId', 'INNER')->
					join('CAT_DATA_TYPE_SHOW_FORMS', 'CAT_DATA_TYPE_SHOW_FORMS.dataTypeShowFormsId = CAT_DATA_TYPES.dataTypeShowFormsId', 'INNER')->
					where('DET_ENTRY_CONTENTS.entryContentsId = '.$entryContentId	)->get();
			$rules = "";
			
			//echo $this->CI->db->last_query();
			//if($rs_qry->num_rows()>0){
			foreach ($rs_qry->result() as $row){
				if($row->showLike!='upload' && $row->showLike!='uploadfile'){
					$fieldRules = "";
					$fieldRules .= $row->mandatory=='1'?strlen($fieldRules)>0?",":""." required: true":"";
					//$fieldRules .= $row->showLike=='integer'?strlen($fieldRules)>0?",":""." digits:true ":"";
					$fieldRules .= $row->minLength>0?
											(strlen($fieldRules)>0?",":"")." minlength: ".$row->minLength:
											"";
					$fieldRules .= $row->maxLength>0?
											(strlen($fieldRules)>0?",":"")." maxlength: ".$row->maxLength:
											"";
					$rules .= strlen($fieldRules)>0 ? 
										( (strlen($rules)>0?",":"")."
												'field-data-".$row->dataId."': { ".$fieldRules." }" ):
										"";				
				}
			}
			
			
			return	 '
							$(document).ready(function(){
								var myForm = $(\'form#editFormData\');
							
								myForm.validate({
										errorClass: "errormessage",
										onkeyup: false,
										errorClass: \'error\',
										validClass: \'valid\',
										rules: {	'.$rules.'
										},
										errorPlacement: function(error, element)
										{
											var elem = $(element),
												corners = [\'left center\', \'right center\'],
												flipIt = elem.parents(\'span.right\').length > 0;
											 elem.qtip(\'destroy\');
											if(!error.is(\':empty\')) {
												elem.filter(\':not(.valid)\').qtip({
													overwrite: true,
													content: error,
													position: {
														my: corners[ flipIt ? 0 : 1 ],
														at: corners[ flipIt ? 1 : 0 ],
														viewport: $(window)
													},
													show: {
														event: false,
														ready: true,
														effect: function(offset) {
															$(this).fadeIn(900); 
														}
													},
													hide: {
														event: \'click\',
														effect: function(offset) {
															$(this).fadeOut(900); 
														}
													},
													style: {
														classes: \'qtip-red\' 
													}
												})
							
												// If we have a tooltip on this element already, just update its content
												.qtip(\'option\', \'content.text\', error);
											}
											else { elem.qtip(\'destroy\'); }
										},
									   submitHandler: function(form) {
										 saveDataForm();
									   }
								})
							});
							
							/*jQuery.validator.addMethod("numeric", function(value, element) { 
							 return !isNaN(value); //this.optional(element) || ; 
							}, jQuery.format("Por favor, ingresa un valor numérico"));*/
';
		}
			
		/**
		 * 
		 * Changes the default field type
		 * @param string $field
		 * @param string $type
		 * @param array|string $extras
		 */
		public function change_field_type($field , $type, $extras = null)
		{
			$field_type = (object)array('type' => $type);
		
			$field_type->extras = $extras;
			
			$this->change_field_type[$field] = $field_type;
			
			return $this;
		}
		
		/**
		 *
		 * Just an alias to the change_field_type method
		 * @param string $field
		 * @param string $type
		 * @param array|string $extras
		 */
		public function field_type($field , $type, $extras = null)
		{
			return $this->change_field_type($field , $type, $extras);
		}	
		
			
		/**
		 * Get the html input for the specific field with the 
		 * current value
		 * 
		 * @param object $field_info
		 * @return object
		 */
		protected function get_field_input($field_info, $value = null)
		{
				$real_type = $field_info->data_type;
				
				$types_array = array(
						'integer', 
						'text',
						'true_false',
						'string', 
						'date',
						'datetime',
						'enum',
						'set',
						'relation', 
						'relation_n_n',
						'upload',
						'uploadfile',
						'hidden',
						'password', 
						'readonly',
						'dropdown',
						'multiselect'
				);
				
				$field_info->input = $this->{"get_".$real_type."_input"}($field_info,$value);
				if (in_array($real_type,$types_array)) {
					/* A quick way to go to an internal method of type $this->get_{type}_input . 
					 * For example if the real type is integer then we will use the method
					 * $this->get_integer_input
					 *  */
					$field_info->input = $this->{"get_".$real_type."_input"}($field_info,$field_info->data);
				}
				else
				{
					$field_info->input = $this->get_string_input($field_info,$value);
				}
			
			return $field_info;
		}
		
		
		
		protected function change_list_value($field_info, $value = null)
		{
			$real_type = $field_info->data_type;
			
			switch ($real_type) {
				case 'hidden':
				case 'invisible':
				case 'integer':
					
				break;
				case 'true_false':
					if(isset($this->default_true_false_text[$value]))
						$value = $this->default_true_false_text[$value];
				break;
				case 'string':
					$value = $this->character_limiter($value,$this->character_limiter,"...");
				break;
				case 'text':
					$value = $this->character_limiter(strip_tags($value),$this->character_limiter,"...");
				break;
				case 'date':
					if(!empty($value) && $value != '0000-00-00' && $value != '1970-01-01')
					{
						list($year,$month,$day) = explode("-",$value);
						
						$value = date($this->php_date_format, mktime (0, 0, 0, (int)$month , (int)$day , (int)$year));
					}
					else 
					{
						$value = '';
					}
				break;
				case 'datetime':
					if(!empty($value) && $value != '0000-00-00 00:00:00' && $value != '1970-01-01 00:00:00')
					{
						list($year,$month,$day) = explode("-",$value);
						list($hours,$minutes) = explode(":",substr($value,11));
						
						$value = date($this->php_date_format." - H:i", mktime ((int)$hours , (int)$minutes , 0, (int)$month , (int)$day ,(int)$year));
					}
					else 
					{
						$value = '';
					}
				break;
				case 'enum':
					$value = $this->character_limiter($value,$this->character_limiter,"...");
				break;
	
				case 'multiselect':
					$value_as_array = array();
					foreach(explode(",",$value) as $row_value)
					{
						$value_as_array[] = array_key_exists($row_value,$field_info->extras) ? $field_info->extras[$row_value] : $row_value;
					}
					$value = implode(",",$value_as_array);
				break;			
				
				case 'relation_n_n':
					$value = $this->character_limiter(str_replace(',',', ',$value),$this->character_limiter,"...");
				break;						
				
				case 'password':
					$value = '******';
				break;
				
				case 'dropdown':
					$value = array_key_exists($value,$field_info->extras) ? $field_info->extras[$value] : $value; 
				break;			
				
				case 'upload_file':
					if(empty($value))
					{
						$value = "";
					}
					else
					{
						$is_image = !empty($value) &&
						( substr($value,-4) == '.jpg'
								|| substr($value,-4) == '.png'
								|| substr($value,-5) == '.jpeg'
								|| substr($value,-4) == '.gif'
								|| substr($value,-5) == '.tiff')
								? true : false;		
									
						$file_url = base_url().$field_info->extras->upload_path."/$value";
						
						$file_url_anchor = '<a href="'.$file_url.'"';
						if($is_image)
						{
							$file_url_anchor .= ' class="image-thumbnail"><img src="'.$file_url.'" height="50px">';
						}
						else
						{
							$file_url_anchor .= ' target="_blank">'.$this->character_limiter($value,$this->character_limiter,'...',true);
						}
						$file_url_anchor .= '</a>';
						
						$value = $file_url_anchor;
					}
				break;
				
				default:
					$value = $this->character_limiter($value,$this->character_limiter,"...");
				break;
			}
			
			return $value;
		}
	
		/**
		 * Character Limiter of codeigniter (I just don't want to load the helper )
		 *
		 * Limits the string based on the character count.  Preserves complete words
		 * so the character count may not be exactly as specified.
		 *
		 * @access	public
		 * @param	string
		 * @param	integer
		 * @param	string	the end character. Usually an ellipsis
		 * @return	string
		 */
		function character_limiter($str, $n = 500, $end_char = '&#8230;')
		{
			if (strlen($str) < $n)
			{
				return $str;
			}
	
			// a bit complicated, but faster than preg_replace with \s+
			$str = preg_replace('/ {2,}/', ' ', str_replace(array("\r", "\n", "\t", "\x0B", "\x0C"), ' ', $str));
	
			if (strlen($str) <= $n)
			{
				return $str;
			}
	
			$out = '';
			foreach (explode(' ', trim($str)) as $val)
			{
				$out .= $val.' ';
	
				if (strlen($out) >= $n)
				{
					$out = trim($out);
					return (strlen($out) === strlen($str)) ? $out : $out.$end_char;
				}
			}
		}
		
		protected function get_type($db_type)
		{
			$type = false;
			if(!empty($db_type->type))
			{
				switch ($db_type->type) {
					case '1':
					case '3':
					case 'int':
					case 'tinyint':
					case 'mediumint':
					case 'longint':					
						if( $db_type->db_type == 'tinyint' && $db_type->db_max_length ==  1)
							$type = 'true_false';
						else
							$type = 'integer';
					break;
					case '254':
					case 'string':
					case 'enum':					
						if($db_type->db_type != 'enum')
							$type = 'string';
						else
							$type = 'enum';
					break;
					case 'set':
						if($db_type->db_type != 'set')
							$type = 'string';
						else
							$type = 'set';
					break;
					case '252':
					case 'blob':
					case 'text':
					case 'mediumtext':					
					case 'longtext':
						$type = 'text';
					break;
					case '10':
					case 'date':
						$type = 'date';
					break;
					case '12':
					case 'datetime':
					case 'timestamp':
						$type = 'datetime';
					break;
				}
			}
			return $type;
		}
		
			
		protected function get_string_input($field_info,$value)
		{
			$value = !is_string($value) ? '' : str_replace('"',"&quot;",$value); 
			
			$extra_attributes = '';
			if(!empty($field_info->maxLength))
				$extra_attributes .= "maxlength='{$field_info->maxLength}'"; 
			$input = "<input id='field-data-{$field_info->dataId}' name='field-data-{$field_info->dataId}' type='text' value=\"$value\" $extra_attributes />";
			return $input;
		}
		
			
		protected function get_integer_input($field_info,$value)
		{
			if(!in_array($this->default_js_path.'/jquery_plugins/jquery.numeric.min.js',$this->js))
			array_push($this->js,$this->default_js_path.'/jquery_plugins/jquery.numeric.min.js');
			if(!in_array($this->default_js_path.'/jquery_plugins/config/jquery.numeric.config.js',$this->js))
			array_push($this->js,$this->default_js_path.'/jquery_plugins/config/jquery.numeric.config.js');
			$extra_attributes = '';
			if(!empty($field_info->db_max_length))
				$extra_attributes .= "maxlength='{$field_info->db_max_length}'"; 
			$input = "<input id='field-data-{$field_info->dataId}' name='field-data-{$field_info->dataId}' type='text' value='$value' class='numeric' $extra_attributes />";
			return $input;
		}
	
		protected function get_true_false_input($field_info,$value)
		{
			if(!in_array($this->default_css_path.'/jquery_plugins/uniform/uniform.default.css',$this->css))
			array_push($this->css,$this->default_css_path.'/jquery_plugins/uniform/uniform.default.css');
			if(!in_array($this->default_js_path.'/jquery_plugins/jquery.uniform.min.js',$this->js))
			array_push($this->js,$this->default_js_path.'/jquery_plugins/jquery.uniform.min.js');
			if(!in_array($this->default_js_path.'/jquery_plugins/config/jquery.uniform.config.js',$this->js))
			array_push($this->js,$this->default_js_path.'/jquery_plugins/config/jquery.uniform.config.js');
			
			$value_is_null = empty($value) && $value !== '0' && $value !== 0 ? true : false;
			
			$input = "<div class='pretty-radio-buttons'>";
			
			$checked = $value === '1' || ($value_is_null && $field_info->default === '1') ? "checked = 'checked'" : "";
			$input .= "<label><input id='field-data-{$field_info->dataId}-true' class='radio-uniform'  type='radio' name='field-data-{$field_info->dataId}' value='1' $checked style='display:1;'/> ".$this->default_true_false_text[1]."</label> ";
			
			$checked = $value === '0' || ($value_is_null && $field_info->default === '0') ? "checked = 'checked'" : ""; 
			$input .= "<label><input id='field-data-{$field_info->dataId}-false' class='radio-uniform' type='radio' name='field-data-{$field_info->dataId}' value='0' $checked /> ".$this->default_true_false_text[0]."</label>";
			
			$input .= "</div>";
			
			return $input;
		}	
	
		protected function get_text_input($field_info,$value)
		{   
			if($field_info->extras == 'text_editor')
			{
				$editor = $this->config->default_text_editor;
				switch ($editor) {
					case 'ckeditor':
						array_push($this->js,$this->default_texteditor_path.'/ckeditor/ckeditor.js');
						array_push($this->js,$this->default_texteditor_path.'/ckeditor/adapters/jquery.js');
						array_push($this->js,$this->default_js_path.'/jquery_plugins/config/jquery.ckeditor.config.js');
					break;
					
					case 'tinymce':
						array_push($this->js,$this->default_texteditor_path.'/tiny_mce/jquery.tinymce.js');
						array_push($this->js,$this->default_js_path.'/jquery_plugins/config/jquery.tine_mce.config.js');					
					break;
					
					case 'markitup':
						array_push($this->css,$this->default_texteditor_path.'/markitup/skins/markitup/style.css');
						array_push($this->css,$this->default_texteditor_path.'/markitup/sets/default/style.css');
						
						array_push($this->js,$this->default_texteditor_path.'/markitup/jquery.markitup.js');
						array_push($this->js,$this->default_js_path.'/jquery_plugins/config/jquery.markitup.config.js');
					break;				
				}
				
				$class_name = $this->config->text_editor_type == 'minimal' ? 'mini-texteditor' : 'texteditor';
				
				$input = "<textarea id='field-data-{$field_info->dataId}' name='field-data-{$field_info->dataId}' class='$class_name' >$value</textarea>";
			}
			else
			{
				$input = "<textarea id='field-data-{$field_info->dataId}' name='field-data-{$field_info->dataId}'>$value</textarea>";
			}
			return $input;
		}
		
		protected function get_datetime_input($field_info,$value)
		{
			if(!in_array($this->default_css_path.'/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS,$this->css))
			array_push($this->css,$this->default_css_path.'/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS);
			if(!in_array($this->default_css_path.'/jquery_plugins/jquery.ui.datetime.css',$this->css))
			array_push($this->css,$this->default_css_path.'/jquery_plugins/jquery.ui.datetime.css');
			if(!in_array($this->default_css_path.'/jquery_plugins/jquery-ui-timepicker-addon.css',$this->css))
			array_push($this->css,$this->default_css_path.'/jquery_plugins/jquery-ui-timepicker-addon.css');
			if(!in_array($this->default_js_path.'/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS,$this->js))
			array_push($this->js,$this->default_js_path.'/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS);
			if(!in_array($this->default_js_path.'/jquery_plugins/jquery-ui-timepicker-addon.min.js',$this->js))
			array_push($this->js,$this->default_js_path.'/jquery_plugins/jquery-ui-timepicker-addon.min.js');
			
			if($this->language !== 'english')
			{
				include('assets/grocery_crud/config/language_alias.php');
				if(array_key_exists($this->language, $language_alias))
				{
					$i18n_date_js_file = $this->default_js_path.'/jquery_plugins/ui/i18n/datepicker/jquery.ui.datepicker-'.$language_alias[$this->language].'.js'; 
					if(file_exists($i18n_date_js_file))
					{
						array_push($this->js,$i18n_date_js_file);
					}
					
					$i18n_datetime_js_file = $this->default_js_path.'/jquery_plugins/ui/i18n/timepicker/jquery-ui-timepicker-'.$language_alias[$this->language].'.js';
					if(file_exists($i18n_datetime_js_file))
					{
						array_push($this->js,$i18n_datetime_js_file);
					}				
				}
			}
			
			array_push($this->js,$this->default_js_path.'/jquery_plugins/config/jquery-ui-timepicker-addon.config.js');
			
			if(!empty($value) && $value != '0000-00-00 00:00:00' && $value != '1970-01-01 00:00:00'){
				list($year,$month,$day) = explode('-',substr($value,0,10));
				$date = date($this->php_date_format, mktime(0,0,0,$month,$day,$year));
				$datetime = $date.substr($value,10);	
			}
			else 
			{
				$datetime = '';
			}
			$input = "<input id='field-data-{$field_info->dataId}' name='field-data-{$field_info->dataId}' type='text' value='$datetime' maxlength='19' class='datetime-input' /> 
			<a class='datetime-input-clear' tabindex='-1'>".$this->lang('form_button_clear')."</a>
			({$this->ui_date_format}) hh:mm:ss";
			return $input;
		}
		
		protected function get_hidden_input($field_info,$value)
		{
			if($field_info->extras !== null && $field_info->extras != false)
				$value = $field_info->extras;
			$input = "<input id='field-data-{$field_info->dataId}' type='hidden' name='field-data-{$field_info->dataId}' value='$value' />";
			return $input;		
		}
		
		protected function get_password_input($field_info,$value)
		{
			$value = !is_string($value) ? '' : $value; 
			
			$extra_attributes = '';
			if(!empty($field_info->db_max_length))
				$extra_attributes .= "maxlength='{$field_info->db_max_length}'"; 
			$input = "<input id='field-data-{$field_info->dataId}' name='field-data-{$field_info->dataId}' type='password' value='$value' $extra_attributes />";
			return $input;
		}
		
		protected function get_date_input($field_info,$value)
		{	
			array_push($this->css,$this->default_css_path.'/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS);
			array_push($this->js,$this->default_js_path.'/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS);
			
			if($this->language !== 'english')
			{
				include('assets/grocery_crud/config/language_alias.php');
				if(array_key_exists($this->language, $language_alias))
				{
					$i18n_date_js_file = $this->default_js_path.'/jquery_plugins/ui/i18n/datepicker/jquery.ui.datepicker-'.$language_alias[$this->language].'.js';
					if(file_exists($i18n_date_js_file))
					{
						array_push($this->js,$i18n_date_js_file);
					}
				}
			}		
			
			array_push($this->js,$this->default_js_path.'/jquery_plugins/config/jquery.datepicker.config.js');
			
			if(!empty($value) && $value != '0000-00-00' && $value != '1970-01-01')
			{
				list($year,$month,$day) = explode('-',substr($value,0,10));
				$date = date($this->php_date_format, mktime(0,0,0,$month,$day,$year));
			}
			else
			{
				$date = '';
			}
			
			$input = "<input id='field-data-{$field_info->dataId}' name='field-data-{$field_info->dataId}' type='text' value='$date' maxlength='10' class='datepicker-input' /> 
			<a class='datepicker-input-clear' tabindex='-1'>".$this->lang('form_button_clear')."</a> (".$this->ui_date_format.")";
			return $input;
		}	
	
		protected function get_dropdown_input($field_info,$value)
		{
			array_push($this->css,$this->default_css_path.'/jquery_plugins/chosen/chosen.css');
			array_push($this->js,$this->default_js_path.'/jquery_plugins/jquery.chosen.min.js');
			array_push($this->js,$this->default_js_path.'/jquery_plugins/config/jquery.chosen.config.js');
		
			$select_title = str_replace('{field_display_as}',$field_info->display_as,$this->lang('set_relation_title'));
			
			$input = "<select id='field-data-{$field_info->dataId}' name='field-data-{$field_info->dataId}' class='chosen-select' data-placeholder='".$select_title."'>";
			$options = array('' => '') + $field_info->extras;
			foreach($options as $option_value => $option_label)
			{
				$selected = !empty($value) && $value == $option_value ? "selected='selected'" : '';
				$input .= "<option value='$option_value' $selected >$option_label</option>";
			}
		
			$input .= "</select>";
			return $input;
		}	
		
		protected function get_enum_input($field_info,$value)
		{
			array_push($this->css,$this->default_css_path.'/jquery_plugins/chosen/chosen.css');
			array_push($this->js,$this->default_js_path.'/jquery_plugins/jquery.chosen.min.js');
			array_push($this->js,$this->default_js_path.'/jquery_plugins/config/jquery.chosen.config.js');
			
			$select_title = str_replace('{field_display_as}',$field_info->display_as,$this->lang('set_relation_title'));
			
			$input = "<select id='field-data-{$field_info->dataId}' name='field-data-{$field_info->dataId}' class='chosen-select' data-placeholder='".$select_title."'>";
			$options_array = $field_info->extras !== false && is_array($field_info->extras)? $field_info->extras : explode("','",substr($field_info->db_max_length,1,-1));
			$options_array = array('' => '') + $options_array;
			
			foreach($options_array as $option)
			{
				$selected = !empty($value) && $value == $option ? "selected='selected'" : '';
				$input .= "<option value='$option' $selected >$option</option>";
			}
		
			$input .= "</select>";
			return $input;
		}
		
		protected function get_readonly_input($field_info,$value)
		{
			return '<div id="field-'.$field_info->name.'" class="readonly_label">'.$value.'</div>';
		}
		
		protected function get_set_input($field_info,$value)
		{
			array_push($this->css,$this->default_css_path.'/jquery_plugins/chosen/chosen.css');
			array_push($this->js,$this->default_js_path.'/jquery_plugins/jquery.chosen.min.js');
			array_push($this->js,$this->default_js_path.'/jquery_plugins/ajax-chosen.js');
			array_push($this->js,$this->default_js_path.'/jquery_plugins/config/jquery.chosen.config.js');
			
			$options_array = $field_info->extras !== false && is_array($field_info->extras)? $field_info->extras : explode("','",substr($field_info->db_max_length,1,-1));
			$selected_values 	= !empty($value) ? explode(",",$value) : array();
			
			$select_title = str_replace('{field_display_as}',$field_info->display_as,$this->lang('set_relation_title'));
			$input = "<select id='field-data-{$field_info->dataId}' name='{$field_info->name}[]' multiple='multiple' size='8' class='chosen-multiple-select' data-placeholder='$select_title' style='width:510px;' >";
			
			foreach($options_array as $option)
			{
				$selected = !empty($value) && in_array($option,$selected_values) ? "selected='selected'" : ''; 
				$input .= "<option value='$option' $selected >$option</option>";	
			}
				
			$input .= "</select>";
			
			return $input;
		}	
		
		protected function get_multiselect_input($field_info,$value)
		{
			array_push($this->css,$this->default_css_path.'/jquery_plugins/chosen/chosen.css');
			array_push($this->js,$this->default_js_path.'/jquery_plugins/jquery.chosen.min.js');
			array_push($this->js,$this->default_js_path.'/jquery_plugins/ajax-chosen.js');
			array_push($this->js,$this->default_js_path.'/jquery_plugins/config/jquery.chosen.config.js');
		
			$options_array = $field_info->extras;
			$selected_values 	= !empty($value) ? explode(",",$value) : array();
		
			$select_title = str_replace('{field_display_as}',$field_info->display_as,$this->lang('set_relation_title'));
			$input = "<select id='field-data-{$field_info->dataId}' name='{$field_info->name}[]' multiple='multiple' size='8' class='chosen-multiple-select' data-placeholder='$select_title' style='width:510px;' >";
		
			foreach($options_array as $option_value => $option_label)
			{
				$selected = !empty($value) && in_array($option_value,$selected_values) ? "selected='selected'" : '';
				$input .= "<option value='$option_value' $selected >$option_label</option>";
			}
		
			$input .= "</select>";
		
			return $input;
		}	
		
		
		
	protected function get_upload_input($field_info, $value)
	{

		if (!in_array($this->default_css_path.'/fileupload.css',$this->css))
		array_push($this->css,$this->default_css_path.'/fileupload.css');

		if (!in_array($this->default_js_path.'/fileupload.js',$this->js))
		array_push($this->js,$this->default_js_path.'/fileupload.js');
		
		//Fancybox
		
		if (!in_array($this->default_css_path.'/jquery_plugins/fancybox/jquery.fancybox.css',$this->css))
		array_push($this->css,$this->default_css_path.'/jquery_plugins/fancybox/jquery.fancybox.css');
		
		if (!in_array($this->default_js_path.'/jquery_plugins/jquery.fancybox.js',$this->js))
		array_push($this->js,$this->default_js_path.'/jquery_plugins/jquery.fancybox.js');
		if (!in_array($this->default_js_path.'/jquery_plugins/jquery.easing-1.3.pack.js',$this->js))
		array_push($this->js,$this->default_js_path.'/jquery_plugins/jquery.easing-1.3.pack.js');		
		
		$unique = uniqid();
		$unique = md5($field_info->dataId);
		
		$allowed_files = $field_info->upload_file_types;
		$allowed_files_ui = '.'.str_replace('|',',.',$allowed_files);
		$max_file_size_ui = $field_info->maxLength;
		$max_file_size_bytes = $this->_convert_bytes_ui_to_bytes($max_file_size_ui);
		
		$pre_inline_js = '
						//$(window).load(function () {
						$(document).ready(function() {
							$(\'a.fancy_image\').fancybox({
								\'transitionIn\'	:	\'elastic\',
								\'transitionOut\'	:	\'elastic\',
								\'speedIn\'			:	400, 
								\'speedOut\'		:	200, 
								\'overlayShow\'		:	true
							});
						});
		';
		
		$this->inline_js = str_replace($pre_inline_js,'',$this->inline_js);
		$this->inline_js .= $pre_inline_js;

		$uploader_display_none 	= empty($value) ? "" : "display:none;";
		$file_display_none  	= empty($value) ?  "display:none;" : "";
		
		$is_image = !empty($value) && 
						( substr($value,-4) == '.jpg' 
								|| substr($value,-4) == '.png' 
								|| substr($value,-5) == '.jpeg' 
								|| substr($value,-4) == '.gif' 
								|| substr($value,-5) == '.tiff')
					? true : false;
		
		$image_class = $is_image ? 'image-thumbnail' : '';
		
		$input = '	
		
						<!-- COMPONENTE LOADER IMAGE -->
						<form action="../../uploadFile/ajax" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" ></form>				
						<form id="file_uploader_'.$unique.'" action="../../uploadFile/ajax" method="post" enctype="multipart/form-data" class="component clearfix"
									 target="upload_target" onsubmit="startUpload(\''.$unique.'\'); return;" >
								<fieldset class="comp-loader-img">
								<label for="" class="bit tagline">Nombre campo <br /><span>campo requerido</span></label>

								<div class="bit">
									<div class="comp-box file-wrapper">
										<input type="text" name="fileText" readonly="readonly" />
										<input type="file" name="file" />
										<input name="prefix" type="hidden" value="'.$field_info->prefix.'" />
										<input name="idContent" type="hidden" value="'.$unique.'" />
									</div>

									<a id="uploader_button_'.$unique.'" class="button" onclick="$(\'#file_uploader_'.$unique.'\').submit();">Cargar</a>
									
									<p class="label">notas lorem ipsum dolor hjvjhvhv</p>
									<img id="upload_process_'.$unique.'" class="loader hidden" src="'.base_url().'/images/ajax-loader-bar.gif" width="100%"/>

									<div class="comp-box img-holder" id="upload_image_'.$unique.'" >
										<a href="'.base_url().$this->CI->config->item('upload_folder_url').$field_info->prefix.$field_info->data.'" 
																				id="fancy_image_'.$unique.'" class="fancy_image">
											<img alt="imagen" id="uploaded_image_'.$unique.'" width="100%"
														src="'.base_url().$this->CI->config->item('upload_folder_url').$field_info->prefix.$field_info->data.'">
										</a>	
									</div>
								</div>
									
								<span class="helper"></span>
							</fieldset>
							<iframe id="upload_target" name="upload_target" src="#" class="hidden" ></iframe>
						</form>
						
					<!--
					<div class="">
		
						<form action="../../uploadFile/ajax" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" ></form>				
						<form id="file_uploader_'.$unique.'" action="../../uploadFile/ajax" method="post" enctype="multipart/form-data" 
									 target="upload_target" onsubmit="startUpload(\''.$unique.'\'); return;" >
							<fieldset>	
								<input name="file" type="file" />
								<input name="idContent" type="hidden" value="'.$unique.'" />
								<input name="prefix" type="hidden" value="'.$field_info->prefix.'" />
								<a id="uploader_button_'.$unique.'" class="button" onclick="$(\'#file_uploader_'.$unique.'\').submit();">Cargar</a>
								<input type="hidden" name="idContent" value="'.$unique.'"/>
								<p id="upload_process_'.$unique.'"  style="width:280px; display:none;">
									<img src="'.base_url().'/images/ajax-loader-bar.gif" width="100%"/>
								</p>
								<p class="">
									'.$field_info->postfix.'
								</p>
								<p id="upload_image_'.$unique.'" style="width:280px;">
									<a href="'.base_url().$this->CI->config->item('upload_folder_url').$field_info->prefix.$field_info->data.'" 
																			id="fancy_image_'.$unique.'" class="fancy_image">
										<img alt="imagen" id="uploaded_image_'.$unique.'" width="100%"
													src="'.base_url().$this->CI->config->item('upload_folder_url').$field_info->prefix.$field_info->data.'">
									</a>								</p>
							
							</fieldset>
							<iframe id="upload_target" name="upload_target" src="#" style="display:none;" ></iframe>
						</form>
					</div>
					 -->
					
';
		
		
		return $input;
	}
	
	
	
		
	protected function get_uploadfile_input($field_info, $value)
	{

		if (!in_array($this->default_css_path.'/fileupload.css',$this->css))
		array_push($this->css,$this->default_css_path.'/fileupload.css');

		if (!in_array($this->default_js_path.'/fileupload.js',$this->js))
		array_push($this->js,$this->default_js_path.'/fileupload.js');
		
		$unique = uniqid();
		$unique = md5($field_info->dataId);
		
		$allowed_files = $field_info->upload_file_types;
		$allowed_files_ui = '.'.str_replace('|',',.',$allowed_files);
		$max_file_size_ui = $field_info->maxLength;
		$max_file_size_bytes = $this->_convert_bytes_ui_to_bytes($max_file_size_ui);

		$uploader_display_none 	= empty($value) ? "" : "display:none;";
		$file_display_none  	= empty($value) ?  "display:none;" : "";
		
		$is_image = !empty($value) && 
						( substr($value,-4) == '.jpg' 
								|| substr($value,-4) == '.png' 
								|| substr($value,-5) == '.jpeg' 
								|| substr($value,-4) == '.gif' 
								|| substr($value,-5) == '.tiff')
					? true : false;
		
		$extension  = '';
		$file = explode('.',$field_info->data);
		if(count($file)>1)
			$extension = $file[count($file)-1];
		$image = 'extension_file_'.$extension.'.png';
		$image_path = 'images/icons/';
		if(strlen($extension)>0)	
			$img_icon = '<img src="'.base_url().$image_path.(file_exists($image_path.$image)?$image:'white_page.png').'">';
		else
			$img_icon = '<img src="'.base_url().$image_path.'white_page.png">';
			
		$input = '	
		
					<div class="">
		
						<form action="../../uploadFile/ajax" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" ></form>				
						<form id="file_uploader_'.$unique.'" action="../../uploadFile/ajax" method="post" enctype="multipart/form-data" 
									 target="upload_target" onsubmit="startUpload(\''.$unique.'\'); return;" >
							<fieldset>	
								<input name="file" type="file" />
								<input name="idContent" type="hidden" value="'.$unique.'" />
								<input name="prefix" type="hidden" value="'.$field_info->prefix.'" />
								<a id="uploader_button_'.$unique.'" class="button" onclick="$(\'#file_uploader_'.$unique.'\').submit();">Cargar</a>
								<input type="hidden" name="idContent" value="'.$unique.'"/>
								<p id="upload_process_'.$unique.'"  style="width:280px; display:none;">
									<img src="'.base_url().'/images/ajax-loader-bar.gif" width="100%"/>
								</p>
								<div class="">
									'.$img_icon.'
									<p class="">
										Tipo de archivo: <span id="file_type_'.$unique.'">'.strtoupper($extension).'</span>								
									</p>
									<p class="">
										Ult. Actualización: <span id="file_last_update_'.$field_info->updateDate.'"></span>								
									</p>
							
							</fieldset>
							<iframe id="upload_target" name="upload_target" src="#" style="display:none;" ></iframe>
						</form>
					</div>
					 
					
';
		
		
		return $input;
	}
	
	
	protected function _convert_bytes_ui_to_bytes($bytes_ui)
	{
		$bytes_ui = str_replace(' ','',$bytes_ui);
		if(strstr($bytes_ui,'MB'))
			$bytes = (int)(str_replace('MB','',$bytes_ui))*1024*1024;
		elseif(strstr($bytes_ui,'KB'))
			$bytes = (int)(str_replace('KB','',$bytes_ui))*1024;
		elseif(strstr($bytes_ui,'B'))
			$bytes = (int)(str_replace('B','',$bytes_ui));
		else 
			$bytes = (int)($bytes_ui);
			
		return $bytes;
	}
		
		
	/**
	 * 
	 * Just an alias to get_lang_string method 
	 * @param string $handle
	 */
	public function lang($handle)
	{
		//return 'spanish';
		return $this->get_lang_string($handle);
	}
	
	/**
	 * 
	 * Get the language string of the inserted string handle
	 * @param string $handle
	 */
	public function get_lang_string($handle)
	{
		return $this->lang_strings[$handle];
	}
	
	/**
	 * 
	 * Simply set the language
	 * @example english
	 * @param string $language
	 */
	public function set_language($language)
	{
		$this->language = $language;
		
		return $this;
	}
	
    protected function _unique_field_name($field_name)
    {
    	return 's'.substr(md5($field_name),0,8); //This s is because is better for a string to begin with a letter and not a number
    }    
    
	
	protected function getListUrl()
	{
		return $this->state_url('');
	}

	protected function getAjaxListUrl()
	{
		return $this->state_url('ajax_list');
	}

	protected function getExportToExcelUrl()
	{
		return $this->state_url('export');
	}
	
	protected function getPrintUrl()
	{
		return $this->state_url('print');
	}	
	
	protected function getAjaxListInfoUrl()
	{
		return $this->state_url('ajax_list_info');
	}
	
	protected function getAddUrl()
	{
		return $this->state_url('add');
	}
	
	protected function getInsertUrl()
	{
		return $this->state_url('insert');
	}
	
	protected function getValidationInsertUrl()
	{
		return $this->state_url('insert_validation');
	}
	
	protected function getValidationUpdateUrl($primary_key = null)
	{
		if($primary_key === null)
			return $this->state_url('update_validation');
		else
			return $this->state_url('update_validation/'.$primary_key);
	}	

	protected function getEditUrl($primary_key = null)
	{
		if($primary_key === null)
			return $this->state_url('edit');
		else
			return $this->state_url('edit/'.$primary_key);
	}
	
	protected function getUpdateUrl($state_info)
	{		
		return $this->state_url('update/'.$state_info->primary_key);
	}	
	
	protected function getDeleteUrl($state_info = null)
	{
		if(empty($state_info))
			return $this->state_url('delete');
		else
			return $this->state_url('delete/'.$state_info->primary_key);
	}

	protected function getListSuccessUrl($primary_key = null)
	{
		if(empty($primary_key))
			return $this->state_url('success');
		else
			return $this->state_url('success/'.$primary_key);
	}	
	
	protected function getUploadUrl($field_name)
	{		
		return $this->state_url('upload_file/'.$field_name);
	}	

	protected function getFileDeleteUrl($field_name)
	{
		return $this->state_url('delete_file/'.$field_name);
	}	


	protected function getAjaxRelationUrl()
	{
		return $this->state_url('ajax_relation');
	}
	
	protected function getAjaxRelationManytoManyUrl()
	{
		return $this->state_url('ajax_relation_n_n');
	}	
	
	public function getStateInfo()
	{
		$state_code = $this->getStateCode();
		$segment_object = $this->get_state_info_from_url();
		
		$first_parameter = $segment_object->first_parameter;
		$second_parameter = $segment_object->second_parameter;
		
		$state_info = (object)array();
		
		switch ($state_code) {
			case 1:
			case 2:
				
			break;		
			
			case 3:
				if($first_parameter !== null)
				{
					$state_info = (object)array('primary_key' => $first_parameter);
				}	
				else
				{
					throw new Exception('On the state "edit" the Primary key cannot be null', 6);
					die();
				}
			break;
			
			case 4:
				if($first_parameter !== null)
				{
					$state_info = (object)array('primary_key' => $first_parameter);
				}	
				else
				{
					throw new Exception('On the state "delete" the Primary key cannot be null',7);
					die();
				}
			break;
			
			case 5:
				if(!empty($_POST))
				{
					$state_info = (object)array('unwrapped_data' => $_POST);
				}
				else
				{
					throw new Exception('On the state "insert" you must have post data',8);
					die();
				}
			break;
			
			case 6:
				if(!empty($_POST) && $first_parameter !== null)
				{
					$state_info = (object)array('primary_key' => $first_parameter,'unwrapped_data' => $_POST);
				}
				elseif(empty($_POST))
				{
					throw new Exception('On the state "update" you must have post data',9);
					die();
				}
				else
				{
					throw new Exception('On the state "update" the Primary key cannot be null',10);
					die();
				}
			break;
			
			case 7:
			case 8:
			case 16: //export to excel
			case 17: //print
				$state_info = (object)array();
				if(!empty($_POST['per_page']))
				{
					$state_info->per_page = is_numeric($_POST['per_page']) ? $_POST['per_page'] : null;
				}
				if(!empty($_POST['page']))
				{
					$state_info->page = is_numeric($_POST['page']) ? $_POST['page'] : null;
				}
				//If we request an export or a print we don't care about what page we are
				if($state_code === 16 || $state_code === 17)
				{
					$state_info->page = 1;
					$state_info->per_page = 1000000; //a big number
				}
				if(!empty($_POST['order_by'][0]))
				{
					$state_info->order_by = $_POST['order_by'];
				}
				if(!empty($_POST['search_text']))
				{
					if(empty($_POST['search_field']))
					{
						
						$search_text = strip_tags($_POST['search_field']);
						
						$state_info->search = (object)array( 'field' => null , 'text' => $_POST['search_text'] );
						
					}
					else 
					{
						$state_info->search	= (object)array( 'field' => strip_tags($_POST['search_field']) , 'text' => $_POST['search_text'] );
					}
				}
			break;
			
			case 9:
			case 10:
				
			break;

			case 11:
				$state_info->field_name = $first_parameter;
			break;

			case 12:
				$state_info->field_name = $first_parameter;
				$state_info->file_name = $second_parameter;
			break;

			case 13:
				$state_info->field_name = $_POST['field_name'];
				$state_info->search 	= $_POST['term'];
			break;

			case 14:
				$state_info->field_name = $_POST['field_name'];
				$state_info->search 	= $_POST['term'];
			break;

			case 15:
				$state_info = (object)array(
					'primary_key' 		=> $first_parameter,
					'success_message'	=> true
				);
			break;				
		}
		
		return $state_info;
	}
	
	
	protected function getStateCode()
	{
		$state_string = $this->get_state_info_from_url()->operation;
		
		if( $state_string != 'unknown' && in_array( $state_string, $this->states ) )
			$state_code =  array_search($state_string, $this->states);
		else
			$state_code = 0;
		
		return $state_code;
	}
	
	protected function state_url($url = '')
	{
		$this->CI =& get_instance();
		
		$segment_object = $this->get_state_info_from_url();
		$method_name = $this->get_method_name();
		$segment_position = $segment_object->segment_position;
		
		$state_url_array = array();

    if( sizeof($this->CI->uri->segments) > 0 ) {
      foreach($this->CI->uri->segments as $num => $value)
      {
        $state_url_array[$num] = $value;
        if($num == ($segment_position - 1))
          break;
      }
          
      if( $method_name == 'index' && !in_array( 'index', $state_url_array ) ) //there is a scenario that you don't have the index to your url
        $state_url_array[$num+1] = 'index';
    }
		
		$state_url = implode('/',$state_url_array).'/'.$url;
		
		return site_url($state_url);
	}
	
	protected function get_state_info_from_url()
	{
		$ci = &get_instance();
		
		$segment_position = count($ci->uri->segments) + 1;
		$operation = 'list';
		
		$segements = $ci->uri->segments;
		foreach($segements as $num => $value)
		{
			if($value != 'unknown' && in_array($value, $this->states))
			{
				$segment_position = (int)$num;
				$operation = $value; //I don't have a "break" here because I want to ensure that is the LAST segment with name that is in the array.
			}
		}
		
		$function_name = $this->get_method_name();
		
		if($function_name == 'index' && !in_array('index',$ci->uri->segments))
			$segment_position++;
		
		$first_parameter = isset($segements[$segment_position+1]) ? $segements[$segment_position+1] : null;
		$second_parameter = isset($segements[$segment_position+2]) ? $segements[$segment_position+2] : null;		
		
		return (object)array('segment_position' => $segment_position, 'operation' => $operation, 'first_parameter' => $first_parameter, 'second_parameter' => $second_parameter);
	}
	
	
	
	protected function get_method_name()
	{
		$ci = &get_instance();		
		return $ci->router->method;
	}
	
	protected function get_controller_name()
	{
		$ci = &get_instance();		
		return $ci->router->class;
	}	
	
	/**
	 * 
	 * Load the language strings array from the language file
	 */
	protected function _load_language()
	{
		if($this->language === null)
		{
			$this->language = strtolower($this->config->default_language);
		}
		include($this->default_language_path.'/'.$this->language.'.php');
		
		foreach($lang as $handle => $lang_string)
			if(!isset($this->lang_strings[$handle]))
				$this->lang_strings[$handle] = $lang_string;
		
		$this->default_true_false_text = array( $this->lang('form_inactive') , $this->lang('form_active'));
		//$this->subject = $this->subject === null ? $this->lang('list_record') : $this->subject;		
		
	}
	
	
	protected function _initialize_helpers()
	{
		$this->CI =& get_instance();
		
		$this->CI->load->helper('url');
		$this->CI->load->helper('form');
	}
	
	protected function _initialize_variables()
	{
		$ci = &get_instance();
		$ci->load->config('grocery_crud');
		
		$this->config = (object)array();
		
		/** Initialize all the config variables into this object */
		$this->config->default_language 	= $ci->config->item('grocery_crud_default_language');
		$this->config->date_format 			= $ci->config->item('grocery_crud_date_format');
		$this->config->default_per_page		= $ci->config->item('grocery_crud_default_per_page');
		$this->config->file_upload_allow_file_types	= $ci->config->item('grocery_crud_file_upload_allow_file_types');
		$this->config->file_upload_max_file_size	= $ci->config->item('grocery_crud_file_upload_max_file_size');
		$this->config->default_text_editor	= $ci->config->item('grocery_crud_default_text_editor');
		$this->config->text_editor_type		= $ci->config->item('grocery_crud_text_editor_type');
		$this->config->character_limiter	= $ci->config->item('grocery_crud_character_limiter');
		
		/** Initialize default paths */
		$this->default_javascript_path				= $this->default_assets_path.'/js';

		$this->default_texteditor_path 				= $this->default_assets_path.'/texteditor';
		$this->default_theme_path					= $this->default_assets_path.'/themes';
	
		
		$this->character_limiter = $this->config->character_limiter;
		
		if($this->character_limiter === 0 || $this->character_limiter === '0')
		{
			$this->character_limiter = 1000000; //a big number
		}
		elseif($this->character_limiter === null || $this->character_limiter === false)
		{
			$this->character_limiter = 30; //is better to have the number 30 rather than the 0 value
		}
	}
	
}
?>