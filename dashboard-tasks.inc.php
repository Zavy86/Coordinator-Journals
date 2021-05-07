<?php
/**
 * Journals - Dashboard (Tasks)
 *
 * @package Coordinator\Modules\Journals
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 // get object
 $tag_obj=new cJournalsTag($_REQUEST['idTag']);
 // check object
 if($tag_obj->id && $tag_obj->fkUser!=$session->user->id){api_alerts_add(api_text("cJournalsTag-alert-unauthorized"),"danger");api_redirect(api_url(["scr"=>"dashboard"]));}
// check for action
if(!defined(ACTION)&&!$tag_obj->id){define("ACTION","today");}
 // get tasks
 $tasks_array=array();
 if($tag_obj->id){$tasks_array=$tag_obj->getTasks();}
 else{
 	if(ACTION=="today"){$tasks_array=cJournalsTask::availables(false,["fkUser"=>$session->user->id,"today"=>true]);}
 	else{$tasks_array=cJournalsTask::availables(false,["fkUser"=>$session->user->id]);}
 }
 // build table
 $table=new strTable(api_text("dashboard-tasks-tr-unvalued"));
 // cycle all tasks
 foreach($tasks_array as $task_fobj){
  // build operation button
  $ob=new strOperationsButton();
  $ob->addElement(api_url(["scr"=>"dashboard","act"=>"task_view","idTag"=>$tag_obj->id,"idTask"=>$task_fobj->id]),"fa-info-circle",api_text("table-td-view"));
  if(!$task_fobj->today){$ob->addElement(api_url(["scr"=>"submit","act"=>"task_today","value"=>true,"idTask"=>$task_fobj->id,"return"=>["scr"=>"dashboard","idTag"=>$tag_obj->id]]),"fa-clock-o",api_text("dashboard-tasks-td-today"));}
 	else{$ob->addElement(api_url(["scr"=>"submit","act"=>"task_today","value"=>false,"idTask"=>$task_fobj->id,"return"=>["scr"=>"dashboard","idTag"=>$tag_obj->id]]),"fa-clock-o",api_text("dashboard-tasks-td-today"));}
  if(!$task_fobj->completed){
   $ob->addElement(api_url(["scr"=>"submit","act"=>"task_complete","idTask"=>$task_fobj->id,"return"=>["scr"=>"dashboard","idTag"=>$tag_obj->id]]),"fa-check-square-o",api_text("dashboard-tasks-td-complete"),true,api_text("dashboard-tasks-td-complete-confirm"));
   $ob->addElement(api_url(["scr"=>"dashboard","act"=>"task_edit","idTag"=>$tag_obj->id,"idTask"=>$task_fobj->id]),"fa-pencil",api_text("table-td-edit"));
  }else{$ob->addElement(api_url(["scr"=>"submit","act"=>"task_uncomplete","idTask"=>$task_fobj->id,"return"=>["scr"=>"dashboard","idTag"=>$tag_obj->id]]),"fa-square-o",api_text("dashboard-tasks-td-uncomplete"),true,api_text("dashboard-tasks-td-uncomplete-confirm"));}
  $ob->addElement(api_url(["scr"=>"submit","act"=>"task_remove","idTask"=>$task_fobj->id,"return"=>["scr"=>"dashboard","idTag"=>$tag_obj->id]]),"fa-trash",api_text("table-td-remove"),true,api_text("dashboard-tasks-td-remove-confirm"));
  // make table row class
  $tr_class_array=array();
  if($task_fobj->id==$_REQUEST['idTask']){$tr_class_array[]="currentrow";}
  // make task
  $task_td=api_tag("span",$task_fobj->title,($task_fobj->completed?"text-deleted":null));
  // cycle all tags
  $tags_array=array();
  foreach($task_fobj->getTags() as $tag_fobj){$tags_array[]=$tag_fobj->getTag();}
  if(count($tags_array)){$task_td.=" ".implode(" ",$tags_array);}
  // check for content
  if($task_fobj->content){$task_td.=" ".api_tag("span",$task_fobj->content,null,"color:#AAAAAA");}
  // make task row
  $table->addRow(implode(" ",$tr_class_array));
  $table->addRowField($task_fobj->getCompleted(true,false));
  $table->addRowField($task_td,"truncate-ellipsis");
  $table->addRowField($ob->render(),"text-right");
 }
 // make tasks content
 $tasks_tags_links_array=array();
 $tasks_tags_links_array[]=api_link(api_url(["scr"=>"dashboard","act"=>"task_add","idTag"=>$tag_obj->id]),api_text("dashboard-tasks-btn-add"),null,"btn btn-sm btn-primary");
 $tasks_tags_links_array[]=api_link(api_url(["scr"=>"dashboard","act"=>"all"]),api_text("dashboard-tasks-btn-all"),null,"btn btn-sm btn-default".(ACTION=="all"?" active":null));
 $tasks_tags_links_array[]=api_link(api_url(["scr"=>"dashboard","act"=>"today"]),api_icon("fa-clock-o")." ".api_text("dashboard-tasks-btn-today"),null,"btn btn-sm btn-default".(ACTION=="today"?" active":null));
 // cycle all tags
 foreach(cJournalsTag::availables(false,["fkUser"=>$session->user->id]) as $tag_fobj){
  // make url label and count tasks
  $url=api_url(["scr"=>"dashboard","idTag"=>$tag_fobj->id]);
  $label=$tag_fobj->name;
  $title=$tag_fobj->description;
  $count=$tag_fobj->tasks_count(false,["fkUser"=>$session->user->id]);
  $label=api_label($count,null,"background-color:".$tag_fobj->color)."&nbsp;&nbsp;".$label;
  // skip if no count
  if(!$count){continue;}
  // add tag link to array
  $tasks_tags_links_array[]=api_link($url,$label,$title,"btn btn-sm btn-default".($tag_obj->id==$tag_fobj->id?" active":null));
 }
 // implode tags links array
 $tasks_grid_content=api_tag("p",implode(" ",$tasks_tags_links_array));
 // add tasks table to content
 $tasks_grid_content.=$table->render();
 // view modal
 if(ACTION=="task_view" && $_REQUEST['idTask']){
  // definitions
  $task_body=null;
  $task_footer=null;
  // get selected task
  $selected_task_obj=new cJournalsTask($_REQUEST['idTask']);
  // add content task body
  if($selected_task_obj->content){$task_body.=api_tag("p",nl2br($selected_task_obj->content),"text-justify");}
  // cycle all tags
  $tags_array=array();
  foreach($selected_task_obj->getTags() as $tag_fobj){$tags_array[]=$tag_fobj->getTag();}
  if(count($tags_array)){$task_body.=api_tag("p",implode(" ",$tags_array));}
  // make footer buttons
  if(!$selected_task_obj->completed){
   $task_footer.=api_link(api_url(["scr"=>"submit","act"=>"task_complete","idTask"=>$selected_task_obj->id,"return"=>["scr"=>"dashboard","idTag"=>$tag_obj->id]]),api_text("dashboard-tasks-modal-btn-complete"),null,"btn btn-primary");
   $task_footer.=api_link(api_url(["scr"=>"dashboard","act"=>"task_edit","idTag"=>$tag_obj->id,"idTask"=>$selected_task_obj->id]),api_text("form-fc-edit"),null,"btn btn-default");
  }else{$task_footer.=api_link(api_url(["scr"=>"submit","act"=>"task_uncomplete","idTask"=>$selected_task_obj->id,"return"=>["scr"=>"dashboard","idTag"=>$tag_obj->id]]),api_text("dashboard-tasks-modal-btn-uncomplete"),null,"btn btn-default");}
  $task_footer.=api_link(api_url(["scr"=>"submit","act"=>"task_remove","idTask"=>$selected_task_obj->id,"return"=>["scr"=>"dashboard","idTag"=>$tag_obj->id]]),api_text("form-fc-remove"),null,"btn btn-danger",false,api_text("dashboard-tasks-modal-btn-remove-confirm"));
  // build task view modal window
  $task_modal=new strModal($task_fobj->getCompleted(true,false)." ".$selected_task_obj->title,null,"dashboard-tasks");
  $task_modal->setBody($task_body);
  $task_modal->setFooter($task_footer);
  // add modal to application
  $app->addModal($task_modal);
  // modal scripts
  $app->addScript("$(function(){\$('#modal_dashboard-tasks').modal('show');});");
 }
 // edit modal
 if(in_array(ACTION,["task_add","task_edit"])){
  // get object
  $selected_task_obj=new cJournalsTask($_REQUEST['idTask']);
  // get form
  $task_form=$selected_task_obj->form_edit(["return"=>["scr"=>"dashboard"]]);
  // replace fkUser form field
  $task_form->removeField("fkUser");
  $task_form->addField("hidden","fkUser",null,$GLOBALS['session']->user->id);
  $task_form->addField("checkbox","today",null);
	$task_form->addFieldOption("1",api_text("cJournalsTask-today"));
  // additional controls
  $task_form->addControl("button",api_text("form-fc-cancel"),"#",null,null,null,"data-dismiss='modal'");
  // build modal
  $modal=new strModal(api_text("dashboard-tasks-modal-title-".($selected_task_obj->id?"edit":"add"),$selected_task_obj->name),null,"dashboard-tasks");
  $modal->setBody($task_form->render());
  // add modal to application
  $app->addModal($modal);
  // modal scripts
  $app->addScript("$(document).ready(function(){\$('select[name=\"tags[]\"]').select2({width:'100%',tags:true,allowClear:true,dropdownParent:\$('#modal_dashboard-tasks')});});");
  $app->addScript("$(function(){\$('#modal_dashboard-tasks').modal({show:true,backdrop:'static',keyboard:false});});");
 }
?>