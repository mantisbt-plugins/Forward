<?php
/*
require_once( 'core.php' );
require_once( 'bug_api.php' );
*/
auth_ensure_user_authenticated();
$f_bug_id = gpc_get_int( 'bug_id' );
$t_bug = bug_get( $f_bug_id, true );
layout_page_header( bug_format_summary( $f_bug_id, SUMMARY_CAPTION ) );
layout_page_begin(  );

$g_issue_send = plugin_page( 'issue_send.php' );

# Send reminder Form BEGIN 
?>

<br/>
<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container" > 
<br>
<form method="post"  action="<?php echo $g_issue_send ?>">
<?php echo form_security_field( 'forward_issue' ) ?>
<input type="hidden" name="bug_id" value="<?php echo $f_bug_id ?>" />
<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-text-width"></i>
		<?php echo lang_get( 'plugin_forward_title') ?>
	</h4>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive"> 
<table class="table table-bordered table-condensed table-striped"> 
<tr>
	<td class="form-title" colspan="2">
		<?php
		echo lang_get( 'plugin_forward_title' ) ;
		echo " => ";
		echo bug_format_summary( $f_bug_id, SUMMARY_CAPTION );
		?>
	</td>
</tr>
<tr>
	<td class="category">
		<?php echo lang_get( 'to' ) ?>
	</td>
	<td>
		<input type="text" size="100" maxlength="200" name="forward_address" value="<?php echo plugin_config_get( 'forward_address'  )?>"/>
	</td>

	</tr>
<tr >
	<td class="category">
		<?php echo lang_get( 'plugin_forward_message' ) ?>
	</td>

	<td class="center">
		<textarea name="body" cols="75" rows="15"></textarea>
	</td>

</tr>
<?php
/*
	<tr >
			<td class="category">
				<?php echo lang_get( 'plugin_forward_attachments' ) ?>
			</td>
			<td >
			<input id="forward_at" type="checkbox" name="forward_at" checked="checked" value="<?php echo plugin_config_get( 'forward_attachments'  )?>"/> Include 			</td>
		</tr>
<tr>
*/
?>
</div>
</div>
<div class="widget-toolbox padding-8 clearfix">
	<input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo lang_get( 'bug_send_button' )?>" />
</div>
</div>
<tr>
	<td colspan="2"class="center">
		<?php
			echo lang_get( 'plugin_forward_explain' ) . ' ';
		?>
	</td>
</tr>
</table>
</div>

</form>
</div>
</div>
<?php
layout_page_end();

