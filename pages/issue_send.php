<?php
require_once( config_get( 'plugin_path' ) . 'Forward' . DIRECTORY_SEPARATOR . 'pages' .DIRECTORY_SEPARATOR .'api.php' );

form_security_validate( 'forward_issue' );
$f_bug_id	= gpc_get_int( 'bug_id' );
$f_to		= gpc_get_string( 'forward_address' );
$f_at		= gpc_get_int( 'forward_at' );
$f_body		= gpc_get_string( 'body' );
$t_cf		= gpc_get_string( 'forward_cf' );
$f_body 	.= "<br>";


// get customfields to print
$ecf ="";
if ( $t_cf <> "*" ) {
	$ecf =  explode(",",$t_cf);
} else {
	$ecf = explode(",",get_customfields());
}



// Split for multiple adresses. 
$eaddress = explode(";",$f_to);
bug_ensure_exists( $f_bug_id );
$bug = bug_get( $f_bug_id, true );
$tpl_description =  $bug->description ;
$tpl_due_date = date( config_get( 'normal_date_format' ), $bug->due_date );
$tpl_steps_to_reproduce = $bug->steps_to_reproduce;
$tpl_additional_information = $bug->additional_information ;
	
foreach ($eaddress as $adres){
	
	$f_body_note  = 'Forwarded issue to:'. $f_to . "\r\n";
	$f_body_note .= gpc_get_string( 'body' );


	// Add summary
	$f_body		.= "<br>". bug_format_summary( $f_bug_id, SUMMARY_CAPTION );

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
	$text= get_customfield_value ( $f_bug_id, $cf );
	if ($text<>""){
		$f_body .= "<br>". $cf;
		$f_body .= " => ";
		$f_body .= $text;
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
	
	if (($f_at = 1) and ($ats>0)){
		// is this user allowed to see/download the attachments
		if ( file_can_download_bug_attachments( $f_bug_id ) and file_can_view_bug_attachments( $f_bug_id ) ) { 
			//	Let's add the Mantis attachment to the mail.
			// AddAttachment(__FILE__);
		}
	}

	$t_header = "\n" . lang_get( 'on_date' ) . " $t_date, $t_sender, $t_sender_email " . lang_get( 'plugin_forward_issue' ) . ": ";
	$t_contents = $t_header .  " <br>$f_body";
	$t_ok = email_store( $adres, $t_subject, $t_contents );
}

// Saving the forwarded issue as note 
$savenote 	= savenote_query( $f_bug_id, $t_sender_id, $f_body_note);
print_header_redirect( 'view.php?id='.$f_bug_id.'' , true);
