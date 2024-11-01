<?php
//Class to create custom post of meta fields
if(!class_exists('WDTP_CUSTUM_POST_META')){
	class WDTP_CUSTUM_POST_META{
		function __construct() {
			add_action( 'init', array($this, 'wdpt_post_meta') );
			add_action( 'add_meta_boxes', array($this, 'wdpt_add_meta_boxes') );
		}
		//register meta post
		function wdpt_post_meta(){
		   $labels = array(
				'name'               => _x( 'Post Meta', 'post type general name', WDTP_POST_TYPE ),
				'singular_name'      => _x( 'Post Meta', 'post type singular name', WDTP_POST_TYPE ),
				'menu_name'          => _x( 'Post Meta', 'admin menu', WDTP_POST_TYPE ),
				'name_admin_bar'     => _x( 'Post Meta', 'add new on admin bar', WDTP_POST_TYPE ),
				'add_new'            => _x( 'Add New', 'product', WDTP_POST_TYPE ),
				'add_new_item'       => __( 'Add New Meta', WDTP_POST_TYPE ),
				'new_item'           => __( 'New Post Meta', WDTP_POST_TYPE ),
				'edit_item'          => __( 'Edit Post Meta', WDTP_POST_TYPE ),
				'view_item'          => __( 'View Post Meta', WDTP_POST_TYPE ),
				'all_items'          => __( 'All Meta', WDTP_POST_TYPE ),
				'search_items'       => __( 'Search Post Meta', WDTP_POST_TYPE ),
				'not_found'          => __( 'No Post Meta found.', WDTP_POST_TYPE ),
				'not_found_in_trash' => __( 'No Post Meta found in Trash.', WDTP_POST_TYPE )
			);
		    $args = array(
				'labels'             => $labels,
				'description'        => __( 'Description.', 'Add New Custom Post', WDTP_POST_TYPE ),
				'public'             => true,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => false,
				'rewrite'            => array( 'slug' => 'wdpt_meta' ),
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 10,
				'supports'           => array( 'title' ),
				'show_in_menu' => 'edit.php?post_type=wdpt_types'
			);
			register_post_type( 'wdpt_meta', $args );
		}
		//add required meta in meta type
		function wdpt_add_meta_boxes(){
			add_meta_box(
				'wdpt_meta_args',                 
				__( 'Meta Options', WDTP_POST_TYPE ),     
				array($this, 'wdpt_custom_meta_html'), 
				'wdpt_meta',
				'normal'                      
			);
			add_meta_box(
				'wdpt_meta_description_args',                 
				__( 'Meta Description', WDTP_POST_TYPE ),     
				array($this, 'wdpt_custom_meta_desc_html'), 
				'wdpt_meta',
				'side'                      
			);
		}
		// dashboar meta fields html
		function wdpt_custom_meta_html($post){
			$type = get_post_meta( $post->ID, 'type', true );
			$required = get_post_meta( $post->ID, 'required', true );
			$default = get_post_meta( $post->ID, 'default', true );
			$data = get_post_meta( $post->ID, 'data', true );
			$meta_name = isset($post->ID) && !empty($post->ID) ? ' ( Use to get meta : '.$post->post_name.')' : '';
			?>
            <style>
				.inputs-wrp {
					margin-top: 15px;
				}
				.inputs-wrp .input-wrp {
					width: 100%;
					margin-bottom: 20px;
				}
				.inputs-wrp .input-wrp input[type='checkbox'] {
   					margin-top: 2px;
					width:auto;
				}
				.inputs-wrp .input-wrp input {
					font-size: 1.2em;
				}
				.inputs-wrp .input-wrp input, .inputs-wrp .input-wrp select {
					padding: 3px 8px;
					line-height: 100%;
					width: 100%;
					outline: 0;
					margin: 10px 0 3px;
					background-color: #fff;
				}
				.inputs-wrp .input-wrp textarea {
					padding: 3px 8px;
					line-height: 100%;
					height: 10.7em;
					width: 100%;
					outline: 0;
					margin: 10px 0 3px;
					background-color: #fff;
				}
			</style>
            <div class="inputs-wrp">
				<div class="input-wrp">
                    <label for="wdpt_field"><strong><?php wdpt_esc_e( 'Meta Type'.$meta_name, 'label' );?></strong></label>
                    <select name="wdpt_args[select][type]" id="field-type" class="wdpt_select wdpt_field">
                    	<option value=""><?php wdpt_esc_e( '...Choose' );?></option>
                        <option value="text"<?php wdpt_esc_e($type == 'text' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Text');?></option>
                        <option value="textarea"<?php wdpt_esc_e($type == 'textarea' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Textarea', 'label' );?></option>
                        <option value="select"<?php wdpt_esc_e($type == 'select' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Select', 'label' );?></option>
<!--                    <option value="multiple"<?php wdpt_esc_e($type == 'multiple' ? ' selected="selected"' : '', 'text');?>>Select multiple</option>-->
                        <option value="checkbox"<?php wdpt_esc_e($type == 'checkbox' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Checkbox', 'label' );?></option>
                        <option value="radio"<?php wdpt_esc_e($type == 'radio' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Radio', 'label' );?></option>
                        <option value="upload"<?php wdpt_esc_e($type == 'upload' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Upload', 'label' );?></option>
                        <option value="wysiwyg editor"<?php wdpt_esc_e($type == 'wysiwyg editor' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Wysiwyg editor', 'label' );?></option>
                        <option value="number"<?php wdpt_esc_e($type == 'number' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Number', 'label' );?></option>
	                    <option value="tel"<?php wdpt_esc_e($type == 'tel' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Phone', 'label' );?></option>
                        <option value="color"<?php wdpt_esc_e($type == 'color' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Color', 'label' );?></option>
	                    <option value="date"<?php wdpt_esc_e($type == 'date' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Date', 'label' );?></option>
                        <option value="datetime-local"<?php wdpt_esc_e($type == 'datetime-local' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Date time', 'label' );?></option>
	                    <option value="email"<?php wdpt_esc_e($type == 'email' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Email', 'label' );?></option>
                        <option value="month"<?php wdpt_esc_e($type == 'month' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Month', 'label' );?></option>
	                    <option value="time"<?php wdpt_esc_e($type == 'time' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Time', 'label' );?></option>
                        <option value="url"<?php wdpt_esc_e($type == 'url' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'URL', 'label' );?></option>
	                    <option value="week"<?php wdpt_esc_e($type == 'week' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Week', 'label' );?></option>
	                    <option value="icon"<?php wdpt_esc_e($type == 'icon' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Icon', 'label' );?></option>
                    </select>
               </div>
				<div class="input-wrp">
                    <label for="wdpt_field"><strong><?php wdpt_esc_e( 'Data Type', 'label' );?></strong></label>
                    <select name="wdpt_args[select][data]" id="data-type" class="wdpt_select wdpt_field">
                    	<option value="free"<?php wdpt_esc_e($data == 'free' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Free Text', 'label' );?></option>
                        <option value="price"<?php wdpt_esc_e($data == 'price' ? ' selected="selected"' : '', 'text');?>><?php wdpt_esc_e( 'Price', 'label' );?></option>
                    </select>
               </div>
				<div class="input-wrp">
                    <label for="wdpt_field"><strong><?php wdpt_esc_e( 'Required', 'label' );?></strong></label>
                    <select name="wdpt_args[select][required]" id="required" class="wdpt_select wdpt_field">
                    	<option value="false"<?php wdpt_esc_e( $required == 'false' ? ' selected="selected"' : '' );?>><?php wdpt_esc_e( 'false', 'label' );?></option>
                        <option value="true"<?php wdpt_esc_e( $required == 'true' ? ' selected="selected"' : '' );?>><?php wdpt_esc_e( 'true', 'label' );?></option>
                    </select>
               </div>
               <div class="input-wrp">
                    <label for="wdpt_field"><strong><?php wdpt_esc_e( 'Default Value(Emty or single line)/Options(Multi lines:Name|Value)', 'label' );?></strong></label>
                    <textarea name="wdpt_args[textarea][default]"><?php wdpt_esc_e( !empty($default) ? $default : '', 'textarea' );?></textarea>
               </div>      
            </div>
			<?php
		}
		// dashboar group fields description html
		function wdpt_custom_meta_desc_html($post){
			$description = get_post_meta( $post->ID, 'description', true );
			?>
            <div class="inputs-wrp">
               <div class="input-wrp">
                    <label for="wdpt_field"><strong><?php wdpt_esc_e( 'Meta Description', 'label' );?></strong></label>
                    <textarea name="wdpt_args[textarea][description]"><?php wdpt_esc_e( !empty($description) ? $description : '', 'textarea' );?></textarea>
               </div>    
            </div>
			<?php
		}
	}
	new WDTP_CUSTUM_POST_META();
}