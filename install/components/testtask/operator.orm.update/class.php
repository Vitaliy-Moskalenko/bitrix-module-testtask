<?

use \Bitrix\Main;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Type;
use Testtask\FacilityOperator\OperatorTable;


class OperatorOrmUpdate extends CBitrixComponent {

    protected function checkModules() {
        if (!Loader::includeModule('testtask.facilityoperator')) {
            ShowError(Loc::getMessage('FACILITY_OPERATOR_MODULE_NOT_INSTALLED'));
            return false;
        }

        return true;
    }
	
	public function updateOperator($id=1, $active='Y', $comment='', $accessLevel=1) {
		return OperatorTable::update($id, array(
			'ID' => $id,
			'ACTIVE' => $active,
			'COMMENT' => $comment,
			'ACCESS_LEVEL' => $accessLevel,				
		));
	}

    public function executeComponent() {
        $this -> includeComponentLang('class.php');

        if($this->checkModules()) {
            
			$result = $this->updateOperator(3, 'N', 'New comment', 5);
			if($result->isSuccess()) {
				$id = $result->getId();
				$this->arResult = 'Запись с id: '.$id.' успешно обновлена.';
			} 
			else {
				$error = $result->getErrorMessages();
				$this->arResult = 'Произошла ошибка при обновлении: <pre>'.var_export($error, true).'</pre>';
			}		

            $this->includeComponentTemplate();
        }
    }
};
