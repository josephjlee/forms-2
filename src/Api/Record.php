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

/*
 * Pi::api('record', 'forms')->getRecord($id);
 * Pi::api('record', 'forms')->getRecordData($record);
 * Pi::api('record', 'forms')->getUser($uid);
 * Pi::api('record', 'forms')->canonizeRecord($record, $form, $user);
 */

class Record extends AbstractApi
{
    public function getRecord($id)
    {
        $record = Pi::model('record', $this->getModule())->find($id);
        $record = $this->canonizeRecord($record);
        return $record;
    }

    public function canonizeRecord($record, $form = [], $user = [])
    {
        // Check
        if (empty($record)) {
            return '';
        }

        // Check form
        if (empty($form)) {
            $form = Pi::api('form', 'forms')->getForm($record['form']);
        }

        // Check user
        if (empty($user)) {
            $user = $this->getUser($record['uid']);
        }

        // object to array
        $record = $record->toArray();

        // Set time view
        $record['time_create_view'] = _date($record['time_create'], ['pattern' => 'yyyy/MM/dd']);

        // Set user
        $record['user'] = $user;

        // Set form
        $record['form'] = $form;

        return $record;
    }

    public function getUser($uid)
    {
        $fields = [
            'id', 'identity', 'name', 'email',
        ];
        $user   = Pi::user()->get($uid, $fields);

        return $user;
    }

    public function getRecordData($record)
    {
        $dataTable    = Pi::model('data', 'forms')->getTable();
        $elementTable = Pi::model('element', 'forms')->getTable();

        $list   = [];
        $where  = ['record' => $record];
        $select = Pi::db()->select();
        $select->from(['data' => $dataTable]);
        $select->join(['element' => $elementTable], 'data.element = element.id', ['element_id' => 'id', 'element_title' => 'title', 'element_type' => 'type']);
        $select->where($where);
        $rowset = Pi::model('data', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
            if ($row->element_type == 'checkbox') {
                $list[$row->id]['value'] = implode(' , ', json_decode($row->value));
            }
        }

        return $list;
    }
}