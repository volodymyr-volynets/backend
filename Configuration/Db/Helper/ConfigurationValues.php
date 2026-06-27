<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Configuration\Db\Helper;

class ConfigurationValues
{
    /**
     * Get
     *
     * @param int $tenant_id
     * @param string $section
     * @param string $key
     * @return mixed
     */
    public static function get(int $tenant_id, string $section, string $key): mixed
    {
        $result = \Numbers\Backend\Configuration\Db\Model\ConfigurationValues::queryBuilderStatic()
             ->select()
             ->columns(['sm_confdbvalue_value'])
             ->where('AND', ['sm_confdbvalue_tenant_id', '=', $tenant_id])
             ->where('AND', ['sm_confdbvalue_section', '=', $section])
             ->where('AND', ['sm_confdbvalue_key', '=', $key])
             ->limit(1)
             ->query();
        return $result['rows'][0]['sm_confdbvalue_value'] ?? null;
    }

    /**
     * Set
     *
     * @param int $tenant_id
     * @param string $section
     * @param string $key
     * @param mixed $value
     * @param int $inactive
     * @return array
     */
    public static function set(int $tenant_id, string $section, string $key, mixed $value, int $inactive = 0): array
    {
        return \Numbers\Backend\Configuration\Db\Model\ConfigurationValues::collectionStatic()->merge([
            'sm_confdbvalue_tenant_id' => $tenant_id,
            'sm_confdbvalue_section' => $section,
            'sm_confdbvalue_key' => $key,
            'sm_confdbvalue_value' => $value,
            'sm_confdbvalue_inactive' => $inactive,
        ]);
    }
}
