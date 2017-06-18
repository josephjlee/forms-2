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
namespace Module\Forms\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Zend\Db\Sql\Predicate\Expression;

/*
 * Pi::api('form', 'forms')->getForm($id);
 * Pi::api('form', 'forms')->getFormView($id, $uid);
 * Pi::api('form', 'forms')->getView($formId);
 * Pi::api('form', 'forms')->getFormList($uid);
 * Pi::api('form', 'forms')->count($uid);
 * Pi::api('form', 'forms')->canonizeForm($form);
 */

class Form extends AbstractApi
{
    public function getForm($id)
    {
        $selectForm = Pi::model('form', $this->getModule())->find($id);
        $selectForm = $this->canonizeForm($selectForm);
        return $selectForm;
    }

    public function getFormView($id, $uid)
    {
        $where = array('uid' => $uid, 'form' => $id);
        $columns = array('count' => new Expression('count(*)'));
        $select = Pi::model('record', $this->getModule())->select()->columns($columns)->where($where);
        $count = Pi::model('record', $this->getModule())->selectWith($select)->current()->count;
        if ($count == 0) {
            $selectForm = Pi::model('form', $this->getModule())->find(intval($id));
            $selectForm = $this->canonizeForm($selectForm);
            return $selectForm;
        } else {
            return false;
        }
    }

    public function getView($formId)
    {
        $links = array();
        $elements = array();
        // Gey links
        $where = array('form' => $formId);
        $select = Pi::model('link', $this->getModule())->select()->where($where);
        $rowset = Pi::model('link', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $links[$row->element] = $row->element;
        }
        // Check link
        if (!empty($links)) {
            // Get elements
            $where = array('id' => $links);
            $select = Pi::model('element', $this->getModule())->select()->where($where);
            $rowset = Pi::model('element', $this->getModule())->selectWith($select);
            foreach ($rowset as $row) {
                $elements[$row->id] = $row->toArray();
            }
        }

        return $elements;
    }

    public function getFormList($uid)
    {
        $forms = array();
        $where = array('status' => 1, 'time_start <= ?' => time(), 'time_end >= ?' => time());
        $select = Pi::model('form', $this->getModule())->select()->where($where);
        $rowSet = Pi::model('form', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $forms[] =  $this->canonizeForm($row);
        }

        return $forms;
    }

    public function count($uid)
    {
        $count = array();

        // User record forms
        $record = array();
        $where = array('uid' => $uid);
        $select = Pi::model('record', $this->getModule())->select()->where($where);
        $rowSet = Pi::model('record', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $record[] = $row->form;
        }
        $record = array_unique($record);
        $count['record'] = implode(',',$record);

        // general
        $where = array('status' => 1, 'time_start <= ?' => time(), 'time_end >= ?' => time(), 'type' => 'general');
        $columns = array('count' => new Expression('count(*)'));
        $select = Pi::model('form', $this->getModule())->select()->columns($columns)->where($where);
        if (!empty($record)) {
            $select->where(array(new Expression(sprintf('id NOT IN (%s)', implode(',',$record)))));
        }
        $count['general'] = Pi::model('form', $this->getModule())->selectWith($select)->current()->count;

        // dedicated
        $where = array('status' => 1, 'time_start <= ?' => time(), 'time_end >= ?' => time(), 'type' => 'dedicated');
        $columns = array('count' => new Expression('count(*)'));
        $select = Pi::model('form', $this->getModule())->select()->columns($columns)->where($where);
        if (!empty($record)) {
            $select->where(array(new Expression(sprintf('id NOT IN (%s)', implode(',',$record)))));
        }
        $count['dedicated'] = Pi::model('form', $this->getModule())->selectWith($select)->current()->count;

        // total
        $count['total'] = $count['general'] + $count['dedicated'];

        return $count;
    }

    public function canonizeForm($form)
    {
        // Check
        if (empty($form)) {
            return '';
        }

        // object to array
        $form = $form->toArray();

        // Set description
        $form['description'] = Pi::service('markup')->render($form['description'], 'html', 'html');

        // Set time view
        $form['time_create_view'] = _date($form['time_create'], array('pattern' => 'yyyy/MM/dd'));
        $form['time_start_view'] = _date($form['time_start'], array('pattern' => 'yyyy/MM/dd'));
        $form['time_end_view'] = _date($form['time_end'], array('pattern' => 'yyyy/MM/dd'));

        // Set type view
        switch ($form['type']) {
            case 'general':
                $form['type_view'] = __('General');
                break;

            case 'dedicated':
                $form['type_view'] = __('Dedicated');
                break;
        }

        // url
        $form['formUrl'] = Pi::url(Pi::service('url')->assemble('default', array(
            'module' => $this->getModule(),
            'controller' => 'index',
            'slug' => $form['slug'],
        )));
        $form['formUrlApi'] = Pi::url(Pi::service('url')->assemble('default', array(
            'module' => 'apis',
            'controller' => 'forms',
            'action' => 'view'
        )));
        
        return $form;
    }
}