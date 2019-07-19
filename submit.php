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

  // default
  default:
   api_alerts_add(api_text("alert_submitFunctionNotFound",array(MODULE,SCRIPT,ACTION)),"danger");
   api_redirect("?mod=".MODULE);
 }

 /**
  * Tag
  *
  * @param string $action Object action
  */
 function tag($action){
  // check authorizations
  api_checkAuthorization("journals-manage","dashboard");
  // get object
  $tag_obj=new cJournalsTag($_REQUEST['idTag']);
  api_dump($tag_obj,"tag object");
  // check object
  if($action!="store" && !$tag_obj->id){api_alerts_add(api_text("cJournalsTag-alert-exists"),"danger");api_redirect("?mod=".MODULE."&scr=tags_list");}
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
   api_redirect(api_return_url(array("scr"=>"tags_list","idTag"=>$tag_obj->id)));
  }catch(Exception $e){
   // dump, alert and redirect
   api_redirect_exception($e,api_url(["scr"=>"tags_list","idTag"=>$tag_obj->id]),"cJournalsTag-alert-error");
  }
 }

?>