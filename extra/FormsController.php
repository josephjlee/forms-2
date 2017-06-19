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
use Module\Forms\Form\ViewForm;
use Module\Forms\Form\ViewFilter;

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
        $uid = $this->params('uid');
        // Check module
        if (Pi::service('module')->isActive('forms')) {
            // Check token
            $check = Pi::api('token', 'tools')->check($token, $module, 'api');
            if ($check['status'] == 1) {
                if ($uid > 0) {



                    $result = Pi::api('form', 'forms')->count($uid);



                    $result['status'] = 1;
                    $result['message'] = 'Its work !';
                    return $result;
                } else {
                    return $result;
                }
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
        $uid = $this->params('uid');
        // Check module
        if (Pi::service('module')->isActive('forms')) {
            // Check token
            $check = Pi::api('token', 'tools')->check($token, $module, 'api');
            if ($check['status'] == 1) {
                if ($uid > 0) {



                    $result['forms'] = Pi::api('form', 'forms')->getFormList($uid);



                    $result['status'] = 1;
                    $result['message'] = 'Its work !';
                    return $result;
                } else {
                    return $result;
                }
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
                if ($uid > 0) {
                    $selectForm = Pi::api('form', 'forms')->getFormView($id, $uid);
                    // Check form
                    if ($selectForm) {
                        // Get view
                        $elements = Pi::api('form', 'forms')->getView($selectForm['id']);
                        // Set option
                        $option = array();
                        $option['elements'] = $elements;
                        // Set form
                        $form = new ViewForm('link', $option);
                        $form->setAttribute('enctype', 'multipart/form-data');
                        if ($this->request->isPost()) {
                            $data = $this->request->getPost();
                            $form->setInputFilter(new ViewFilter($option));
                            $form->setData($data);
                            if ($form->isValid()) {
                                $values = $form->getData();
                                // Save record
                                $saveRecord = Pi::model('record', 'forms')->createRow();
                                $saveRecord->uid = Pi::user()->getId();
                                $saveRecord->form = $selectForm['id'];
                                $saveRecord->time_create = time();
                                $saveRecord->ip = Pi::user()->getIp();
                                $saveRecord->save();
                                // Save data
                                foreach ($elements as $element) {
                                    $elementKey = sprintf('element-%s', $element['id']);
                                    if (isset($values[$elementKey]) && !empty($values[$elementKey])) {
                                        if (is_array($values[$elementKey])) {
                                            $values[$elementKey] = json_encode($values[$elementKey]);
                                        }
                                        $saveData = Pi::model('data', 'forms')->createRow();
                                        $saveData->record = $saveRecord->id;
                                        $saveData->uid = Pi::user()->getId();
                                        $saveData->form = $selectForm['id'];
                                        $saveData->time_create = time();
                                        $saveData->element = $element['id'];
                                        $saveData->value = $values[$elementKey];
                                        $saveData->save();
                                    }
                                }
                                // Update count
                                Pi::model('form', 'forms')->increment('count', array('id' => $selectForm['id']));
                                // Jump
                                $this->jump(array('action' => 'finish'));
                            }
                        } else {
                            $data = array(
                                'id' => $selectForm['id'],
                            );
                            $form->setData($data);
                        }
                        // Set view
                        $this->view()->assign('elements', $elements);
                        $this->view()->assign('form', $form);
                    }
                    // Set template
                    $this->view()->setTemplate('api-view', 'forms', 'front')->setLayout('layout-front');
                    $this->view()->assign('selectForm', $selectForm);
                } else {
                    return $result;
                }
            } else {
                return $check;
            }
        } else {
            return $result;
        }
    }

    public function finishAction()
    {
        $this->view()->setTemplate('api-finish', 'forms', 'front')->setLayout('layout-front');
    }
}