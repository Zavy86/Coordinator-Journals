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
 $app->setTitle(api_text("journals"));
 // build dashboard object
 $dashboard=new strDashboard();

 $dashboard->addTile(api_url(["scr"=>"tags_list"]),api_text("tags_list"),api_text("tags_list-description"),(api_checkAuthorization("journals-manage")),"1x1","fa-book");
 // build grid object
 $grid=new strGrid();
 $grid->addRow();
 $grid->addCol($dashboard->render(),"col-xs-12");
 // add content to application
 $app->addContent($grid->render());
 // renderize application
 $app->render();
?>