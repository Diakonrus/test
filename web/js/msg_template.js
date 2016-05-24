$(document).on('change','#msgtemplate-code',function(){
	var type = $(this).val();
	$.ajax({
		url: '/template/ajax',
		type: "POST",
		dataType: "json",
		data: {'TemplateDescription[type]':type},
		success: function (response) {
			var html = 'Возможные параметры для вставки в шаблон:<BR>';
			$('#description_template').empty().append(html + '<PRE>'+ response + '</PRE>');
		}
	});
});

$(document).on('click','#msgtemplate-users_chkbox',function(){
	if($(this).prop('checked') == true){
		//Поставили галку - блокирую селектор
		$('#msgtemplate-users').prop( "disabled", true );
	} else {
		//Снимаю блок
		$('#msgtemplate-users').removeAttr("disabled");
	}


});