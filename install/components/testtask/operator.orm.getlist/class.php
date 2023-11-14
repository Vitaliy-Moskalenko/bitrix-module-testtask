<?

use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Type;
use Testtask\FacilityOperator\OperatorTable;
use Testtask\FacilityOperator\FacilityTable;
use Testtask\FacilityOperator\FacilityOperatorTable;


class OperatorOrmGetList extends CBitrixComponent {

    protected function checkModules() {
        if (!Main\Loader::includeModule('testtask.facilityoperator'))
            throw new Main\LoaderException(Loc::getMessage('FACILITY_OPERATOR_MODULE_NOT_INSTALLED'));
    }
	
	public function getAll() {
		return OperatorTable::getList();
	}
	
	public function getById($id) {
		return OperatorTable::getList(
			array(
				'select'  => array('ID', 'DATE_CREATED', 'ACTIVE', 'ACTIVE_TO', 'USER_ID', 'COMMENT', 'ACCESS_LEVEL'),
				'filter'  => array('ID' => $id), // WHERE и HAVING filter description
				// 'group'   => array(), // явное указание полей, по которым нужно группировать результат
				'order'   => array('ID'=>'DESC'), 
				'limit'   => 100,
				// 'offset'  => 0,
			)		
		);
	}
	
	public function getByUserId($userId) {
		return OperatorTable::getList(
			array(
				'select' => arrray('ID', 'DATE_CREATED', 'ACTIVE', 'ACTIVE_TO', 'USER_ID', 'COMMENT', 'ACCESS_LEVEL'),
				'filter' => array('USER_ID' => $userId),
			)	
		);
	}

    public function executeComponent() {
        $this->includeComponentLang('class.php');

        $this->checkModules();
		
		$result = $this->getAll();   

        //Вариант 1 получения данных
        /*while ($row = $result->fetch())
        {
            $this -> arResult[] = $row;
        }*/

        //Вариант 2 получения данных
        $this->arResult = $result->fetchAll();

        $this->includeComponentTemplate();
    }
};
























