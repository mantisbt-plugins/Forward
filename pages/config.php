<?php
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
layout_page_header( lang_get( 'plugin_forward_title' ) );
layout_page_begin( 'config_page.php' );
print_manage_menu();
?>
<br/>
<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container" > 
<br>
<form action="<?php echo plugin_page( 'config_edit' ) ?>" method="post">

<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-text-width"></i>
		<?php echo lang_get( 'plugin_forward_title') . ': ' . lang_get( 'plugin_forward_config' )?>
	</h4>
</div>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive"> 
<table class="table table-bordered table-condensed table-striped"> 


	<?php echo form_security_field( 'plugin_forward_config_update' ) ?>


		<tr >
			<td class="category">
				<?php echo lang_get( 'plugin_forward_threshold' ) ?>
			</td>
			<td >
				<select name="forward_threshold">
				<?php print_enum_string_option_list( 'access_levels', plugin_config_get( 'forward_threshold'  ) ) ?>;
				</select>
			</td>
		</tr>

		<tr >
			<td class="category">
				<?php echo lang_get( 'plugin_forward_address' ) ?>
			</td>
			<td >
				<input type="text" size="30" maxlength="200" name="forward_address" value="<?php echo plugin_config_get( 'forward_address'  )?>"/>
			</td>
		</tr>
<?php
/*		
		<tr>
			<td class="category">
				<?php echo lang_get( 'plugin_forward_customfields' ) ?>
			</td>
			<td >
				<input type="text" size="80" maxlength="200" name="forward_cf" value="<?php echo plugin_config_get( 'forward_customfields'  )?>"/>
			</td>
		</tr>
		
		<tr >
			<td class="category">
				<?php echo lang_get( 'plugin_forward_attachments' ) ?>
			</td>
			<td >
			<input id="forward_at" type="checkbox" name="forward_at" checked="checked" value="<?php echo plugin_config_get( 'forward_attachments'  )?>"/> Include 			</td>
		</tr>
*/
?>
		</table>
</div>
</div>
<div class="widget-toolbox padding-8 clearfix">
	<input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo lang_get( 'change_configuration' )?>" />
</div>
</div>
</div>
</form>
</div>
</div>
	</table>
<form>

<?php
layout_page_end();