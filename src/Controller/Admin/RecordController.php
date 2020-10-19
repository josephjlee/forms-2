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

use Pi;
use Pi\Mvc\Controller\ActionController;
use Module\Forms\Form\ReviewFilter;
use Module\Forms\Form\ReviewForm;
use Laminas\Db\Sql\Predicate\Expression;

class RecordController extends ActionController
{
    public function indexAction()
    {
        // Get id
        $selectForm = $this->params('selectForm');

        // Get info
        $user  = [];
        $form  = [];
        $list  = [];
        $order = ['time_create DESC', 'id DESC'];
        $where = [];
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
        // Get review
        $id             = $this->params('id');
        $record         = Pi::api('record', 'forms')->getRecord($id);
        $record['data'] = Pi::api('record', 'forms')->getRecordData($record['id']);

        // Set form
        $form = new ReviewForm('element');
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new ReviewFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Save values
                $row = $this->getModel('record')->find($id);
                $row->assign($values);
                $row->save();

                // Jump
                $message = __('Review saved successfully.');
                $this->jump(['action' => 'view', 'id' => $id], $message);
            }
        } else {
            $form->setData($record);
        }

        // Set template
        $this->view()->setTemplate('record-view');
        $this->view()->assign('record', $record);
        $this->view()->assign('form', $form);
    }

    public function exportAction()
    {
        // Get inf0
        $module       = $this->params('module');
        $selectFormId = $this->params('selectForm');
        $file         = $this->params('file');
        $page         = $this->params('page', 1);
        $count        = $this->params('count');
        $complete     = $this->params('complete', 0);
        $confirm      = $this->params('confirm', 0);

        // Get form
        $selectForm = Pi::api('form', 'forms')->getForm($selectFormId);

        // Check form
        if (!$selectForm || $selectForm['status'] != 1) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('The form not found.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Set file
        if (empty($file)) {
            $file = sprintf('form-%s-%s-%s', $selectFormId, date('Y-m-d-H-i-s'), rand(100, 999));
        }

        // Set path
        $path = Pi::path('upload/forms');
        if (!Pi::service('file')->exists($path . '/index.html')) {
            Pi::service('file')->copy(
                Pi::path('upload/index.html'),
                Pi::path('upload/forms/index.html')
            );
        }

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check request
        if ($confirm == 1) {

            // Set file
            Pi::service('audit')->attach(
                'forms-export', [
                    'file'   => Pi::path(sprintf('upload/forms/%s.csv', $file)),
                    'format' => 'csv',
                ]
            );

            // Get info
            $exportData = [];
            $user       = [];
            $order      = ['time_create ASC', 'id ASC'];
            $where      = ['form' => $selectForm];
            $limit      = 15;
            $offset     = (int)($page - 1) * $limit;
            $select     = $this->getModel('record')->select()->where($where)->order($order)->limit($limit)->offset($offset);
            $rowSet     = $this->getModel('record')->selectWith($select);

            // Make export title
            $exportTitle = [
                'form_title'       => __('Form title'),
                'user_id'          => __('User ID'),
                'name'             => __('User name'),
                'identity'         => __('User identity'),
                'email'            => __('User email'),
                'time_create'      => __('Time create'),
                'time_create_view' => __('Time create view'),
            ];

            // Make list
            foreach ($rowSet as $row) {
                // Set user
                if (isset($row->uid) && $row->uid > 0 && !isset($user[$row->uid])) {
                    $user[$row->uid] = Pi::api('record', 'forms')->getUser($row->uid);
                }

                // Set list
                $record = Pi::api('record', 'forms')->canonizeRecord($row, [], $user[$row->uid], false);

                // Set record data
                $recordDate = Pi::api('record', 'forms')->getRecordData($row->id);

                // Make export static list
                $exportData[$row->id] = [
                    'form_title'       => $selectForm['title'],
                    'user_id'          => isset($user[$row->uid]['id']) ? $user[$row->uid]['id'] : '',
                    'name'             => isset($user[$row->uid]['name']) ? $user[$row->uid]['name'] : '',
                    'identity'         => isset($user[$row->uid]['identity']) ? $user[$row->uid]['identity'] : '',
                    'email'            => isset($user[$row->uid]['email']) ? $user[$row->uid]['email'] : '',
                    'mobile'           => isset($user[$row->uid]['mobile']) ? $user[$row->uid]['mobile'] : '',
                    'time_create'      => $record['time_create'],
                    'time_create_view' => $record['time_create_view'],
                ];

                // Make export dynamic list
                foreach ($recordDate as $date) {


                    $exportData[$row->id][sprintf('element_%s', $date['element_id'])] = $date['value'];
                    $exportTitle[sprintf('element_%s', $date['element_id'])]          = $date['element_title'];
                }
            }

            // Make list
            foreach ($exportData as $exportDataSingle) {
                // Set key
                if ($complete == 0) {
                    Pi::service('audit')->log('forms-export', $exportTitle);
                }

                // Set to csv
                Pi::service('audit')->log('forms-export', $exportDataSingle);

                // Set complete
                $complete++;
            }

            // Update page
            $page++;

            // Get count
            if (!$count) {
                $columns = ['count' => new Expression('count(*)')];
                $select  = $this->getModel('record')->select()->columns($columns)->where($where);
                $count   = $this->getModel('record')->selectWith($select)->current()->count;
            }

            // Set complete
            $percent = (100 * $complete) / $count;

            // Set next url
            if ($complete >= $count) {
                $nextUrl       = '';
                $downloadAllow = 1;
            } else {
                $nextUrl       = Pi::url(
                    $this->url(
                        '', [
                            'action'     => 'export',
                            'page'       => $page,
                            'count'      => $count,
                            'complete'   => $complete,
                            'confirm'    => $confirm,
                            'file'       => $file,
                            'selectForm' => $selectFormId,
                        ]
                    )
                );
                $downloadAllow = 0;
            }

            $info = [
                'count'         => $count,
                'complete'      => $complete,
                'percent'       => $percent,
                'nextUrl'       => $nextUrl,
                'downloadAllow' => $downloadAllow,
            ];

            $percent = ($percent > 99 && $percent < 100) ? (intval($percent) + 1) : intval($percent);

            //$fileList = '';
        } else {
            // Set info
            $info          = [];
            $percent       = 0;
            $nextUrl       = '';
            $downloadAllow = 0;
            // Set filter
            $filter = function ($fileinfo) {
                if (!$fileinfo->isFile()) {
                    return false;
                }
                $filename = $fileinfo->getFilename();
                if ('index.html' == $filename) {
                    return false;
                }
                return $filename;
            };
        }

        // Set view
        $this->view()->setTemplate('record-export');
        $this->view()->assign('config', $config);
        $this->view()->assign('nextUrl', $nextUrl);
        $this->view()->assign('downloadAllow', $downloadAllow);
        $this->view()->assign('percent', $percent);
        $this->view()->assign('info', $info);
        $this->view()->assign('confirm', $confirm);
        $this->view()->assign('file', $file);
        $this->view()->assign('selectFormId', $selectFormId);
    }

    public function downloadAction()
    {
        // Get file
        $file = $this->params('file');

        // Set file name
        $csvFile = $file . '.csv';
        $csvPath = Pi::path('upload/forms/') . $csvFile;

        // Set url
        if (Pi::service('file')->exists($csvPath)) {
            $url = sprintf(
                '%s?upload/forms/%s',
                Pi::url('www/script/download.php'),
                $csvFile
            );

            // Set url
            return $this->redirect()->toUrl($url);
        }

        // Set error message
        return ['message' => __('File not exit !')];
    }
}
