<?

namespace Testtask\FacilityOperator;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;


class FacilityOperatorTable extends Entity\DataManager {
	
	public static function getFilePath() {
        return __FILE__;
    }
	
	public static function getTableName() {
		return 'facility_operator';
	}
	
	public static function getUfId() {
		return 'FACILITY_OPERATOR';
	}
	
	public static function getMap() {
		return array(			
			new Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
			)),			
			new Entity\IntegerField('ID_OPERATOR', array(				
				'required' => true,
			)),	
			new Entity\StringField('ID_FACILITY', array(
				'required' => true,
			)),	
		);
	}
}