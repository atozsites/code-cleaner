//Dynamic Form Selection
jQuery(document).ready(function($) {

	/*Disable Radio*/
	$('.atoz_codecleaner-disable-select').on('change', function(ev) {
		if($(this).val() == 'everywhere') {
			$(this).closest('.atoz_codecleaner-script-manager-controls').find('.atoz_codecleaner-script-manager-enable').show();
		}
		else {
			$(this).closest('.atoz_codecleaner-script-manager-controls').find('.atoz_codecleaner-script-manager-enable').hide();
		}
	});	

	/*Script Status*/
	$('.atoz_codecleaner-script-manager-status .atoz_codecleaner-status-select').on('change', function(ev) {
		if($(this).children(':selected').val() == 'enabled') {
			$(this).removeClass('disabled');
			$(this).closest('tr').find('.atoz_codecleaner-script-manager-controls').hide();
		}
		else {
			$(this).addClass('disabled');
			$(this).closest('tr').find('.atoz_codecleaner-script-manager-controls').show();
		}
	});
	$('.atoz_codecleaner-script-manager-status .atoz_codecleaner-status-toggle').on('change', function(ev) {
		if($(this).is(':checked')) {
			$(this).closest('tr').find('.atoz_codecleaner-script-manager-controls').show();
		}
		else {
			$(this).closest('tr').find('.atoz_codecleaner-script-manager-controls').hide();
		}
	});
	
	/*Group Status*/
	$('.atoz_codecleaner-script-manager-group-status .atoz_codecleaner-status-select').on('change', function(ev) {
		if($(this).children(':selected').val() == 'enabled') {
			$(this).removeClass('disabled');
			$(this).closest('.atoz_codecleaner-script-manager-group').find('.atoz_codecleaner-script-manager-section .atoz_codecleaner-script-manager-assets-disabled').hide();
			$(this).closest('.atoz_codecleaner-script-manager-group').find('.atoz_codecleaner-script-manager-section table').show();
		}
		else {
			$(this).addClass('disabled');
			$(this).closest('.atoz_codecleaner-script-manager-group').find('.atoz_codecleaner-script-manager-section table').hide();
			$(this).closest('.atoz_codecleaner-script-manager-group').find('.atoz_codecleaner-script-manager-section .atoz_codecleaner-script-manager-assets-disabled').show();
		}
	});
	$('.atoz_codecleaner-script-manager-group-status .atoz_codecleaner-status-toggle').on('change', function(ev) {
		if($(this).is(':checked')) {
			$(this).closest('.atoz_codecleaner-script-manager-group').find('.atoz_codecleaner-script-manager-section table').hide();
			$(this).closest('.atoz_codecleaner-script-manager-group').find('.atoz_codecleaner-script-manager-section .atoz_codecleaner-script-manager-assets-disabled').show();
		}
		else {
			$(this).closest('.atoz_codecleaner-script-manager-group').find('.atoz_codecleaner-script-manager-section .atoz_codecleaner-script-manager-assets-disabled').hide();
			$(this).closest('.atoz_codecleaner-script-manager-group').find('.atoz_codecleaner-script-manager-section table').show();
		}
	});

});