<?php
/**
 * Journals - Template
 *
 * @package Coordinator\Modules\Journals
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 // build application
 $app=new strApplication();
 // build nav object
 $nav=new strNav("nav-tabs");
 // dashboard
 $nav->addItem(api_icon("fa-th-large",null,"hidden-link"),api_url(["scr"=>"dashboard"]));
 // tags
 if(explode("_",SCRIPT)[0]=="tags"){
  $nav->addItem(api_text("nav-tags-list"),api_url(["scr"=>"tags_list"]));
  // operations
  if($tag_obj->id && in_array(SCRIPT,["tags_view","tags_edit"])){
   $nav->addItem(api_text("nav-operations"),null,null,"active");
   $nav->addSubItem(api_text("nav-tags-operations-edit"),api_url(["scr"=>"tags_edit","idTag"=>$tag_obj->id]),(api_checkAuthorization("journals-manage")));
  }else{
   $nav->addItem(api_text("nav-tags-add"),api_url(["scr"=>"tags_edit"]),(api_checkAuthorization("journals-manage")));
  }
 }
 // add nav to html
 $app->addContent($nav->render(false));
?>