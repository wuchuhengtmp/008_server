jQuery(function($){
	$('#J_upload').on('change','input[type=file]',function(){
		$(this).parents('#J_upload').prev('.inptxt').val(this.files[0].name);
	})
});
