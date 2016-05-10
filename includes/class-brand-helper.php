<?php

class BrandHelper {
	
	/**
	 * Constructor.
	 */
	public function __construct() { 
        
	}
	
	public function getTotalSales( $brandId ) {
		global $wpdb;
		
		$total = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT 
                    SUM(meta_value)
                FROM 
                    wp_woocommerce_order_itemmeta
                WHERE 
                    meta_key = '_line_total'
                    AND order_item_id IN (
                        SELECT 
                            order_item_id
                        FROM 
                            wp_woocommerce_order_itemmeta
                        WHERE 
                            meta_key = '_product_id'
                            AND META_VALUE IN (
                                SELECT 
                                    object_id
                                FROM 
                                    wp_term_relationships wptr
                                WHERE 
                                    wptr.term_taxonomy_id = %d                            
                        )
                    )
                   ",
                    $brandId
            )
        );
		
		return $total;
	}
	
	public function getPercent( $brandId ) {
		$crowd_funding_goal = get_woocommerce_term_meta( $brandId, 'crowd_funding_goal' );
		return ($this->getTotalSales( $brandId )/$crowd_funding_goal)*100;
	}
	
	public function getGoal( $brandId ) {
		return get_woocommerce_term_meta( $brandId, 'crowd_funding_goal' );
	}    
    
	public function getDiscount( $brandId ) {
		return get_woocommerce_term_meta( $brandId, 'crowd_funding_discount' );
	}
    
    
	public function getDescription( $brandId ) {
		return  get_woocommerce_term_meta( $brandId, 'description', true );
	}
	
	public function getTimeLeft( $brandId ) {
		$endTime = get_woocommerce_term_meta( $brandId, 'end_crowd_funding_time' );                                
        	$seconds=strtotime($endTime) - strtotime('now');
        	$days_left=intval($seconds/60/60/24);
 
		return $days_left >=0 ? $days_left : 0;
	}
    
    public function applyDiscount( $brandId ) {
        
		global $wpdb;
		
        $discount =  get_woocommerce_term_meta( $brandId, 'crowd_funding_discount' );
        
        $products = $wpdb->get_col (
            $wpdb->prepare (
                "SELECT 
                    order_item_id
                FROM 
                    wp_woocommerce_order_itemmeta im2
                WHERE 
                    im2.meta_key = '_product_id'
                    AND META_VALUE IN (
                        SELECT 
                            object_id
                        FROM 
                            wp_term_relationships wptr
                        WHERE 
                            wptr.term_taxonomy_id = %d
                        )",
                $brandId
            )
        );
                            
        foreach ( $products as $id ) {
            $wpdb->query (
                $wpdb->prepare (
                    "update
                        wp_woocommerce_order_itemmeta im
                    set                    
                        im.meta_value = im.meta_value - ( (im.meta_value * (%d / 100) ) )
                    WHERE 
                        im.meta_key = '_line_total' 
                        AND order_item_id = %d
                    ",
                    $discount,
                    $id                   
                )
            );            
        } 
    }
    
    public function getImageSrc( $brandId ) {        
				
		$thumbnail_id = get_woocommerce_term_meta( $brandId, 'thumbnail_id', true );
		$image = wp_get_attachment_thumb_url( $thumbnail_id );
		
		$image = str_replace( ' ', '%20', $image );
		$image = str_replace( '-150x150', '', $image );
		$image = esc_url( $image );	
        
        return $image;	
    }
    
}

?>
