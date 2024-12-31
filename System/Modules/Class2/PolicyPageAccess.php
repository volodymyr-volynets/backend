<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Class2;

use Numbers\Backend\System\Policies\Abstract2\PolicyTypesAbstract;

class PolicyPageAccess extends PolicyTypesAbstract
{
    /**
     * @var array
     */
    public $rules_columns = [
        'Operator' => ['name' => 'Operator', 'domain' => 'type_code', 'sometimes' => true, 'default' => 'AND', 'in' => ['AND', 'OR']],
        'module_codes' => ['name' => 'Module Codes', 'domain' => 'module_code_extended[]', 'sometimes' => true],
    ];

    /**
     * Preset
     *
     * @return string
     */
    public function preset(): string
    {
        $result = <<<EOL
{
	"Action": "Allow",
	"Rules": [
		{"Operator": "OR", "module_codes": ["UM", "SM-UM"]}
	],
	"Effective": "2024-12-01"
}
EOL;
        return trim($result);
    }
}
