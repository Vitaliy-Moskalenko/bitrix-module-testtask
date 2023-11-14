<?

namespace Testtask\FacilityOperator;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;


class OperatorTable extends Entity\DataManager {
	
	public static function getFilePath() {
        return __FILE__;
    }
	
	public static function getTableName() {
		return 'operator';
	}
	
	public static function getUfId() {
		return 'OPERATOR';
	}
	
	public static function getMap() {
		return array(			
			new Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
			)),			
			new Entity\DatetimeField('DATE_CREATED', array(
				'dafault_value' => new Type\DateTime
			)),	
			new Entity\BooleanField('ACTIVE', array(
				'values' => array('N', 'Y')
			)),
			new Entity\DatetimeField('ACTIVE_TO', array(				
			)),
			new Entity\IntegerField('USER_ID', array(
				'required' => true,
				'validation' => function() {
					return aray(
						new Entity\Validator\Unique,
					);			
				},
			)),
			new Entity\StringField('COMMENT', array(
				'validation' => function () {
                    return array(
                        new Validator\Length(null, 512),
                    );
                },
			)),
			new Entity\IntegerField('ACCESS_LEVEL', array(
				'default_value' => 1,
				'validation' => function () {
                    return array(
                        new Validator\Range(1, 12),
                    );
                },				
			)),		
		);
	}	
}