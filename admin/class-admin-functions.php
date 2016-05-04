<?php

if ( ! defined( 'WPINC' ) ) { die; }
 
class Product_Brands_For_WooCommerce_Admin_Function {
	public function __construct(){
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
		add_action( 'wp_ajax_apply_discount', 'applyDiscount' );
		add_filter( 'manage_edit-product_brands_columns', array( $this, 'product_cat_columns' ) );
		add_filter( 'manage_product_brands_custom_column', array( $this, 'product_cat_column' ), 10, 3 );
		add_action( 'product_brands_add_form_fields', array( $this, 'add_category_fields' ) );
		add_action( 'product_brands_edit_form_fields', array( $this, 'edit_category_fields' ), 10 );
		add_action( 'created_term', array( $this, 'save_category_fields' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_category_fields' ), 10, 3 );
	}
	
	/**
	 * Renders the meta box on the post and pages.
	 *
	 * @since 0.1.0
	 */
	public function add_meta_box() {
		add_meta_box(PBF_WC_NAME,__('Product Brand Image',PBF_WC_TXT),array( $this, 'display_product_brand_image_selector' ),'product','side');
	}
	
	public function display_product_brand_image_selector( $post ) {  
		$image_id = get_post_meta( $post->ID,PBF_WC_DB.'post-image', true );
		$image_url = wp_get_attachment_image_src($image_id);
		$show_image = 'hidden';
		$show_add = '';
		if(is_array($image_url)){
			$image_url = $image_url[0];
			$show_image = '';
			$show_add = 'hidden';
		}
		
		echo	'<p class="hide-if-no-js '.$show_add.'">
    				<a title="'.__('Set Product Brand Image',PBF_WC_TXT).'" 
					   href="javascript:;" id="set-product-brand-image">'.__('Select Product Brand Image',PBF_WC_TXT).'</a>
				</p>
				<div id="product-brand-image-container" class="'.$show_image.'">
					<img src="'.$image_url.'" />
				</div> 
				
				<p class="hide-if-no-js '.$show_image.'">
					<a title="'.__('Remove Product Brand Image',PBF_WC_TXT).'" 
					   href="javascript:;" id="remove-product-brand-image">'.__('Remove Product Brand Image',PBF_WC_TXT).'</a>
				</p>
				<input type="hidden" name="product-brand-image-id"  id="product-brand-image-id" 
				value="'.$image_id.'"/>
';
	}
	
	public function save_post( $post_id ){
		if ( isset( $_REQUEST['product-brand-image-id'] ) ) {
			update_post_meta( $post_id, PBF_WC_DB.'post-image', sanitize_text_field( $_REQUEST['product-brand-image-id'] ) );
		}
	}
	
	/**
	 * Thumbnail column added to category admin.
	 *
	 * @param mixed $columns
	 * @return array
	 */
	public function product_cat_columns( $columns ) {
		$new_columns          = array();
		$new_columns['cb']    = $columns['cb'];
		$new_columns['thumb'] = __( 'Image', 'woocommerce' );

		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );
	}	
	/**
	 * Thumbnail column value added to category admin.
	 *
	 * @param mixed $columns
	 * @param mixed $column
	 * @param mixed $id
	 * @return array
	 */
	public function product_cat_column( $columns, $column, $id ) {

		if ( 'thumb' == $column ) {

			$thumbnail_id = get_woocommerce_term_meta( $id, 'thumbnail_id', true );

			if ( $thumbnail_id ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			} else {
				$image = wc_placeholder_img_src();
			}

			// Prevent esc_url from breaking spaces in urls for image embeds
			// Ref: http://core.trac.wordpress.org/ticket/23605
			$image = str_replace( ' ', '%20', $image );

			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Thumbnail', 'woocommerce' ) . '" class="wp-post-image" height="48" width="48" />';

		}

		return $columns;
	}	
	/**
	 * Category thumbnail fields.
	 */
	public function add_category_fields() {
		?>
		 
		<div class="form-field">
			<label><?php _e( 'Goal', PBF_WC_TXT ); ?></label>
			<input type="number" id="crowd_funding_goal" name="crowd_funding_goal" />
		</div>
		<div class="form-field">
			<label><?php _e( 'Start time', PBF_WC_TXT ); ?></label>
			<input type="datetime-local" id="start_crowd_funding_time" name="start_crowd_funding_time" />
		</div>
		<div class="form-field">
			<label><?php _e( 'End time', PBF_WC_TXT ); ?></label>
			<input type="datetime-local" id="end_crowd_funding_time" name="end_crowd_funding_time" />
		</div>
		<div class="form-field">
			<label><?php _e( 'Discount', PBF_WC_TXT ); ?></label>
			<input type="number" id="crowd_funding_discount" name="crowd_funding_discount" />
		</div>		
		<div class="form-field">
			<label><?php _e( 'Brand Logo', PBF_WC_TXT ); ?></label>
			<div id="product_brands_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
			<div style="line-height: 60px;">
				<input type="hidden" id="product_brands_thumbnail_id" name="product_brands_thumbnail_id" />
				<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
				<button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
			</div>
			<script type="text/javascript">

				// Only show the "remove image" button when needed
				if ( ! jQuery( '#product_brands_thumbnail_id' ).val() ) {jQuery( '.remove_image_button' ).hide();}

				// Uploading files
				var file_frame;

				jQuery( document ).on( 'click', '.upload_image_button', function( event ) {
					event.preventDefault();
					// If the media frame already exists, reopen it.
					if ( file_frame ) { file_frame.open(); return; }

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php _e( "Choose an image", "woocommerce" ); ?>',
						button: {
							text: '<?php _e( "Use image", "woocommerce" ); ?>'
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						var attachment = file_frame.state().get( 'selection' ).first().toJSON();
						jQuery( '#product_brands_thumbnail_id' ).val( attachment.id );
						jQuery( '#product_brands_thumbnail' ).find( 'img' ).attr( 'src', attachment.sizes.thumbnail.url );
						jQuery( '.remove_image_button' ).show();
					});
					// Finally, open the modal.
					file_frame.open();
				});

				jQuery( document ).on( 'click', '.remove_image_button', function() {
					jQuery( '#product_brands_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
					jQuery( '#product_brands_thumbnail_id' ).val( '' );
					jQuery( '.remove_image_button' ).hide();
					return false;
				});

			</script>
			<div class="clear"></div>
		</div>
		<?php
	}	
	
	/**
	 * Edit category thumbnail field.
	 *
	 * @param mixed $term Term (category) being edited
	 */
	public function edit_category_fields( $term ) {
 
		$thumbnail_id = absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );
		$crowd_funding_goal = get_woocommerce_term_meta( $term->term_id, 'crowd_funding_goal' );
		$start_crowd_funding_time = get_woocommerce_term_meta( $term->term_id, 'start_crowd_funding_time' );
		$end_crowd_funding_time = get_woocommerce_term_meta( $term->term_id, 'end_crowd_funding_time' );
		$crowd_funding_discount = get_woocommerce_term_meta( $term->term_id, 'crowd_funding_discount' );
		
		$brandHelper = new BrandHelper();
        $restedTime = $brandHelper->getTimeLeft( $term->term_id );		
		                       
		if ( $thumbnail_id ) {
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		} else {
			$image = wc_placeholder_img_src();
		}
		?> 
        <tr class="form-field">            
			<th scope="row" valign="top">
			<label><?php _e( 'Goal', PBF_WC_TXT ); ?></label>
            </td><td>
			     <input type="number" id="crowd_funding_goal" name="crowd_funding_goal" value="<?= $crowd_funding_goal;?>"/>
            </td>
		</tr>
		<tr class="form-field">
            <th scope="row" valign="top">
                <label><?php _e( 'Start time', PBF_WC_TXT ); ?></label>
            </td><td>
			     <input type="datetime-local" id="start_crowd_funding_time" name="start_crowd_funding_time" value="<?= $start_crowd_funding_time;?>" />
            </td>
		</tr>
		<tr class="form-field">
            <th scope="row" valign="top">
			     <label><?php _e( 'End time', PBF_WC_TXT ); ?></label>
            </td><td>
			     <input type="datetime-local" id="end_crowd_funding_time" name="end_crowd_funding_time" value="<?= $end_crowd_funding_time;?>" />
            </td>
		</tr>
        <tr class="form-field">            
			<th scope="row" valign="top">
			<label><?php _e( 'Discount', PBF_WC_TXT ); ?></label>
            </td><td>
			     <input type="number" id="crowd_funding_discount" name="crowd_funding_discount" value="<?= $crowd_funding_discount;?>"/>
            </td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Thumbnail', 'woocommerce' ); ?></label></th>
			<td>
				<div id="product_brands_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="product_brands_thumbnail_id" name="product_brands_thumbnail_id" value="<?php echo $thumbnail_id; ?>" />
					<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
					<button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
				</div>
				<script type="text/javascript">
					if ( '0' === jQuery( '#product_brands_thumbnail_id' ).val() ) { jQuery( '.remove_image_button' ).hide(); }
					var file_frame;

					jQuery( document ).on( 'click', '.upload_image_button', function( event ) {
						event.preventDefault();
						if ( file_frame ) { file_frame.open(); return; }

						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: '<?php _e( "Choose an image", "woocommerce" ); ?>',
							button: {
								text: '<?php _e( "Use image", "woocommerce" ); ?>'
							},
							multiple: false
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							var attachment = file_frame.state().get( 'selection' ).first().toJSON();

							jQuery( '#product_brands_thumbnail_id' ).val( attachment.id );
							jQuery( '#product_brands_thumbnail' ).find( 'img' ).attr( 'src', attachment.sizes.thumbnail.url );
							jQuery( '.remove_image_button' ).show();
						});

						// Finally, open the modal.
						file_frame.open();
					});

					jQuery( document ).on( 'click', '.remove_image_button', function() {
						jQuery( '#product_brands_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
						jQuery( '#product_brands_thumbnail_id' ).val( '' );
						jQuery( '.remove_image_button' ).hide();
						return false;
					});

				</script>
				<div class="clear"></div>
			</td>
		</tr>
		
		<?php if ( $restedTime == 0 ) { ?>	
		
			<tr class="form-field">            
				<th scope="row" valign="top">
				<label><?php _e( 'Apply Discount', PBF_WC_TXT ); ?></label>
				</td><td>
					<input type="checkbox" id="crowd_funding_apply_discount" name="crowd_funding_apply_discount"/>
				</td>
			</tr>
			
			<!--
			<tr class="form-field">
				<td>
					<p class="submit">
						<input onclick="callApplyDiscount();"; type="button" name="applyDiscount" id="applyDiscount" class="button button-primary" value="Aplicar descuento">
					</p>
					
					<script type="text/javascript" >
						function callApplyDiscount() {
							jQuery.post(
								ajaxurl, 
								{
									'action': 'apply_discount',
									'data':   'foobarid'
								}, 
								function(response){
									alert('The server responded: ' + response);
								}
							);
						}
					</script>			
				</td>
			</tr>
			-->
		<?php } 
	}
	
	/**
	 * save_category_fields function.
	 *
	 * @param mixed $term_id Term ID being saved
	 */
	public function save_category_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
                
        if ( 'product_brands' !== $taxonomy ) {
            return;            
        }
        
		if ( isset( $_POST['product_brands_thumbnail_id'] ) ) {
			update_woocommerce_term_meta( $term_id, 'thumbnail_id', absint( $_POST['product_brands_thumbnail_id'] ) );
		}
        
		if ( isset( $_POST['crowd_funding_goal'] ) ) {
			update_woocommerce_term_meta( $term_id, 'crowd_funding_goal', $_POST['crowd_funding_goal']);
		}
        
        if ( isset( $_POST['start_crowd_funding_time'] ) ) {
			update_woocommerce_term_meta( $term_id, 'start_crowd_funding_time', $_POST['start_crowd_funding_time']);
		}
        
        if ( isset( $_POST['end_crowd_funding_time'] ) ) {
			update_woocommerce_term_meta( $term_id, 'end_crowd_funding_time', $_POST['end_crowd_funding_time']);
		}
		
		if ( isset( $_POST['crowd_funding_discount'] ) ) {
			update_woocommerce_term_meta( $term_id, 'crowd_funding_discount', $_POST['crowd_funding_discount']);
		}
		
		if ( isset( $_POST['crowd_funding_apply_discount'] ) ) {
			$this->applyDiscount( $term_id );
		}
		
        
	}

	public function applyDiscount( $term_id ) {
		$brandHelper = new BrandHelper();
		$brandHelper->applyDiscount( $term_id );
	}	
}
?>
