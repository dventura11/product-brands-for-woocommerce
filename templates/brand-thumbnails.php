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
                
        $brandHelper = new Brand( $brand->term_id );
        $crowd_funding_goal = $brandHelper->getGoal();
        $percent = $brandHelper->getPercent();
        $restedTime = $brandHelper->getTimeLeft();
		            
        ?>
        
        <article class="krown-id-item" style="position: absolute; left: 0px; top: 0px;">

            <a class="fancybox-thumb" href="product_brands/<?= $brand->slug ?>">
                <figure class="img">                    
                    <?= $image_url ?>
                </figure>
                <span></span>
            </a>
            <div class="container">

                <a href="product_brands/<?= $brand->slug ?>">
                    <h3 class="title"><?= $brand->name ?></h3>
                </a>

                <div class="cats"></div>

                <section class="content"><?= $brand->name ?></section>

                <?php
                    if ($restedTime > 0) {
                ?>
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
                <?php 
                    } else { 
                ?>      
                    <h4>Finalizado.</h4>                    
                <?php 
                    } 
                ?>

            </div>
	   </article>
	<?php endforeach; ?>

    </div> 
</div>
<div style="display: none;"> 
    <div class="krown-id-grid default clearfix" id="ign-2722" style="position: relative; height: 0px;">        
    </div> 
</div>	    	