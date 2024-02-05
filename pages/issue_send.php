<?php
require_once( config_get( 'plugin_path' ) . 'Forward' . DIRECTORY_SEPARATOR . 'pages' .DIRECTORY_SEPARATOR .'api.php' );

form_security_validate( 'forward_issue' );
$f_bug_id	= gpc_get_int( 'bug_id' );
$f_to		= gpc_get_string( 'forward_address' );
$f_body		= gpc_get_string( 'body' );
$f_body 	.= "<br>";
$f_mailbody = $f_body;
$free = plugin_config_get( 'forward_free'  );
if ( $free == 1 ) {
	$f_at		= gpc_get_int( 'forward_attachments' );
	$t_cf		= gpc_get_string( 'forward_customfields' );
	$t_cc		= gpc_get_int( 'forward_cc' );
} else {
	$f_at		= plugin_config_get( 'forward_attachments'  );
	$t_cf		= plugin_config_get( 'forward_customfields'  );
	$t_cc		= plugin_config_get( 'forward_cc'  );
}



// get customfields to print
$ecf ="";
if ( $t_cf <> "*" ) {
	$ecf =  explode( ",",$t_cf );
} else {
	$ecf = explode( ",",get_customfields() );
}

// get reporter address
$cc_id		= $bug->reporter_id ;
$cc_mail	= user_get_field( $cc_id, 'email' );

bug_ensure_exists( $f_bug_id );
$bug = bug_get( $f_bug_id, true );
$tpl_description =  $bug->description ;
$tpl_due_date = date( config_get( 'normal_date_format' ), $bug->due_date );
$tpl_steps_to_reproduce = $bug->steps_to_reproduce;
$tpl_additional_information = $bug->additional_information ;

// get Reporter address
if ( $t_cc == 1 ) {
	$cc_id		= $bug->reporter_id ;
	$cc_mail	= user_get_field( $cc_id, 'email' );	
	$f_to .= ";";
	$f_to .= $cc_mail;
}
// Split for multiple adresses. 
$eaddress = explode(";",$f_to);

foreach ($eaddress as $adres){
	
	$f_body_note  = 'Forwarded issue to:'. $f_to . "\r\n";
	$f_body_note .= gpc_get_string( 'body' );


	// Add summary
	$f_body		= "<br>". bug_format_summary( $f_bug_id, SUMMARY_CAPTION );

	// Add description
	$f_body		.= "<br>" . lang_get( "description" ) ;
	$f_body		.= " : ";
	$f_body		.= $tpl_description ;

	// Add due date
	if( !is_blank( $tpl_due_date ) ) {
		$f_body		.= "<br><br>". lang_get( "due_date" );
		$f_body		.= " : ";
		$f_body		.= $tpl_due_date ;
	}
	// Steps to reproduce
	if( !is_blank( $tpl_steps_to_reproduce ) ) {
		$f_body		.=  "<br><br>". lang_get( "steps_to_reproduce" );
		$f_body		.= " : ";
		$f_body		.= $tpl_steps_to_reproduce ;
	}

	// additional info
	if( !is_blank( $tpl_additional_information ) ) {
		$f_body		.=  "<br>". lang_get( "additional_information" );
		$f_body		.= " : ";
		$f_body		.= $tpl_additional_information ;
	}

	// add customfields & their value
	$f_body .= "<br>";
	foreach ($ecf as $cf){
		$text = get_customfield_value ( $f_bug_id, $cf );
		$type = get_customfield_type ( $f_bug_id, $cf ); 
		if ($text<>""){
			$f_body .= "<br>". $cf;
			$f_body .= " => ";
			if ( $type == 8 ){
				$f_body .= date( config_get( 'short_date_format' ), $text  );
			} else {
				$f_body .= $text;
			}
		}
	}

	$t_subject = email_build_subject( $f_bug_id );
	$t_date = date( config_get( 'normal_date_format' ) );
	$t_sender_id = auth_get_current_user_id();
	$t_sender = user_get_name( $t_sender_id );
	$t_sender_email = user_get_email( $t_sender_id );

	// how any attachments to process
	$ats = file_bug_attachment_count( $f_bug_id );
	$f_body .= "<br><br>". "Total attachments :";
	$f_body .= " => ";
	$f_body .= $ats;
	if ( $ats > 0 ) {
		$t_attachments = file_get_visible_attachments( $f_bug_id );
		$f_body .=  "\n";
		foreach ( $t_attachments as $t_attachment ) {
			$f_body .= "<br>";
			$f_body .=  "\n";
			$f_body .= string_display_line( $t_attachment['display_name'] . " (". number_format( $t_attachment['size'] ) . " " . lang_get( 'bytes' ) . " )" );
			$f_body .=  "\n";
		}
	}

	$t_header = "\n" . lang_get( 'on_date' ) . " $t_date, $t_sender, $t_sender_email " . lang_get( 'plugin_forward_issue' ) . ": ";
	$t_contents = $t_header .  " <br>$f_mailbody<br>$f_body<br>" ;
	$t_ok = email_store( $adres, $t_subject, $t_contents );
}

// Saving the forwarded issue as note 
$savenote 	= savenote_query( $f_bug_id, $t_sender_id, $f_body_note);
print_header_redirect( 'view.php?id='.$f_bug_id.'' , true);
