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
	
	public function getTimeLeft( $brandId ) {
		$endTime = get_woocommerce_term_meta( $brandId, 'end_crowd_funding_time' );                                
    /*
        //hay que hacer que aqui pueda tomar los dias
        $restedTime = strtotime($end_crowd_funding_time);
        $restedTime = $now - $restedTime;
        $restedTime = floor($restedTime/(60*60*24));
    */  
		return $endTime;
	}
    
}

?>