<?php
namespace WLQAB;

class User_Row_QA_Actions{
    public function run(){
        add_filter( 'user_row_actions', array( $this, 'assessment_answer_show_activate' ), 10, 2 );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ), 10, 1 );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style' ), 10, 1 );
        add_action( 'wp_ajax_wlqab_show_quiz_answer_status', array( $this, 'show_ld_answer_update_ajax' ), 10, 1 );
    }

    public function assessment_answer_show_activate( $actions, $row_user_obj ){
        $user = wp_get_current_user();
        if ( in_array( WLQAB_LD_TEST_QA_ROLE, (array) $user->roles ) ) {
            $row_user_id = $row_user_obj->ID;
            $show_answer_enabled = get_user_meta($row_user_id, WLQAB_LD_SHOW_ANSWER_USER_META, true);
            $active = "0";
            $text = __( 'Show Quiz Answers', 'wlqab' );
            $icon = '<span class="wlqab_icon dashicons dashicons-visibility"></span>';
            $styleClass="wlqab_ld_show_quiz_answer";
            if( !empty($show_answer_enabled) && 1 == $show_answer_enabled  ){
                $active = "1";
                $text = __( 'Hide Quiz Answers', 'wlqab' );
                $styleClass="wlqab_ld_show_quiz_answer active";
            }
            $row_attr_string = 'data-action="wlqab_show_quiz_answer_status" data-active="'.$active.'" data-security="'.wp_create_nonce( "wlqab_update_show_quiz_{$row_user_id}" ).'" data-user_id="'.$row_user_id.'" ';
            $actions['wlqab_ld_show_quiz_answer'] = '<span class="wlqab_ld_show_quiz_answer_span '.$styleClass.'" '.$row_attr_string.'>'.$icon.'<span class="wlqab_display_text">'.$text.'</span></span>';
        }
        return $actions;
    }

    public function enqueue_script( $hook ){
        if ( 'users.php' != $hook ) {
            return;
        }
        wp_enqueue_script( 'wlqab_users_js', plugin_dir_url( __FILE__ ) . "assets/js/backend.user.js", array(), "1.0.0" );
        wp_localize_script( 'wlqab_users_js', 'wlqab_users_obj', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }

    public function enqueue_style( $hook ){
        if ( 'users.php' != $hook ) {
            return;
        }
        wp_enqueue_style( 'wlqab_users_css', plugin_dir_url( __FILE__ ) . "assets/css/backend.user.css", array(), "1.0.0" );
    }

    public function show_ld_answer_update_ajax() {
        $row_user_id =  filter_input( INPUT_POST, 'user_id', FILTER_VALIDATE_INT );
        if(!$row_user_id || 0 === $row_user_id ){
            $response = array(
                'status'  => 0,
                'message' => __( 'Illegal Attempt.', 'wlqab' ),
            );
            wp_send_json( $response );
        }
        check_ajax_referer( 'wlqab_update_show_quiz_'.$row_user_id, 'security' );

        // $active_status = get_user_meta($row_user_id, WLQAB_LD_SHOW_ANSWER_USER_META, true);
        $active_status = filter_input( INPUT_POST, 'active', FILTER_VALIDATE_INT );
        $new_active_status = $active_status^1;
        update_user_meta($row_user_id, WLQAB_LD_SHOW_ANSWER_USER_META, $new_active_status);
        $response = array(
            'status'  => 1,
            'row_user'  => $row_user_id,
            'active'  => $new_active_status
        );
		wp_send_json( $response );
	}
}