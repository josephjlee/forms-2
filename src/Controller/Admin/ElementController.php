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

namespace Module\Forms\Controller\Admin;

use Module\Forms\Form\ElementFilter;
use Module\Forms\Form\ElementForm;
use Pi\Mvc\Controller\ActionController;

class ElementController extends ActionController
{
    public function indexAction()
    {
        // Get info
        $list   = [];
        $order  = ['order ASC', 'id ASC'];
        $select = $this->getModel('element')->select()->order($order);
        $rowset = $this->getModel('element')->selectWith($select);

        // Make list
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
        }

        // Set template
        $this->view()->setTemplate('element-index');
        $this->view()->assign('list', $list);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');

        // Set form
        $form = new ElementForm('element');
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new ElementFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Save values
                if (!empty($id)) {
                    $row = $this->getModel('element')->find($id);
                } else {
                    $row = $this->getModel('element')->createRow();
                }
                $row->assign($values);
                $row->save();

                // Jump
                $message = __('Element data saved successfully.');
                $this->jump(['action' => 'index'], $message);
            }
        } else {
            if ($id) {
                $formManage = $this->getModel('element')->find($id)->toArray();
                $form->setData($formManage);
            }
        }

        // Set template
        $this->view()->setTemplate('element-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Element form'));
    }

    public function sortAction()
    {
        $order = 1;
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            foreach ($data['mod'] as $id) {
                if ($id > 0) {
                    $row        = $this->getModel('element')->find($id);
                    $row->order = $order;
                    $row->save();
                    $order++;
                }
            }
        }
        // Set view
        $this->view()->setTemplate(false);
    }
}
