<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Policies\Abstract2;

use NF\Error;
use Object\Enum\PolicyReturnTypes;
use Object\Enum\PolicyProcessingTypes;

abstract class PolicyTypesAbstract
{
    /**
     * @var array
     */
    public $rules_columns = [];

    /**
     * @var array
     */
    public $common_columns = [
        'Action' => ['name' => 'Action', 'domain' => 'pccode', 'required' => true, 'in' => ['Allow', 'Deny']],
        'Effective' => ['name' => 'Effective', 'type' => 'date', 'required' => true],
    ];

    /**
     * Preset
     */
    abstract public function preset(): string;

    /**
     * Validate
     *
     * @param mixed $values
     * @return array
     */
    public function validate(mixed $values): array
    {
        $internal_values = [];
        if (empty($values) || !is_array($values)) {
            return ['success' => false, 'error' => [loc(Error::EMPTY_VALUES)]];
        }
        $validator = \Validator::validateInputStatic($values, $this->common_columns);
        if ($validator->hasErrors()) {
            return $validator->errors('result');
        }
        $internal_values = $validator->values();
        // rules
        if (empty($values['Rules'])) {
            return ['success' => false, 'error' => [loc(Error::EMPTY_WHAT_VALUES, '', ['what' => 'Rules'])]];
        }
        $rules_result = $this->computeRules($values['Rules'], $this->rules_columns);
        if (!$rules_result['success']) {
            return $rules_result;
        }
        $internal_values['RulesPHP'] = $rules_result['data'];
        return [
            'success' => true,
            'error' => [],
            'data' => $internal_values,
        ];
    }

    /**
     * Compute rules
     *
     * @param array $rules
     * @param array $columns
     * @throws \Exception
     * @return array
     */
    protected function computeRules(array $rules, array $columns): array
    {
        $result = [];
        foreach ($rules as $k => $v) {
            $result[] = $v['Operator'] ?? 'AND';
            unset($v['Operator']);
            // we must have 1 key left in the array
            if (count($v) != 1) {
                return ['success' => false, 'error' => [loc(Error::INVALID_WHAT_VALUES, '', ['what' => 'Rules Multiple Keys'])]];
            }
            $key = array_key_first($v);
            $value = $v[$key];
            $temp = explode(';', $key);
            $key = $temp[0];
            $field_operator = $temp[1] ?? '=';
            $validator = \Validator::validateInputStatic([
                $key => $value,
            ], $columns);
            if ($validator->hasErrors()) {
                return $validator->errors('result');
            }
            $temp = $validator->values();
            if ($field_operator == '=') {
                $result[] = "array_key_compare(\$input['$key'], " . var_export_condensed($value) . ")";
            } else {
                throw new \Exception('Operator?');
            }
        }
        array_shift($result);
        $if = implode(' ', $result);
        $if_extended = <<<TTT
if ($if) {
    \$policy_comparion_result = true;
}
TTT;
        return ['success' => true, 'error' => [], 'data' => $if_extended];
    }

    /**
     * Compute all policies
     *
     * @param PolicyProcessingTypes $strategy
     * @param array $input
     * @param array $policies
     * @throws \Exception
     * @return string
     */
    public function computeAllPolicies(PolicyProcessingTypes $strategy, array & $input, array & $policies): PolicyReturnTypes
    {
        $result = [];
        $options['effective'] = \Format::now('date');
        foreach ($policies as $k => $v) {
            if (empty($v) || empty($v['internal_json']['RulesPHP'])) {
                continue;
            }
            $result[$k] = $this->computeSinglePolicy($input, $v, $options);
        }
        switch ($strategy) {
            case PolicyProcessingTypes::AnyAllow:
                if (array_search(PolicyReturnTypes::Deny, $result) !== false) {
                    return PolicyReturnTypes::Deny;
                } else if (array_search(PolicyReturnTypes::Allow, $result) !== false) {
                    return PolicyReturnTypes::Allow;
                } else {
                    return PolicyReturnTypes::NotMached;
                }
            default:
                throw new \Exception('Strategy type?');
        }
        return PolicyReturnTypes::NotMached;
    }

    /**
     * Compute single policy
     *
     * @param array $policy_input
     * @param array $policy
     * @param array $options
     * @return PolicyReturnTypes
     */
    public function computeSinglePolicy(array & $input, array & $policy, array $options = []): PolicyReturnTypes
    {
        $policy_comparion_result = false;
        eval($policy['internal_json']['RulesPHP']);
        if ($policy_comparion_result) {
            // compare effective date
            if (isset($policy['internal_json']['Effective'])) {
                if (\Helper\Date::is($policy['internal_json']['Effective'], $options['effective']) > 0) {
                    return PolicyReturnTypes::NotMached;
                }
            }
            return PolicyReturnTypes::from($policy['internal_json']['Action']);
        } else {
            return PolicyReturnTypes::NotMached;
        }
    }
}
