<?php
namespace WLQAB;

class Show_LD_Answers{
    public function run(){
        add_action('wp_enqueue_scripts', array( $this, 'enqueue_answers_script' ) );
        add_action('wp_ajax_wlqab_get_answers', array( $this, 'get_answers_ajax' ) );
    }

    public function enqueue_answers_script(){
        $show_answer = get_user_meta(get_current_user_id(), WLQAB_LD_SHOW_ANSWER_USER_META, true);
        if($show_answer){
            wp_enqueue_script( 'wlqab_show_answers_js', plugin_dir_url( __FILE__ ) . "assets/js/frontend.show-answers.js", array(), "1.0.0" );
            wp_localize_script( 'wlqab_show_answers_js', 'wlqab_show_ans_obj', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'security' => wp_create_nonce( "wlqab_show_answers" ) ) );
        }
    }

    public function get_answers_ajax() {
        check_ajax_referer( 'wlqab_show_answers', 'security' );
        $quiz_pro_id = $_POST['quiz_details']['quiz_pro_id'];
        $quiz_post_id = $_POST['quiz_details']['quiz_post_id'];
		$view       = new \WpProQuiz_View_FrontQuiz();
		$quizMapper = new \WpProQuiz_Model_QuizMapper();
        $quiz       = $quizMapper->fetch( $quiz_pro_id );
		if ( $quiz_post_id !== absint( $quiz->getPostId() ) ) {
			$quiz->setPostId( $quiz_post_id );
		}
		$questionMapper = new \WpProQuiz_Model_QuestionMapper();
		$categoryMapper = new \WpProQuiz_Model_CategoryMapper();
		$questionModels = $questionMapper->fetchAll( $quiz );
		$view->quiz     = $quiz;
		$view->question = $questionModels;
		$view->category = $categoryMapper->fetchByQuiz( $quiz );
		$question_count = count( $questionModels );
		ob_start();
		$quizData = $view->showQuizBox( $question_count );
		ob_get_clean();
        $json = array_map('self::update_answer_sort_answer', $quizData['json']);
        $response = array(
            'status' => 1,
            'data'   => $json
        );
		wp_send_json( $response );
	}

    static function update_answer_sort_answer($item) {
        if($item['type'] == 'sort_answer') {
            foreach($item['correct'] as $idx => $pos) {
                $item['correct'][$idx] = \LD_QuizPro::datapos( $item['id'], $pos );
            }
        }
        return $item;
    }
}