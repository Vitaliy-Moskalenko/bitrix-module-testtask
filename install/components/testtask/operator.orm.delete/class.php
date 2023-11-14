<?

use \Bitrix\Main;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Type;
use Testtask\FacilityOperator\OperatorTable;


class OperatorOrmDelete extends CBitrixComponent {

    protected function checkModules() {
        if (!Loader::includeModule('testtask.facilityoperator')) {
            ShowError(Loc::getMessage('FACILITY_OPERATOR_MODULE_NOT_INSTALLED'));
            return false;
        }

        return true;
    }
	
	public function deletteOperator($id=1) {
		return OperatorTable::delete($id);
	}

    public function executeComponent() {
        $this -> includeComponentLang('class.php');

        if($this->checkModules()) {
            
			$result = $this->deleteOperator(3);
			if($result->isSuccess()) {
				$this->arResult = 'Запись была успешно удалена.';
			} 
			else {
				$error = $result->getErrorMessages();
				$this->arResult = 'Произошла ошибка при удалении: <pre>'.var_export($error, true).'</pre>';
			}		

            $this->includeComponentTemplate();
        }
    }
};
