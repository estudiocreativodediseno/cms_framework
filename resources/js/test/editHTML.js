// JavaScript Document
	
	
	var GBL_FOCUS_LINE = undefined;
	var tiny = undefined;
	
	$(document).ready(function() {
		enableEditable();
		
		$("#cmbVersions").chosen({
			allow_single_deselect:true,
			no_results_text: "No se encontraron resultados"
		}); 
			
	});
	
	function enableSortable(){
		$( "#line-codes" ).sortable();
	}
	
	function disableSortable(){
		$( "#line-codes" ).sortable({ disabled: true });
	}
	
	
	
	
	function enableEditable(){
		divEditable(".editable-line");
		$('pre').litelighter();
	}
	
	function divEditable(div){
		  $(div)
			.on('click',function(e){ 
				$(this).attr('contenteditable','true');
				$(this).addClass('selected');
				$(this).focus();
			}).on('blur',function(e){ 
				$(this).removeClass('selected');
			}).on('keypress',function(e){ 
				if (e.which == 13) 
					addDivs(' ', this.id);
				return e.which != 13; 
			}).on('focus',function() {
				  GBL_FOCUS_LINE = this.id;
			});
	
	}
	
	function addFunction(opt,isS,isF){
		
		if(isS+isF >= 0){
			tiny = TINY.box.show({ 													
				url:'../getShortcutInfo', 													
				post:'shortcutId='+opt, 													
				width:800, 													
				height:600, 													
				opacity:80, 													
				topsplit:3,
				openjs:function(){
					$('.shortcut-pre').litelighter();
					$("#cmbStructureId").change(function() {
						getDataTypes();
					});
				}								
			});
		}else{
			data 			= new Object();
			data.shortcutId = opt;
			$.post($('#base-url').val()+"index.php/Cms/Content/TemplateView/getShortcut", 
						{	'data': 	JSON.stringify(data)	}, 
						function(data){
							var response = $.parseJSON(data);
							for (var i=0; i<response.lineCodes.length; i++)
								addDivs(response.lineCodes[i]+'\n');
							createGrowl('Comando agregado', response.message, false);
							
						}
			);
		}
	}
	
	
	
	function viewVersionCode(sectionId){
		tiny = TINY.box.show({ 													
			url:'../getVersionViewCode', 													
			post:'version='+$('#cmbVersions').val()+"&sectionId="+sectionId, 													
			width:800, 													
			height:600, 													
			opacity:80, 													
			topsplit:3,
			openjs:function(){
					$('.shortcut-pre').litelighter();
			}								
		});
	}
	
	function addDivs(val, elementId){
		if(elementId === undefined)
			elementId = GBL_FOCUS_LINE;
		
		if(!(elementId === undefined)){
			var newDiv = 'line-'+new Date().getTime();
			$('#'+elementId).after('<div id="'+newDiv+'" class="editable-line new-editable-line added" contenteditable="true"><code><pre>'+val+'</pre></code></div>');
			$('#'+elementId+'-delete').after('<div id="'+newDiv+'" class="editable-line new-delete-line added"><code><pre> </pre></code></div>');
			divEditable('#'+newDiv);
			var newLine = parseInt($('#line-numbers div').last().text())+1;
			$('#line-numbers div').last().after('<div class="line-number"><code><pre class="pre-line">'+newLine+'</pre></code></div>');
			$('#'+newDiv+' pre').litelighter();
			//$('#'+elementId).next().find('code').find('pre').focus();
			$('#'+elementId).next().children().children().focus();
		}
	}
	
	function removeLine(line){
		if(!$('#line-'+line).hasClass('deleted'))
			$('#line-'+line).addClass('deleted');
		else
			$('#line-'+line).removeClass('deleted');
	}
	
	function save(sectionId, file){
		var data = new Object();
		$( ".editable-line" ).each(function( index ) {
			if($(this).attr('class').indexOf("deleted") === -1){
				$pre = $(this).find('pre');
				data[index] = new Object();
				data[index].text = $pre.text();
				data[index].class = $(this).attr('class');
				}
		});
		data.file 		= file;
		data.sectionId 	= sectionId;
		
		
		$.post($('#base-url').val()+"index.php/Cms/Content/TemplateView/saveTemplate", 
					{	'data': 	JSON.stringify(data)	}, 
					function(data){
						var response = $.parseJSON(data);
						createGrowl('Operación terminada', response.message, false);
					}
		);
	}
	
	
	function publish(sectionId){
		var data = new Object();
		data.sectionId 	= sectionId;
		
		$.post($('#base-url').val()+"index.php/Cms/Content/TemplateView/publishTemplate", 
					{	'data': 	JSON.stringify(data)	}, 
					function(data){
						var response = $.parseJSON(data);
						createGrowl('Operación terminada', response.message, false);
					}
		);
	}
	
	
	function getDataTypes(){
		var $subDataType = $("#cmbDataType");
		$subDataType.empty();
	
		
		$.post($('#base-url').val()+"index.php/Cms/Content/TemplateView/getDataTypes", 
					{	'structureId': 	$("#cmbStructureId").val()	}, 
					function(data){
						var response = $.parseJSON(data);
						for(var  i=0; i<response.length; i++)
							$subDataType.append($('<option></option>').attr("value", response[i].id).text(response[i].name));
						
					}
		);
	}
	
	function addShortcutCode(){
		
			if($('#cmbStructureId').val().length==0 && $('#isStructure').val()==1){
				alert("Seleccione la estructura a insertar");
				return;
			}
			
			$($( "#sc-line-codes input" ).get().reverse()).each(function( index ) {
				var line 	=	$(this).val().replace('%structureId%',$('#cmbStructureId').val()); 
				line 		=	line.replace("%fieldId%",$('#cmbDataType').val()); 
				addDivs(line+'\n');
			});		
			
			tiny = TINY.box.hide();
			createGrowl('Comando agregado', '   ', false);
			
	}