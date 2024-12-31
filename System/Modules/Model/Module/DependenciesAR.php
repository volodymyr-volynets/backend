<?php

namespace Numbers\Backend\System\Modules\Model\Module;
class DependenciesAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\Module\Dependencies::class;

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
