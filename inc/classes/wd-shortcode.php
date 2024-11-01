<?php
if(!class_exists('WDTP_POSTS_SHORTCODE')){
	class WDTP_POSTS_SHORTCODE{
		function __construct() {
			add_action('admin_menu', array($this, 'wdpt_menu_handler'));
		}
		function wdpt_menu_handler(){
			add_submenu_page(
				'edit.php?post_type=wdpt_types',
				__( 'Post Types Shortcodes', WDTP_POST_TYPE ),
				__( 'Shortcodes', WDTP_POST_TYPE ),
				'manage_options',
				'wdpt_shortcode',
				array($this, 'wdpt_shortcode_callback'),
				33
			);
		}
		function wdpt_shortcode_callback(){
			$args = array(
				'numberposts' => -1,
				'post_type' => 'wdpt-types',
				'post_status' => 'publish',
			);
			$types = get_posts( $args );
			if ( $types ) {
				foreach ( $types as $type ){
					$this->wdpt_list_group_code( $type );
					$this->wdpt_list_tax_code( $type );
					$this->wdpt_list_meta_code( $type );
				}
			}
			return;
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
				.input-wrp input {
					font-size: 1.2em;
				}
				.input-wrp input, .input-wrp select {
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
					})
				</script>
            <?php
		}
		function wdpt_list_group_code($type){}
		function wdpt_list_tax_code($type){}
		function wdpt_list_meta_code($type){}
	}
	new WDTP_POSTS_SHORTCODE();
}