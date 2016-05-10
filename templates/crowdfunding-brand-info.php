<?php
/*
 * Display_crowdfunding_brand_info
 */
?>

<div class="id-widget-wrap nofloat">	<div class="id-widget id-full" data-projectid="13">
<div class="id-product-infobox">
<div class="panel clearfix"><div class="product-wrapper clearfix">
<div class="pledge">
<h2 class="id-product-title"><a href="#"><?= $crowd_funding_brand_title ?></a></h2>
<div class="progress-wrapper"><div class="krown-pie large" data-color="#43b3cf"><div class="holder"><span class="value" data-percent="<?= $percent ?>"> <?= $percent ?> <sup>%</sup></span><div class="pie-holder"><canvas class="pie-canvas" width="274" height="274"></canvas></div></div></div></div>
<!-- end progress wrapper --> 
</div>

<!-- end pledge -->

<div class="clearing"></div>
<div class="rholder"><div class="rpdata"><div class="id-progress-raised"> $<?= $raised ?> </div><div class="id-product-funding">Pledged of <?= $crowd_funding_price_goal ?> Goal</div></div><div class="rpdata"><div class="id-product-total"> <?= $num_pledgers ?></div><div class="id-product-pledges">Pledgers</div></div></div>



</div><div class="id-product-proposed-end">Ended on <div class="id-widget-date">
<div class="id-widget-month"> <?= $month_end ?></div>
<div class="id-widget-day"> <?= $day_end ?></div>
<div class="id-widget-year"><?= $year_end ?></div>
</div>
</div><div class="btn-container" style="display: block;">
<a href=".idc_lightbox" class="main-btn krown-button medium color">Support Now</a>
</div></div>

<!-- end product-wrapper -->	

<!--
<div class="separator">&nbsp;<div id="project-p-author" class="panel clearfix">

<div class="comment-avatar">
<img alt="" src="http://2.gravatar.com/avatar/baec5328cff79443a8de1baa0870bfa8?s=65&amp;d=mm&amp;r=g" srcset="http://2.gravatar.com/avatar/baec5328cff79443a8de1baa0870bfa8?s=130&amp;d=mm&amp;r=g 2x" class="avatar avatar-65 photo" height="65" width="65" pagespeed_url_hash="29549993" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">	</div>

<div class="comment-content">

<span>Project by</span>

<h6>Diego Ventura</h6>

<ul class="author-meta">

<li>1 Project</li>
-->