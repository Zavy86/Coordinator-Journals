<?php
/**
 * Journals - Notebook
 *
 * @package Coordinator\Modules\Journals
 * @company Cogne Acciai Speciali s.p.a
 * @authors Manuel Zavatta <manuel.zavatta@cogne.com>
 */

/**
 * Journals Notebook class
 */
class cJournalsNotebook extends cObject{

  /** Parameters */
  static protected $table="journals__notebooks";
  static protected $logs=false;

  /** Properties */
  protected $id;
  protected $deleted;
  protected $fkUser;
  protected $content;

  /**
   * Check
   *
   * @return boolean
   * @throws Exception
   */
  protected function check(){
   // check properties
   if(!strlen(trim($this->fkUser))){throw new Exception("User key is mandatory..");}
   // return
   return true;
  }

  /**
   * Edit form
   *
   * @param string[] $additional_parameters Array of url additional parameters
   * @return object Form structure
   */
  public function form_edit(array $additional_parameters=null){
   // build form
   $form=new strForm(api_url(array_merge(["mod"=>"journals","scr"=>"submit","act"=>"notebook_store","idNotebook"=>$this->id],$additional_parameters)),"POST",null,null,"journals_notebooks_form");
   // fields
   $form->addField("hidden","fkUser",null,$GLOBALS['session']->user->id);
   $form->addField("textarea","content",api_text("cJournalsNotebook-ff-content"),$this->content,api_text("cJournalsNotebook-ff-content-placeholder"),null,null,null,"rows='9'");
   // controls
   $form->addControl("submit",api_text("form-fc-submit"));
   // return
   return $form;
  }

 }

?>