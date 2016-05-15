<div id="project-container" class="krown-tabs responsive-on clearfix">
   <div class="contents clearfix">
      <div id="description" style="display: block;">
         <div class="project-header">
            <div class="mini">
               <ul class="slides">
                  <li class="slide">
                      <img src="<?=$crowd_funding_brand_image ?>" width="845" alt="">
                  </li>
               </ul>
            </div>
         </div>
         <div class="project-content">
            <aside class="share-buttons clearfix">
               <p>Share this project</p>
               <div class="holder clearfix">
                  <a target="_blank" class="btn-twitter" href="https://twitter.com/home?status=<?= $_SERVER['REQUEST_URI'] ?>"></a>
                  <a target="_blank" class="btn-facebook" href="https://www.facebook.com/share.php?u=<?= $_SERVER['REQUEST_URI'] ?>"></a>
                  <a target="_blank" class="btn-gplus" href="https://plus.google.com/share?url=<?= $_SERVER['REQUEST_URI'] ?>"></a>
                  <a id="share-embed" class="social-share"><i class="fa fa-code"></i></a>
                  <div class="embed-box social-share" style="display: none;">
                     <code>&lt;iframe frameBorder="0" scrolling="no" src="http://backer-demo.krownthemes.com/?ig_embed_widget=1&amp;product_no=" width="214" height="366"&gt;&lt;/iframe&gt;</code>
                  </div>
               </div>
            </aside>
            <p>&nbsp;</p>
            <p> <?= $crowd_funding_brand_description ?></p>
         </div>
      </div>      
   </div>
   <aside id="project-sidebar" style="height: auto;">
      <div class="rtitle">
         <div class="krown-pie small" data-color="#43b3cf">
            <div class="holder">
               <span class="value" data-percent="<?=$crowd_funding_brand_percent ?>"><?=$crowd_funding_brand_percent ?></span>
               <div class="pie-holder">
                  <canvas class="pie-canvas" width="122" height="122"></canvas>
               </div>
               <div class="pie-holder">
                  <canvas class="pie-canvas" width="122" height="122"></canvas>
               </div>
            </div>
         </div>
         <p>Back this Project</p>
      </div>
      <div class="id-widget-wrap nofloat">
         <div class="id-widget id-full" data-projectid="12">
            <div class="id-product-infobox">
               <div class="panel clearfix">
                  <div class="product-wrapper clearfix">
                     <div class="pledge">
                        <h2 class="id-product-title"><a href="http://backer-demo.krownthemes.com/projects/audio-playlist">Audio Playlist</a></h2>
                        <div class="progress-wrapper">
                           <div class="krown-pie large" data-color="#43b3cf">
                              <div class="holder">
                                 <span class="value" data-percent="<?=$crowd_funding_brand_percent ?>"><?=$crowd_funding_brand_percent ?></span>
                                 <div class="pie-holder">
                                    <canvas class="pie-canvas" width="274" height="274"></canvas>
                                 </div>
                                 <div class="pie-holder">
                                    <canvas class="pie-canvas" width="274" height="274"></canvas>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- end progress wrapper --> 
                     </div>
                     <!-- end pledge -->
                     <div class="clearing"></div>
                     <div class="rholder">
                        <div class="rpdata">
                           <div class="id-progress-raised"> $ <?= number_format($crowd_funding_brand_goal, 2)?> </div>
                           <div class="id-product-funding">Meta</div>
                        </div>
                        <div class="rpdata">
                           <div class="id-product-total"> <?=$crowd_funding_brand_disacount ?>%</div>
                           <div class="id-product-pledges">Descuento</div>
                        </div>
                        <div class="rpdata">
                           <div class="id-product-days"><?= $crowd_funding_brand_restedTime ?></div>
                           <div class="id-product-days-to-go">Días restantes</div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- end product-wrapper -->	
               <!-- Project description -->
               <div class="id-product-description">He lay on his armour-like back, and if he lifted his head a little he could see his brown belly.</div>
               <!-- end id product description -->
               <!--Product Levels-->
               <!-- end product levels -->
            </div>
            <!-- end product-infobox -->
         </div>
         <!-- end id-widget -->
      </div>
      <!--if show author -->
   </aside>
</div>
