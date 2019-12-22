//Dynamic Form Selection
jQuery(document).ready(function($) {

	/*Disable Radio*/
	$('.atoz_codecleaner-disable-select').on('change', function(ev) {
		if($(this).val() == 'everywhere') {
			$(this).closest('.atoz_codecleaner-deep-cleaning-controls').find('.atoz_codecleaner-deep-cleaning-enable').show();
		}
		else {
			$(this).closest('.atoz_codecleaner-deep-cleaning-controls').find('.atoz_codecleaner-deep-cleaning-enable').hide();
		}
	});	

	/*Script Status*/
	$('.atoz_codecleaner-deep-cleaning-status .atoz_codecleaner-status-select').on('change', function(ev) {
		if($(this).children(':selected').val() == 'enabled') {
			$(this).removeClass('disabled');
			$(this).closest('tr').find('.atoz_codecleaner-deep-cleaning-controls').hide();
		}
		else {
			$(this).addClass('disabled');
			$(this).closest('tr').find('.atoz_codecleaner-deep-cleaning-controls').show();
		}
	});
	$('.atoz_codecleaner-deep-cleaning-status .atoz_codecleaner-status-toggle').on('change', function(ev) {
		if($(this).is(':checked')) {
			$(this).closest('tr').find('.atoz_codecleaner-deep-cleaning-controls').show();
		}
		else {
			$(this).closest('tr').find('.atoz_codecleaner-deep-cleaning-controls').hide();
		}
	});
	
	/*Group Status*/
	$('.atoz_codecleaner-deep-cleaning-group-status .atoz_codecleaner-status-select').on('change', function(ev) {
		if($(this).children(':selected').val() == 'enabled') {
			$(this).removeClass('disabled');
			$(this).closest('.atoz_codecleaner-deep-cleaning-group').find('.atoz_codecleaner-deep-cleaning-section .atoz_codecleaner-deep-cleaning-assets-disabled').hide();
			$(this).closest('.atoz_codecleaner-deep-cleaning-group').find('.atoz_codecleaner-deep-cleaning-section table').show();
		}
		else {
			$(this).addClass('disabled');
			$(this).closest('.atoz_codecleaner-deep-cleaning-group').find('.atoz_codecleaner-deep-cleaning-section table').hide();
			$(this).closest('.atoz_codecleaner-deep-cleaning-group').find('.atoz_codecleaner-deep-cleaning-section .atoz_codecleaner-deep-cleaning-assets-disabled').show();
		}
	});
	$('.atoz_codecleaner-deep-cleaning-group-status .atoz_codecleaner-status-toggle').on('change', function(ev) {
		if($(this).is(':checked')) {
			$(this).closest('.atoz_codecleaner-deep-cleaning-group').find('.atoz_codecleaner-deep-cleaning-section table').hide();
			$(this).closest('.atoz_codecleaner-deep-cleaning-group').find('.atoz_codecleaner-deep-cleaning-section .atoz_codecleaner-deep-cleaning-assets-disabled').show();
		}
		else {
			$(this).closest('.atoz_codecleaner-deep-cleaning-group').find('.atoz_codecleaner-deep-cleaning-section .atoz_codecleaner-deep-cleaning-assets-disabled').hide();
			$(this).closest('.atoz_codecleaner-deep-cleaning-group').find('.atoz_codecleaner-deep-cleaning-section table').show();
		}
	});

});