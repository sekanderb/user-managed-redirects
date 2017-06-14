<?php

/*
* Plugin Name:       User Managed Redirects
* Plugin URI:        https://sekander.pro
* Description:       Let the users manage what page they want to see after logging in
* Version:           1.0.0
* Author:            Sekander Badsha
* Author URI:        https://sekander.pro/
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       umr
* Domain Path:       /languages
*/

//Add the custom field in profile page

function umr_set_redirect( $user ){ ?>
	<h3>Which Page You Want to See After You Log In?</h3>
	
	<table class="form-table">
		<th>
			<lable for="umr-redirect-url">Page Link:</lable>
		</th>
		<td>
			<input type="url" name="umr_redirect_url" id="umr_redirect_url" value="<?php esc_attr( the_author_meta( 'umr_redirect_url' , $user->ID ) ); ?>" placeholder="Paste the page link here" class="regular-text code">
		</td>
	</table>
	
<?php }

add_action('show_user_profile', 'umr_set_redirect');
add_action('edit_user_profile', 'umr_set_redirect');

//Save the data


function umr_save_redirect( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	update_usermeta( $user_id, 'umr_redirect_url', $_POST['umr_redirect_url'] );
}


add_action( 'personal_options_update', 'umr_save_redirect' );
add_action( 'edit_user_profile_update', 'umr_save_redirect' );


/**
 * WordPress function for redirecting users based on custom user meta
 */
function my_login_redirect( $url, $request, $user ){
	if( isset( $user->ID ) ) {
		$url = get_user_meta( $user->ID, 'umr_redirect_url', true );
	}
	return $url;
}

add_filter('login_redirect', 'my_login_redirect', 10, 3 );
