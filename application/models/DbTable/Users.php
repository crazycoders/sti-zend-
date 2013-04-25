<?php

class Model_DbTable_Users extends Zend_Db_Table_Abstract {

    protected $_name = 'user';

    public function findCredentials($username, $pwd) {
        $select = $this->select()->where('email = ?', $username)
                        ->where('password = ?', $this->hashPassword($pwd));
        $row = $this->fetchRow($select);
        if ($row) {
            return $row;
        }
        return false;
    }

    protected function hashPassword($pwd) {
        return md5($pwd);
    }

    public function userRegistration($post=array()) {

        $data = array('id' => '',
            'first_name' => isset($post['first_name']) && ($post['first_name']) ? $post['first_name'] : 0,
            'last_name' => isset($post['last_name']) && ($post['last_name']) ? $post['last_name'] : 0, //$post['cat_id'],
            'email' => isset($post['email']) && ($post['email']) ? $post['email'] : 0, //$post['section_id'],
            'telephone' => isset($post['telephone']) && ($post['telephone']) ? $post['telephone'] : '',
            'password' => isset($post['password']) && ($post['password']) ? $post['password'] : 0,
            'address_id' => isset($post['address_id']) && ($post['address_id']) ? $post['address_id'] : 0,
            'online' => isset($post['online']) && ($post['online']) ? $post['online'] : 0,
            'is_active' => isset($post['is_active']) && ($post['is_active']) ? $post['is_active'] : 0,
            'province' => isset($post['province']) && ($post['province']) ? $post['province'] : 0,
            'date' => isset($post['date']) && ($post['date']) ? $post['date'] : 0
        );
        return $this->insert($data);
    }

    public function updateUserInformation($post) {

        $data = array('first_name' => $post['first_name'],
            'last_name' => $post['last_name'],
            'email' => $post['email']
        );
        $where = 'id = "' . $post['id'] . '"';
        $this->update($data, $where);
        return true;
    }

    public function getUserInformation($id=NULL) {
        $id = (int) $id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            return false;
        }
        return $row->toArray();
    }

    public function updatePassword($post=array()) {

        $data = array('password' => $post['new_password']
        );
        $where = 'id = "' . $post['id'] . '"';
        $this->update($data, $where);
        return true;
    }

}