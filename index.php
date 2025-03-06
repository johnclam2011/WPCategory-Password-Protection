<?php

/**
 * Plugin Name: Category Password Protection
 * Description: Adds password protection to individual categories.
 * Version: 1.0.0
 * Author: John Paul Clam
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add category meta field for password.
 */
function category_password_add_category_fields( $taxonomy ) {
	?>
	<div class="form-field term-password-wrap">
		<label for="term-password"><?php _e( 'Category Password', 'category-password' ); ?></label>
		<input type="password" name="term_meta[password]" id="term-password" value="">
		<p class="description"><?php _e( 'Set a password for this category.  Leave blank for no password.', 'category-password' ); ?></p>
	</div>
	<?php
}
add_action( 'category_add_form_fields', 'category_password_add_category_fields', 10, 2 );

/**
 * Edit category meta field for password.
 */
function category_password_edit_category_fields( $term, $taxonomy ) {

	// Get existing term meta.
	$term_meta = get_term_meta( $term->term_id, 'term_meta', true );
	$password = isset( $term_meta['password'] ) ? esc_attr( $term_meta['password'] ) : '';
	?>
	<tr class="form-field term-password-wrap">
		<th scope="row"><label for="term-password"><?php _e( 'Category Password', 'category-password' ); ?></label></th>
		<td>
			<input type="password" name="term_meta[password]" id="term-password" value="<?php echo $password; ?>">
			<p class="description"><?php _e( 'Set a password for this category.  Leave blank for no password.', 'category-password' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'category_edit_form_fields', 'category_password_edit_category_fields', 10, 2 );

/**
 * Save category meta field for password.
 */
function category_password_save_category_fields( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$term_meta = get_term_meta( $term_id, 'term_meta', true );

		// Sanitize and update the password field
		$term_meta['password'] = isset( $_POST['term_meta']['password'] ) ? sanitize_text_field( $_POST['term_meta']['password'] ) : '';

		// Save the option array.
		update_term_meta( $term_id, 'term_meta', $term_meta );
	}
}
add_action( 'edited_category', 'category_password_save_category_fields', 10, 2 );
add_action( 'create_category', 'category_password_save_category_fields', 10, 2 );


/**
 * Check if the category is password protected and redirect if needed.
 */
function category_password_check_category_access( $query ) {
	if ( ! is_admin() && $query->is_category() && $query->is_main_query() ) {
		$category = get_queried_object();

		if ( $category ) {
			$term_meta = get_term_meta( $category->term_id, 'term_meta', true );
			$password = isset( $term_meta['password'] ) ? $term_meta['password'] : '';

			if ( ! empty( $password ) ) {

				// Check if the password has been entered for this category
				if ( ! isset( $_COOKIE[ 'category_password_' . $category->term_id ] ) || $_COOKIE[ 'category_password_' . $category->term_id ] !== md5( $password ) ) {

					// Handle Password Submission
					if ( isset( $_POST['category_password'] ) && $_POST['category_password'] === $password ) {
						// Set a cookie to remember the password for this category
						setcookie( 'category_password_' . $category->term_id, md5( $password ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN ); // Expires in 30 days

						// Reload the page to show the content
						wp_redirect( home_url( add_query_arg( array(), $wp->request ) ) );
						exit;

					} else {
						// Show password form
						add_filter( 'the_content', function( $content ) use ( $category ) {
							ob_start();
							?>
							<form method="post" action="">
								<p><?php _e( 'This category is password protected. Please enter the password to view its contents.', 'category-password' ); ?></p>
								<label for="category_password"><?php _e( 'Password:', 'category-password' ); ?></label>
								<input type="password" name="category_password" id="category_password">
								<input type="submit" value="<?php _e( 'Submit', 'category-password' ); ?>">
							</form>
							<?php
							return ob_get_clean();
						});

						// Prevents the original content from rendering
						$query->set( 'posts_per_page', 0 ); // Prevent posts from loading

					}
				}
			}
		}
	}
}
add_action( 'pre_get_posts', 'category_password_check_category_access' );


// Localization (Optional)
function category_password_load_textdomain() {
	load_plugin_textdomain( 'category-password', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'category_password_load_textdomain' );

?>
