jQuery(document).ready(function() {
	
	

	/* Toggle admin screen headings */
	if ( jQuery('div.linkpro-admin h3').length <= 3) {
		jQuery('div.linkpro-admin h3:first').addClass('selected');
		jQuery('div.linkpro-admin h3:first').next('table.form-table, .upadmin-panel').show();
		jQuery('table[data-type=conditional]').hide();
		jQuery('table[rel=' + jQuery('#badge_method').val() + ']').show();
		jQuery(".linkpro-admin select").removeClass("chzn-done").css('display', 'inline').data('chosen', null);
		jQuery(".linkpro-admin *[class*=chzn], .linkpro-admin .chosen-container").remove();
		jQuery(".linkpro-admin .chosen-select").chosen({
			disable_search_threshold: 10
		});
	}
	
	/* Expand table under h3 */
	jQuery(document).on('click', 'div.linkpro-admin h3:not(.selected)', function(){
		jQuery(this).addClass('selected');
		jQuery(this).next('table.form-table, .upadmin-panel').show();
		jQuery('table[data-type=conditional]').hide();
		jQuery('table[rel=' + jQuery('#badge_method').val() + ']').show();
		jQuery(".linkpro-admin select").removeClass("chzn-done").css('display', 'inline').data('chosen', null);
		jQuery(".linkpro-admin *[class*=chzn], .linkpro-admin .chosen-container").remove();
		jQuery(".linkpro-admin .chosen-select").chosen({
			disable_search_threshold: 10
		});
	});
	
	/* Collapse table under h3 */
	jQuery(document).on('click', 'div.linkpro-admin h3.selected', function(){
		jQuery(this).removeClass('selected');
		jQuery(this).next('table.form-table, .upadmin-panel').hide();
	});
	
	

	
	/* chosen select */
	jQuery(".chosen-select").chosen({
		disable_search_threshold: 10
	});
	
	/* Setup field options (multi choice) */
	jQuery(document).on('change', '#upadmin_n_type', function(e){
		var type = jQuery(this).val();
		if( type == 'select' || type == 'multiselect' || type == 'radio' || type == 'radio-full' || type == 'checkbox' || type == 'checkbox-full' ) {
			jQuery('.choicebased').show();
		} else {
			jQuery('.choicebased').hide();
		}
		if ( type == 'file' ) {
			jQuery('.filetypes').show();
		} else {
			jQuery('.filetypes').hide();
		}
	});
	
	/* Custom input show/hide */
	if (jQuery('#dashboard_redirect_users').val()==2){
		jQuery('#dashboard_redirect_users').parents('td').find('.linkpro-admin-hide-input').css({'display':'block'});
	}
	if (jQuery('#profile_redirect_users').val()==2){
		jQuery('#profile_redirect_users').parents('td').find('.linkpro-admin-hide-input').css({'display':'block'});
	}
	if (jQuery('#register_redirect_users').val()==2){
		jQuery('#register_redirect_users').parents('td').find('.linkpro-admin-hide-input').css({'display':'block'});
	}
	if (jQuery('#login_redirect_users').val()==2){
		jQuery('#login_redirect_users').parents('td').find('.linkpro-admin-hide-input').css({'display':'block'});
	}
	jQuery('#dashboard_redirect_users,#profile_redirect_users,#register_redirect_users,#login_redirect_users').change(function(){
		if (jQuery(this).val() == 2) {
			jQuery(this).parents('td').find('.linkpro-admin-hide-input').css({'display':'block'});
		} else {
			jQuery(this).parents('td').find('.linkpro-admin-hide-input').css({'display':'none'});
		}
	});
	

	

	


	


	/* toggle/un-toggle field groups */
	jQuery(document).on('click', '.upadmin-icon-abs a.max', function(e){
		var tpl = jQuery(this).parents('.upadmin-tpl');
		tpl.find('.upadmin-tpl-body').removeClass('max').addClass('min');
		tpl.find('.upadmin-tpl-head').removeClass('max').addClass('min');
		jQuery(this).removeClass('max').addClass('min');
	});
	
	jQuery(document).on('click', '.upadmin-icon-abs a.min', function(e){
		var tpl = jQuery(this).parents('.upadmin-tpl');
		tpl.find('.upadmin-tpl-body').removeClass('min').addClass('max');
		tpl.find('.upadmin-tpl-head').removeClass('min').addClass('max');
		jQuery(this).removeClass('min').addClass('max');
	});
	
	/* cancel new field div */
	jQuery(document).on('click', '#upadmin_n_cancel', function(e){
		e.preventDefault();
		var new_field = jQuery('.upadmin-new');
		new_field.hide();
		return false;
	});
	
	

	
	
	/* Publish new field */
	jQuery(document).on('submit', '.upadmin-new form', function(e){
		e.preventDefault();
		form = jQuery(this);
		form.find('span.error-text').remove();
		form.find('input').removeClass('error');
		form.parents('.upadmin-fieldlist').find('.upadmin-loader').addClass('loading');
		jQuery.ajax({
			url: ajaxurl,
			data: form.serialize() + '&action=linkpro_create_field',
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				form.parents('.upadmin-fieldlist').find('.upadmin-loader').removeClass('loading');
				if (data.error){
					jQuery.each( data.error, function(i, v) {
						jQuery('#'+i).addClass('error').focus().after('<span class="error-text">'+v+'</span>');
					});
				} else {
					form.find('input').removeClass('error');
					jQuery('ul#upadmin-sortable-fields').prepend( data.html );
					jQuery('span.upadmin-ajax-fieldcount').html( data.count );
				}
			}
		});
		return false;
	});
	

	
	/* Save forms */
	jQuery(document).on('click', '.upadmin-tpl a.saveform', function(e){
		form = jQuery(this).parents('.upadmin-tpl').find('form');
		form.trigger('submit');
	});
	
	jQuery(document).on('submit', '.upadmin-tpl form', function(e){
		e.preventDefault();
		form = jQuery(this);
		var role = jQuery(this).data('role');
		var group = jQuery(this).data('group');

		form.find('.upadmin-tpl-head').append('<img src="'+form.data('loading')+'" alt="" class="upadmin-miniload" />');
		
		jQuery.ajax({
			url: ajaxurl,
			data: form.serialize() + '&action=linkpro_save_group&role='+role+'&group='+group,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				form.find('.upadmin-miniload').remove();
			}
		});
		return false;
	});


	
	
	
});