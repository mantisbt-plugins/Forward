<?php

class ForwardPlugin extends MantisPlugin {

	function register() {
		$this->name = lang_get( 'plugin_forward_title' );
		$this->description = lang_get( 'plugin_forward_description' );
		$this->page = 'config';
		$this->version = '2.12';
		$this->requires = array( 'MantisCore' => '2.0.0', );
		$this->author = 'Cas Nuy';
		$this->contact = 'cas@nuy.info';
		$this->url = 'http://www.nuy.info';
	}

	function config() {
		return array(
			'forward_address'		=> 'whoever@domain.com',
			'forward_threshold'		=> DEVELOPER,
			'forward_attachments'	=> 0,
			'forward_customfields'	=> ''
			);
	}


	function init() {
		plugin_event_hook( 'EVENT_MENU_ISSUE', 'forward' );

	}

	function forward(  ) {
		$bugid =  gpc_get_int( 'id' );
		if ( access_has_bug_level( plugin_config_get( 'forward_threshold' ), $bugid ) ){
			$forward_page ='issue_send_page.php';
			$forward_page .='&bug_id=';
			$forward_page .= $bugid;
			return array( lang_get( 'plugin_forward_title' )=>plugin_page( $forward_page ) );
		}

	}


}
