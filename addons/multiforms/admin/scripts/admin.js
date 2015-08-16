jQuery(document).ready(function() {

	/* Create a process */
	jQuery(document).on('click', '.linkpro_mu_create', function(e){
		e.preventDefault();
		elem = jQuery(this);
		name = jQuery('#linkpro_mu_name').val();
		form = jQuery('#results-wrap').find('form');
		if (name != ''){
			jQuery('img.upadmin-load-inline').show();
			jQuery.ajax({
				url: ajaxurl,
				data: form.serialize() + '&action=linkpro_mu_create&name='+name,
				dataType: 'JSON',
				type: 'POST',
				success:function(data){
					jQuery('img.upadmin-load-inline').hide();
					jQuery('#results-wrap').empty().html( data.result );
					elem.removeClass('linkpro_mu_create').addClass('linkpro_mu_start').html( elem.data('create') );
					jQuery('#linkpro_mu_name').val('');
				}
			});
		}
		return false;
	});
	
	/* Start a process */
	jQuery(document).on('click', '.linkpro_mu_start', function(e){
		e.preventDefault();
		elem = jQuery(this);
		if (jQuery('#linkpro_mu_name').val() != ''){
			jQuery('img.upadmin-load-inline').show();
			jQuery.ajax({
				url: ajaxurl,
				data: 'action=linkpro_mu_getfields',
				dataType: 'JSON',
				type: 'POST',
				success:function(data){
					jQuery('img.upadmin-load-inline').hide();
					elem.addClass('linkpro_mu_create').removeClass('linkpro_mu_start').html( elem.data('ready') );
					jQuery('#results-wrap').html(data.res).slideDown();
				}
			});
		} else {
			jQuery('#linkpro_mu_name').focus();
		}
		return false;
	});
	
});