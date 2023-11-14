<?

namespace Testtask\FacilityOperator;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;


class FacilityTable extends Entity\DataManager {
	
	public static function getFilePath() {
        return __FILE__;
    }
	
	public static function getTableName() {
		return 'facility';
	}
	
	public static function getUfId() {
		return 'FACILITY';
	}
	
	public static function getMap() {
		return array(			
			new Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
			)),			
			new Entity\DatetimeField('DATE_CREATED', array(
				'required' => true,
			)),	
			new Entity\StringField('NAME', array(
				'required' => true,
			)),
			new Entity\StringField('ADDRESS', array(				
				'required' => true,			
			)),
			new Entity\StringField('COMMENT', array()),	
		);
	}
}