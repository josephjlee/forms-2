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

namespace Module\Forms\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Zend\Db\Sql\Predicate\Expression;

/*
 * Pi::api('form', 'forms')->getForm($parameter, $field);
 * Pi::api('form', 'forms')->getFormView($id, $uid, $key);
 * Pi::api('form', 'forms')->getView($formId);
 * Pi::api('form', 'forms')->getFormList();
 * Pi::api('form', 'forms')->getAllowIdList($form, $uid);
 * Pi::api('form', 'forms')->count($uid);
 * Pi::api('form', 'forms')->canonizeForm($form);
 * Pi::api('form', 'forms')->save($formId, $params);
 */

class Form extends AbstractApi
{
    public function getForm($parameter, $field = 'id')
    {
        $selectForm = Pi::model('form', $this->getModule())->find($parameter, $field);
        $selectForm = $this->canonizeForm($selectForm);
        return $selectForm;
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
        $form['time_create_view'] = _date($form['time_create'], ['pattern' => 'yyyy/MM/dd']);
        $form['time_start_view']  = _date($form['time_start'], ['pattern' => 'yyyy/MM/dd']);
        $form['time_end_view']    = _date($form['time_end'], ['pattern' => 'yyyy/MM/dd']);

        // Count view
        $form['count_view'] = _number($form['count']);

        // url
        $form['formUrl']    = Pi::url(
            Pi::service('url')->assemble(
                'default', [
                'module'     => $this->getModule(),
                'controller' => 'index',
                'action'     => 'view',
                'slug'       => $form['slug'],
            ]
            )
        );
        $form['formUrlApi'] = Pi::url(
            Pi::service('url')->assemble(
                'default', [
                'module'     => 'apis',
                'controller' => 'forms',
                'action'     => 'view',
            ]
            )
        );

        return $form;
    }

    public function getFormCount($id, $uid, $key = 0)
    {
        // Set info
        $where = ['uid' => $uid, 'form' => $id];
        if ($key > 0) {
            $where['extra_key'] = $key;
        }
        $columns = ['count' => new Expression('count(*)')];

        // Get count
        $select  = Pi::model('record', $this->getModule())->select()->columns($columns)->where($where);
        $count   = Pi::model('record', $this->getModule())->selectWith($select)->current()->count;

        return $count;
    }

    public function getFormView($id, $uid, $key = 0)
    {
        $where = ['uid' => $uid, 'form' => $id];
        if ($key > 0) {
            $where['extra_key'] = $key;
        }
        $columns = ['count' => new Expression('count(*)')];
        $select  = Pi::model('record', $this->getModule())->select()->columns($columns)->where($where);
        $count   = Pi::model('record', $this->getModule())->selectWith($select)->current()->count;
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
        $links    = [];
        $elements = [];
        // Gey links
        $where  = ['form' => $formId];
        $select = Pi::model('link', $this->getModule())->select()->where($where);
        $rowset = Pi::model('link', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $links[$row->element] = $row->element;
        }
        // Check link
        if (!empty($links)) {
            // Get elements
            $where  = ['id' => $links];
            $order  = ['order ASC', 'id ASC'];
            $select = Pi::model('element', $this->getModule())->select()->where($where)->order($order);
            $rowset = Pi::model('element', $this->getModule())->selectWith($select);
            foreach ($rowset as $row) {
                $elements[$row->id] = $row->toArray();
            }
        }

        return $elements;
    }

    public function getFormList()
    {
        // Get list form
        $forms  = [];
        $where  = ['status' => 1, 'time_start <= ?' => time(), 'time_end >= ?' => time()];
        $select = Pi::model('form', $this->getModule())->select()->where($where);
        $rowSet = Pi::model('form', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $forms[] = $this->canonizeForm($row);
        }

        return $forms;
    }

    public function getAllowIdList($form)
    {
        $idList = [];
        $where  = ['form' => $form];
        $select = Pi::model('extra', $this->getModule())->select()->where($where);
        $rowSet = Pi::model('extra', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $idList[] = $row->extra_key;
        }
        return array_unique($idList);
    }

    /* public function count($uid)
    {
        $count = array();

        // User record forms
        $record = array();
        $where = array('uid' => $uid, 'extra_key' => 0);
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
    } */

    public function getNotAllowIdList($form, $uid)
    {
        $idList = [];
        $where  = ['form' => $form, 'uid' => $uid];
        $select = Pi::model('record', $this->getModule())->select()->where($where);
        $rowSet = Pi::model('record', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $idList[] = $row->form;
        }
        return array_unique($idList);
    }

    public function save($formId, $params)
    {
        // Save record
        $saveRecord              = Pi::model('record', 'forms')->createRow();
        $saveRecord->uid         = $params['uid'];
        $saveRecord->form        = $formId;
        $saveRecord->time_create = time();
        $saveRecord->ip          = Pi::user()->getIp();
        $saveRecord->save();

        // Save elements
        foreach ($params['elements'] as $element) {
            $elementKey = sprintf('element-%s', $element['id']);
            if (isset($params['values'][$elementKey]) && !empty($params['values'][$elementKey])) {

                // Check is array
                if (is_array($params['values'][$elementKey])) {
                    $params['values'][$elementKey] = json_encode($params['values'][$elementKey]);
                }

                $saveData              = Pi::model('data', 'forms')->createRow();
                $saveData->record      = $saveRecord->id;
                $saveData->uid         = $params['uid'];
                $saveData->form        = $formId;
                $saveData->time_create = time();
                $saveData->element     = $element['id'];
                $saveData->value       = _escape(_strip($params['values'][$elementKey]));
                $saveData->save();
            }
        }

        // Update count
        Pi::model('form', 'forms')->increment('count', ['id' => $formId]);
    }
}
