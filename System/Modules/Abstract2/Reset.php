<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Abstract2;

use Numbers\Backend\Db\Common\Model\Sequences;
use Object\Table;

abstract class Reset
{
    /**
     * Db link
     *
     * @var string
     */
    protected $db_link;

    /**
     * Module code
     *
     * @var string
     */
    protected $module_code;

    /**
     * Feature code
     *
     * @var string
     */
    protected $feature_code;

    /**
     * Module #
     *
     * @var int
     */
    protected $module_id;

    /**
     * Activation model
     *
     * @var string
     */
    protected $activation_model;

    /**
     * Constructor
     *
     * @param string $db_link
     * @param string $module_code
     * @param int $module_id
     * @param array $options
     */
    public function __construct(string $db_link, int $module_id, array $options = [])
    {
        $this->module_code = $options['module_code'] ?? null;
        $this->feature_code = $options['feature_code'] ?? null;
        $this->activation_model = $options['activation_model'] ?? null;
        $this->module_id = $module_id;
        $this->db_link = $db_link;
    }

    /**
     * Execute (abstract)
     */
    abstract public function execute();

    /**
     * Process
     *
     * @return array
     *		boolean success
     *		array error
     */
    public function process(): array
    {
        $result = [
            'success' => false,
            'error' => []
        ];
        try {
            $db_object = new \Db($this->db_link);
            $db_object->begin();
            $this->execute();
            // reactivate
            if (!empty($this->activation_model)) {
                $class = $this->activation_model;
                $model = new $class();
                if (method_exists($model, 'activate')) {
                    $temp = $model->activate();
                } else {
                    $temp = $model->process();
                }
                if (!$temp['success']) {
                    throw new \Exception('Could not reactivate module/feature!');
                }
            }
            $db_object->commit();
        } catch (\Exception $e) {
            $result['error'][] = $e->getMessage();
            $db_object->rollback();
            return $result;
        }
        $result['success'] = true;
        return $result;
    }

    /**
     * Clear table
     *
     * @param Table $model
     * @param array $where
     * @param array $options
     *	boolean truncate
     */
    protected function clearTable(Table $model, array $where = [], array $options = [])
    {
        if (!empty($model->module_column)) {
            $where[$model->module_column] = $this->module_id;
        }
        // we need to cleanup widgets
        if (!empty($model->all_widgets)) {
            foreach ($model->all_widgets as $k => $v) {
                $widget_model = \Factory::model($v);
                $this->clearTable($widget_model);
            }
        }
        // clear table sequences
        foreach ($model->columns as $k => $v) {
            if (!empty($v['sequence'])) {
                $this->clearSequence($model->full_table_name . '_' . $k . '_seq', $v['sequence_type'], 0);
            }
        }
        // delete from table
        if (empty($options['truncate'])) {
            $query = $model->queryBuilder()->delete();
            if (!empty($where)) {
                $query->whereMultiple('AND', $where);
            }
        } else {
            $query = $model->queryBuilder()->truncate()->cascade();
        }
        $result = $query->query();
        if (!$result['success']) {
            throw new \Exception(implode(', ', $result['error']));
        }
    }

    /**
     * Clear sequence
     *
     * @param string $name
     * @param string $type
     * @param int $value
     * @throws \Exception
     */
    protected function clearSequence(string $name, string $type, int $value = 0)
    {
        $model = new Sequences();
        switch ($type) {
            case 'tenant_simple':
            case 'tenant_advanced':
                $model->db_object->setval($name, $value, \Tenant::id(), null);
                break;
            case 'module_simple':
            case 'module_advanced':
                $model->db_object->setval($name, $value, \Tenant::id(), $this->module_id);
                break;
            case 'global_simple': // for these we need to execute setval command
            case 'global_advanced':
                $model->db_object->setval($name, $value, null, null);
                break;
            default:
                throw new \Exception('Unknown sequence type!');
        }
    }
}
