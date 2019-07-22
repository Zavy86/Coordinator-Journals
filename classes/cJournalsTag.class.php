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
  static protected $logs=false;

  /** Properties */
  protected $id;
  protected $deleted;
  protected $fkUser;
  protected $name;
  protected $description;
  protected $color;

  /*
   * Get Label
   */
  public function getLabel(){
   // make label
   $label=$this->name;
   if($this->description){$label.=" (".$this->description.")";}
   // return
   return $label;
  }

  /*
   * Get Tag
   */
  public function getTag(){
   return api_label($this->name,null,"background-color:".$this->color);
  }

  /**
   * Get Tasks
   *
   * @return objects[] Tasks array
   */
  public function getTasks(){return $this->joined_select("journals__tasks__join__tags","fkTag","cJournalsTask","fkTask");}


  /**
   * Count Tasks
   *
   * @return integer
   */
  public function tasks_count(){
   return $this->joined_count("journals__tasks__join__tags","fkTag");
  }

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
   $form->addField("select","fkUser",api_text("cJournalsTag-ff-fkUser"),$this->fkUser,api_text("cJournalsTag-ff-fkUser-select"),null,null,null,"required");
   foreach(cUser::availables() as $user_fobj){$form->addFieldOption($user_fobj->id,$user_fobj->fullname);}
   $form->addField("text","name",api_text("cJournalsTag-ff-name"),$this->name,api_text("cJournalsTag-ff-name-placeholder"),null,null,null,"required");
   $form->addField("textarea","description",api_text("cJournalsTag-ff-description"),$this->description,api_text("cJournalsTag-ff-description-placeholder"),null,null,null,"rows='2'");
   $form->addField("color","color",api_text("cJournalsTag-ff-color"),($this->color?$this->color:api_random_color()),null,2,null,null,"required");
   // controls
   $form->addControl("submit",api_text("form-fc-submit"));
   // return
   return $form;
  }

  /**
   * Disable remove function
   */
  public function remove(){
   // check for tasks
   if($this->tasks_count()){throw new Exception("Remove disabled until there are assigned tasks..");}
   // call parent
   return parent::remove();
  }

  // debug
  //protected function event_triggered($event){api_dump($event,static::class." event triggered");}

 }

?>