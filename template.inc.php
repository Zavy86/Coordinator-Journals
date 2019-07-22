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
 $nav->addItem(api_text("nav-settings"));
 $nav->addSubItem(api_text("nav-settings-tags"),api_url(["scr"=>"tags_list"]));
 // add nav to html
 $app->addContent($nav->render(false));
?>