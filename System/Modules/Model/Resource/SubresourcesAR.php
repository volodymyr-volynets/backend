<?php

namespace Numbers\Backend\System\Modules\Model\Resource;
class SubresourcesAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\Resource\Subresources::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_rsrsubres_id'];
    /**
     * Subresource #
     *
     *
     *
     * {domain{resource_id_sequence}}
     *
     * @var int|null Domain: resource_id_sequence Type: serial
     */
    public int|null $sm_rsrsubres_id = null {
                        get => $this->sm_rsrsubres_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubres_id', $value);
                            $this->sm_rsrsubres_id = $value;
                        }
                    }

    /**
     * Resource #
     *
     *
     *
     * {domain{resource_id}}
     *
     * @var int|null Domain: resource_id Type: integer
     */
    public int|null $sm_rsrsubres_resource_id = 0 {
                        get => $this->sm_rsrsubres_resource_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubres_resource_id', $value);
                            $this->sm_rsrsubres_resource_id = $value;
                        }
                    }

    /**
     * Parent Subresource #
     *
     *
     *
     * {domain{resource_id}}
     *
     * @var int|null Domain: resource_id Type: integer
     */
    public int|null $sm_rsrsubres_parent_rsrsubres_id = 0 {
                        get => $this->sm_rsrsubres_parent_rsrsubres_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubres_parent_rsrsubres_id', $value);
                            $this->sm_rsrsubres_parent_rsrsubres_id = $value;
                        }
                    }

    /**
     * Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_rsrsubres_code = null {
                        get => $this->sm_rsrsubres_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubres_code', $value);
                            $this->sm_rsrsubres_code = $value;
                        }
                    }

    /**
     * Name
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_rsrsubres_name = null {
                        get => $this->sm_rsrsubres_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubres_name', $value);
                            $this->sm_rsrsubres_name = $value;
                        }
                    }

    /**
     * Icon
     *
     *
     *
     * {domain{icon}}
     *
     * @var string|null Domain: icon Type: varchar
     */
    public string|null $sm_rsrsubres_icon = null {
                        get => $this->sm_rsrsubres_icon;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubres_icon', $value);
                            $this->sm_rsrsubres_icon = $value;
                        }
                    }

    /**
     * Module Code
     *
     *
     *
     * {domain{module_code}}
     *
     * @var string|null Domain: module_code Type: char
     */
    public string|null $sm_rsrsubres_module_code = null {
                        get => $this->sm_rsrsubres_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubres_module_code', $value);
                            $this->sm_rsrsubres_module_code = $value;
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
    public int|null $sm_rsrsubres_disabled = 0 {
                        get => $this->sm_rsrsubres_disabled;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubres_disabled', $value);
                            $this->sm_rsrsubres_disabled = $value;
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
    public int|null $sm_rsrsubres_inactive = 0 {
                        get => $this->sm_rsrsubres_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrsubres_inactive', $value);
                            $this->sm_rsrsubres_inactive = $value;
                        }
                    }
}
