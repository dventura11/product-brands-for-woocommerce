<?php
/**
 * Show a grid of thumbnails
 */
?>
<div class="contents clearfix autop"><div style="display: block;"> 
    <div class="krown-id-grid default clearfix" id="ign-1753" style="position: relative; height: 434px;">
                
	<?php foreach ( $brands as $index => $brand ) : 
		
		$thumbnail = $image_size;

		$image = get_woocommerce_term_meta( $brand->term_id, 'thumbnail_id', true );

		$image_url = wp_get_attachment_image($image,$thumbnail, array('alt' => $brand->name)); 

		if ( ! $thumbnail ) $thumbnail = woocommerce_placeholder_img_src();    
            
        global $wpdb;
        
		$crowd_funding_goal = get_woocommerce_term_meta( $brand->term_id, 'crowd_funding_goal' );        
		$end_crowd_funding_time = get_woocommerce_term_meta( $brand->term_id, 'end_crowd_funding_time' );
        $restedTime = $end_crowd_funding_time;                
    /*
        //hay que hacer que aqui pueda tomar los dias
        $restedTime = strtotime($end_crowd_funding_time);
        $restedTime = $now - $restedTime;
        $restedTime = floor($restedTime/(60*60*24));
    */     
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
                    $brand->term_id
            )
        ); 
        $percent =  ($total/$crowd_funding_goal)*100;
        ?>
        
        <article class="krown-id-item" style="position: absolute; left: 0px; top: 0px;">

            <a class="fancybox-thumb" href="product_brands/<?= $brand->name ?>">
                <figure class="img">                    
                    <?= $image_url ?>
                </figure>
                <span></span>
            </a>
            <div class="container">

                <a href="product_brands/<?= $brand->name ?>">
                    <h3 class="title"><?= $brand->name ?></h3>
                </a>

                <div class="cats"></div>

                <section class="content"><?= $brand->name ?></section>

                <aside class="meta">

                    <div class="krown-pie small" data-color="">
                        <div class="holder">
                            <span class="value" data-percent="<?= $percent ?>">
                                <?= $percent ?><sup>%</sup>
                            </span>
                            <div class="pie-holder">
                                <canvas class="pie-canvas" width="122" height="122"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <ul>
                        <li><span>$<?= $crowd_funding_goal ?></span> Pledged</li>
                        <li><span><?= $restedTime ?></span> Días faltantes</li>
                    </ul>

                </aside>

            </div>
	   </article>
	<?php endforeach; ?>

    </div> 
</div>
<div style="display: none;"> 
    <div class="krown-id-grid default clearfix" id="ign-2722" style="position: relative; height: 0px;">        
    </div> 
</div>	    	