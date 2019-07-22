<?php
/**
 * Journals - Tags List
 *
 * @package Coordinator\Modules\Journals
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 //api_checkAuthorization("journals-usage","dashboard");
 // include module template
 require_once(MODULE_PATH."template.inc.php");
 // set application title
 $app->setTitle(api_text("tags_list"));
 // build table
 $table=new strTable(api_text("tags_list-tr-unvalued"));
 $table->addHeader(api_text("tags_list-th-name"),"nowrap");
 $table->addHeader(api_text("tags_list-th-description"),null,"100%");
 $table->addHeaderAction(api_url(["scr"=>"tags_list","act"=>"tag_add"]),"fa-plus",api_text("table-td-add"),null,"text-right");
 // cycle all tags
 foreach(cJournalsTag::availables(true,["fkUser"=>$session->user->id]) as $tag_fobj){
  // build operation button
  $ob=new strOperationsButton();
  $ob->addElement(api_url(["scr"=>"tags_list","act"=>"tag_edit","idTag"=>$tag_fobj->id,"return"=>["scr"=>"tags_list"]]),"fa-pencil",api_text("table-td-edit"));
  if($tag_fobj->deleted){$ob->addElement(api_url(["scr"=>"submit","act"=>"tag_undelete","idTag"=>$tag_fobj->id,"return"=>["scr"=>"tags_list"]]),"fa-trash-o",api_text("table-td-undelete"),true,api_text("tags_list-td-undelete-confirm"));}
  else{$ob->addElement(api_url(["scr"=>"submit","act"=>"tag_delete","idTag"=>$tag_fobj->id,"return"=>["scr"=>"tags_list"]]),"fa-trash",api_text("table-td-delete"),true,api_text("tags_list-td-delete-confirm"));}
  // make table row class
  $tr_class_array=array();
  if($tag_fobj->id==$_REQUEST['idTag']){$tr_class_array[]="info";}
  if($tag_fobj->deleted){$tr_class_array[]="deleted";}
  // make tag row
  $table->addRow(implode(" ",$tr_class_array));
  $table->addRowField($tag_fobj->getTag(),"nowrap");
  $table->addRowField($tag_fobj->description,"truncate-ellipsis");
  $table->addRowField($ob->render(),"text-right");
 }












 // edit modal
 if(in_array(ACTION,["tag_add","tag_edit"])){
  // get object
  $selected_tag_obj=new cJournalsTag($_REQUEST['idTag']);
  // get form
  $notebook_form=$selected_tag_obj->form_edit(["return"=>["scr"=>"tags_list"]]);
  // replace fkUser form field
  $notebook_form->removeField("fkUser");
  $notebook_form->addField("hidden","fkUser",null,$GLOBALS['session']->user->id);
  // additional controls
  $notebook_form->addControl("button",api_text("form-fc-cancel"),"#",null,null,null,"data-dismiss='modal'");
  if($selected_tag_obj->id){
   if($selected_tag_obj->deleted){
    $notebook_form->addControl("button",api_text("form-fc-undelete"),api_url(["scr"=>"submit","act"=>"tag_undelete","idTag"=>$selected_tag_obj->id]),"btn-warning");
    $notebook_form->addControl("button",api_text("form-fc-remove"),api_url(["scr"=>"submit","act"=>"tag_remove","idTag"=>$selected_tag_obj->id]),"btn-danger",api_text("tags_list-modal-fc-remove-confirm"),null,null,(!$selected_tag_obj->tasks_count()));
   }else{$notebook_form->addControl("button",api_text("form-fc-delete"),api_url(["scr"=>"submit","act"=>"tag_delete","idTag"=>$selected_tag_obj->id]),"btn-danger",api_text("tags_list-modal-fc-delete-confirm"));}
  }
  // build modal
  $modal=new strModal(api_text("tags_list-modal-title-".($selected_tag_obj->id?"edit":"add"),$selected_tag_obj->name),null,"tags_list-edit");
  $modal->setBody($notebook_form->render());
  // add modal to application
  $app->addModal($modal);
  // modal scripts
  $app->addScript("$(function(){\$('#modal_tags_list-edit').modal({show:true,backdrop:'static',keyboard:false});});");
 }

 // build grid object
 $grid=new strGrid();
 $grid->addRow();
 $grid->addCol($table->render(),"col-xs-12");
 // add content to application
 $app->addContent($grid->render());
 // renderize application
 $app->render();
 // debug
 if($selected_tag_obj->id){api_dump($selected_tag_obj,"selected tag object");}
?>