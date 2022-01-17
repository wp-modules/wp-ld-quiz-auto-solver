(function( $ ) {
	'use strict';
	let observer = new MutationObserver(function( mutations ) {
		mutations.forEach(function( mutation ) {
			let elm = $(mutation.target)
			if(elm.hasClass("ui-sortable")){
				elm.html('')
			}
		});    
	});
	$(function() {
		let questions = $('.wpProQuiz_questionList');
		let question_ids = [];
		$.each(questions, function(index, question){
			question_ids.push($(question).data('question_id'));
		})
		let quiz_details = $('.wpProQuiz_content').data('quiz-meta');
		console.log(question_ids);
		let data = {
			question_ids,
			quiz_details,
			security: wlqab_show_ans_obj.security,
			action: 'wlqab_get_answers',
		};
		let ajax_url = wlqab_show_ans_obj.ajax_url;
		$.post(ajax_url, data, function(response) {
			if(response.status === 1){
				let data_obj = response.data;
				question_ids.forEach(function(question_id){
					/**
					 * Single Choice and Multi Choice - single|multiple
					 */
					if(data_obj[question_id].type == 'single' || data_obj[question_id].type == 'multiple'){
						let correct_ans = data_obj[question_id].correct;
						correct_ans.forEach(function(correct, index){
							if(correct){
								$(`.wpProQuiz_questionList[data-question_id="${question_id}"] .wpProQuiz_questionListItem[data-pos="${index}"] .wpProQuiz_questionInput`).prop("checked", true);		
							}
						});
						return
					}

					/**
					 * Fill in the blank - cloze_answer
					 */
					 if(data_obj[question_id].type == 'cloze_answer'){
						data_obj[question_id].correct.forEach((answers, index) => {
							let answer = answers[Math.floor(Math.random() * answers.length)]
							$(`.wpProQuiz_questionList[data-question_id="${question_id}"] .wpProQuiz_cloze:eq(${index}) input`).val(answer)
						})	
						return
					}

					/**
					 * Matrix Sort - matrix_sort_answer
					 */
					if(data_obj[question_id].type == 'matrix_sort_answer'){
						$(`.wpProQuiz_questionList[data-question_id="${question_id}"] .wpProQuiz_questionListItem`).each(function(idx){
							let ansElm = $(this).parents('.wpProQuiz_question').find(`.wpProQuiz_sortStringList .wpProQuiz_sortStringItem[data-pos="${idx}"]`)
							let ansHtml = ansElm[0].outerHTML
							$(this).find('.wpProQuiz_maxtrixSortCriterion').html(ansHtml)
						})
						let observerDOM = $(`.wpProQuiz_questionList[data-question_id="${question_id}"]`).parents('.wpProQuiz_question').find('.wpProQuiz_sortStringList');
						let config = { 
							attributes: true,
							attributeOldValue: true,
							attributeNewValue: true,
						};
						observer.observe(observerDOM[0], config);
						return
					}

					/**
					 * Sort answer - sort_answer
					 */
					 if(data_obj[question_id].type == 'sort_answer'){
						console.log(data_obj)
						let items = $(`.wpProQuiz_questionList[data-question_id="${question_id}"]`)
						let final = ''
						let correct_ans = data_obj[question_id].correct;
						correct_ans.forEach(function(correct, index){
							final += $(`.wpProQuiz_questionListItem[data-pos="${correct}"]`)[0].outerHTML
						});
						$(`.wpProQuiz_questionList[data-question_id="${question_id}"]`).html(final)
						return
					}

					console.log(data_obj[question_id])
				});
			} else {
				console.log("Show answers failing with some issue");
			}
		});
		// })
	});
})( jQuery );