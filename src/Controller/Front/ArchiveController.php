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

use Pi;
use Pi\Mvc\Controller\ActionController;

class ArchiveController extends ActionController
{
    public function listAction()
    {
        // Check user is login
        Pi::service('authentication')->requireLogin();

        // Get info
        $module = $this->params('module');

        // Get Module Config
        $config = Pi::service('registry')->config->read($module);

        // Get record list
        $uid     = Pi::user()->getId();
        $records = Pi::api('record', 'forms')->getRecordList($uid);

        // Set template
        $this->view()->setTemplate('archive-list');
        $this->view()->assign('config', $config);
        $this->view()->assign('records', $records);
    }

    public function viewAction()
    {
        // Check user is login
        Pi::service('authentication')->requireLogin();

        // Get info
        $module = $this->params('module');
        $id     = $this->params('id');

        // Get Module Config
        $config = Pi::service('registry')->config->read($module);

        // Get data
        $record     = Pi::api('record', 'forms')->getRecord($id);
        $data       = Pi::api('record', 'forms')->getRecordData($record['id']);
        $selectForm = Pi::api('form', 'forms')->getForm($record['form']);

        // Check
        if ($record['uid'] != Pi::user()->getId()) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('This is not your record.'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Set template
        $this->view()->setTemplate('archive-view');
        $this->view()->assign('config', $config);
        $this->view()->assign('record', $record);
        $this->view()->assign('dataList', $data);
        $this->view()->assign('selectForm', $selectForm);
    }
}
