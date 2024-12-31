<?php

namespace Numbers\Backend\System\Modules\Model\Resource\Subresource;
class MapAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\Resource\Subresource\Map::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_rsrsubmap_rsrsubres_id','sm_rsrsubmap_action_id'];
    /**
     * Subresource #
     *
     *
     *
     * {domain{resource_id}}
     *
     * @var int|null Domain: resource_id Type: integer
     */
    public int|null $sm_rsrsubmap_rsrsubres_id = 0 {
                        get => $this->sm_rsrsubmap_rsrsubres_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubmap_rsrsubres_id', $value);
                            $this->sm_rsrsubmap_rsrsubres_id = $value;
                        }
                    }

    /**
     * Action #
     *
     *
     *
     * {domain{action_id}}
     *
     * @var int|null Domain: action_id Type: smallint
     */
    public int|null $sm_rsrsubmap_action_id = 0 {
                        get => $this->sm_rsrsubmap_action_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubmap_action_id', $value);
                            $this->sm_rsrsubmap_action_id = $value;
                        }
                    }

    /**
     * Disabled
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_rsrsubmap_disabled = 0 {
                        get => $this->sm_rsrsubmap_disabled;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubmap_disabled', $value);
                            $this->sm_rsrsubmap_disabled = $value;
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
    public int|null $sm_rsrsubmap_inactive = 0 {
                        get => $this->sm_rsrsubmap_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubmap_inactive', $value);
                            $this->sm_rsrsubmap_inactive = $value;
                        }
                    }
}
