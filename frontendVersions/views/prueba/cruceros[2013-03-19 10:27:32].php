            ﻿<!DOCTYPE html PUBLIC "litelighterstyle" style="color:#992">"-//W3C//DTD XHTML 1.0 Strict//EN" "litelighterstyle" style="color:#992">"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
            "litelighterstyle" style="color:#2F2;"><html xmlns="litelighterstyle" style="color:#992">"http://www.w3.org/1999/xhtml">
                "litelighterstyle" style="color:#2F2;"><head>
                    "litelighterstyle" style="color:#2F2;"><meta http-equiv="litelighterstyle" style="color:#992">"Content-Type" content="litelighterstyle" style="color:#992">"text/html; charset=utf-8" />
                    "litelighterstyle" style="color:#2F2;"><meta name="litelighterstyle" style="color:#992">"format-detection" content="litelighterstyle" style="color:#992">"telephone=no" />
                
          "litelighterstyle" style="color:#F22">				<link rel="stylesheet" type="text/css" href="http://192.168.0.12/sistemas/FRAMEWORKS/CMS_CRUD//uploads/files/resources/css/ba378-base.css" />
				<script type="text/javascript" src="http://192.168.0.12/sistemas/FRAMEWORKS/CMS_CRUD//uploads/files/resources/js/05d0d-jquery.min.js"></script>
				<link rel="stylesheet" type="text/css" href="http://192.168.0.12/sistemas/FRAMEWORKS/CMS_CRUD//uploads/files/resources/css/2d935-showcase.css" />

     "litelighterstyle" style="color:#2F2;"><link rel="litelighterstyle" style="color:#992">"stylesheet" type="litelighterstyle" style="color:#992">"text/css" href="litelighterstyle" style="color:#992">"css/base.css" />
                    "litelighterstyle" style="color:#2F2;"><link rel="litelighterstyle" style="color:#992">"stylesheet" type="litelighterstyle" style="color:#992">"text/css" href="litelighterstyle" style="color:#992">"css/showcase.css" />
            
                    "litelighterstyle" style="color:#2F2;"><script type="litelighterstyle" style="color:#992">"text/javascript" src="litelighterstyle" style="color:#992">"js/jquery.min.js"></span>class="litelighterstyle" style="color:#2F2;"></script>
                     "litelighterstyle" style="color:#2F2;"><script type="litelighterstyle" style="color:#992">"text/javascript" src="litelighterstyle" style="color:#992">"js/jquery.bxSlider.min.js"></span>class="litelighterstyle" style="color:#2F2;"></script>
            
                    "litelighterstyle" style="color:#2F2;"><script type="litelighterstyle" style="color:#992">"text/javascript" src="litelighterstyle" style="color:#992">"js/showcase.js"></span>class="litelighterstyle" style="color:#2F2;"></script>
                    "litelighterstyle" style="color:#2F2;"><script type="litelighterstyle" style="color:#992">"text/javascript" src="litelighterstyle" style="color:#992">"js/tools-manager.js"></span>class="litelighterstyle" style="color:#2F2;"></script>
                    "litelighterstyle" style="color:#2F2;"><script type="litelighterstyle" style="color:#992">"text/javascript" src="litelighterstyle" style="color:#992">"Cru/includes/javascripts/boletin.js"></span>class="litelighterstyle" style="color:#2F2;"></script>
                    "litelighterstyle" style="color:#2F2;"><script type="litelighterstyle" style="color:#992">"text/javascript" src="litelighterstyle" style="color:#992">"js/analytics.js"></span>class="litelighterstyle" style="color:#2F2;"></script>
                        
            "litelighterstyle" style="color:#2F2;"></head>
            
            "litelighterstyle" style="color:#2F2;"><body>
            "litelighterstyle" style="color:#2F2;"><div class="litelighterstyle" style="color:#992">"wrap">
                  
                  "litelighterstyle" style="color:#999"><!-- Catalogo de Productos -->
                  "litelighterstyle" style="color:#2F2;"><div class="litelighterstyle" style="color:#992">"catalog-showcase">
                    "litelighterstyle" style="color:#2F2;"><ul>
                        "litelighterstyle" style="color:#F22"><?php foreach($cruceros_elements as $cruceros_element): ?>             "litelighterstyle" style="color:#2F2;"><li class="litelighterstyle" style="color:#992">"catalog-item" id="litelighterstyle" style="color:#992">"<?php echo $cruceros_element["id"]; ?>">
                            "litelighterstyle" style="color:#2F2;"><div class="litelighterstyle" style="color:#992">"product-banner" rel="litelighterstyle" style="color:#992">"1">
                              "litelighterstyle" style="color:#2F2;"><h2 class="litelighterstyle" style="color:#992">"product-tag">
                                  "litelighterstyle" style="color:#F22"><?php echo $cruceros_element[$fieldTags->nombre]["data"]; ?>"litelighterstyle" style="color:#2F2;"><br />
                                  "litelighterstyle" style="color:#2F2;"><span>"litelighterstyle" style="color:#F22"><?php echo $cruceros_element[$fieldTags->lugar]["data"]; ?>"litelighterstyle" style="color:#2F2;"></span>
                              "litelighterstyle" style="color:#2F2;"></h2>
                              "litelighterstyle" style="color:#2F2;"><a rel="litelighterstyle" style="color:#992">"1" href="litelighterstyle" style="color:#992">"#" class="litelighterstyle" style="color:#992">"product-shutter">
                                    "litelighterstyle" style="color:#2F2;"><img src="litelighterstyle" style="color:#992">"<?php echo base_url();?>uploads<?php echo $cruceros_element[$fieldTags->imagen]["data"]; ?>" alt="litelighterstyle" style="color:#992">"" />
                              "litelighterstyle" style="color:#2F2;"></a> 
                              "litelighterstyle" style="color:#999"><!-- Promocion -->
                              "litelighterstyle" style="color:#2F2;"><input type="litelighterstyle" style="color:#992">"hidden" value="litelighterstyle" style="color:#992">"">
                              "litelighterstyle" style="color:#2F2;"><input type="litelighterstyle" style="color:#992">"hidden" value="litelighterstyle" style="color:#992">"">
                            "litelighterstyle" style="color:#2F2;"></div>
                            "litelighterstyle" style="color:#2F2;"><div class="litelighterstyle" style="color:#992">"tab">
                              "litelighterstyle" style="color:#2F2;"><p class="litelighterstyle" style="color:#992">"product-destiny promotion">"litelighterstyle" style="color:#2F2;"><span>"litelighterstyle" style="color:#F22"><?php echo $cruceros_element[$fieldTags->precio]["data"]; ?>"litelighterstyle" style="color:#2F2;"></span>"litelighterstyle" style="color:#2F2;"></p>
                              "litelighterstyle" style="color:#2F2;"><a href="litelighterstyle" style="color:#992">"#" rel="litelighterstyle" style="color:#992">"1" class="litelighterstyle" style="color:#992">"btn detail-btn">Ver &rsaquo;"litelighterstyle" style="color:#2F2;"></a> "litelighterstyle" style="color:#2F2;"></div>
                          "litelighterstyle" style="color:#2F2;"></li>
                          "litelighterstyle" style="color:#F22"><?php endforeach; ?>           "litelighterstyle" style="color:#2F2;"></ul>
                  "litelighterstyle" style="color:#2F2;"></div>
                  "litelighterstyle" style="color:#2F2;"><div class="litelighterstyle" style="color:#992">"paginator-bar">
                    "litelighterstyle" style="color:#2F2;"><ul class="litelighterstyle" style="color:#992">"paginator">
                     
                      "litelighterstyle" style="color:#2F2;"><li> "litelighterstyle" style="color:#2F2;"><a class="litelighterstyle" style="color:#992">"arrow-btn prev" href="litelighterstyle" style="color:#992">"#page">&lt;"litelighterstyle" style="color:#2F2;"></a> "litelighterstyle" style="color:#2F2;"></li>
                      "litelighterstyle" style="color:#2F2;"><li>Página "litelighterstyle" style="color:#2F2;"><span>1"litelighterstyle" style="color:#2F2;"></span> de "litelighterstyle" style="color:#2F2;"><span>1"litelighterstyle" style="color:#2F2;"></span>"litelighterstyle" style="color:#2F2;"></li>
                     
                      "litelighterstyle" style="color:#2F2;"><li> "litelighterstyle" style="color:#2F2;"><a class="litelighterstyle" style="color:#992">"arrow-btn next" href="litelighterstyle" style="color:#992">"#page">&gt;"litelighterstyle" style="color:#2F2;"></a> "litelighterstyle" style="color:#2F2;"></li>
                    "litelighterstyle" style="color:#2F2;"></ul>
                  "litelighterstyle" style="color:#2F2;"></div>
                "litelighterstyle" style="color:#2F2;"></div>
                
              "litelighterstyle" style="color:#2F2;"></div>
              "litelighterstyle" style="color:#999"><!-- end board --> 
            "litelighterstyle" style="color:#2F2;"></div>
            "litelighterstyle" style="color:#2F2;"></div>
            "litelighterstyle" style="color:#2F2;"></body>
            "litelighterstyle" style="color:#2F2;"></html>   