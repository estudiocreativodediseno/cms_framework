          ﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
          <html xmlns="http://www.w3.org/1999/xhtml">
              <head>
                  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                  <meta name="format-detection" content="telephone=no" />
              
        				<link rel="stylesheet" type="text/css" href="http://192.168.0.12/sistemas/FRAMEWORKS/CMS_CRUD//uploads/files/resources/css/ba378-base.css" />
				<script type="text/javascript" src="http://192.168.0.12/sistemas/FRAMEWORKS/CMS_CRUD//uploads/files/resources/js/05d0d-jquery.min.js"></script>
				<link rel="stylesheet" type="text/css" href="http://192.168.0.12/sistemas/FRAMEWORKS/CMS_CRUD//uploads/files/resources/css/2d935-showcase.css" />

   <link rel="stylesheet" type="text/css" href="css/base.css" />
                  <link rel="stylesheet" type="text/css" href="css/showcase.css" />
          
                  <script type="text/javascript" src="js/jquery.min.js"></script>
                   <script type="text/javascript" src="js/jquery.bxSlider.min.js"></script>
          
                  <script type="text/javascript" src="js/showcase.js"></script>
                  <script type="text/javascript" src="js/tools-manager.js"></script>
                  <script type="text/javascript" src="Cru/includes/javascripts/boletin.js"></script>
                  <script type="text/javascript" src="js/analytics.js"></script>
                      
          </head>
          
          <body>
          <div class="wrap">
                
                <!-- Catalogo de Productos -->
                <div class="catalog-showcase">
                  <ul>
                      <?php echo json_encode($cruceros_elements); foreach($cruceros_elements as $cruceros_element): ?>             <li class="catalog-item" id="<?php echo $cruceros_element["id"]; ?>">
                          <div class="product-banner" rel="1">
                            <h2 class="product-tag">
                                <?php echo $cruceros_element[$fieldTags->nombre]["data"]; ?><br />
                                <span><?php echo $cruceros_element[$fieldTags->lugar]["data"]; ?></span>
                            </h2>
                            <a rel="1" href="#" class="product-shutter">
                                  <img src="<?php echo $cruceros_element[$fieldTags->imagen]["data"]; ?>" alt="" />
                            </a> 
                            <!-- Promocion -->
                            <input type="hidden" value="">
                            <input type="hidden" value="">
                          </div>
                          <div class="tab">
                            <p class="product-destiny promotion"><span><?php echo $cruceros_element[$fieldTags->precio]["data"]; ?></span></p>
                            <a href="#" rel="1" class="btn detail-btn">Ver &rsaquo;</a> </div>
                        </li>
                        <?php endforeach; ?>           </ul>
                </div>
                <div class="paginator-bar">
                  <ul class="paginator">
                    <li class="arrow-btn prev">
                    <li> <a class="arrow-btn prev" href="#page">&lt;</a> </li>
                    <li>Página <span>1</span> de <span>1</span></li>
                    <li class="arrow-btn next">
                    <li> <a class="arrow-btn next" href="#page">&gt;</a> </li>
                  </ul>
                </div>
              </div>
              
            </div>
            <!-- end board --> 
          </div>
          </div>
          </body>
          </html>   