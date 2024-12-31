<?php

namespace Numbers\Backend\System\Modules\Model\System\Flag;
class MapAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\System\Flag\Map::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_sysflgmap_sysflag_id','sm_sysflgmap_action_id'];
    /**
     * Subresource #
     *
     *
     *
     * {domain{resource_id}}
     *
     * @var int|null Domain: resource_id Type: integer
     */
    public int|null $sm_sysflgmap_sysflag_id = 0 {
                        get => $this->sm_sysflgmap_sysflag_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflgmap_sysflag_id', $value);
                            $this->sm_sysflgmap_sysflag_id = $value;
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
    public int|null $sm_sysflgmap_action_id = 0 {
                        get => $this->sm_sysflgmap_action_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflgmap_action_id', $value);
                            $this->sm_sysflgmap_action_id = $value;
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
    public int|null $sm_sysflgmap_disabled = 0 {
                        get => $this->sm_sysflgmap_disabled;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflgmap_disabled', $value);
                            $this->sm_sysflgmap_disabled = $value;
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
    public int|null $sm_sysflgmap_inactive = 0 {
                        get => $this->sm_sysflgmap_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_sysflgmap_inactive', $value);
                            $this->sm_sysflgmap_inactive = $value;
                        }
                    }
}
