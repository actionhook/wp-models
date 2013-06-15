<?php

require_once getenv( 'WP_TESTS_DIR' ) . '/includes/functions.php';

function _manually_load_plugin() {
	require dirname( __FILE__ ) . '/../wp-models.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require getenv( 'WP_TESTS_DIR' ) . '/includes/bootstrap.php';

require_once( 'framework/factory.php' );

define( 'WPM_PLUGIN_PATH',	dirname( dirname( __FILE__ ) ) );
define( 'WPM_PLUGIN_URI',	plugin_dir_url( dirname( dirname ( __FILE__ ) ) ) );
define( 'WPM_MAIN_PLUGIN_FILE',	dirname( dirname ( __FILE__ ) . 'wp-models.php' ) ) ;
