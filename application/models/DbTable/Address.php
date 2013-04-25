<?php

class Model_DbTable_Address extends Zend_Db_Table_Abstract {

    protected $_name = 'address';

    public function saveAddress($post=array(), $user_id=NULL) {

        $data = array('id' => '',
            'city' => isset($post['city']) && ($post['city']) ? $post['city'] : 0,
            'address_1' => isset($post['address_1']) && ($post['address_1']) ? $post['address_1'] : 0, //$post['cat_id'],
            'address_2' => isset($post['address_2']) && ($post['address_2']) ? $post['address_2'] : 0, //$post['section_id'],
            'telephone' => isset($post['telephone']) && ($post['telephone']) ? $post['telephone'] : '',
            'postal_code' => isset($post['postal_code']) && ($post['postal_code']) ? $post['postal_code'] : 0,
            'user_id' => $user_id
        );
        return $this->insert($data);
    }

    public function getUserAddress($id=NULL) {
        $id = (int) $id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            return false;
        }
        return $row->toArray();
    }

    public function updateAddress($post=NULL) {
        $data = array('city' => $post['city'],
            'address_1' => $post['address_1'],
            'address_2' => $post['address_2'],
            'postal_code' => $post['postal_code']
        );
        $where = 'id = "' . $post['id'] . '" AND user_id="' . $post['user_id'] . '"';
        $this->update($data, $where);
        return true;
    }

}