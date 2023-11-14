<?

use \Bitrix\Main\Config\Configuration;
use \Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) return;

/* work with .settings.php if needed 
$installCount = Configuration::getInstance()->get('testtask_facilityoperator');
$cacheType = Configuration::getInstance()->get('cache'); */

if ($ex = $APPLICATION->GetException()) {
	echo CAdminMessage::ShowMessage(array(
		"TYPE"    => "ERROR",
		"MESSAGE" => Loc::getMessage("MOD_INST_ERR"),
		"DETAILS" => $ex->GetString(),
		"HTML"    => true,
	));
} else {
	echo CAdminMessage::ShowNote(Loc::getMessage("MOD_INST_OK"));
}


/* work with .settings.php if needed
echo CAdminMessage::ShowMessage(array("MESSAGE" => Loc::getMessage("FACILITY_OPERATOR_INSTALL_COUNT").$installCount['install'], "TYPE" => "OK"));
if(!$cacheType['type'] || $cacheType['type'] == 'none')
	echo CAdminMessage::ShowMessage(array("MESSAGE" => Loc::getMessage("FACILITY_OPERATOR_NO_CACHE"), "TYPE" => "ERROR")); */
?>

<form action="<?= $APPLICATION->GetCurPage() ?>">
	<input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
	<input type="submit" name="" value="<?= Loc::getMessage("MOD_BACK") ?>">	
</form>	
	