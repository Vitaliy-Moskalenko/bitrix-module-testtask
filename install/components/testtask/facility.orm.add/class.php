<?

use \Bitrix\Main;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Type;
use Testtask\FacilityOperator\FacilityTable;


class FacilityOrmAdd extends CBitrixComponent {
    var $test;

    protected function checkModules() {
        if (!Loader::includeModule('testtask.facilityoperator')) {
            ShowError(Loc::getMessage('FACILITY_OPERATOR_MODULE_NOT_INSTALLED'));
            return false;
        }

        return true;
    }
	
	public function addMockOperator($addUserId = 1) {
		return OperatorTable::add(array(
			'DATE_CREATED' => new Type\Datetime('05.09.2022 00:00:00'),
			'ACTIVE' => 'Y',
			'ACTIVE_TO' => new Type\Datetime('05.09.2028 00:00:00'),
			'USER_ID' => $addUserId,
			'COMMENT' => 'Test comment',
			'ACCESS_LEVEL' => 12,		
		));
	}
	
	public function addMockFacility($name, $adress, $comment = '') {
		return FacilityTable::add(array(
			'DATE_CREATED' => new Type\Datetime('05.09.2022 00:00:00'),
			'NAME' => $name,
			'ADDRESS' => $adress,
			'COMMENT' => $comment,		
		));
	}	

    public function executeComponent() {
        $this -> includeComponentLang('class.php');

        if($this->checkModules()) {
            
			$result = $this->addMockFacility("Sahalin", "2343 Elm street 15, Krasnoyarsk", "test comment");
			if($result->isSuccess()) {
				$id = $result->getId();
				$this->arResult = 'Запись добавлена с id: '.$id;
			} 
			else {
				$error = $result->getErrorMessages();
				$this->arResult = 'Произошла ошибка при добавлении: <pre>'.var_export($error, true).'</pre>';
			}
			
			$result = $this->addMockFacility("Facility #5", "188565 Petrovke 38 Moscow", "test comment");
			if($result->isSuccess()) {
				$id = $result->getId();
				$this->arResult = 'Запись добавлена с id: '.$id;
			} 
			else {
				$error = $result->getErrorMessages();
				$this->arResult = 'Произошла ошибка при добавлении: <pre>'.var_export($error, true).'</pre>';
			}			

            $this->includeComponentTemplate();
        }
    }
};
