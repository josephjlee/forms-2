<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
namespace Module\Forms\Controller\Admin;

use Pi;
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;
use Module\Forms\Form\ManageForm;
use Module\Forms\Form\ManageFilter;
use Module\Forms\Form\LinkForm;
use Module\Forms\Form\LinkFilter;
use Module\Forms\Form\ViewForm;
use Module\Forms\Form\ViewFilter;

class FormController extends ActionController
{
    public function indexAction()
    {
        // Get info
        $list = array();
        $order = array('time_create DESC', 'id DESC');
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
        // Set form
        $form = new ManageForm('manage');
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            // Set slug
            $slug = ($data['slug']) ? $data['slug'] : $data['title'];
            $filter = new Filter\Slug;
            $data['slug'] = $filter($slug);
            // Form filter
            $form->setInputFilter(new ManageFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                $values['time_start'] = strtotime($values['time_start']);
                $values['time_end'] = ($values['time_end']) ? strtotime($values['time_end']) : '';
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
                // Jump
                $message = __('Form data saved successfully.');
                $this->jump(array('action' => 'element', 'id' => $values['id']), $message);
            }
        } else {
            if ($id) {
                $formManage = $this->getModel('form')->find($id)->toArray();
                $formManage['time_start'] = ($formManage['time_start']) ? date('Y/m/d', $formManage['time_start']) : date('Y/m/d');
                $formManage['time_end'] = ($formManage['time_end']) ? date('Y/m/d', $formManage['time_end']) : '';
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
        $links = array();
        $where = array('form' => $selectForm['id']);
        $select = $this->getModel('link')->select()->where($where);
        $rowset = $this->getModel('link')->selectWith($select);
        foreach ($rowset as $row) {
            $links[$row->element] = $row->toArray();
        }
        // Get elements
        $elements = array();
        $where = array('status' => 1);
        $select = $this->getModel('element')->select()->where($where);
        $rowset = $this->getModel('element')->selectWith($select);
        foreach ($rowset as $row) {
            $elements[$row->id] = $row->toArray();
            if (isset($links[$row->id])) {
                $elements[$row->id]['link'] = 1;
            } else {
                $elements[$row->id]['link'] = 0;
            }
        }
        // Set option
        $option = array();
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
                $saveLink = array();
                foreach ($elements as $element) {
                    $elementKey = sprintf('element-%s', $element['id']);
                    if (isset($values[$elementKey]) && $values[$elementKey] == 1) {
                        $saveLink[] = array(
                            'form' => $selectForm['id'],
                            'element' => $element['id'],
                        );
                    }
                }
                // Remove links
                $this->getModel('link')->delete(array(
                    'form' => $selectForm['id']
                ));
                // Save links
                foreach ($saveLink as $link) {
                    // Save
                    $row = $this->getModel('link')->createRow();
                    $row->form = $link['form'];
                    $row->element = $link['element'];
                    $row->save();
                }
                // Jump
                $message = __('Form elements saved successfully.');
                $this->jump(array('action' => 'index'), $message);
            }
        } else {
            $data = array(
                'id' => $selectForm['id'],
            );
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
            }


        } else {
            $data = array(
                'id' => $selectForm['id'],
            );
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