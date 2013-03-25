// JavaScript Document

function saveDataForm(){
	 var datas=id_fields.split(",");
	 var dataform = new Object(); 
	 for(var i=0; i<datas.length; i++){
	 	dataform[i] = new Object(); 
	 	dataform[i].dataId = datas[i]; 
	 }
	 for(var i=0; i<datas.length; i++)
	 	dataform[i].dataValue = $('#field-data-'+datas[i]).val(); 
	 for(var i=0; i<datas.length; i++){
	 	if(!($('#uniform-field-data-'+datas[i]+'-false .checked #field-data-'+datas[i]+'-false').val() === undefined))
	 		dataform[i].dataValue = $('#uniform-field-data-'+datas[i]+'-false .checked #field-data-'+datas[i]+'-false').val(); 
	 	if(!($('#uniform-field-data-'+datas[i]+'-true .checked #field-data-'+datas[i]+'-true').val() === undefined))
	 		dataform[i].dataValue = $('#uniform-field-data-'+datas[i]+'-true .checked #field-data-'+datas[i]+'-true').val(); 
	 }

	$.post($('#base-url').val()+"index.php/Cms/Content/Publish/savePublishData", 
				{	'data': 	JSON.stringify(dataform)	}, 
				function(data){
					var response = $.parseJSON(data);
					createGrowl('Operación terminada', response.message, false);
				}
	);
		
}