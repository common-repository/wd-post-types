<?php
//Class to create type of custom fields in dashboard
if(!class_exists('WDTP_CUSTUM_TYPES')){
	class WDTP_CUSTUM_TYPES{
		public $post = array();
		public $groups = array();
		public $group = array();
		public $post_type = '';
		function __construct() {
			$args = array(
				'numberposts' => -1,
				'post_type' => 'wdpt_types',
				'post_status' => 'publish',
			);
			$types = get_posts( $args );
			if ( $types ) {
				foreach ( $types as $type ){
					$this->post = $type;
					add_action( 'init', array($this, 'wdpt_create_types') );
				}
			}
			add_action( 'admin_head', array($this, 'wdpt_add_css_script_in_head') );
			add_action( 'admin_enqueue_scripts', array($this, 'wdpt_enqueue_color_picker') );
		}
		//display custom meta in post
		private function wdpt_get_meta($id, $name){
			$meta = get_post_meta( $id, $name, false );
			return !empty($meta) ? (is_array($meta) ? $meta[0] : $meta) : '';
		}
		//display sctipt of color in post
		function wdpt_enqueue_color_picker( $hook_suffix ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'my-script-handle', plugins_url('my-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
		}
		//display sctipt to handle color in post
		function wdpt_add_css_script_in_head(){
			?>
            <style>
				.input-wrp {
					width: 100%;
					margin-bottom: 20px;
				}
				.input-wrp input[type='checkbox'] {
   					margin-top: 2px;
					width:auto;
				}
				.input-wrp h2.label {
					font-size: 15px;
					font-weight: bold;
				}
				.input-wrp input {
					font-size: 1.2em;
				}
				.input-wrp input:not([type=checkbox]):not([type=radio]), .input-wrp select {
					padding: 10px 8px;
					line-height: 100%;
					border:1px solid;
					width: 100%;
					outline: 0;
					margin: 10px 0 3px;
					background-color: #fff;
				}
				.input-wrp textarea {
					border: 1px solid;
					padding: 10px ​8p;
					line-height: 100%;
					height: 10.7em;
					width: 100%;
					outline: 0;
					margin: 10px 0 3px;
					background-color: #fff;
				}
			</style>
             <script type="text/javascript">
					jQuery(function($) {
						jQuery('body').on('click', '.wc_multi_upload_image_button', function(e) {
							e.preventDefault();
			
							var button = jQuery(this),
							custom_uploader = wp.media({
								title: 'Insert image',
								button: { text: 'Use this image' },
								multiple: false 
							}).on('select', function() {
								var attech_ids = '';
								attachments
								var attachments = custom_uploader.state().get('selection'),
								attachment_ids = new Array(),
								i = 0;
								attachments.each(function(attachment) {
									attachment_ids[i] = attachment['id'];
									attech_ids += ',' + attachment['id'];
									if (attachment.attributes.type == 'image') {
										jQuery(button).siblings('ul').append('<li data-attechment-id="' + attachment['id'] + '"><a href="' + attachment.attributes.url + '" target="_blank"><img class="true_pre_image" src="' + attachment.attributes.url + '" /></a><i class=" dashicons dashicons-no delete-img"></i></li>');
									} else {
										jQuery(button).siblings('ul').append('<li data-attechment-id="' + attachment['id'] + '"><a href="' + attachment.attributes.url + '" target="_blank"><img class="true_pre_image" src="' + attachment.attributes.icon + '" /></a><i class=" dashicons dashicons-no delete-img"></i></li>');
									}
			
									i++;
								});
								jQuery('.wc_multi_upload_image_button').css("display", "none");
								var ids = jQuery(button).siblings('.attechments-ids').attr('value');
								if (ids) {
									var ids = ids + attech_ids;
									jQuery(button).siblings('.attechments-ids').attr('value', ids);
								} else {
									jQuery(button).siblings('.attechments-ids').attr('value', attachment_ids);
								}
								jQuery(button).siblings('.wc_multi_remove_image_button').show();
							})
							.open();
						});
			
						jQuery('body').on('click', '.wc_multi_remove_image_button', function() {
							jQuery(this).hide().prev().val('').prev().addClass('button').html('Add Media');
							jQuery(this).parent().find('ul').empty();
							jQuery('.wc_multi_upload_image_button').css("display", "inline-block");
							return false;
						});
			
					});
			
					jQuery(document).ready(function() {
						jQuery(document).on('click', '.multi-upload-medias ul li i.delete-img', function() {
							var ids = [];
							var this_c = jQuery(this);
							jQuery(this).parent().remove();
							jQuery('.multi-upload-medias ul li').each(function() {
								ids.push(jQuery(this).attr('data-attechment-id'));
							});
							jQuery('.multi-upload-medias').find('input[type="hidden"]').attr('value', ids);
							jQuery('.wc_multi_upload_image_button').css("display", "inline-block");
						});
						jQuery(document).ready(function($){
							jQuery('.wdpt_color_pick').wpColorPicker();
						});
					})
				</script>
            <?php
		}
		//create post type 
		function wdpt_create_types(){
			$post = $this->post;
			$labels = get_post_meta( $post->ID, 'labels', true );
			$labels = json_decode(stripslashes($labels), true);
			
			$labels['name'] = $post->post_title;
			$_supports = get_post_meta( $post->ID, 'supports', false );
			$_supports = isset($_supports[0]) && !is_array($_supports[0]) ? json_decode($_supports[0], true) : $_supports;
			//$_supports = json_decode(stripslashes($_supports), true);
			$_taxonomies = get_post_meta( $post->ID, 'taxonomies', false );
			$_taxonomies = isset($_taxonomies[0]) && !is_array($_taxonomies[0]) ? json_decode($_taxonomies[0], true) : $_taxonomies;
			//echo '<pre>'.$post->ID;print_r($_taxonomies);echo '</pre>';
			//$_taxonomies = json_decode(stripslashes($_taxonomies), true);
			$rewrite = get_post_meta( $post->ID, 'rewrite', true );
			$rewrite = json_decode(stripslashes($rewrite), true);
			$with_front = get_post_meta( $post->ID, 'with_front', true );
			if(!empty($with_front)){
				$rewrite['with_front'] = $with_front;
			}
			$feeds = get_post_meta( $post->ID, 'feeds', true );
			if(!empty($feeds)){
				$rewrite['feeds'] = $feeds;
			}
			$pages = get_post_meta( $post->ID, 'pages', true );
			if(!empty($pages)){
				$rewrite['pages'] = $pages;
			}
			
			$supports = array();
			if(is_array($_supports)){
				foreach($_supports as $key=>$value){
					if($value == 1)$supports[] = $key;
				}
			}
			$dtaxonomies = array();
			$taxonomies = array();
			if(is_array($_taxonomies) && sizeof($_taxonomies)>=1){
				foreach($_taxonomies as $key=>$value){
					if($value == '1-d')$dtaxonomies[] = $key;
					elseif($value == '1')$taxonomies[] = $key;
				}
			}
		    $args = array(
				'labels'             => is_array($labels) ? array_filter($labels) : $labels,
				'description'        => __( $this->wdpt_get_meta($post->ID, 'description'), 'Add New Custom Post' ),
				'public'             => $this->wdpt_get_meta($post->ID, 'public'),
				'publicly_queryable' => $this->wdpt_get_meta($post->ID, 'publicly_queryable'),
				'show_ui'            => $this->wdpt_get_meta($post->ID, 'show_ui'),
				'show_in_menu'       => $this->wdpt_get_meta($post->ID, 'show_in_menu'),
				'query_var'          => $this->wdpt_get_meta($post->ID, 'query_var'),
				'rewrite'            => is_array($rewrite) ? array_filter($rewrite) : $rewrite,
				'has_archive'        => $this->wdpt_get_meta($post->ID, 'has_archive'),
				'hierarchical'       => $this->wdpt_get_meta($post->ID, 'hierarchical'),
				'menu_position'      => $this->wdpt_get_meta($post->ID, 'menu_position'),
				'menu_icon'          => $this->wdpt_get_meta($post->ID, 'menu_icon'),
				'supports'           =>  $supports,
				'taxonomies'         =>  $dtaxonomies,
			);
			$args = array_filter($args);
			$post_type = $this->wdpt_get_meta($post->ID, 'post_type');
			$args = wdpt_type_format($args, $post_type);
			add_post_type_support( $post_type, 'thumbnail' );
			register_post_type( $post_type, $args );
			$this->wdpt_create_custom_taxonomies( $post->ID, $post_type );
			$this->wdpt_create_custom_meta( $post->ID, $post_type );
		}
		function wdpt_add_column_list_handler( $columns ) {
			global $field, $title;
			//$new_columns = array(field => esc_html__( $title, WDTP_POST_TYPE ),);
        	//return array_merge($columns, $new_columns);
			$columns[$field] = esc_html__( $title, WDTP_POST_TYPE );
        	return $columns;
		}
		//create post type meta
		function wdpt_create_custom_meta($pos_id, $post_type){
			$_meta = get_post_meta( $pos_id, 'meta', false );
			$_meta = isset($_meta[0]) && !is_array($_meta[0]) ? json_decode($_meta[0], true) : $_meta;
			$_groups = get_post_meta( $pos_id, 'group', false );
			$_groups = isset($_groups[0]) && !is_array($_groups[0]) ? json_decode($_groups[0], true) : $_groups;
			$groups = array();
			if(!is_array($_meta) || !is_array($_groups)) return;
			foreach($_groups as $key=>$value){
					$groups[$value][] = $key;
			}
			$this->groups = $groups;
			$this->post_type = $post_type;
			add_action( 'add_meta_boxes', array($this, 'wdpt_add_boxes'));
		}
		//create post type meta box
		function wdpt_add_boxes(){
			global $group, $counter;
			$counter = 0;
			$groups = $this->groups;
			$post_type = $this->post_type;
			foreach($groups as $key=>$value){
				$id = 'wdpt_post_'.$key;
				$title = get_the_title($key);
				$type = get_post_meta( $key, 'type', true );
				$position = get_post_meta( $key, 'position', true );
				$priority = get_post_meta( $key, 'priority', true );
				add_meta_box(
					$id,                 
					$title,     
					array($this, 'wdpt_create_meta_html'), 
					$post_type,
					$position,
					$priority                   
				);
				$group = $value;
			}
		}
		//create post type meta html
		function wdpt_create_meta_html($cpost){
			global $counter, $group;
			$groups = $this->groups;
			$i=0;
			$output = '';
			foreach($groups as $key=>$fields){
				if($counter==$i){
					foreach($fields as $fkey=>$field){
						$output = '';
						$meta_post = get_post($field);
						$title = $meta_post->post_title;
						$name = $meta_post->post_name;
						$val = get_post_meta( $cpost->ID, $name, true );
						$type = get_post_meta( $field, 'type', true );
						$required = get_post_meta( $field, 'required', true );
						$default = get_post_meta( $field, 'default', true );
						$description = get_post_meta( $field, 'description', true );
						switch($type){
							case 'textarea':
								$output .= '<div class="input-wrp '.$type.'">
												<h2 class="label" style="padding: 10px 0px 0px 0px">'.wdpt_esc( $title, 'label' ).'</h2>
												<textarea name="wdpt_args[textarea]['.$name.']" id="'.$name.'-'.$field.'">'.wdpt_esc(!empty($val) ? $val : $default, 'textarea').'</textarea>
										   </div> ';
								break;
							case 'select':
								$options = explode("\n", str_replace("\r", "", $default));
								$selected ='';
								$_option = '';
								foreach($options as $option){
									if (stripos($option, "|") !== false) {
										$ex_opt = explode("|", $option);
										$sval =strtolower(str_replace(" ", "-", $ex_opt[1]));
										$eval =$ex_opt[0];
									} else {
										$sval =strtolower(str_replace(" ", "-", $option));
										$eval =$option;
									}
									$sval =strtolower(str_replace(" ", "-", $option));
									$selected = (!empty($val) && $val == $sval) ? ' selected="selected"' : '';
									$_option .= '<option value="'.wdpt_esc($sval, 'text').'"'.$selected.'>'.wdpt_esc($eval).'</option>';
								}
								$output = '<div class="input-wrp '.$type.'">
												<h2 class="label" style="padding: 10px 0px 0px 0px">'.wdpt_esc( $title, 'label' ).'</h2>
												<select name="wdpt_args[select]['.$name.']" id="'.$name.'-'.$field.'" class="wdpt-select wdpt-'.$type.'-field">
													'.$_option.'
												</select>
										   </div>';
								break;
							case 'multiple':
								$options = explode("\n", str_replace("\r", "", $default));
								$selected ='';
								$_option = '';
								foreach($options as $option){
									$_option .= '<option value="'.wdpt_esc(strtolower(str_replace(" ", "-", $option)), 'text').'"'.$selected.'>'.wdpt_esc($option).'</option>';
								}
								$output = '<div class="input-wrp '.$type.'">
												<h2 class="label" style="padding: 10px 0px 0px 0px">'.wdpt_esc( $title, 'label' ).'</h2>
												<select name="wdpt_args[select]['.$name.']" id="'.$name.'-'.$field.'" class="wdpt-select wdpt-'.$type.'-field" multiple>
													'.$_option.'
												</select>
										   </div>';
								break;
							case 'checkbox':
								$options = explode("\n", str_replace("\r", "", $default));
								$checked ='';
								$checkbox = '';
								foreach($options as $option){
									$cval =strtolower(str_replace(" ", "-", wdpt_esc($option, 'text')));
									$checked = (isset($val[$cval]) && $val[$cval] == $cval) ? ' checked="checked"' : '';
									$checkbox .= '<label for="wdpt_field"><input type="checkbox" name="wdpt_args[text]['.wdpt_esc($name, 'text').']['.wdpt_esc($cval, 'text').']" id="'.wdpt_esc($name, 'text').'-'.wdpt_esc($field, 'text').'" class="wdpt-radio wdpt-'.wdpt_esc($type, 'text').'-field" value="'.wdpt_esc($cval, 'text').'"'.$checked.' /><strong>'.$option.'</strong></label></br>';
								}
								$output = '<div class="input-wrp '.$type.'">
												<h2 class="label" style="padding: 10px 0 6px 0">'.wdpt_esc( $title, 'label' ).'</h2>
												'.$checkbox.'
										   </div>';
								break;
							case 'radio':
								$options = explode("\n", str_replace("\r", "", $default));
								$checked ='';
								$radio = '';
								foreach($options as $option){
									$cval =strtolower(str_replace(" ", "-", wdpt_esc($option, 'text')));
									$checked = (!empty($val) && $val == $cval) ? ' checked="checked"' : '';
									$radio .= '<label for="wdpt_field"><input type="radio" name="wdpt_args['.wdpt_esc($name, 'text').']" id="'.wdpt_esc($name, 'text').'-'.wdpt_esc($field, 'text').'" class="wdpt-radio wdpt-'.wdpt_esc($type, 'text').'-field" value="'.wdpt_esc($cval, 'text').'"'.$checked.' /><strong>'.$option.'</strong></label></br>';
								}
								$output = '<div class="input-wrp '.$type.'">
												<h2 class="label" style="padding: 10px 0 6px 0">'.wdpt_esc( $title, 'label' ).'</h2>
												'.$radio.'
										   </div>';
								break;
							case 'upload':
								$output = '<div class="input-wrp '.$type.'">
												<h2 class="label" style="padding: 10px 0px 0px 0px">'.wdpt_esc( $title, 'label' ).'</h2>
												'.wdpt_multi_media_uploader_field( $name, !empty($val) ? $val : $default ).'
										   </div>';
								break;
							case 'wysiwyg editor':
							    wdpt_esc_e('<div class="input-wrp '.wdpt_esc($type, 'text').'">
												<h2 class="label" style="padding: 10px 0px 0px 0px">'.wdpt_esc( $title, 'label' ).'</h2>');
								wp_editor( !empty($val) ? $val : $default , $name, array(
									'wpautop'       => true,
									'media_buttons' => false,
									'textarea_name' => 'wdpt_args['.$name.']',
									'editor_class'  => 'my_custom_class',
									'textarea_rows' => 10
								) );
								wdpt_esc_e('</div>');
								break;
							case 'color':
								if(!empty($default)){
									$options = explode("\n", str_replace("\r", "", $default));
									$checked ='';
									$radio = '';
									if(sizeof($options)>=1){
										foreach($options as $option){
											$ovalues = explode("|", $option);
											if(sizeof($ovalues)>=1){
												foreach($ovalues as $ovalue){
													$output = '<div class="options-wrp '.$type.'-optiton">
																	<label for="Colors" style="padding: 10px 0px 0px 0px">'.wdpt_esc( $title, 'label' ).'</h2>
																	<input type="text" class="wdpt_color_pick" name="wdpt_args['.wdpt_esc($name, 'text').']" id="'.wdpt_esc($name, 'text').'-'.wdpt_esc($field, 'text').'" value="'.wdpt_esc(!empty($val) ? $val : $default, 'text').'" />
													
															   </div> ';
												}
											}
										}
									}
								}else{
									$inputs = '<input type="text" class="wdpt_color_pick" name="wdpt_args['.wdpt_esc($name, 'text').']" id="'.wdpt_esc($name, 'text').'-'.wdpt_esc($field, 'text').'" value="'.wdpt_esc(!empty($val) ? $val : $default, 'text').'" />';
								}
								$output = '<div class="input-wrp '.$type.'-wrp">
														<h2 class="label" style="padding: 10px 0px 0px 0px">'.wdpt_esc( $title, 'label' ).'</h2>
														'.$inputs.'
										
												   </div> ';
								break;
							default:
								$output = '<div class="input-wrp '.$type.'-wrp">
												<h2 class="label" style="padding: 10px 0px 0px 0px">'.wdpt_esc( $title, 'label' ).'</h2>
	<input type="'.wdpt_esc($type, 'text').'" name="wdpt_args['.$type.']['.wdpt_esc($name, 'text').']" id="'.wdpt_esc($name, 'text').'-'.wdpt_esc($field, 'text').'" value="'.wdpt_esc(!empty($val) ? $val : $default, 'text').'" />
										   </div> ';
								break;	
						}
						wdpt_esc_e($output, 'raw');
					}
				}
				$i++;
			}
			$counter++;
		}
		//create post type taxonomies
		function wdpt_create_custom_taxonomies($pos_id, $post_type){
			global $field, $title;
			$_taxonomies = get_post_meta( $pos_id, 'taxonomies', false );
			$_taxonomies = isset($_taxonomies[0]) && !is_array($_taxonomies[0]) ? json_decode($_taxonomies[0], true) : $_taxonomies;
			if(is_array($_taxonomies) && sizeof($_taxonomies)>=1){
				foreach($_taxonomies as $key=>$value){
					if($value != '1-d'){
						$taxonomy = get_post( $value );
						if(isset($taxonomy->ID)){
							$rewrite = get_post_meta( $taxonomy->ID, 'rewrite', true );
							$rewrite = json_decode(stripslashes($rewrite), true);
							$with_front = get_post_meta( $taxonomy->ID, 'with_front', true );
							if(!empty($with_front)){
								$rewrite['with_front'] = $with_front;
							}
							$feeds = get_post_meta( $taxonomy->ID, 'feeds', true );
							if(!empty($feeds)){
								$rewrite['feeds'] = $feeds;
							}
							$pages = get_post_meta( $taxonomy->ID, 'pages', true );
							if(!empty($pages)){
								$rewrite['pages'] = $pages;
							}
							
							$_args = get_post_meta( $taxonomy->ID, 'args', true );
							$_args = !is_array($_args) ? json_decode($_args, true) : $_args;
							$taxonomy_key = get_post_meta( $taxonomy->ID, 'taxonomy_key', true );
							$taxonomy_key = !empty($taxonomy_key) ? $taxonomy_key : $taxonomy->post_name;
							$labels = isset($_args['labels']) && !is_array($_args['labels'])  ? json_decode($_args['labels'], true) : $_args['labels'];
							$labels['name'] = $taxonomy->post_title;
							$labels['menu_name'] = $taxonomy->post_title;
							$labels['popular_items'] = $taxonomy->post_title;
							
							$_args = array(
								'labels'             => is_array($labels) ? array_filter($labels) : $labels,
								'description'        => __( $this->wdpt_get_meta($taxonomy->ID, 'description'), 'Add New Custom Post' ),
								'public'             => $this->wdpt_get_meta($taxonomy->ID, 'public'),
								'publicly_queryable' => $this->wdpt_get_meta($taxonomy->ID, 'publicly_queryable'),
								'show_ui'            => $this->wdpt_get_meta($taxonomy->ID, 'show_ui'),
								'show_in_menu'       => $this->wdpt_get_meta($taxonomy->ID, 'show_in_menu'),
								'query_var'          => $this->wdpt_get_meta($taxonomy->ID, 'query_var'),
								'rewrite'            => is_array($rewrite) ? array_filter($rewrite) : $rewrite,
								'has_archive'        => $this->wdpt_get_meta($taxonomy->ID, 'has_archive'),
								'hierarchical'       => $this->wdpt_get_meta($taxonomy->ID, 'hierarchical'),
								'menu_position'      => $this->wdpt_get_meta($taxonomy->ID, 'menu_position'),
								'menu_icon'          => $this->wdpt_get_meta($taxonomy->ID, 'menu_icon'),
							);
							$_args = array_filter($_args);
							
							$args = wdpt_type_format($_args);
							register_taxonomy( $taxonomy_key, $post_type, $args );
							$manage_column = get_post_meta( $taxonomy->ID, 'manage_column', true );
							if($manage_column == 1){
								$field = $taxonomy_key;
								$title = $taxonomy->post_title;
								apply_filters('manage_'.$post_type.'_posts_columns', array($this, 'wdpt_add_column_list_handler'));
							}
						}
					}
				}
			}
		}
	}
	new WDTP_CUSTUM_TYPES();
}