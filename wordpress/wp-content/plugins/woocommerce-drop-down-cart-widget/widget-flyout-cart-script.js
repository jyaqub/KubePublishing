jQuery(document).ready(function(){

    jQuery(".dropdowncartcontents").hide();
	jQuery(".dropdowncarttrigger").show();
	
	jQuery('.dropdowncarttrigger').click(function(e){
		e.preventDefault();
		jQuery(".dropdowncartcontents").slideToggle();
	});

});