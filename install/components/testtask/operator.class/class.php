<?
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;


class OperatorClass extends CBitrixComponent {
    var $test;

    protected function checkModules() {
        if (!Loader::includeModule('testtask.facilityoperator')) {
            ShowError(Loc::getMessage('FACILITY_OPERATOR_MODULE_NOT_INSTALLED'));
            return false;
        }

        return true;
    }

    public function executeComponent() {
        $this -> includeComponentLang('class.php');

        if($this->checkModules()) {
            /*  Yout code */

            $this->includeComponentTemplate();
        }
    }
};
