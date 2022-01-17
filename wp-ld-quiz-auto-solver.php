<?php
/**
 * Plugin Name:     WP LearnDash Quiz Auto Solver
 * Plugin URI:      https://kt12.in/wp-learndash-quiz-auto-solver
 * Description:     This plugin helps tester and developer save time by auto-solving LearnDash Quizes. It supports all question types (except text answers). It can be operated in safe mode to prevent it from auto filling on production server.
 * Author:          Karthik Thayyil
 * Author URI:      https://kt12.in
 * Text Domain:     wp-ld-quiz-auto-solver
 * Domain Path:     /languages
 * Version:         0.5.0
 *
 * @package         Wp_Learndash_Quiz_Auto_Solver
 */

namespace WLQAB;

if ( ! defined( 'WPINC' ) ) {
	die;
}

require plugin_dir_path( __FILE__ ) . 'defs.php';
require plugin_dir_path( __FILE__ ) . 'class-qa-user.php';
require plugin_dir_path( __FILE__ ) . 'class-user-row-qa-actions.php';
require plugin_dir_path( __FILE__ ) . 'class-show-ld-answers.php';

$wlqab_qa_role = new QA_User();
$wlqab_qa_role->run();

$wlqab_user_actions = new User_Row_QA_Actions();
$wlqab_user_actions->run();

$wlqab_show_answers = new Show_LD_Answers();
$wlqab_show_answers->run();