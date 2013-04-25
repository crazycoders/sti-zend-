<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $this->_redirect('index/login/');
    }

    public function signupAction() {
        $this->_helper->layout()->disableLayout();
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->layout()->disableLayout();
            $this->_redirect('index');
        }
        $request = $this->getRequest();
        $province = $this->getprovinceAction();

        $this->view->province = $province;
        if ($request->isPost()) {
            $user_info = new Model_DbTable_Users();
            $user_id = NULL;
            $user_id = $user_info->userRegistration($request->getPost());
            // if (!empty($user_id)) {
            $this->saveaddressAction($request->getPost(), $user_id);
            $this->_redirect("index/index");
            //}
        }
    }

    public function saveaddressAction($address=array(), $user_id=NULL) {
        // print_r($address);print_r($user_id);die();
        if (!empty($user_id)) {
            $user = new Model_DbTable_Address();
            $user->saveAddress($address, $user_id);
        }
        return true;
    }

    public function isOnlineAction() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->layout()->disableLayout();
            return true; //$this->_redirect('index/userprofile');
        }
        $this->_redirect('index/logout');
    }

    public function loginAction() {
        // $this->isOnlineAction();
        //$form = new Form_LoginForm();
        //$this->view->form = $form;
        $request = $this->getRequest();
        if ($request->isPost()) {
            //if ($form->isValid($this->_request->getPost())) {

            $authAdapter = $this->getAuthAdapter();
            $username = $request->getPost("username");
            $password = $request->getPost("password");
            $authAdapter->setIdentity($username)
                    ->setCredential($password);
            $auth = Zend_Auth::getInstance();
            $result = $auth->authenticate($authAdapter);
            if ($result->isValid()) {
                $this->_helper->layout()->disableLayout();
                $province = $this->getprovinceAction();
                $this->view->province = $province;
                $identity = $authAdapter->getResultRowObject();
                $authStorage = $auth->getStorage();
                $authStorage->write($identity);
                $this->_redirect('index/userprofile');
            }
           
        }
    }

    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect("index/login");
    }

    private function getAuthAdapter() {
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName("user")
                ->setIdentityColumn("username")
                ->setCredentialColumn("password");
        return $authAdapter;
    }

    public function welcomeAction() {

        echo "welcome !!!!!!";
    }

    public function getprovinceAction() {
        $province = new Model_DbTable_Province();
        return $province->getProvince();
    }

    public function changeaddressAction() {
        $this->isOnlineAction();
        $request = $this->getRequest();
        $address = new Model_DbTable_Address();
        $user_address = $address->getUserAddress(Zend_Auth::getInstance()->getStorage()->read()->id);
        $this->view->address = $user_address;
        if ($request->isPost()) {

            if ($address->updateAddress($request->getPost()))
                $this->_redirect("index/userprofile");
        }
    }

    public function editprofileAction() {
        $this->isOnlineAction();

        $this->view->userInfo = $this->getuserinformationAction();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $user = new Model_DbTable_Users();
            if ($user->updateUserInformation($request->getPost()))
                $this->_redirect("index/userprofile");
        }
    }

    public function sendMailAction($email_to=NULL, $subject=NULL, $body=NULL) {
        $email_from = "brij420@gmail.com";
        $name_from = "Brijesh Kumar";
        $email_to = "brij420@gmail.com";
        $name_to = "Brijesh";
        $mail = new Zend_Mail ();
        $mail->setReplyTo($email_from, $name_from);
        $mail->setFrom($email_from, $name_from);
        $mail->addTo($email_to, $name_to);
        $mail->setSubject($subject);
        $mail->setBodyText($body);
        $mail->send();
    }

    public function forgotpasswordAction() {
        $this->isOnlineAction();
        $request = $this->getRequest();
        $email = $request->getPost("email");
        $this->sendMailAction($email, 'Subject', 'Body');
    }

    public function changepasswordAction() {
        $this->isOnlineAction();
        $request = $this->getRequest();
        $user = new Model_DbTable_Users();
        $this->view->id = Zend_Auth::getInstance()->getStorage()->read()->id;
        if ($request->isPost()) {

            if ($user->updatePassword($request->getPost()))
                $this->_redirect("index/userprofile");
        }
    }

    public function getuserinformationAction() {
        $this->isOnlineAction();
        $this->setuserinformationAction();
        $sess = new Zend_Session_Namespace('userinfo');
        return $sess->userinfo;
    }

    public function setuserinformationAction() {
        $this->isOnlineAction();
        $user = new Model_DbTable_Users();
        $sess = new Zend_Session_Namespace('userinfo');
        $sess->userinfo = $user->getUserInformation(Zend_Auth::getInstance()->getStorage()->read()->id);
        return true;
    }

    public function userprofileAction() {
        $this->isOnlineAction();
        //$userInfo = Zend_Auth::getInstance()->getStorage()->read();
        $this->view->userInfo = $this->getuserinformationAction();
    }

}

