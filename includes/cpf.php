<?php
	
	add_shortcode('create_post_front_end','create_post_front_end');
	function create_post_front_end(){
		if($_POST['title'] != null && $_POST['content'] != null && $_POST['cat'] !=0 && $_FILES['post_image'])
		{
			wpshout_save_post_if_submitted();
			add_action( 'wp_ajax_nopriv_dsp_data', 'wpshout_save_post_if_submitted' );
			add_action( 'wp_ajax_dsp_data', 'wpshout_save_post_if_submitted' );
		}
		?>
		<div id="postbox">
		    <form id="new_post" name="new_post" method="post"  enctype="multipart/form-data">

            <p><label for="title"><?php echo esc_html__('Title','theme-domain'); ?></label><br />
                <input type="text" class="" id="title" value="" tabindex="1" size="20" name="title" />
            </p>

            <?php //wp_editor( '', 'content' ); ?>
            <p>
            	<textarea class="form-control postdesc" rows="8" name="content"></textarea>
            </p>

            <p><?php //wp_dropdown_categories( 'show_option_none=Category&tab_index=4&taxonomy=category&hide_empty=0' ); 

            $args = array(
	        'show_option_all'    => 'All Catagories',
	        'show_option_none'   => '',
	        'orderby'            => 'ID',
	        'order'              => 'ASC',
	        'show_count'         => 1,
	        'hide_empty'         => 0,
	        'child_of'           => 0,
	        'exclude'            => '1,5',
	        'echo'               => 1,
	        'selected'           => 0,
	        'hierarchical'       => 0,
	        'name'               => 'cat',
	        'id'                 => '',
	        'class'              => 'postform postcategory createpostform form-control',
	        'depth'              => 1,
	        'tab_index'          => 0,
	        'taxonomy'           => 'category',
	        'hide_if_empty'      => false,
	             ); 

             wp_dropdown_categories( $args ); 
            	
        ?></p>

            <p><label for="post_tags"><?php echo esc_html__('Tags','theme-domain'); ?></label>

            <input type="text" value="" tabindex="5" size="16" name="post_tags" id="post_tags" /></p>

            <input type="file" name="post_image" accept="image/*" id="post_image" aria-required="true" onchange="loadFile(event)">
			<img id="output" style="width: 100px !important;height: 100px !important;border-radius: 20px !important;"/>

            <p><input type="submit" value="Publish" tabindex="6" id="btnsubmit" name="submit" /></p>
        
        </form>
		<script>
			
			var loadFile = function(event) {
				var output = document.getElementById('output');
				output.src = URL.createObjectURL(event.target.files[0]);
				output.onload = function() {
				URL.revokeObjectURL(output.src) // free memory
				}
			};
		</script>
		</div>
		<?php
	}

	function wpshout_save_post_if_submitted() {
    // Stop running function if form wasn't submitted
    if ( !isset($_POST['title']) ) {
		        return;
		    }

		    // Add the content of the form to $post as an array
		    $post = array(
		        'post_title'    => $_POST['title'],
		        'post_content'  => $_POST['content'],
		        'post_category' => array($_POST['cat']), 
		        'tags_input'    => $_POST['post_tags'],
		        'post_status'   => 'publish',   // Could be: publish
		        'post_type'     => 'post' // Could be: 'page' or your CPT
		    );
		    $post_id = wp_insert_post($post);
		    
		    // For Featured Image
		    if( !function_exists('wp_generate_attachment_metadata')){
		        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		    }
		    if($_FILES) {
		        foreach( $_FILES as $file => $array ) {
		            if($_FILES[$file]['error'] !== UPLOAD_ERR_OK){
		                return "upload error : " . $_FILES[$file]['error'];
		            }
		            $attach_id = media_handle_upload( $file, $post_id );
		        }
		    }
		    if($attach_id > 0) {
		        update_post_meta( $post_id,'_thumbnail_id', $attach_id );
		    }
		    // ob_start();

		    echo 'Saved your post successfully! :)';
		    $post_page = get_permalink( get_option( 'page_for_posts' ) );
		    if (headers_sent()) {
		    	// echo $post_page;
			    echo "<a href='".$post_page."'>"."View Post"."</a>";
			}
		    // ob_end_flush();
		    // header("Location: $post_page"); 
		    // exit();
}

/*Display Post Data*/

add_shortcode('dsp_post_table','display_post_table');
function display_post_table() {
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$wpb_all_query = new WP_Query(array('post_type'=>'post', 
							'posts_per_page'=>5,
							'paged' => $paged,
						)); ?>
    <?php if ( $wpb_all_query->have_posts() ) : ?>
<table class="responstable" border="1px solid black">
            <thead>
                <tr>
					<th>ID</th>
                    <th>Post Name</th>
                    <th colspan=2>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); ?>
					<td><?php the_ID(); ?></td>
                    <td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
					<form method='GET' action="<?php echo get_permalink(83); ?>">
					<input type='hidden' name='pid' value="<?php the_ID(); ?>">
						<td><a href="#"><button type="submit" name="submit" class="edtcls">Edit</button></a></td>
					</form>
					<td><a href="<?php echo get_delete_post_link(); ?>"><button>Delete</button></a></td>
                </tr>

    <?php endwhile; 
	?>
     </tbody>
        </table>
    <!-- end of the loop -->


<?php 
$total_pages = $wpb_all_query->max_num_pages;
if ($total_pages > 1){

	$current_page = max(1, get_query_var('paged'));

	echo paginate_links(array(
		'base' => get_pagenum_link(1) . '%_%',
		'format' => '/page/%#%',
		'current' => $current_page,
		'total' => $total_pages,
		'prev_text'    => __('« prev'),
		'next_text'    => __('next »'),
	));
}

if(isset($_GET['submit'])) {
	die("submitted");
}
?>
<?php else : ?>
    <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>
 <?php wp_reset_postdata(); 
}
/*END Post Data*/

