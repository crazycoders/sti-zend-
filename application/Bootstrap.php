<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initAutoload() {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
                    'namespace' => '',
                    'basePath' => APPLICATION_PATH));
        // print_r($moduleLoader);die();
        return $moduleLoader;
    }

    public function _initViewHelpers() {

        $this->bootstrap("layout");
        $layout = $this->getResource("layout");
        $view = $layout->getView();
//        $view->doctype('HTML4_STRICT');
//        $view->headMeta()->appendHttpEquiv('content-type','text/html')
//                ->appendName('description','using view');
    }

    protected function _initMail() {
        try {
            $config = array(
                'auth' => 'login',
                'username' => 'username@gmail.com',
                'password' => 'password',
                'ssl' => 'ssl',
                'port' => 465
            );

            $mailTransport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
            Zend_Mail::setDefaultTransport($mailTransport);
        } catch (Zend_Exception $e) {

        }
    }

}

