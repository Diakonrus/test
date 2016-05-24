$(document).on('click','.notice_views',function(){
	$.ajax({
		url: '/site/ajax',
		type: "POST",
		dataType: "json",
		data: {'SendingMsgLog[id]':$(this).data('id')},
		success: function (response) {
			$("#block_list_view_notice").load(document.location.href+" #block_list_view_notice");
		}
	});
	return false;
});