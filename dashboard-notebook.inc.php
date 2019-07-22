<?php
/**
 * Journals - Dashboard (Notebook)
 *
 * @package Coordinator\Modules\Journals
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 // get object
 $notebook_obj=array_values(cJournalsNotebook::availables(true,["fkUser"=>$session->user->id]))[0];
 // make notebook form
 $notebook_form="<form action=\"".api_url(["scr"=>"submit","act"=>"notebook_store","idNotebook"=>$notebook_obj->id,"return"=>["scr"=>"dashboard"]])."\" method=\"POST\" id=\"form_journals_notebook\">\n";
 $notebook_form.=api_tag("p","<button type=\"submit\" class=\"btn btn-sm btn-primary\" id=\"form_journals_notebook_control_submit\" disabled=\"disabled\">".api_text("dashboard-notebook-btn-save")."</button>","text-right")."\n";
 $notebook_form.="<input type=\"hidden\" name=\"fkUser\" value=\"".$session->user->id."\">\n";
 $notebook_form.="<textarea name=\"content\" class=\"form-control\" id=\"form_journals_notebook_input_content\" placeholder=\"".api_text("cJournalsNotebook-ff-content-placeholder")."\" rows=\"10\">".$notebook_obj->content."</textarea>\n";
 $notebook_form.="</form>\n<br>\n";
 // submit enabler script
 $app->addScript("$('#form_journals_notebook_input_content').on('change keyup paste',function(){\$('#form_journals_notebook_control_submit').attr('disabled',false);});");
 // add form to content
 $notebook_grid_content=$notebook_form;
?>