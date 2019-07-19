<?php
/**
 * Journals - Tags View (Informations)
 *
 * @package Coordinator\Modules\Journals
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 // build informations description list
 $informations_dl=new strDescriptionList("br","dl-horizontal");
 $informations_dl->addElement(api_text("developments_view-informations-dt-name"),$tag_obj->name);
?>