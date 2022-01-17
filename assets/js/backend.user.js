(function( $ ) {
	'use strict';
	$(function() {
		$('.wlqab_ld_show_quiz_answer_span').on('click', function(e){
			e.preventDefault();
			let currentRow= $(this)
			let data = $(this).data();
			let ajax_url = wlqab_users_obj.ajax_url;
			$.post(ajax_url, data, function(response) {
				if(response.status === 1){
					if(currentRow.hasClass('active')) {
						currentRow.find('.wlqab_display_text').text('Show Quiz Answers')
					} else {
						currentRow.find('.wlqab_display_text').text('Hide Quiz Answers')
					}
					currentRow.toggleClass("active")
				} else {
					alert("Somthing went wrong");
				}
			});
		})
	});
})( jQuery );