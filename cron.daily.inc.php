<?php
/**
 * Journals - Cron
 *
 * @package Coordinator\Modules\Journals
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 // definitions
 $logs=array();
 // remove completed tasks
 $updated_rows=$GLOBALS['database']->queryExecute("DELETE FROM `journals__tasks` WHERE `completed`='1'");
 // log
 $logs[]=intval($updated_rows)." completed tasks deleted";
 // remove today from all tasks
 $updated_rows=$GLOBALS['database']->queryExecute("UPDATE `journals__tasks` SET `today`='0' WHERE `today`='1'");
 // log
 $logs[]=intval($updated_rows)." today tasks resetted";
 // debug
 api_dump($logs,"journal");
?>