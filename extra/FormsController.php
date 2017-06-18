<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt BSD 3-Clause License
 */

namespace Module\Apis\Controller\Front;

use Pi;
use Pi\Mvc\Controller\ActionController;

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
class FormsController extends ActionController
{
    public function countAction()
    {
        // Set result
        $result = array(
            'status' => 0,
            'message' => '',
        );
        // Set template
        $this->view()->setTemplate(false)->setLayout('layout-content');
        // Get info form url
        $module = $this->params('module');
        $token = $this->params('token');
        // Check module
        if (Pi::service('module')->isActive('forms')) {
            // Check token
            $check = Pi::api('token', 'tools')->check($token, $module, 'api');
            if ($check['status'] == 1) {



                $result = Pi::api('form', 'forms')->count();



                $result['status'] = 1;
                $result['message'] = 'Its work !';
                return $result;
            } else {
                return $check;
            }
        } else {
            return $result;
        }
    }

    public function listAction()
    {
        // Set result
        $result = array(
            'status' => 0,
            'message' => '',
        );
        // Set template
        $this->view()->setTemplate(false)->setLayout('layout-content');
        // Get info form url
        $module = $this->params('module');
        $token = $this->params('token');
        // Check module
        if (Pi::service('module')->isActive('forms')) {
            // Check token
            $check = Pi::api('token', 'tools')->check($token, $module, 'api');
            if ($check['status'] == 1) {



                $result['forms'] = Pi::api('form', 'forms')->getFormList();



                $result['status'] = 1;
                $result['message'] = 'Its work !';
                return $result;
            } else {
                return $check;
            }
        } else {
            return $result;
        }
    }

    public function viewAction()
    {
        // Set result
        $result = array(
            'status' => 0,
            'message' => '',
        );
        // Set template
        $this->view()->setTemplate(false)->setLayout('layout-content');
        // Get info form url
        $module = $this->params('module');
        $token = $this->params('token');
        $uid = $this->params('uid');
        $id = $this->params('id');
        // Check module
        if (Pi::service('module')->isActive('forms')) {
            // Check token
            $check = Pi::api('token', 'tools')->check($token, $module, 'api');
            if ($check['status'] == 1) {



                $selectForm = Pi::api('form', 'forms')->getFormView($id, $uid);
                // Set template
                $this->view()->setTemplate('api-view', 'forms', 'front')->setLayout('layout-content');
                $this->view()->assign('selectForm', $selectForm);
            } else {
                return $check;
            }
        } else {
            return $result;
        }
    }
}