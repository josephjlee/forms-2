<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Forms\Controller\Front;

use Module\Forms\Form\ViewFilter;
use Module\Forms\Form\ViewForm;
use Pi;
use Pi\Mvc\Controller\ActionController;

class IndexController extends ActionController
{
    public function indexAction()
    {
        // Check login in
        Pi::service('authentication')->requireLogin();

        // Get info
        $module = $this->params('module');

        // Get Module Config
        $config = Pi::service('registry')->config->read($module);

        // Get uid
        $uid = Pi::user()->getId();

        // Get form list
        $forms = Pi::api('form', 'forms')->getFormList();

        // Get record list
        $records = Pi::api('record', 'forms')->getRecordList($uid);

        // Set template
        $this->view()->setTemplate('form-index');
        $this->view()->assign('config', $config);
        $this->view()->assign('records', $records);
        $this->view()->assign('forms', $forms);
    }

    public function viewAction()
    {
        // Check login in
        Pi::service('authentication')->requireLogin();

        // Get info
        $module = $this->params('module');
        $slug   = $this->params('slug');

        // Get Module Config
        $config = Pi::service('registry')->config->read($module);

        // Get uid
        $uid = Pi::user()->getId();

        // Get form
        $selectForm = Pi::api('form', 'forms')->getForm($slug, 'slug');

        // Check form
        if (!$selectForm || $selectForm['status'] != 1) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('The form not found.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Check form time
        if ($selectForm['time_start'] > time() || $selectForm['time_end'] < time()) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('You not allowed to fill this form.'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Get form
        $recordCount = Pi::api('form', 'forms')->getFormCount($selectForm['id'], $uid);

        // Check
        if ($recordCount > 0) {
            // Jump
            //$this->jump(['action' => 'index'],  __('You fill this form before'), 'error');
        }

        // Get view
        $elements = Pi::api('form', 'forms')->getView($selectForm['id']);

        // Set option
        $option             = [];
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
                $saveRecord              = Pi::model('record', 'forms')->createRow();
                $saveRecord->uid         = $uid;
                $saveRecord->form        = $selectForm['id'];
                $saveRecord->time_create = time();
                $saveRecord->ip          = Pi::user()->getIp();
                $saveRecord->save();

                // Save data
                foreach ($elements as $element) {
                    $elementKey = sprintf('element-%s', $element['id']);
                    if (isset($values[$elementKey]) && !empty($values[$elementKey])) {
                        if (is_array($values[$elementKey])) {
                            $values[$elementKey] = json_encode($values[$elementKey]);
                        }
                        $saveData              = Pi::model('data', 'forms')->createRow();
                        $saveData->record      = $saveRecord->id;
                        $saveData->uid         = Pi::user()->getId();
                        $saveData->form        = $selectForm['id'];
                        $saveData->time_create = time();
                        $saveData->element     = $element['id'];
                        $saveData->value       = $values[$elementKey];
                        $saveData->save();
                    }
                }

                // Update count
                Pi::model('form', 'forms')->increment('count', ['id' => $selectForm['id']]);

                // Jump
                $this->jump(['action' => 'index'],  __('Form input values saved successfully.'), 'success');
            }
        } else {
            $data = [
                'id' => $selectForm['id'],
            ];
            $form->setData($data);
        }

        // Set template
        $this->view()->setTemplate('form-view');
        $this->view()->assign('config', $config);
        $this->view()->assign('selectForm', $selectForm);
        $this->view()->assign('elements', $elements);
        $this->view()->assign('form', $form);
    }
}
