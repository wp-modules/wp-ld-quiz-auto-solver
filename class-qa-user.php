<?php
namespace WLQAB;

class QA_User{
    public function run(){
        add_action('init', array( $this, 'add_qa_role' ) );
    }

    public function add_qa_role(){
        if ( get_option( 'wlqab_qa_role_v' ) < 2 ) {
            add_role( WLQAB_LD_TEST_QA_ROLE, 'LD TEST QA', array( 'read' => true, 'level_0' => true ) );
            update_option( 'wlqab_qa_role_v', 2 );
        }
    }
}