<?php
/**
 * Journals - Tag Edit
 *
 * @package Coordinator\Modules\Journals
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 api_checkAuthorization("journals-manage","dashboard");
 // get objects
 $tag_obj=new cJournalsTag($_REQUEST['idTag']);
 // include module template
 require_once(MODULE_PATH."template.inc.php");
 // set application title
 $app->setTitle(($tag_obj->id?api_text("tags_edit",$tag_obj->subject):api_text("tags_edit-add")));
 // get form
 $form=$tag_obj->form_edit(["return"=>api_return(["scr"=>"tags_view"])]);
 // additional controls
 if($tag_obj->id){
  $form->addControl("button",api_text("form-fc-cancel"),api_return_url(["scr"=>"tags_view","idTag"=>$tag_obj->id]));
  if(!$tag_obj->deleted){
   $form->addControl("button",api_text("form-fc-delete"),api_url(["scr"=>"submit","act"=>"tag_delete","idTag"=>$tag_obj->id]),"btn-danger",api_text("tags_edit-fc-delete-confirm"));
  }else{
   $form->addControl("button",api_text("form-fc-undelete"),api_url(["scr"=>"submit","act"=>"tag_undelete","idTag"=>$tag_obj->id,"return"=>["scr"=>"tags_view"]]),"btn-warning");
   $form->addControl("button",api_text("form-fc-remove"),api_url(["scr"=>"submit","act"=>"tag_remove","idTag"=>$tag_obj->id]),"btn-danger",api_text("tags_edit-fc-remove-confirm"));
  }
 }else{$form->addControl("button",api_text("form-fc-cancel"),api_url(["scr"=>"tags_list"]));}
 // build grid object
 $grid=new strGrid();
 $grid->addRow();
 $grid->addCol($form->render(),"col-xs-12");
 // add content to application
 $app->addContent($grid->render());
 // renderize application
 $app->render();
 // debug
 api_dump($tag_obj,"tag object");
?>