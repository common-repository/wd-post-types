<?php
if ( !function_exists( 'wdpt_init' ) ) {
	add_action( 'init', 'wdpt_init' );
	function wdpt_init() {
		
	}
}
if ( !function_exists( 'wdpt_inc_pro' ) ) {
	function wdpt_inc_pro() {
		$directory = WDTP_DIR.'inc/pro';
		if(file_exists($directory)) {
			foreach (scandir($directory) as $file) {
				if ($file !== '.' && $file !== '..' && $file != 'templates') {
					require_once( $directory.'/'.$file );
				}
			}
    	}
	}
}
//function to create taxonomy
if ( !function_exists( 'wdpt_get_meta_taxs' ) ) {
	function wdpt_get_meta_taxs($ID){
		$supports = get_post_meta( $ID, 'supports', false );
		$options .= '<option value="">Select</option>';
		if(is_array($supports[0]) && sizeof($supports[0])>=1){
			$options .= '<optgroup label="Supported">';
			foreach($supports[0] as $sk => $st){
				$options .= '<option value="support:'.wdpt_esc($sk, 'text').'">'.wdpt_esc(ucfirst($sk)).'</option>';
			}
		}
		$_taxonomies = get_post_meta( $ID, 'taxonomies', false );
		if(is_array($_taxonomies[0]) && sizeof($_taxonomies[0])>=1){
			$options .= '<optgroup label="Taxonomies">';
			foreach($_taxonomies[0] as $tk => $tax){
				if($tax == '1-d'){
					$options .= '<option value="tax:'.wdpt_esc($tk, 'text').'">category</option>';
				}else{
					$tx = get_post_meta( $tax, 'args', true );
					$options .= '<option value="tax:'.wdpt_esc($tx['taxonomy_key'], 'text').'">'.wdpt_esc(get_the_title($tax)).'</option>';
				}
			}
		}
		$_meta = get_post_meta( $ID, 'meta', false );
		if(is_array($_taxonomies[0]) && sizeof($_taxonomies[0])>=1){
			$options .= '<optgroup label="Meta">';
			foreach($_meta[0] as $mk => $mt){
				$options .= '<option value="meta:'.wdpt_esc($mk, 'text').'">'.wdpt_esc(get_the_title($mt['name'])).'</option>';
			}
		}
		return $options;
	}
}
//function to create dropdown options
if ( !function_exists( 'wdpt_get_heading' ) ) {
	function wdpt_get_heading($heads){
		$options = '<option value="">Select</option>';
		if(is_array($heads) && sizeof($heads)>=1){
			foreach($heads as $head){
				$options .= !empty($head) ? '<option value="'.wdpt_esc($head, 'text').'">'.wdpt_esc($head).'</option>' : '';
			}
		}
		return $options;
	}
}
//function to print array or object with pre tag formated
if ( !function_exists( 'wdpt_print_p' ) ) {
	function wdpt_print_p($value = array()) {
		if(!is_array($value) && !is_object($value)) echo $value;
		else{
			wdpt_esc_e("<pre>", 'raw');print_r($value);wdpt_esc_e("</pre>", 'raw');
		}
	}
}
//function to escaping value and send to screen with echo
if ( !function_exists( 'wdpt_esc_e' ) ) {
	function wdpt_esc_e($value='', $type='html') {
		if(empty($value)) return;
		if(is_array($value) && is_object($value)) $value = json_encode($value);
		echo wdpt_esc($value, $type);
	}
}
//function to escaping value
if ( !function_exists( 'wdpt_esc' ) ) {
	function wdpt_esc($value, $type='html') {
		if(is_array($value) && is_object($value)) $value = json_encode($value);
		switch($type){
			case 'label':
				$res = esc_html__($value, WDTP_POST_TYPE);
				break;
			case 'text':
				$res = esc_attr($value);
				break;
			case 'js':
				$res = esc_js($value);
				break;
			case 'textarea':
				$res = esc_textarea($value);
				break;
			case 'url':
				$res = esc_url($value);
				break;
			case 'html':
				$res = esc_html($value);
				break;
			case 'raw':
				$res = _e($value);
				break;
			default:
				$res = wp_kses($value);
				break;
		}
		return $res;
	}
}
//function to upload images
if ( !function_exists( 'wdpt_multi_media_uploader_field' ) ) {
	function wdpt_multi_media_uploader_field($name, $value = '') {
		$image = 'Add Media';
		$image_str = '';
		$image_size = 'thumb';
		$display = 'none';
		$value = explode(',', $value);
	
		if (!empty($value)) {
			foreach ($value as $values) {
				if ($image_attributes = wp_get_attachment_image_src($values, $image_size)) {
					$image_str .= '<li data-attechment-id=' . wdpt_esc($values, 'text') . '><a href="' . wdpt_esc($image_attributes[0], 'url') . '" target="_blank"><img  style="width:150px;" src="' . wdpt_esc($image_attributes[0], 'url') . '" /></a><i class="dashicons dashicons-no delete-img"></i></li>';
				}
			}
	
		}
	
		if($image_str){
			$display = 'inline-block';
		}
	
return '<div class="multi-upload-medias"><style>.multi-upload-medias img{width:150px;}</style><ul>' . wdpt_esc($image_str, 'text') . '</ul><a href="#" class="wc_multi_upload_image_button button"> '. wdpt_esc($image, 'text') . '</a><input type="hidden" class="attechments-ids ' . wdpt_esc($name, 'text') . '" name="wdpt_args[' . wdpt_esc($name, 'text') . ']" id="' . wdpt_esc($name, 'text') . '" value="' . wdpt_esc(implode(',', $value), 'text') . '" /></div>';
	}
}
//function to sanitize value
if ( !function_exists( 'wdpt_sanitize' ) ) {
	function wdpt_sanitize($value, $sanitize) {
		if(is_array($value) && is_object($value)) $value = json_encode($value);
		switch($sanitize){
			case 'file':
				$res = sanitize_file_name($value);
				break;
			case 'text':
				$res = sanitize_text_field($value);
				break;
			case 'select':
				$res = sanitize_text_field($value);
				break;
			case 'textarea':
				$res = sanitize_textarea_field($value);
				break;
			case 'email':
				$res = sanitize_email($value);
				break;
			case 'htmlclass':
				$res = sanitize_html_class($value);
				break;
			case 'title':
				$res = sanitize_title($value);
				break;
			case 'user':
				$res = sanitize_user($value);
				break;
			case 'key':
				$res = sanitize_key($value);
				break;
			case 'url':
				$res = sanitize_url($value);
				break;
			case 'color':
				$res = sanitize_hex_color($value);
				break;
			default:
				$res = sanitize_text_field($value);
				break;
		}
		return $res;
	}
}
//function to sanitize multidimentional array values
if ( !function_exists( 'wdpt_senitize_array_val' ) ) {
	function wdpt_senitize_array_val($value, $type='text') {
		if(is_array($value) && sizeof($value)>=1){
			foreach($value as $key=>$data ) {
				if(is_array($data) && sizeof($data)>=1){
					wdpt_senitize_array_val($data, $type);
				}else{
					$value[$key] = !empty($data) ? wdpt_sanitize($data, $type) : '';
				}
			}
		}
		return $value;
	}
}
//save meta data
function wdpt_save_data( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
	if(isset($_POST['wdpt_args']) && is_array($_POST['wdpt_args'])){
		foreach($_POST['wdpt_args'] as $_key=>$_value ) {
			foreach($_value as $key=>$value ) {			
				//$senitized_value = is_array($value) ? wdpt_senitize_array_val($value, $_key) : wdpt_sanitize($value, $_key);
				$new_val = is_array($value) ? json_encode($value) : $value;
				$new_val = wdpt_sanitize($new_val, $_key);
				update_post_meta(
					$post_id,
					$key,
					$new_val
				);
			}
		}
	}
}
add_action( 'save_post', 'wdpt_save_data' );
//change title of custom type in dashboard
function wdpt_change_title($title){
	$screen = get_current_screen();   
	 if  ( 'wdpt_types' == $screen->post_type ) {
		  $title = 'Plural Label';
	 }elseif  ( 'wdpt_meta' == $screen->post_type ) {
		  $title = 'Meta Box Title(A slug will automatically be generated).';
	 }elseif  ( 'wdpt_taxonomies' == $screen->post_type ) {
		  $title = 'Plural Name';
	 }elseif  ( 'wdpt_group' == $screen->post_type ) {
		  $title = 'Group Name';
	 }
   
	 return $title;
}
add_filter( 'enter_title_here', 'wdpt_change_title' );
//post type format arguments
if (!function_exists( 'wdpt_type_format')) {
	function wdpt_type_format($post=array(), $slug=''){
		$post['labels']['menu_name'] = isset($post['labels']['menu_name']) && !empty($post['labels']['menu_name']) ? $post['labels']['menu_name'] : $post['labels']['name'];
		$labels = array(
						'name'                  => _x( isset($post['labels']['name']) ? $post['labels']['name'] : '', 'Post type general(plural) name', WDTP_POST_TYPE ),
						'singular_name'         => _x( isset($post['labels']['singular_name']) ? $post['labels']['singular_name'] : '', 'Post type singular name', WDTP_POST_TYPE ),
						'menu_name'             => _x( isset($post['labels']['menu_name']) ? $post['labels']['menu_name'] : '', 'Admin Menu text', WDTP_POST_TYPE ),
						'add_new'               => __( isset($post['labels']['add_new']) ? $post['labels']['add_new'] : 'Add New', WDTP_POST_TYPE ),
						'add_new_item'          => __( isset($post['labels']['add_new_item']) ? $post['labels']['add_new_item'] : 'Add New '.(isset($post['labels']['name']) ? $post['labels']['name'] : ''), WDTP_POST_TYPE ),
						'new_item'              => __( isset($post['labels']['new_item']) ? $post['labels']['new_item'] : 'New '.(isset($post['labels']['name']) ? $post['labels']['name'] : ''), WDTP_POST_TYPE ),
						'edit_item'             => __( isset($post['labels']['edit_item']) ? $post['labels']['edit_item'] : 'Edit '.(isset($post['labels']['name']) ? $post['labels']['name'] : ''), WDTP_POST_TYPE ),
						'view_item'             => __( isset($post['labels']['view_item']) ? $post['labels']['view_item'] : 'View '.(isset($post['labels']['name']) ? $post['labels']['name'] : ''), WDTP_POST_TYPE ),
						'all_items'             => __( isset($post['labels']['all_items']) ? $post['labels']['all_items'] : 'All '.(isset($post['labels']['name']) ? $post['labels']['name'] : ''), WDTP_POST_TYPE ),
						'search_items'          => __( isset($post['labels']['search_items']) ? $post['labels']['search_items'] : 'Search '.(isset($post['labels']['name']) ? $post['labels']['name'] : ''), WDTP_POST_TYPE ),
						'not_found'             => __( isset($post['labels']['not_found']) ? $post['labels']['not_found'] : 'No '.(isset($post['labels']['name']) ? $post['labels']['name'] : '').' Found', WDTP_POST_TYPE ),
					);
					
					
		if(isset($post['labels']['name_admin_bar']) && !empty($post['labels']['name_admin_bar'])) 
			$labels['name_admin_bar'] = _x( isset($post['labels']['name_admin_bar']) ? $post['labels']['name_admin_bar'] : '', 'Add New on Toolbar', WDTP_POST_TYPE );

		if(isset($post['labels']['parent_item_colon']) && !empty($post['labels']['parent_item_colon'])) 
			$labels['parent_item_colon'] = __( isset($post['labels']['parent_item_colon']) ? $post['labels']['parent_item_colon'] : '', WDTP_POST_TYPE );
					
		if(isset($post['labels']['not_found_in_trash']) && !empty($post['labels']['not_found_in_trash'])) 
			$labels['not_found_in_trash'] = __( isset($post['labels']['not_found_in_trash']) ? $post['labels']['not_found_in_trash'] : '', WDTP_POST_TYPE );

		if(isset($post['labels']['featured_image']) && !empty($post['labels']['featured_image'])) 
			$labels['featured_image'] = _x( isset($post['labels']['featured_image']) ? $post['labels']['featured_image'] : '', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', WDTP_POST_TYPE );

		if(isset($post['labels']['set_featured_image']) && !empty($post['labels']['set_featured_image'])) 
			$labels['set_featured_image'] =  _x( isset($post['labels']['set_featured_image']) ? $post['labels']['set_featured_image'] : '', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', WDTP_POST_TYPE );

		if(isset($post['labels']['remove_featured_image']) && !empty($post['labels']['remove_featured_image'])) 
			$labels['remove_featured_image'] = _x( isset($post['labels']['remove_featured_image']) ? $post['labels']['remove_featured_image'] : '', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', WDTP_POST_TYPE );

		if(isset($post['labels']['use_featured_image']) && !empty($post['labels']['use_featured_image'])) 
			$labels['use_featured_image'] = _x( isset($post['labels']['use_featured_image']) ? $post['labels']['use_featured_image'] : '', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', WDTP_POST_TYPE );

		if(isset($post['labels']['archives']) && !empty($post['labels']['archives'])) 
			$labels['archives'] = _x( isset($post['labels']['archives']) ? $post['labels']['archives'] : '', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', WDTP_POST_TYPE );	
			
		if(isset($post['labels']['insert_into_item']) && !empty($post['labels']['insert_into_item'])) 
			$labels['insert_into_item'] = _x( isset($post['labels']['insert_into_item']) ? $post['labels']['insert_into_item'] : '', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', WDTP_POST_TYPE );

		if(isset($post['labels']['uploaded_to_this_item']) && !empty($post['labels']['uploaded_to_this_item'])) 
			$labels['uploaded_to_this_item'] = _x( isset($post['labels']['uploaded_to_this_item']) ? $post['labels']['uploaded_to_this_item'] : '', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', WDTP_POST_TYPE );

		if(isset($post['labels']['filter_items_list']) && !empty($post['labels']['filter_items_list'])) 
			$labels['filter_items_list'] = _x( isset($post['labels']['filter_items_list']) ? $post['labels']['filter_items_list'] : '', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', WDTP_POST_TYPE );

		if(isset($post['labels']['items_list_navigation']) && !empty($post['labels']['items_list_navigation'])) 
			$labels['items_list_navigation'] = _x( isset($post['labels']['items_list_navigation']) ? $post['labels']['items_list_navigation'] : '', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', WDTP_POST_TYPE );

		if(isset($post['labels']['items_list']) && !empty($post['labels']['items_list'])) 
			$labels['items_list'] = _x( isset($post['labels']['items_list']) ? $post['labels']['items_list'] : '', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', WDTP_POST_TYPE );	

				     
		$args = array(
						  'labels'             => $labels,
						  'public'             => __((isset($post['public']) && $post['public'] == 'true' ? true : false), WDTP_POST_TYPE ),
						  'query_var'          => __((isset($post['query_var']) && $post['query_var'] == 'true' ? true : false), WDTP_POST_TYPE ),
						  'has_archive'        => __((isset($post['has_archive']) && $post['has_archive'] == 'true' ? true : false), WDTP_POST_TYPE ),
						  'hierarchical'       => __((isset($post['hierarchical']) && $post['hierarchical'] == 'true' ? true : false), WDTP_POST_TYPE ),
					  );			  						
		if(isset($post['rewrite']['slug']) && !empty($post['rewrite']['slug'])) 
			$args['rewrite']['slug'] = __(isset($post['rewrite']['slug']) && !empty($post['rewrite']['slug'])? $post['rewrite']['slug'] : $slug, WDTP_POST_TYPE );
		if(isset($post['rewrite']['with_front']) && !empty($post['rewrite']['with_front'])) 
			$args['rewrite']['with_front'] = __(isset($post['rewrite']['with_front']) && $post['rewrite']['with_front'] == 'true' ? true : false, WDTP_POST_TYPE );
		if(isset($post['rewrite']['feeds']) && !empty($post['rewrite']['feeds'])) 
			$args['rewrite']['feeds'] = __(isset($post['rewrite']['feeds']) && $post['rewrite']['feeds'] == 'true' ? true : false, WDTP_POST_TYPE );
		if(isset($post['rewrite']['pages']) && !empty($post['rewrite']['pages']) )
			$args['rewrite']['pages'] = __(isset($post['rewrite']['pages']) && $post['rewrite']['pages'] == 'true' ? true : false, WDTP_POST_TYPE );						
		if(isset($post['rewrite']['ep_mask']) && !empty($post['rewrite']['ep_mask'])) 
			$args['rewrite']['ep_mask'] = __(isset($post['rewrite']['ep_mask']) && $post['rewrite']['ep_mask'] == 'true' ? true : false, WDTP_POST_TYPE );						
		if(isset($post['taxonomies']) && sizeof($post['taxonomies'])>=1 )
			$args['taxonomies'] =  __(isset($post['taxonomies']) && is_array($post['taxonomies']) ? $post['taxonomies'] : array($post['taxonomies']), WDTP_POST_TYPE );
			
		if(isset($post['description']) && !empty($post['description']))
			$args['description'] = __(isset($post['description']) ? $post['description'] : '', WDTP_POST_TYPE );	
		if(isset($post['publicly_queryable']) && !empty($post['publicly_queryable'])) 
			$args['publicly_queryable'] =   __((isset($post['publicly_queryable']) && $post['publicly_queryable'] == 'true' ? true : false), WDTP_POST_TYPE );
		if(isset($post['show_ui']) && !empty($post['show_ui'])) 
			$args['show_ui'] = __((isset($post['show_ui']) && $post['show_ui'] == 'true' ? true : false), WDTP_POST_TYPE );
		if(isset($post['show_in_menu']) && !empty($post['show_in_menu'])) 
			$args['show_in_menu'] =  __((isset($post['show_in_menu']) && $post['show_in_menu'] == 'true' ? true : false), WDTP_POST_TYPE );
		if(isset($post['capability_type']) && !empty($post['capability_type'])) 
			$args['capability_type'] = __(isset($post['capability_type']) && !empty($post['capability_type']) ? $post['capability_type'] : 'post', WDTP_POST_TYPE );
		if(isset($post['show_in_rest']) && !empty($post['show_in_rest'])) 
			$args['show_in_rest'] =  __((isset($post['show_in_rest']) && $post['show_in_rest'] == 'true' ? true : false), WDTP_POST_TYPE );					
		if(isset($post['supports']) && sizeof($post['supports'])>=1) 
			$args['supports'] =  __(isset($post['supports']) && is_array($post['supports']) ? $post['supports'] : array($post['supports']), WDTP_POST_TYPE );			
		if(isset($post['menu_icon']) && !empty($post['menu_icon'])) 
			$args['menu_icon'] =  __(isset($post['menu_icon']) ? $post['menu_icon'] : '', WDTP_POST_TYPE );
		if(isset($post['menu_position']) && !empty($post['menu_position'])) 
			$args['menu_position'] =  __(isset($post['menu_position']) ? $post['menu_position'] : '', WDTP_POST_TYPE );	
		return $args;		
	}
}
add_filter( 'template_include', 'wdpt_single_template', 1 );
function wdpt_single_template($template_path) {
	global $cpt_content;
	$args = array(
					'numberposts' => 1,
					'post_type'   => 'wdpt-templates',
					'meta_key'    => 'template',
					'meta_value'  => get_post_type(),
				);
	$types_custom = get_posts( $args );
	if ( $types_custom ) {
		foreach ( $types_custom  as $_post ) {//wdpt_print_p($_post);
			$cpt_content = $_post->post_content;
		}
		
		$template_path = WDTP_DIR.'inc/templates/custom-template.php';
		if(file_exists($template_path)) {
			return $template_path;
		}
	}
			
	return $template_path;
}