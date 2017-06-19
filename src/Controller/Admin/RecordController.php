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
use Pi\Mvc\Controller\ActionController;

class RecordController extends ActionController
{
    public function indexAction()
    {
        // Get id
        $selectForm = $this->params('selectForm');
        // Get info
        $user = array();
        $form = array();
        $list = array();
        $order = array('time_create DESC', 'id DESC');
        $where = array();
        if ($selectForm) {
            $where['form'] = $selectForm;
        }
        $select = $this->getModel('record')->select()->where($where)->order($order);
        $rowset = $this->getModel('record')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            if (!isset($user[$row->uid])) {
                $user[$row->uid] = Pi::api('record', 'forms')->getUser($row->uid);
            }
            if (!isset($form[$row->form])) {
                $form[$row->form] = Pi::api('form', 'forms')->getForm($row->form);
            }
            $list[$row->id] = Pi::api('record', 'forms')->canonizeRecord($row, $form[$row->form], $user[$row->uid]);
        }

        // Set template
        $this->view()->setTemplate('record-index');
        $this->view()->assign('list', $list);
    }

    public function viewAction()
    {
        $id = $this->params('id');
        $record = Pi::api('record', 'forms')->getRecord($id);

        $record['data'] = Pi::api('record', 'forms')->getRecordData($record['id']);

        // Set template
        $this->view()->setTemplate('record-view');
        $this->view()->assign('record', $record);
    }
}