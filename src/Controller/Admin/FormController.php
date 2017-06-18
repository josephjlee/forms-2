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
use Zend\Db\Sql\Predicate\Expression;

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
                $this->jump(array('action' => 'index'), $message);
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
}