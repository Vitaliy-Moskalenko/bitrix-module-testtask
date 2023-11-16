<?

use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Type;
use Testtask\FacilityOperator\OperatorTable;
use Testtask\FacilityOperator\FacilityTable;


class OperatorOrmGetList extends CBitrixComponent {

    protected function checkModules() {
        if (!Main\Loader::includeModule('testtask.facilityoperator'))
            throw new Main\LoaderException(Loc::getMessage('FACILITY_OPERATOR_MODULE_NOT_INSTALLED'));
    }
	
	public function getAll() {
		return (OperatorTable::getList())->fetchAll();		 
	}	

	
	public function getById($id) {
		return (OperatorTable::getByPrimary(
			$id,
			array('select' => array('*', 'FACILITY'))
		))->fetch();
	}
	
	public function getByUserId($userId) {
		return OperatorTable::getList(
			array(
				'select' => array('ID', 'FACILITY', 'DATE_CREATED', 'ACTIVE', 'ACTIVE_TO', 'USER_ID', 'COMMENT', 'ACCESS_LEVEL'),
				'filter' => array('USER_ID' => $userId),
			)	
		);
	}
	
	public function getByFacility($facilityId) {
		return OperatorTable::getList(
			array(
				'select' => array('ID', 'DATE_CREATED', 'ACTIVE', 'ACTIVE_TO', 'USER_ID', 'COMMENT', 'ACCESS_LEVEL'),
				// 'FACILITY_ID' => '\Testtask\FacilityOperator\FacilityOperatorTable:OPERATOR.ID',
				'runtime' => [
					(new \Bitrix\Main\ORM\Fields\Relations\Reference(
						'FACILITY_OPERATOR_TABLE',
						\Bitrix\Main\FacilityOperator\FacilityOperatorTable::class,
						\Bitrix\Main\ORM\Query\Join::on('this.ID', 'ref.ID')
            ))
                ->configureJoinType(\Bitrix\Main\ORM\Query\Join::TYPE_INNER)
        ]	
	
			)
		);
	}

    public function executeComponent() {
        $this->includeComponentLang('class.php');
        $this->checkModules();
		
		if ($this->StartResultCache()) {
			// Get all operators
			// $this->arResult = $this->getAll();
			
			// Get by ID
			$this->arResult = $this->getById(3);

			$this->includeComponentTemplate();
		}	
    }
};
























