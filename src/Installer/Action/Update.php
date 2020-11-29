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

namespace Module\Forms\Installer\Action;

use Pi;
use Pi\Application\Installer\Action\Update as BasicUpdate;
use Pi\Application\Installer\SqlSchema;
use Laminas\EventManager\Event;

class Update extends BasicUpdate
{
    /**
     * {@inheritDoc}
     */
    protected function attachDefaultListeners()
    {
        $events = $this->events;
        $events->attach('update.pre', [$this, 'updateSchema']);
        parent::attachDefaultListeners();

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function updateSchema(Event $e)
    {
        $moduleVersion = $e->getParam('version');

        // Set form model
        $formModel   = Pi::model('form', $this->module);
        $formTable   = $formModel->getTable();
        $formAdapter = $formModel->getAdapter();

        // Set element model
        $elementModel   = Pi::model('element', $this->module);
        $elementTable   = $elementModel->getTable();
        $elementAdapter = $elementModel->getAdapter();

        // Set record model
        $recordModel   = Pi::model('record', $this->module);
        $recordTable   = $recordModel->getTable();
        $recordAdapter = $recordModel->getAdapter();

        // Update to version 0.0.7
        if (version_compare($moduleVersion, '0.0.7', '<')) {
            // Alter table add field `is_name`
            $sql = sprintf("ALTER TABLE %s ADD `is_name` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'", $elementTable);
            try {
                $elementAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'Table alter query failed: '
                            . $exception->getMessage(),
                    ]
                );
                return false;
            }

            // Alter table add field `is_email`
            $sql = sprintf("ALTER TABLE %s ADD `is_email` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'", $elementTable);
            try {
                $elementAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'Table alter query failed: '
                            . $exception->getMessage(),
                    ]
                );
                return false;
            }

            // Alter table add field `is_mobile`
            $sql = sprintf("ALTER TABLE %s ADD `is_mobile` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'", $elementTable);
            try {
                $elementAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'Table alter query failed: '
                            . $exception->getMessage(),
                    ]
                );
                return false;
            }
        }

        // Update to version 0.1.1
        if (version_compare($moduleVersion, '0.1.1', '<')) {
            // Alter table add field `review_need`
            $sql = sprintf("ALTER TABLE %s ADD `review_need` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'", $formTable);
            try {
                $formAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'Table alter query failed: '
                            . $exception->getMessage(),
                    ]
                );
                return false;
            }

            // Alter table add field `review_action`
            $sql = sprintf("ALTER TABLE %s ADD `review_action` VARCHAR(32) NOT NULL DEFAULT ''", $formTable);
            try {
                $formAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'Table alter query failed: '
                            . $exception->getMessage(),
                    ]
                );
                return false;
            }

            // Alter table add field `review_status`
            $sql = sprintf("ALTER TABLE %s ADD `review_status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'", $recordTable);
            try {
                $recordAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'Table alter query failed: '
                            . $exception->getMessage(),
                    ]
                );
                return false;
            }

            // Alter table add field `review_result`
            $sql = sprintf("ALTER TABLE %s ADD `review_result` TEXT", $recordTable);
            try {
                $recordAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'Table alter query failed: '
                            . $exception->getMessage(),
                    ]
                );
                return false;
            }
        }

        // Update to version 0.2.2
        if (version_compare($moduleVersion, '0.2.2', '<')) {
            // Alter table add field `review_action`
            $sql = sprintf("ALTER TABLE %s ADD `show_answer` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'", $formTable);
            try {
                $formAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'Table alter query failed: '
                            . $exception->getMessage(),
                    ]
                );
                return false;
            }

            // Alter table add field `review_status`
            $sql = sprintf("ALTER TABLE %s ADD `answer` MEDIUMTEXT", $elementTable);
            try {
                $elementAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'Table alter query failed: '
                            . $exception->getMessage(),
                    ]
                );
                return false;
            }
        }

        // Update to version 0.2.3
        if (version_compare($moduleVersion, '0.2.3', '<')) {
            // Alter table add field `review_action`
            $sql = sprintf("ALTER TABLE %s ADD `multi_steps` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'", $formTable);
            try {
                $formAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'Table alter query failed: '
                            . $exception->getMessage(),
                    ]
                );
                return false;
            }
        }

        return true;
    }
}
