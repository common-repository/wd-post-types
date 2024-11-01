<?php
if(!class_exists('WDTP_POSTS_TEMPLATES')){
	class WDTP_POSTS_TEMPLATES{
		function __construct() {
			add_action( 'init', array($this, 'wdpt_templates_post_callback') );
			add_action( 'add_meta_boxes', array($this, 'wdpt_templates_meta_callback') );
			add_action('wp_footer', array($this, 'wdpt_single_template'));
		}
		function wdpt_templates_post_callback(){
					   $labels = array(
				'name'               => _x( 'Post Template', 'post type general name', WDTP_POST_TYPE ),
				'singular_name'      => _x( 'Post Template', 'post type singular name', WDTP_POST_TYPE ),
				'menu_name'          => _x( 'Post Templates', 'admin menu', WDTP_POST_TYPE ),
				'name_admin_bar'     => _x( 'Post Template', 'add new on admin bar', WDTP_POST_TYPE ),
				'add_new'            => _x( 'Add New', 'Template', WDTP_POST_TYPE ),
				'add_new_item'       => __( 'Add New Post Template', WDTP_POST_TYPE ),
				'new_item'           => __( 'New Post Template', WDTP_POST_TYPE ),
				'edit_item'          => __( 'Edit Post Template', WDTP_POST_TYPE ),
				'view_item'          => __( 'View Post Template', WDTP_POST_TYPE ),
				'all_items'          => __( 'All Templates', WDTP_POST_TYPE ),
				'search_items'       => __( 'Search Post Template', WDTP_POST_TYPE ),
				'not_found'          => __( 'No Post Template found.', WDTP_POST_TYPE ),
				'not_found_in_trash' => __( 'No Post Template found in Trash.', WDTP_POST_TYPE )
			);
		    $args = array(
				'labels'             => $labels,
				'description'        => __( 'Description.', 'Add New Custom Post', WDTP_POST_TYPE  ),
				'public'             => true,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => false,
				'rewrite'            => array( 'slug' => 'wdpt-templates' ),
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 10,
				'supports'           => array( 'title', 'editor' ),
				'show_in_menu' => 'edit.php?post_type=wdpt_types'
			);
			register_post_type( 'wdpt-templates', $args );
		}
		
		function wdpt_templates_meta_callback(){
			  add_meta_box(
				'wdpt_template_post_type_args',                 
				__( 'Post Type Template', WDTP_POST_TYPE ),     
				array($this, 'wdpt_template_post_type_html'), 
				'wdpt-templates',
				'side'                      
			); 
		}// dashboar custom template terms html
		function wdpt_template_post_type_html($post){
			$template = get_post_meta( $post->ID, 'template', true );
			$args = array(
							 'public'   => true,
							 '_builtin' => true
						  );
			$types_builtin = get_post_types( $args, 'objects' );
			$args = array(
				'numberposts' => -1,
				'post_type' => 'wdpt_types',
				'post_status' => 'publish',
			);
			$types_custom = get_posts( $args );
			if ( $types_builtin || $types_custom ) {?>
            	<div class="inputs-wrp">
					<div class="input-wrp">
                    	<label for="wdpt_field"><strong><?php wdpt_esc_e( 'Post Type', 'label' );?></strong></label>
                        <select name="wdpt_args[select][template]" id="template" class="postbox">
                       		<option value=""><?php wdpt_esc_e( 'Post Type' );?></option>
                            <option value="post"<?php wdpt_esc_e($template == 'post' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Post' );?></option>
                		<?php 
						if ( $types_custom ){   
							foreach ( $types_custom  as $post_type ) {
								$_type = get_post_meta( $post_type->ID, 'post_type', false );
								$type = is_array($_type) ? $_type[0] : (!empty($_type) ? $_type : '');
								 ?>
								 <option value="<?php wdpt_esc_e($type, 'text');?>"<?php wdpt_esc_e($template == $type ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( $post_type->post_title );?></option>
								 <?php
							}
						}
						?>
                        </select>
                     </div>
                 </div>
            <?php
			}
		}
		/**
		* Single template function which will choose our template
		*/
		function wdpt_single_template($template_path) {
			global $template;
			$args = array(
							'numberposts' => 1,
							'post_type'   => 'wdpt-templates',
							'meta_key'    => 'template',
    						'meta_value'  => get_query_var( 'post_type' ),
						);
			$types_custom = get_posts( $args );
			if ( $types_custom ) {
				foreach ( $types_custom  as $post_type ) {
					$template = $post_type;
				}
				$template_path = WDTP_DIR.'inc/templates/custom-template.php';
				if(file_exists($template_path)) {
					return $template_path;
				}
			}
					
			return $template_path;
		}
		function wdpt_list_tax_code($type){}
		function wdpt_list_meta_code($type){}
	}
	new WDTP_POSTS_TEMPLATES();
}