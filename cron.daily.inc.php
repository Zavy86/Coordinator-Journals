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
 // stop manual expired plannings
 $updated_rows=$GLOBALS['database']->queryExecute("DELETE FROM `journals__tasks` WHERE `completed`='1'");
 // log
 $logs[]=intval($updated_rows)." completed tasks deleted";
 // debug
 api_dump($logs,"journal");
?>