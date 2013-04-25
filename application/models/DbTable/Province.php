<?php

class Model_DbTable_Province extends Zend_Db_Table_Abstract {

    protected $_name = 'province';

    
    public function getProvince() {
        $result = $this->fetchAll();
        if (!$result) {
            return false;
        }
        return $result->toArray();
    }

}