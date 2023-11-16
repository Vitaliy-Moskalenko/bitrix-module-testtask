<?php

use \Bitrix\Main\Application;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\EventManager;
use \Bitrix\Main\Entity\Base;
use \Bitrix\Main\IO\Directory;
use \Bitrix\Main\IO\File;
use \Bitrix\Main\IO\InvalidPathException;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\EventManager;
use \Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);


class testtask_facilityoperator extends CModule {
	
	var $exclusionAdminFiles;
	
	function __construct() {
		 $arModuleVersion = array();
		 include(__DIR__."/version.php");
		 
		 $this->exclusionAdminFiles = array(
			"..",
			".",
			"menu.php",
			"operation_description.php",
			"task_description.php",
		 );
		 
		 $this->MODULE_ID = "testtask.facilityoperator";
		 $this->MODULE_VERSION      = $arModuleVersion["VERSION"];
		 $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		 $this->MODULE_NAME         = Loc::getMessage("FACILITY_OPERATOR_MODULE_NAME");
		 $this->MODULE_DESCRIPTION  = Loc::getMessage("FACILITY_OPERATOR_MODULE_DESC");
		 
		 $this->PARTNER_NAME = Loc::getMessage("FACILITY_OPERATOR_PARTNER_NAME");
		 $this->PARTNER_NAME = Loc::getMessage("FACILITY_OPERATOR_PARTNER_URI");
		 
		 $this->MODULE_SORT = 1;
		 $this->MODULE_SUPER_ADMIN_GROUP_RIGHTS = "Y";
		 $this->MODULE_GROUP_RIGHTS = "Y";		 
	}
	
	// Return module path
	public function GetPath($notDocumentRoot = false) {
		if ($notDocumentRoot)
			return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
		else
			return dirname(__DIR__);
	}
	
	// Check if D7 supported
	public function isVersionD7() {
		return CheckVersion(ModuleManager::getVersion('main'), '14.00.00');
	}
	
	function InstallDB() {
		Loader::includeModule($this->MODULE_ID);
		
		if (!Application::getConnection(\Testtask\FacilityOperator\OperatorTable::getConnectionName())
				->isTableExists(Base::getInstance('\Testtask\FacilityOperator\OperatorTable')->getDBTableName())			
		) {
			Base::getInstance('\Testtask\FacilityOperator\OperatorTable')->createDbTable();
		}
		
		if (!Application::getConnection(\Testtask\FacilityOperator\FacilityTable::getConnectionName())
				->isTableExists(Base::getInstance('\Testtask\FacilityOperator\FacilityTable')->getDBTableName())
		) {
			Base::getInstance('\Testtask\FacilityOperator\FacilityTable')->createDbTable();
		}
	}
	
	function UnInstallDB() {
		Loader::includeModule($this->MODULE_ID);
		
		Application::getConnection(\Testtask\FacilityOperator\OperatorTable::getConnectionName())
			->queryExecute('DROP TABLE IF EXISTS '.Base::getInstance('\Testtask\FacilityOperator\OperatorTable')
			->getDBTableName());
		
		Application::getConnection(\Testtask\FacilityOperator\FacilityTable::getConnectionName())
			->queryExecute('DROP TABLE IF EXISTS '.Base::getInstance('\Testtask\FacilityOperator\FacilityTable')
			->getDBTableName());
		
		Option::delete($this->MODULE_ID);
	}
	
	function InstallFiles($arParams = array()) {
		$path = $this->GetPath()."/install/components";
		
		if (Directory::isDirectoryExists($path)) {
			CopyDirFiles($path, $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
		}
		else {
			throw new InvalidPathException($path);
		}
		
		if (Directory::isDirectoryExists($path=$this->GetPath()."/admin")) {
			CopyDirFiles($this->GetPath()."/install/admin/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
			
			if ($dir = opendir($path)) {
				while (false !== $item = readdir($dir)) {
					if (in_array($item, $this->exclusionAdminFiles)) continue;
					
					file_put_contents(
						$_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/".$this->MODULE_ID."_".$item, 
	                    '<'.'? require($_SERVER["DOCUMENT_ROOT"]."'.$this->GetPath(true).'/admin/'.$item.'");?'.'>'
					);				
				}
				
				closedir($dir);
			}
		}		
		return true;		
	}
	
	function UnInstallFiles() {
        Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"].'/bitrix/components/testtask/');

        if (Directory::isDirectoryExists($path=$this->GetPath().'/admin')) {
            DeleteDirFiles(
				$_SERVER["DOCUMENT_ROOT"].$this->GetPath().'/install/admin/',
				$_SERVER["DOCUMENT_ROOT"].'/bitrix/admin'
			);
			
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if (in_array($item, $this->exclusionAdminFiles)) continue;
					
                    File::deleteFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . $this->MODULE_ID . '_' . $item);
                }
				
                closedir($dir);
            }
        }
		return true;		
	}
	
	function InststallEvents() {
		RegisterModuleDependences("main", "OnUserDelete", $this->MODULE_ID, '\Testtask\FacilityOperator\Event', 'eventHandler');
	}
	
	function UnInstallEvents() {
		UnRegisterModuleDependences("main", "OnUserDelete", $this->MODULE_ID, '\Testtask\FacilityOperator\Event', 'eventHandler');
	}
	
	function DoInstall() {
		global $APPLICATION;
		
		if ($this->isVersionD7()) {			
			ModuleManager::registerModule($this->MODULE_ID);
			
			$this->InstallDB();
			$this->InstallEvents();
			$this->InstallFiles();
			
			/* work with settings if needed .settings.php 
			$config = Conf\Configuration::getInstance();
			$testtask_facilityoperator = $config->get('testtask_facilityoperator');
			++$testtask_facilityoperator['install-count']; 
			$config->add('testtask_facilityoperator', $testtask_facilityoperator);
			$config->saveConfiguration(); */
			
		} 
		else {
			$APPLICATION->ThrowException(Loc::getMessage("FACILITY_OPERATOR_INSTALL_ERROR_VERSION"));
		}
		
		$APPLICATION->IncludeAdminFile(
			Loc::getMessage("FACILITY_OPERATOR_INSTALL_TITLE"),
			$this->GetPath()."/install/step.php"
		);
	}
	
	function DoUninstall() {
		global $APPLICATION;
		
		$context = Application::getInstance()->getContext();
		$request = $context->getRequest();
		
		if ($request["step"] < 2) {
			$APPLICATION->IncludeAdminFile(Loc::getMessage("FACILITY_OPERATOR_UNINSTALL_TITLE"), $this->GetPath()."/install/unstep1.php");
		}
		elseif ($request["step"] == 2) {
			$this->UnInstallEvents();
			$this->UnInstallFiles();

			if ($request["savedata"] != "Y") {
				$this->UnInstallDB();
			}
			
			ModuleManager::unRegisterModule($this->MODULE_ID);
			
			/* work with settings if needed .settings.php 
			$config = Conf\Configuration::getInstance();
			$testtask_facilityoperator = $config->get('testtask_facilityoperator');
			++$testtask_facilityoperator['uninstall-count']; 
			$config->add('testtask_facilityoperator', $testtask_facilityoperator);
			$config->saveConfiguration(); */

			$APPLICATION->IncludeAdminFile(
				Loc::getMessage("FACILITY_OPERATOR_UNINSTALL_TITLE"),
				$this->GetPath()."/install/unstep2.php"
			);			
		}		
	}
	
	function GetModuleRightList() {
		return array(
			"reference_id" => array("D", "K", "S", "W"),
			"reference" => array(
				"[D] ".Loc::getMessage("FACILITY_OPERATOR_DENIED"),
				"[K] ".Loc::getMessage("FACILITY_OPERATOR_READ_COMPONENT"),
				"[S] ".Loc::getMessage("FACILITY_OPERATOR_WRITE_SETTINGS"),
				"[W] ".Loc::getMessage("FACILITY_OPERATOR_FULL"),
			)
		);		
	}	
}
