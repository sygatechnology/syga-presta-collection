<?php
class SiCollection extends Module {
    
     public function __construct() {

        $this->name = 'sicollection';
        $this->tab = 'others';
        $this->author = 'B.Clash';
        $this->version = '0.1.0';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Syga Collection');
        $this->description = $this->l('Asigner un produit à une collection');
        $this->ps_versions_compliancy = array('min' => '1.7.1', 'max' => _PS_VERSION_);
    }
    
   public function install() {
        if (!parent::install()
                //Pour les hooks suivants regarder le fichier src\PrestaShopBundle\Resources\views\Admin\Product\form.html.twig
                || ! $this->registerHook('displayAdminProductsMainStepRightColumnBottom')
        ) {
            return false;
        }

        return true;
    }
    
     public function uninstall() {
        return parent::uninstall();
    }

    /**
     * Modifications sql du module
     * @return boolean
     */
    protected function _installSql() {
        /*$sqlInstall = "ALTER TABLE " . _DB_PREFIX_ . "product "
                . "ADD how_to_use_video TEXT NULL";
        $sqlInstallLang = "ALTER TABLE " . _DB_PREFIX_ . "product_lang "
                . "ADD how_to_use_details TEXT NULL";

        $returnSql = Db::getInstance()->execute($sqlInstall);
        $returnSqlLang = Db::getInstance()->execute($sqlInstallLang);
        
        return $returnSql && $returnSqlLang;*/
    }

    /**
     * Suppression des modification sql du module
     * @return boolean
     */
    protected function _unInstallSql() {
       /*$sqlInstall = "ALTER TABLE " . _DB_PREFIX_ . "product "
                . "DROP how_to_use_video";
        $sqlInstallLang = "ALTER TABLE " . _DB_PREFIX_ . "product_lang "
                . "DROP how_to_use_details";

        $returnSql = Db::getInstance()->execute($sqlInstall);
        $returnSqlLang = Db::getInstance()->execute($sqlInstallLang);
        
        return $returnSql && $returnSqlLang;*/
    }
    
    
    /*public function hookDisplayAdminProductsExtra($params)
    {
       return $this->_displayHookContentBlock(__FUNCTION__);
    }*/
    
    /**
     * Affichage des informations supplémentaires sur la fiche produit
     * @param type $params
     * @return type
     */
    public function displayAdminProductsMainStepRightColumnBottom($params) {
        $this->context->smarty->assign(array(
            'collections' => self::cats_tree( 4 )
        ));
        return $this->display(__FILE__, 'views/templates/hook/categories.tpl');
    }

    function cats_tree( $lang )
    {
        $categories = array( 15, 18, 20, 21, 23, 24, 29 );
        $ids = array();
        $results = Db::getInstance()->ExecuteS("SELECT c.id_parent, c.id_category, CONCAT ( REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(level_depth,1,''),2,''),3,''),4,''),5,''),6,''),cl.name) as name, cl.description, cl.link_rewrite,cs.position ,level_depth
                FROM "._DB_PREFIX_."category c
                LEFT JOIN "._DB_PREFIX_."category_lang cl ON (c.id_category = cl.id_category AND id_lang = $lang)
                LEFT JOIN "._DB_PREFIX_."category_group cg ON (cg.`id_category` = c.id_category)
                LEFT JOIN `"._DB_PREFIX_."category_shop` cs ON (c.`id_category` = cs.`id_category` )
                WHERE c.id_category <> '1'  
                GROUP BY c.id_category
                ORDER BY c.`id_parent` ASC,level_depth ASC");
        foreach ( $results as $row ) {
            if ( in_array( $row['id_category'], $ids ) ) {
                $categories[] = $row;
            }
        }
        return $categories;
    }

    
    /**
     * Fonction pour afficher les différents blocks disponibles
     * @param type $hookName
     * @return type
     */
    protected function  _displayHookContentBlock( $hookName ) {
        $this->context->smarty->assign( 'hookName', $hookName );
        return $this->display(__FILE__, 'views/templates/hook/hookBlock.tpl');
    }

}
