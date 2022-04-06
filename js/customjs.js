jQuery(document).ready(function(){
	// alert();
});

jQuery(document).on("click","#btnsubmit",function(e){
	// e.preventDefault();
	if(jQuery('#title').val() && jQuery('.postdesc').val() && jQuery('.postcategory').val()!=0 ){
		jQuery.ajax({
			type: "post",
			dataType : "json",
			url : ajaxurl,
			data : {action: "dsp_data"},
			success: function(response){
				// alert ("111222");	
			},
		});
		window.location.href = 'https://pbsolution.in/';

	}
	else
	{
		alert ("can't publish");
	}
});