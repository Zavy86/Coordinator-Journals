<?php
/**
 * Journals - Dashboard
 *
 * @package Coordinator\Modules\Journals
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 // include module template
 require_once(MODULE_PATH."template.inc.php");
 // set application title
 $app->setTitle(api_text(MODULE));
 // include tasks
 require_once(MODULE_PATH."dashboard-tasks.inc.php");
 // include notebook
 require_once(MODULE_PATH."dashboard-notebook.inc.php");
 // build grid object
 $grid=new strGrid();
 $grid->addRow();
 $grid->addCol($tasks_grid_content,"col-xs-12 col-md-8");
 $grid->addCol($notebook_grid_content,"col-xs-12 col-md-4");
 // add content to application
 $app->addContent($grid->render());
 // renderize application
 $app->render();
 // debug
 if($selected_task_obj->id){api_dump($selected_task_obj,"selected task object");}
 if($tag_obj->id){api_dump($tag_obj,"tag object");}
 if($notebook_obj->id){api_dump($notebook_obj,"notebook object");}
?>