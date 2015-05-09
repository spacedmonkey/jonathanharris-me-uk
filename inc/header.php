<?php
// add our custom header options hook
add_action('custom_header_options', 'my_custom_image_options');
 
/* Adds two new text fields, custom_option_one and custom_option_two to the Custom Header options screen */
function my_custom_image_options()
{
?>
<table class="form-table">
	<tbody>
		<tr valign="top" class="hide-if-no-js">
			<th scope="row"><?php _e( 'Header Colour' ); ?></th>
			<td>
				<p>
					<input type="text" name="spacedmonkey_header_colour" id="spacedmonkey_header_colour" value="<?php echo esc_attr( get_theme_mod( 'spacedmonkey_header_colour', '#0000000' ) ); ?>" />
				</p>
			</td>
		</tr>
		
	</tbody>
</table>


<script type="text/javascript">
    jQuery(document).ready(function($) {   
        $('#spacedmonkey_header_colour').wpColorPicker();
    });             
    </script>
<?php
} // end my_custom_image_options

	add_action('admin_head', 'save_my_custom_options');
	function save_my_custom_options()
	{
		if ( isset( $_POST['spacedmonkey_header_colour'] )  )
		{
			// validate the request itself by verifying the _wpnonce-custom-header-options nonce
			// (note: this nonce was present in the normal Custom Header form already, so we didn't have to add our own)
			check_admin_referer( 'custom-header-options', '_wpnonce-custom-header-options' );
 
			// be sure the user has permission to save theme options (i.e., is an administrator)
			if ( current_user_can('manage_options') ) {
 
				// NOTE: Add your own validation methods here
				set_theme_mod( 'spacedmonkey_header_colour', $_POST['spacedmonkey_header_colour'] );
			}
		}
		return;
	}
?>
