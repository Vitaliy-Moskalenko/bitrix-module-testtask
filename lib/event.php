<?php

namespace Testtask\FacilityOperator;

class Event {
    public static function eventHandler($user_id) {
	   // delete associated records 
       global $DB;
       $strSql = "DELETE FROM OPERATOR WHERE USER_ID=".intval($user_id);
       $rs = $DB->Query($strSql, false, "FILE: ".__FILE__."LINE: ".__LINE__);

	   return new \Bitrix\Main\Entity\EventResult;
    }
}