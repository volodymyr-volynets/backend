<?php

namespace Numbers\Backend\System\Modules\Model\System\Flag;
class FeaturesAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\System\Flag\Features::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_sysflgftr_sysflag_id','sm_sysflgftr_feature_code'];
    /**
     * Subresource #
     *
     *
     *
     * {domain{resource_id}}
     *
     * @var int|null Domain: resource_id Type: integer
     */
    public int|null $sm_sysflgftr_sysflag_id = 0 {
                        get => $this->sm_sysflgftr_sysflag_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflgftr_sysflag_id', $value);
                            $this->sm_sysflgftr_sysflag_id = $value;
                        }
                    }

    /**
     * Feature Code
     *
     *
     *
     * {domain{feature_code}}
     *
     * @var string|null Domain: feature_code Type: varchar
     */
    public string|null $sm_sysflgftr_feature_code = null {
                        get => $this->sm_sysflgftr_feature_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflgftr_feature_code', $value);
                            $this->sm_sysflgftr_feature_code = $value;
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
    public int|null $sm_sysflgftr_inactive = 0 {
                        get => $this->sm_sysflgftr_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflgftr_inactive', $value);
                            $this->sm_sysflgftr_inactive = $value;
                        }
                    }
}
