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

use Module\Forms\Form\LinkFilter;
use Module\Forms\Form\LinkForm;
use Module\Forms\Form\ManageFilter;
use Module\Forms\Form\ManageForm;
use Module\Forms\Form\ViewFilter;
use Module\Forms\Form\ViewForm;
use Pi;
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;

class FormController extends ActionController
{
    public function indexAction()
    {
        // Get info
        $list   = [];
        $order  = ['time_create DESC', 'id DESC'];
        $select = $this->getModel('form')->select()->order($order);
        $rowset = $this->getModel('form')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $list[$row->id] = Pi::api('form', 'forms')->canonizeForm($row);
        }
        // Set template
        $this->view()->setTemplate('form-index');
        $this->view()->assign('list', $list);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');

        // Set option
        $option = [
            'brand' => [],
        ];

        // Get brand list
        $option['brand'] = [];
        if (Pi::service('module')->isActive('shop')) {
            $where  = ['status' => 1, 'type' => 'brand'];
            $order  = ['display_order ASC', 'title ASC', 'id DESC'];
            $select = Pi::model('category', 'shop')->select()->where($where)->order($order);
            $rowset = Pi::model('category', 'shop')->selectWith($select);
            foreach ($rowset as $row) {
                $option['brand'][$row->id] = $row->title;
            }
        }

        // Set form
        $form = new ManageForm('manage', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            // Set slug
            $slug         = ($data['slug']) ? $data['slug'] : $data['title'];
            $filter       = new Filter\Slug;
            $data['slug'] = $filter($slug);
            // Form filter
            $form->setInputFilter(new ManageFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $values               = $form->getData();
                $values['time_start'] = strtotime($values['time_start']);
                $values['time_end']   = ($values['time_end']) ? strtotime($values['time_end']) : '';
                if (empty($values['id'])) {
                    $values['time_create'] = time();
                }
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('form')->find($values['id']);
                } else {
                    $row = $this->getModel('form')->createRow();
                }
                $row->assign($values);
                $row->save();

                // Remove links
                $this->getModel('extra')->delete(
                    [
                        'form' => $row->id,
                    ]
                );
                // Save links
                foreach ($values['extra_key'] as $extraId) {
                    // Save
                    $extra            = $this->getModel('extra')->createRow();
                    $extra->form      = $row->id;
                    $extra->extra_key = $extraId;
                    $extra->save();
                }
                // Jump
                $message = __('Form data saved successfully.');
                $this->jump(['action' => 'element', 'id' => $row->id], $message);
            }
        } else {
            if ($id) {
                $formManage               = $this->getModel('form')->find($id)->toArray();
                $formManage['time_start'] = ($formManage['time_start']) ? date('Y/m/d', $formManage['time_start']) : date('Y/m/d');
                $formManage['time_end']   = ($formManage['time_end']) ? date('Y/m/d', $formManage['time_end']) : '';

                $where  = ['form' => $formManage['id']];
                $select = $this->getModel('extra')->select()->where($where);
                $rowset = $this->getModel('extra')->selectWith($select);
                foreach ($rowset as $row) {
                    $formManage['extra_key'][] = $row['extra_key'];
                }

                $form->setData($formManage);
            }
        }
        // Set template
        $this->view()->setTemplate('form-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Manage form'));
    }

    public function elementAction()
    {
        // Get id
        $id = $this->params('id');
        // Get form
        $selectForm = Pi::api('form', 'forms')->getForm($id);
        // Get links
        $links  = [];
        $where  = ['form' => $selectForm['id']];
        $select = $this->getModel('link')->select()->where($where);
        $rowset = $this->getModel('link')->selectWith($select);
        foreach ($rowset as $row) {
            $links[$row->element] = $row->toArray();
        }
        // Get elements
        $elements = [];
        $where    = ['status' => 1];
        $select   = $this->getModel('element')->select()->where($where);
        $rowset   = $this->getModel('element')->selectWith($select);
        foreach ($rowset as $row) {
            $elements[$row->id] = $row->toArray();
            if (isset($links[$row->id])) {
                $elements[$row->id]['link'] = 1;
            } else {
                $elements[$row->id]['link'] = 0;
            }
        }
        // Set option
        $option             = [];
        $option['elements'] = $elements;
        // Set form
        $form = new LinkForm('link', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new LinkFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set save link array
                $saveLink = [];
                foreach ($elements as $element) {
                    $elementKey = sprintf('element-%s', $element['id']);
                    if (isset($values[$elementKey]) && $values[$elementKey] == 1) {
                        $saveLink[] = [
                            'form'    => $selectForm['id'],
                            'element' => $element['id'],
                        ];
                    }
                }
                // Remove links
                $this->getModel('link')->delete(
                    [
                        'form' => $selectForm['id'],
                    ]
                );
                // Save links
                foreach ($saveLink as $link) {
                    // Save
                    $row          = $this->getModel('link')->createRow();
                    $row->form    = $link['form'];
                    $row->element = $link['element'];
                    $row->save();
                }
                // Jump
                $message = __('Form elements saved successfully.');
                $this->jump(['action' => 'index'], $message);
            }
        } else {
            $data = [
                'id' => $selectForm['id'],
            ];
            $form->setData($data);
        }
        // Set template
        $this->view()->setTemplate('form-element');
        $this->view()->assign('title', sprintf(__('Manage %s elements'), $selectForm['title']));
        $this->view()->assign('selectForm', $selectForm);
        $this->view()->assign('elements', $elements);
        $this->view()->assign('form', $form);
    }

    public function viewAction()
    {
        // Get id
        $id = $this->params('id');

        // Get form
        $selectForm = Pi::api('form', 'forms')->getForm($id);

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
                $saveRecord              = $this->getModel('record')->createRow();
                $saveRecord->uid         = Pi::user()->getId();
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
                        $saveData              = $this->getModel('data')->createRow();
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
                $this->getModel('form')->increment('count', ['id' => $selectForm['id']]);

                // Jump
                $message = __('Form input values saved successfully.');
                $this->jump(['action' => 'index'], $message);
            }
        } else {
            $data = [
                'id' => $selectForm['id'],
            ];
            $form->setData($data);
        }

        // Set template
        $this->view()->setTemplate('form-view');
        $this->view()->assign('title', sprintf(__('View %s elements'), $selectForm['title']));
        $this->view()->assign('selectForm', $selectForm);
        $this->view()->assign('elements', $elements);
        $this->view()->assign('form', $form);
    }
}