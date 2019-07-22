<?php
/**
 * Journals - Submit
 *
 * @package Coordinator\Modules\Journals
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */

 // debug
 api_dump($_REQUEST,"_REQUEST");
 // switch action
 switch(ACTION){
  // tags
  case "tag_store":tag("store");break;
  case "tag_delete":tag("delete");break;
  case "tag_undelete":tag("undelete");break;
  case "tag_remove":tag("remove");break;
  // tasks
  case "task_store":task("store");break;
  case "task_complete":task("complete");break;
  case "task_uncomplete":task("uncomplete");break;
  case "task_remove":task("remove");break;
  // notebooks
  case "notebook_store":notebook("store");break;
  // default
  default:
   api_alerts_add(api_text("alert_submitFunctionNotFound",[MODULE,SCRIPT,ACTION]),"danger");
   api_redirect("?mod=".MODULE);
 }

 /**
  * Tag
  *
  * @param string $action Object action
  */
 function tag($action){
  // check authorizations
  api_checkAuthorization("journals-usage","dashboard");
  // get object
  $tag_obj=new cJournalsTag($_REQUEST['idTag']);
  api_dump($tag_obj,"tag object");
  // check object
  if($action!="store" && !$tag_obj->id){api_alerts_add(api_text("cJournalsTag-alert-exists"),"danger");api_redirect("?mod=".MODULE."&scr=tags_list");}
  // check owner
  if($tag_obj->id && $tag_obj->fkUser!=$GLOBALS['session']->user->id && !api_checkAuthorization("journals-manage")){api_alerts_add(api_text("cJournalsTag-alert-unauthorized"),"danger");api_redirect(api_url(["scr"=>"dashboard"]));}
  // execution
  try{
   switch($action){
    case "store":
     $tag_obj->store($_REQUEST);
     api_alerts_add(api_text("cJournalsTag-alert-stored"),"success");
     break;
    case "delete":
     $tag_obj->delete();
     api_alerts_add(api_text("cJournalsTag-alert-deleted"),"warning");
     break;
    case "undelete":
     $tag_obj->undelete();
     api_alerts_add(api_text("cJournalsTag-alert-undeleted"),"warning");
     break;
    case "remove":
     $tag_obj->remove();
     api_alerts_add(api_text("cJournalsTag-alert-removed"),"warning");
     break;
    default:
     throw new Exception("Tag action \"".$action."\" was not defined..");
   }
   // redirect
   api_redirect(api_return_url(["scr"=>"tags_list","idTag"=>$tag_obj->id]));
  }catch(Exception $e){
   // dump, alert and redirect
   api_redirect_exception($e,api_url(["scr"=>"tags_list","idTag"=>$tag_obj->id]),"cJournalsTag-alert-error");
  }
 }

 /**
  * Task
  *
  * @param string $action Object action
  */
 function task($action){
  // check authorizations
  api_checkAuthorization("journals-usage","dashboard");
  // get object
  $task_obj=new cJournalsTask($_REQUEST['idTask']);
  api_dump($task_obj,"task object");
  // check object
  if($action!="store" && !$task_obj->id){api_alerts_add(api_text("cJournalsTask-alert-exists"),"danger");api_redirect("?mod=".MODULE."&scr=tasks_list");}
  // check owner
  if($task_obj->id && $task_obj->fkUser!=$GLOBALS['session']->user->id && !api_checkAuthorization("journals-manage")){api_alerts_add(api_text("cJournalsTask-alert-unauthorized"),"danger");api_redirect(api_url(["scr"=>"dashboard"]));}
  // execution
  try{
   switch($action){
    case "store":
     $task_obj->store($_REQUEST);
     $task_obj->store_tags($_REQUEST['tags']);
     api_alerts_add(api_text("cJournalsTask-alert-stored"),"success");
     break;
    case "complete":
     $task_obj->complete();
     api_alerts_add(api_text("cJournalsTask-alert-completed"),"success");
     break;
    case "uncomplete":
     $task_obj->uncomplete();
     api_alerts_add(api_text("cJournalsTask-alert-uncompleted"),"warning");
     break;
    case "remove":
     $task_obj->remove();
     api_alerts_add(api_text("cJournalsTask-alert-removed"),"warning");
     break;
    default:
     throw new Exception("Task action \"".$action."\" was not defined..");
   }
   // redirect
   api_redirect(api_return_url(["scr"=>"tasks_list","idTask"=>$task_obj->id]));
  }catch(Exception $e){
   // dump, alert and redirect
   api_redirect_exception($e,api_url(["scr"=>"tasks_list","idTask"=>$task_obj->id]),"cJournalsTask-alert-error");
  }
 }

 /**
  * Notebook
  *
  * @param string $action Object action
  */
 function notebook($action){
  // check authorizations
  api_checkAuthorization("journals-usage","dashboard");
  // get object
  $notebook_obj=new cJournalsNotebook($_REQUEST['idNotebook']);
  api_dump($notebook_obj,"notebook object");
  // check object
  if($action!="store" && !$notebook_obj->id){api_alerts_add(api_text("cJournalsNotebook-alert-exists"),"danger");api_redirect("?mod=".MODULE."&scr=notebooks_list");}
  // check owner
  if($notebook_obj->id && $notebook_obj->fkUser!=$GLOBALS['session']->user->id && !api_checkAuthorization("journals-manage")){api_alerts_add(api_text("cJournalsNotebook-alert-unauthorized"),"danger");api_redirect(api_url(["scr"=>"dashboard"]));}
  // execution
  try{
   switch($action){
    case "store":
     $notebook_obj->store($_REQUEST);
     api_alerts_add(api_text("cJournalsNotebook-alert-stored"),"success");
     break;
    default:
     throw new Exception("Notebook action \"".$action."\" was not defined..");
   }
   // redirect
   api_redirect(api_return_url(["scr"=>"notebooks_list","idNotebook"=>$notebook_obj->id]));
  }catch(Exception $e){
   // dump, alert and redirect
   api_redirect_exception($e,api_url(["scr"=>"notebooks_list","idNotebook"=>$notebook_obj->id]),"cJournalsNotebook-alert-error");
  }
 }

?>