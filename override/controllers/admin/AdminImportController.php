<?php
class AdminImportController extends AdminImportControllerCore
{
    
    public function __construct()
    {
        self::$validators['cost'] = array('AdminImportController', 'getPrice');
        self::$validators['conseils'] = array('AdminImportController', 'createMultiLangField');
        parent::__construct();
        switch ((int)Tools::getValue('entity')) {
            case $this->entities[$this->trans('Products', array(), 'Admin.Global')]:
                $this->available_fields ['conseils'] = array('label' => $this->trans('Conseils', array(), 'Admin.Catalog.Feature'));
                self::$default_values['conseils'] = '';
                $this->available_fields ['cost'] = array('label' => $this->trans('Confidential price', array(), 'Admin.Catalog.Feature'));
                self::$default_values['cost'] = '';
                break;
        }
    }
}