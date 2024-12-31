<?php

namespace Numbers\Backend\System\Modules\Model\Resource\Subresource;
class FeaturesAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\Resource\Subresource\Features::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_rsrsubftr_rsrsubres_id','sm_rsrsubftr_feature_code'];
    /**
     * Subresource #
     *
     *
     *
     * {domain{resource_id}}
     *
     * @var int|null Domain: resource_id Type: integer
     */
    public int|null $sm_rsrsubftr_rsrsubres_id = 0 {
                        get => $this->sm_rsrsubftr_rsrsubres_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubftr_rsrsubres_id', $value);
                            $this->sm_rsrsubftr_rsrsubres_id = $value;
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
    public string|null $sm_rsrsubftr_feature_code = null {
                        get => $this->sm_rsrsubftr_feature_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubftr_feature_code', $value);
                            $this->sm_rsrsubftr_feature_code = $value;
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
    public int|null $sm_rsrsubftr_inactive = 0 {
                        get => $this->sm_rsrsubftr_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubftr_inactive', $value);
                            $this->sm_rsrsubftr_inactive = $value;
                        }
                    }
}
