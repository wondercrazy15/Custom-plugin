<?php
/**
 * Plugin Name: Front-End Post Create
 * Plugin URI: http://bhavin.com/
 * Description: The very first plugin that I have ever created.
 * Version: 1.0
 * Author: Bhavin
 * Author URI: http://bhavin.in/
 */

add_action('wp_footer', 'myplugin_ajaxurl');
function myplugin_ajaxurl() {

   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
           var redirecturl = "'.admin_url().'";
         </script>';
}

function myenqueuescript(){
    wp_enqueue_script('mycustomjs1', plugins_url('js/customjs.js', __FILE__),'','1.0',true);
    // wp_enqueue_script('jqueryslimmin', plugins_url('js/jquery.slim.min.js', __FILE__),'','1.0',true);
    wp_enqueue_script('bootsrapjs', plugins_url('js/bootstrap.bundle.min.js', __FILE__),'','1.0',true);
    wp_enqueue_script('bootsrapcss', plugins_url('css/bootstrap.min.css', __FILE__),'','1.0',false);
    wp_enqueue_script('mycustomcss', plugins_url('css/mycustomcss.css', __FILE__),'','1.0',true);
    wp_localize_script( 'ajax-script', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'myenqueuescript' );

include('includes/cpf.php');

add_action( 'admin_notices', 'fx_admin_notice_example_notice' );
function fx_admin_notice_example_notice() {
	if( get_transient( 'fx-admin-notice-example' ) ){
		?>
		<div class="updated notice is-dismissible">
			<p> Go to Tools>>CPF shortcodes.</p>
		</div>
		<?php
		        delete_transient( 'fx-admin-notice-example' );

	}
}

add_action('admin_menu', 'wpdocs_register_my_custom_submenu_page');
 
function wpdocs_register_my_custom_submenu_page() {
    add_submenu_page(
        'tools.php',
        'Shortcode Lists',
        'CPF shortcodes',
        'manage_options',
        'cpfshortcodes',
        'wpdocs_my_custom_shortcode_page_callback' );
}
 
function wpdocs_my_custom_shortcode_page_callback() {
	?>
	<div class="wrap shortcodepage">
		<div id="icon-tools" class="icon32"></div>
		<h2>This Shortcode will Help you to manage the post</h2>
		<div class="shortcodedsp">
			<div><label>Create New Post</label></div>
			<div><input type="text" name="createpost" value="[create_new_post]" readonly></div>
		</div>
		<div class="shortcodedsp">
			<div><label>View Post</label></div>
			<div><input type="text" name="viewpost" value="[view_all_post]" readonly></div>
		</div>
		<div class="shortcodedsp">
			<div><label>Update post</label></div>
			<div><input type="text" name="updatepost" value="[update_post]" readonly></div>
		</div>
	</div>
	<?php
    
}