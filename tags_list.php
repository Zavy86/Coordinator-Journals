<?php
/**
 * Journals - Tags List
 *
 * @package Coordinator\Modules\Journals
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 api_checkAuthorization("journals-manage","dashboard");
 // include module template
 require_once(MODULE_PATH."template.inc.php");
 // definitions
 $users_array=array();
 // set application title
 $app->setTitle(api_text("tags_list"));
 // definitions
 $tags_array=array();
 // build filter
 $filter=new strFilter();
 $filter->addSearch(["name","description"]);
 // build query object
 $query=new cQuery("journals__tags",$filter->getQueryWhere());
 $query->addQueryOrderField("name");
 // build pagination object
 $pagination=new strPagination($query->getRecordsCount());
 // cycle all results
 foreach($query->getRecords($pagination->getQueryLimits()) as $result_f){$tags_array[$result_f->id]=new cJournalsTag($result_f);}
 // build table
 $table=new strTable(api_text("tags_list-tr-unvalued"));
 $table->addHeader($filter->link(api_icon("fa-filter",api_text("filters-modal-link"),"hidden-link")),null,16);
 $table->addHeader(api_text("tags_list-th-name"),"nowrap");
 $table->addHeader(api_text("tags_list-th-description"),null,"100%");
 $table->addHeader("&nbsp;",null,16);
 // cycle all tags
 foreach($tags_array as $tag_fobj){
  // build operation button
  $ob=new strOperationsButton();
  $ob->addElement(api_url(["scr"=>"tags_edit","idTag"=>$tag_fobj->id,"return"=>["scr"=>"tags_list"]]),"fa-pencil",api_text("table-td-edit"),(api_checkAuthorization("journals-manage")));
  if($developer_fobj->deleted){$ob->addElement(api_url(["scr"=>"submit","act"=>"tag_undelete","idTag"=>$tag_fobj->id,"return"=>["scr"=>"tags_list"]]),"fa-trash-o",api_text("table-td-undelete"),(api_checkAuthorization("journals-manage")),api_text("tags_list-td-undelete-confirm"));}
  else{$ob->addElement(api_url(["scr"=>"submit","act"=>"tag_delete","idTag"=>$tag_fobj->id,"return"=>["scr"=>"tags_list"]]),"fa-trash",api_text("table-td-delete"),(api_checkAuthorization("journals-manage")),api_text("tags_list-td-delete-confirm"));}
  // make table row class
  $tr_class_array=array();
  if($tag_fobj->id==$_REQUEST['idTag']){$tr_class_array[]="info";}
  if($tag_fobj->deleted){$tr_class_array[]="deleted";}
  // make tag row
  $table->addRow(implode(" ",$tr_class_array));
  $table->addRowFieldAction(api_url(["scr"=>"tags_view","idTag"=>$tag_fobj->id]),"fa-search",api_text("table-td-view"));
  $table->addRowField($tag_fobj->getTag(),"nowrap");
  $table->addRowField($tag_fobj->description,"truncate-ellipsis");
  $table->addRowField($ob->render(),"text-right");
 }
 // build grid object
 $grid=new strGrid();
 $grid->addRow();
 $grid->addCol($filter->render(),"col-xs-12");
 $grid->addRow();
 $grid->addCol($table->render(),"col-xs-12");
 $grid->addRow();
 $grid->addCol($pagination->render(),"col-xs-12");
 // add content to application
 $app->addContent($grid->render());
 // renderize application
 $app->render();
 // debug
 api_dump($query,"query");
?>