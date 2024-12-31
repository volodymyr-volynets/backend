<?php

namespace Numbers\Backend\System\Modules\Model\Resource;
class MapAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\Resource\Map::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_rsrcmp_resource_id','sm_rsrcmp_method_code','sm_rsrcmp_action_id'];
    /**
     * Resource #
     *
     *
     *
     * {domain{resource_id}}
     *
     * @var int|null Domain: resource_id Type: integer
     */
    public int|null $sm_rsrcmp_resource_id = 0 {
                        get => $this->sm_rsrcmp_resource_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrcmp_resource_id', $value);
                            $this->sm_rsrcmp_resource_id = $value;
                        }
                    }

    /**
     * Method Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_rsrcmp_method_code = null {
                        get => $this->sm_rsrcmp_method_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrcmp_method_code', $value);
                            $this->sm_rsrcmp_method_code = $value;
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
    public int|null $sm_rsrcmp_action_id = 0 {
                        get => $this->sm_rsrcmp_action_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrcmp_action_id', $value);
                            $this->sm_rsrcmp_action_id = $value;
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
    public int|null $sm_rsrcmp_disabled = 0 {
                        get => $this->sm_rsrcmp_disabled;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrcmp_disabled', $value);
                            $this->sm_rsrcmp_disabled = $value;
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
    public int|null $sm_rsrcmp_inactive = 0 {
                        get => $this->sm_rsrcmp_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrcmp_inactive', $value);
                            $this->sm_rsrcmp_inactive = $value;
                        }
                    }
}
