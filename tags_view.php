<?php
/**
 * Journals - Tags View
 *
 * @package Coordinator\Modules\Journals
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 api_checkAuthorization("journals-manage","dashboard");
 // get objects
 $tag_obj=new cJournalsTag($_REQUEST['idTag']);
 // check objects
 if(!$tag_obj->id){api_alerts_add(api_text("cJournalsTag-alert-exists"),"danger");api_redirect(api_url(["scr"=>"tags_list"]));}
 // deleted alert
 if($tag_obj->deleted){api_alerts_add(api_text("cJournalsTag-alert-deleted"),"warning");}
 // include module template
 require_once(MODULE_PATH."template.inc.php");
 // set application title
 $app->setTitle(api_text("tags_view",$tag_obj->name));
 // check for tab
 if(!defined(TAB)){define("TAB","informations");}
 // build left description lists
 $dl=new strDescriptionList("br","dl-horizontal");
 $dl->addElement(api_text("tags_view-dt-name"),$tag_obj->getTag());
 if($tag_obj->description){$dl->addElement(api_text("tags_view-dt-description"),nl2br($tag_obj->description));}
 // include tabs
 require_once(MODULE_PATH."tags_view-informations.inc.php");
 require_once(MODULE_PATH."tags_view-tasks.inc.php");
 $tab=new strTab();
 $tab->addItem(api_icon("fa-flag-o")." ".api_text("tags_view-tab-informations"),$informations_dl->render(),("informations"==TAB?"active":null));
 $tab->addItem(api_icon("fa-check-square-o")." ".api_text("tags_view-tab-tasks"),$journals_table->render(),("tasks"==TAB?"active":null));
 $tab->addItem(api_icon("fa-file-text-o")." ".api_text("tags_view-tab-logs"),api_logs_table($tag_obj->getLogs())->render(),("logs"==TAB?"active":null));
 // build grid object
 $grid=new strGrid();
 $grid->addRow();
 $grid->addCol($dl->render(),"col-xs-12");
 $grid->addRow();
 $grid->addCol($tab->render(),"col-xs-12");
 // add content to application
 $app->addContent($grid->render());
 // renderize application
 $app->render();
 // debug
 api_dump($tag_obj,"tag object");
 if($selected_tag_obj->id){api_dump($selected_tag_obj,"selected tag object");}
?>