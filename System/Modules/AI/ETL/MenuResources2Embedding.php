<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\AI\ETL;

class MenuResources2Embedding
{
    /**
     * Transform
     *
     * @param array $row
     * @param array $options
     * @return array{content: string, error: array, success: bool}
     */
    public static function transform(array $row, array $options = []): array
    {
        $content = [];
        $content[] = 'Type: Menu';
        $content[] = 'Name: ' . $row['sm_resource_name'];
        $content[] = 'ID: ' . $row['sm_resource_id'];
        $content[] = 'Module: ' . $row['sm_resource_module_code'] . ', ' . $row['sm_resource_module_code'][0] . '/' . $row['sm_resource_module_code'][1];
        $groups = [];
        for ($i = 1; $i < 10; $i++) {
            if (isset($row['sm_resource_group' . $i . '_name'])) {
                if ($row['sm_resource_group' . $i . '_name'] == 'Operations') {
                    continue;
                }
                $groups[] = $row['sm_resource_group' . $i . '_name'];
            }
        }
        $content[] = 'Groups: ' . implode(', ', $groups);
        $content[] = 'URL: ' . $row['sm_resource_menu_url'];
        $acl = [];
        if (!empty($row['sm_resource_acl_public'])) {
            $acl[] = 'Public';
        }
        if (!empty($row['sm_resource_acl_authorized'])) {
            $acl[] = 'Authorized';
        }
        if (!empty($row['sm_resource_acl_permission'])) {
            $acl[] = 'Permission';
        }
        $content[] = 'ACL: ' . implode(', ', $acl);
        $content = implode("\n", $content);
        return [
            'success' => true,
            'error' => [],
            'content' => $content,
        ];
    }
}
