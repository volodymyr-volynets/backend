<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Module;

use Object\ActiveRecord;

class DependenciesAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = Dependencies::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_mdldep_parent_module_code','sm_mdldep_parent_feature_code','sm_mdldep_child_module_code','sm_mdldep_child_feature_code'];
    /**
     * Parent Module Code
     *
     *
     *
     * {domain{module_code}}
     *
     * @var string|null Domain: module_code Type: char
     */
    public string|null $sm_mdldep_parent_module_code = null {
        get => $this->sm_mdldep_parent_module_code;
        set {
            $this->setFullPkAndFilledColumn('sm_mdldep_parent_module_code', $value);
            $this->sm_mdldep_parent_module_code = $value;
        }
    }

    /**
     * Parent Feature Code
     *
     *
     *
     * {domain{feature_code}}
     *
     * @var string|null Domain: feature_code Type: varchar
     */
    public string|null $sm_mdldep_parent_feature_code = null {
        get => $this->sm_mdldep_parent_feature_code;
        set {
            $this->setFullPkAndFilledColumn('sm_mdldep_parent_feature_code', $value);
            $this->sm_mdldep_parent_feature_code = $value;
        }
    }

    /**
     * Child Module Code
     *
     *
     *
     * {domain{module_code}}
     *
     * @var string|null Domain: module_code Type: char
     */
    public string|null $sm_mdldep_child_module_code = null {
        get => $this->sm_mdldep_child_module_code;
        set {
            $this->setFullPkAndFilledColumn('sm_mdldep_child_module_code', $value);
            $this->sm_mdldep_child_module_code = $value;
        }
    }

    /**
     * Child Feature Code
     *
     *
     *
     * {domain{feature_code}}
     *
     * @var string|null Domain: feature_code Type: varchar
     */
    public string|null $sm_mdldep_child_feature_code = null {
        get => $this->sm_mdldep_child_feature_code;
        set {
            $this->setFullPkAndFilledColumn('sm_mdldep_child_feature_code', $value);
            $this->sm_mdldep_child_feature_code = $value;
        }
    }

    /**
     * Inactive
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_mdldep_inactive = 0 {
        get => $this->sm_mdldep_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_mdldep_inactive', $value);
            $this->sm_mdldep_inactive = $value;
        }
    }
}
