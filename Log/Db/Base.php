<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Db;

use Numbers\Backend\Log\Db\Model\Logs;

class Base extends \Numbers\Backend\Log\Common\Base
{
    /**
     * Save
     *
     * @param array $data
     * @return bool
     */
    public function save(string $log_link, array $data): array
    {
        $result = RESULT_BLANK;
        if (!empty($this->options['db']['enabled']) && count($data) > 0) {
            /** @var Logs $model */
            $model = \Factory::model('\Numbers\Backend\Log\Db\Model\LogsGeneratedYear' . date('Y'), false);
            array_multiple_prefix_and_suffix($data, 'sm_log_', null, false, true);
            // apply filter
            foreach ($data as $k => $v) {
                unset($v['sm_log_options']); // options is an array with additional data that we do not put into db
                $v['sm_log_inserted_timestamp'] = $v['sm_log_inserted_timestamp'] ?? \Format::now('timestamp');
                $v['sm_log_inserted_user_id'] = \User::id();
                $v['sm_log_chanel'] = $log_link;
                $data[$k] = array_merge($v, $model->filter);
                ksort($data[$k]);
            }
            return $model->queryBuilder()
                ->insert()
                ->columns(array_keys(current($data)))
                ->values($data)
                ->query();
        }
        $result['success'] = true;
        return $result;
    }
}
