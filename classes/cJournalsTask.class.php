<?php
/**
 * Journals - Task
 *
 * @package Coordinator\Modules\Journals
 * @company Cogne Acciai Speciali s.p.a
 * @authors Manuel Zavatta <manuel.zavatta@cogne.com>
 */

 /**
  * Journals Task class
  */
 class cJournalsTask extends cObject{

  /** Parameters */
  static protected $table="journals__tasks";
  static protected $logs=false;

  /** Properties */
  protected $id;
  protected $deleted;
  protected $completed;
	protected $today;
  protected $fkUser;
  protected $title;
  protected $content;

  /**
   * Availables Completed
   *
   * @return object[] Array of available completed
   */
  public static function availablesCompleted(){
   return array(
    "0"=>(object)array("code"=>"0","text"=>api_text("cJournalsTask-completed-false"),"icon"=>api_icon("fa-square-o",api_text("cJournalsTask-completed-false"))),
    "1"=>(object)array("code"=>"1","text"=>api_text("cJournalsTask-completed-true"),"icon"=>api_icon("fa-check-square-o",api_text("cJournalsTask-completed-true")))
   );
  }

  /**
   * Get Completed
   *
   * @param boolean $icon Return icon
   * @param boolean $text Return text
   * @param string $align Icon alignment [left|right]
   * @return string
   */
  public function getCompleted($icon=true,$text=true,$align="left"){
   return parent::decode($this->completed,static::availablesCompleted(),$icon,$text,$align);
  }

  /**
   * Get Tags
   *
   * @return objects[] Tags array sorted by name
   */
  public function getTags(){return api_sortObjectsArray($this->joined_select("journals__tasks__join__tags","fkTask","cJournalsTag","fkTag"),"name");}

  /**
   * Check
   */
  protected function check(){
   // check properties
   if(!strlen(trim($this->fkUser))){throw new Exception("User key is mandatory..");}
   if(!strlen(trim($this->title))){throw new Exception("Task title is mandatory..");}
   // return
   return true;
  }

	/**
	 * Today
	 *
	 * @param boolean $value Today or not today
	 * @param boolean $log Log event
	 * @return boolean
	 */
  public function today($value,$log=false){
	 // check existence
	 if(!$this->exists()){return false;}
	 // build query object
	 $query_obj=new stdClass();
	 $query_obj->id=$this->id;
	 $query_obj->today=$value;
	 // debug
	 api_dump($query_obj,static::class."->today query object");
	 // execute query
	 $GLOBALS['database']->queryUpdate(static::$table,$query_obj);
	 // throw event
	 $this->event("information","today",null,$log);
	 // return
	 return true;
  }

  /**
   * Complete
   *
   * @param boolean $log Log event
   * @return boolean
   */
  public function complete($log=true){
   // check existence
   if(!$this->exists()){return false;}
   // build query object
   $query_obj=new stdClass();
   $query_obj->id=$this->id;
   $query_obj->completed=1;
   // debug
   api_dump($query_obj,static::class."->complete query object");
   // execute query
   $GLOBALS['database']->queryUpdate(static::$table,$query_obj);
   /* @todo check? */
   // throw event
   $this->event("information","completed",null,$log);
   // return
   return true;
  }

  /**
   * Uncomplete
   *
   * @param boolean $log Log event
   * @return boolean
   */
  public function uncomplete($log=true){
   // check existence
   if(!$this->exists()){return false;}
   // build query object
   $query_obj=new stdClass();
   $query_obj->id=$this->id;
   $query_obj->completed=0;
   // debug
   api_dump($query_obj,static::class."->uncomplete query object");
   // execute query
   $GLOBALS['database']->queryUpdate(static::$table,$query_obj);
   /* @todo check? */
   // throw event
   $this->event("information","uncompleted",null,$log);
   // return
   return true;
  }

  /**
   * Edit form
   *
   * @param string[] $additional_parameters Array of url additional parameters
   * @return object Form structure
   */
  public function form_edit(array $additional_parameters=array()){
   // build form
   $form=new strForm("?mod=journals&scr=submit&act=task_store&idTask=".$this->id."&".http_build_query($additional_parameters),"POST",null,null,"journals_tasks_form_edit");
   // fields
   $form->addField("select","fkUser",api_text("cJournalsTag-ff-fkUser"),$this->fkUser,api_text("cJournalsTag-ff-fkUser-select"),null,null,null,"required");
   foreach(cUser::availables() as $user_fobj){$form->addFieldOption($user_fobj->id,$user_fobj->fullname);}
   $form->addField("text","title",api_text("cJournalsTask-ff-title"),$this->title,api_text("cJournalsTask-ff-title-placeholder"),null,null,null,"required");
   $form->addField("textarea","content",api_text("cJournalsTask-ff-content"),$this->content,api_text("cJournalsTask-ff-content-placeholder"),null,null,null,"rows='4'");
   foreach($this->getTags() as $tag_fobj){$tags_array[]=$tag_fobj->id;}
   $form->addField("select","tags[]",api_text("cJournalsTask-ff-tags"),$tags_array,null,null,null,null,"multiple");
   foreach(cJournalsTag::availables(false,["fkUser"=>$GLOBALS['session']->user->id]) as $tag_fobj){$form->addFieldOption($tag_fobj->id,$tag_fobj->name);}
   // controls
   $form->addControl("submit",api_text("form-fc-submit"));
   // return
   return $form;
  }

  /**
   * Store Tags
   *
   * @param mixed[] $tags Array of tag keys or new tags
   */
  public function store_tags($tags){
   // reset tags
   $this->joined_reset("journals__tasks__join__tags","fkTask","tag_resetted");
   // check for array
   if(!is_array($tags)){return;}
   // cycle all tags
   foreach($tags as $tag_f){
    // get object
    $tag_obj=new cJournalsTag($tag_f);
    // check object
    if(!$tag_obj->id){api_random();
     // store new tag
     $tag_obj->store(["fkUser"=>$GLOBALS['session']->user->id,"name"=>$tag_f,"color"=>api_random_color()]);
    }
    // check object
    if(!$tag_obj->id){continue;}
    // add tag to task
    $this->joined_add("journals__tasks__join__tags","fkTask","cJournalsTag","fkTag",$tag_obj,"tag_added");
   }
  }

  // debug
  //protected function event_triggered($event){api_dump($event,static::class." event triggered");}

 }

?>