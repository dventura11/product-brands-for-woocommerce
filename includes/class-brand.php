<?php

class Brand {
	
    private $brandId;
	/**
	 * Constructor.
	 */
	public function __construct( $id = 0) { 
        $this->brandId = $id;
	}
	
	public function getPercent() {
		return ($this->getTotalSales()/$this->getGoal())*100;
	}
	
	public function getGoal() {
		return get_woocommerce_term_meta( $this->brandId, 'crowd_funding_goal' );
	}    
    
	public function getDiscount() {
		return get_woocommerce_term_meta( $this->brandId, 'crowd_funding_discount' );
	}    
    
	public function getStartDate() {
		return get_woocommerce_term_meta( $this->brandId, 'start_crowd_funding_time' );
	}    
    
	public function getEndDate() {
		return get_woocommerce_term_meta( $this->brandId, 'end_crowd_funding_time' );
	}
    
    
	public function getDescription() {
		return  get_woocommerce_term_meta( $this->brandId, 'description', true );
	}
	
	public function getTimeLeft() {
		$endTime = get_woocommerce_term_meta( $this->brandId, 'end_crowd_funding_time' );                                
        	$seconds=strtotime($endTime) - strtotime('now');
        	$days_left=intval($seconds/60/60/24);
 
		return $days_left >=0 ? $days_left : 0;
	}
    
    public function applyDiscount() {
        $resume = array();
        $products = array();
        $discount = $this->getDiscount();
		$orders = $this->getOrdersWithBrandItems();        
        foreach ( $orders as $order ) {
            
			$items = $order->items; 
            
            foreach ( $items as $item ) {
                                
                if ( $item['line_subtotal'] == $item['line_total'] ) {

                    $val = ($item['line_total'] * $discount) /100;
                    $item['line_total'] -= $val;                                    
                    wc_update_order_item_meta( $item['item_id'], '_line_total', $item['line_total']);
                    
                }                          
                
                $product = array();
                
                $product["original_total"] = number_format($item['line_subtotal'], 2);
                $product["total"] = number_format($item['line_total'], 2);
                $product["discount"] = number_format($item['line_subtotal'] - $item['line_total'], 2);
                $product["productId"] = $item['product_id'];
                $product["productName"] = $item['name'];
                $product["quantity"] = $item['qty'];                
                $product["orderId"] = $order->id;
                                
                array_push($products, $product);
            }
			
			$order->calculate_totals();
        }
        
        $resume["products"] = $products;
        
        return $resume;
                
    }
    
    public function getImageSrc() {        
				
		$thumbnail_id = get_woocommerce_term_meta( $this->brandId, 'thumbnail_id', true );
		$image = wp_get_attachment_thumb_url( $thumbnail_id );
		
		$image = str_replace( ' ', '%20', $image );
		$image = str_replace( '-150x150', '', $image );
		$image = esc_url( $image );	
        
        return $image;	
    }
    
    public function getProducts() {
        global $wpdb;
        
		$products = $wpdb->get_col (
			$wpdb->prepare (
				"SELECT 
					object_id
				FROM 
					wp_term_relationships wptr
				WHERE 
					wptr.term_taxonomy_id = %d",
				$this->brandId
			)
		);
        
        return $products;       
    }
    
    public function getOrderIds() {
        global $wpdb;
        
        $orders = $wpdb->get_col (
            $wpdb->prepare (
                "SELECT id 
                FROM wp_posts 
                WHERE 
                    post_type = %s
                    and post_date BETWEEN %s and %s
					and post_status in (%s,%s)",
				"shop_order",
                date("Y-m-d H:i:s",strtotime($this->getStartDate())),
				date("Y-m-d H:i:s",strtotime($this->getEndDate())),
				"wc-on-hold",
                "wc-pending"
            )
        );
        
        return $orders;
    }
    
    public function getOrders() {
        $orderIds = $this->getOrderIds();
        $orders = array();

        foreach ( $orderIds as $orderId ) {            
            array_push($orders, new WC_Order($orderId));
        }
        
        return $orders;
    }
    
    public function getOrdersWithBrandItems() {
        
        $brandProducts = $this->getProducts();
        $orders = $this->getOrders();        
        
        foreach ( $orders as $order ) {
			            
            $items = $order->get_items();
            $orderItems = array();
            
			foreach ( $items as $item_id => $item ) {
				if ( in_array ($item['product_id'], $brandProducts) ) {
                    $item['item_id'] = $item_id;
					array_push( $orderItems, $item );
				}
			}
			
			$order->items = $orderItems;
        }
        
        return $orders;
    }
    
    public function getTotalSales() {        
        $orders = $this->getOrdersWithBrandItems();
        
        $total = 0.0;
        
        foreach ( $orders as $order ) {
            foreach ( $order->items as $item ) {
                $total += floatval($item["line_subtotal"]);
            }
        }
        
        return $total;
    }
}

?>
