<?php
/**
 * Journals - Tag
 *
 * @package Coordinator\Modules\Journals
 * @company Cogne Acciai Speciali s.p.a
 * @authors Manuel Zavatta <manuel.zavatta@cogne.com>
 */

/**
 * Journals Tag class
 */
class cJournalsTag extends cObject{

  /** Parameters */
  static protected $table="journals__tags";
  static protected $logs=true;

  /** Properties */
  protected $id;
  protected $deleted;
  protected $fkUser;
  protected $name;
  protected $description;
  protected $color;

  /*
   * Get Tag
   */
  public function getTag(){
   return api_tag("span",$this->name,"label","background-color:".$this->color);
  }

  /**
   * Get Journals
   */
  public function getJournals(){/** @todo get joined */}

  /**
   * Check
   *
   * @return boolean
   * @throws Exception
   */
  protected function check(){
   // check properties
   if(!strlen(trim($this->fkUser))){throw new Exception("User key is mandatory..");}
   if(!strlen(trim($this->name))){throw new Exception("Tag name is mandatory..");}
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
   $form=new strForm(api_url(array_merge(["mod"=>"journals","scr"=>"submit","act"=>"tag_store","idTag"=>$this->id],$additional_parameters)),"POST",null,null,"journals_tags_form");
   // fields
   $form->addField("hidden","fkUser",null,$GLOBALS['session']->user->id);
   $form->addField("text","name",api_text("cJournalsTag-ff-name"),$this->name,api_text("cJournalsTag-ff-name-placeholder"),null,null,null,"required");
   $form->addField("textarea","description",api_text("cJournalsTag-ff-description"),$this->description,api_text("cJournalsTag-ff-description-placeholder"),null,null,null,"rows='2'");
   $form->addField("color","color",api_text("cJournalsTag-ff-color"),($this->color?$this->color:"#".substr(md5(rand()),0,6)),null,1,null,null,"required");
   // controls
   $form->addControl("submit",api_text("form-fc-submit"));
   // return
   return $form;
  }

  /**
   * Disable remove function
   */
  public function remove(){throw new Exception("Remove function disabled by developer..");}
  /** @todo check per vedere se è vuoto e permetterne l'eliminazione */

  // debug
  //protected function event_triggered($event){api_dump($event,static::class." event triggered");}

 }

?>