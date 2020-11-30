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

/*
 * Pi::api('record', 'forms')->getRecord($id);
 * Pi::api('record', 'forms')->getUser($uid);
 * Pi::api('record', 'forms')->getRecordData($record);
 * Pi::api('record', 'forms')->getRecordList($uid);
 * Pi::api('record', 'forms')->canonizeRecord($record, $form, $user);
 */

class Record extends AbstractApi
{
    public function getRecord($id, $form = [], $user = [], $setForm = true, $setUser = true)
    {
        $record = Pi::model('record', $this->getModule())->find($id);
        return $this->canonizeRecord($record, $form, $user, $setForm, $setUser);
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
        // Set data list
        $list = [];

        // Set tables
        $dataTable    = Pi::model('data', 'forms')->getTable();
        $elementTable = Pi::model('element', 'forms')->getTable();

        // Select
        $where  = ['record' => $record];
        $select = Pi::db()->select();
        $select->from(['data' => $dataTable]);
        $select->join(
            ['element' => $elementTable],
            'data.element = element.id',
            [
                'element_id'     => 'id',
                'element_title'  => 'title',
                'element_type'   => 'type',
                'element_value'  => 'value',
                'element_answer' => 'answer',
            ]
        );
        $select->where($where);
        $rowSet = Pi::db()->query($select);

        // Make list
        foreach ($rowSet as $row) {

            // Make as answer
            $row['answer_date'] = [];
            if (isset($row['element_value'])
                && !empty($row['element_value'])
                && isset($row['element_answer'])
                && !empty($row['element_answer'])
            ) {
                $row['element_value']  = explode('|', $row['element_value']);
                $row['element_answer'] = explode('|', $row['element_answer']);

                if ($row['element_type'] != 'checkbox') {
                    foreach ($row['element_value'] as $key => $value) {
                        if ($value == $row['value'] && isset($row['element_answer'][$key]) && !empty($row['element_answer'][$key])) {
                            $row['answer_date'][$key] = $row['element_answer'][$key];
                        }
                    }
                }
            }

            // Set for checkbox
            if ($row['element_type'] == 'checkbox') {
                $row['value'] = implode(' , ', json_decode($row['value']));
            }

            // Add to list
            $list[$row['id']] = $row;
        }

        return $list;
    }

    public function getRecordList($uid)
    {
        // Set recode list
        $records = [];

        // Set tables
        $recordTable = Pi::model('record', 'forms')->getTable();
        $formTable   = Pi::model('form', 'forms')->getTable();

        // Select
        $select = Pi::db()->select();
        $select->from(['record' => $recordTable]);
        $select->join(
            ['form' => $formTable],
            'form.id = record.form',
            ['title']
        );
        $select->where(['record.uid' => $uid]);
        $select->order(['record.time_create DESC']);
        $rowset = Pi::db()->query($select);

        // Make list
        foreach ($rowset as $row) {
            $records[$row['id']] = $this->canonizeRecord($row, [], [], false, false);
        }

        return $records;
    }

    public function canonizeRecord($record, $form = [], $user = [], $setForm = true, $setUser = true)
    {
        // Check
        if (empty($record)) {
            return '';
        }

        // Check form
        if (empty($form) && $setForm) {
            $form = Pi::api('form', 'forms')->getForm($record['form']);
        }

        // Check user
        if (empty($user) && $record['uid'] > 0 && $setUser) {
            $user = $this->getUser($record['uid']);
        }

        // object to array
        if (is_object($record)) {
            $record = $record->toArray();
        }

        // Set time view
        $record['time_create_view'] = _date($record['time_create'], ['pattern' => 'yyyy/MM/dd']);

        // Set review_status_view
        switch ($record['review_status']) {
            case 0:
                $record['review_status_view'] = __('Pending');
                break;

            case 1:
                $record['review_status_view'] = __('Accepted');
                break;

            case 2:
                $record['review_status_view'] = __('Rejected');
                break;
        }

        // Set user
        if ($setUser) {
            $record['user'] = $user;
        }

        // Set form
        if ($setForm) {
            $record['form'] = $form;
        }

        $record['urlView'] = Pi::url(
            Pi::service('url')->assemble(
                'default',
                [
                    'module'     => $this->getModule(),
                    'controller' => 'archive',
                    'action'     => 'view',
                    'id'         => $record['id'],
                ]
            )
        );

        return $record;
    }
}
