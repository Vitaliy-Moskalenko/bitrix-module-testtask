<?

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

$module_id = 'testtask.facilityoperator'; 

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
Loc::loadMessages(__FILE__);

if ($APPLICATION->GetGroupRight($module_id) < "S") {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

\Bitrix\Main\Loader::includeModule($module_id);

$request = \Bitrix\Main\HttpApplication::getInstance()->getContext()->getRequest();

// Module options
$aTabs = array(
    array(
        'DIV' => 'edit1',
        'TAB' => Loc::getMessage('TESTTASK_FACILITYOPERATOR_TAB_SETTINGS'),
        'OPTIONS' => array(
            array('field_text', Loc::getMessage('TESTTASK_FACILITYOPERATOR_FIELD_TEXT_TITLE'),
                '',
                array('textarea', 10, 50)),
            array('field_line', Loc::getMessage('TESTTASK_FACILITYOPERATOR_FIELD_LINE_TITLE'),
                '',
                array('text', 10)),
            array('field_list', Loc::getMessage('TESTTASK_FACILITYOPERATOR_FIELD_LIST_TITLE'),
                '',
                array('multiselectbox',array('var1'=>'var1','var2'=>'var2','var3'=>'var3','var4'=>'var4'))),
        )
    ),
    array(
        "DIV" => "edit2",
        "TAB" => Loc::getMessage("MAIN_TAB_RIGHTS"),
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS")
    ),
);

if ($request->isPost() && $request['Update'] && check_bitrix_sessid()) {
    foreach ($aTabs as $aTab) {
        foreach ($aTab['OPTIONS'] as $arOption) {
            if (!is_array($arOption)) continue;

            if ($arOption['note']) continue;

            $optionName  = $arOption[0];
            $optionValue = $request->getPost($optionName);

            Option::set($module_id, $optionName, is_array($optionValue) ? implode(",", $optionValue):$optionValue);
        }
    }
}

$tabControl = new CAdminTabControl('tabControl', $aTabs);
?>
<? $tabControl->Begin(); ?>
	<form method='post' action='<?= $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($request['mid'])?>&amp;lang=<?=$request['lang']?>' name='testtask_facilityoperator_settings'>

    <?  foreach ($aTabs as $aTab) { 
			if($aTab['OPTIONS']) {
				$tabControl->BeginNextTab();
				__AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
			}
		}	
    
    $tabControl->BeginNextTab();
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");

    $tabControl->Buttons(); ?>

    <input type="submit" name="Update" value="<?echo GetMessage('MAIN_SAVE')?>">
    <input type="reset" name="reset" value="<?echo GetMessage('MAIN_RESET')?>">
	
    <?=bitrix_sessid_post();?>
</form>
<? $tabControl->End(); ?>

