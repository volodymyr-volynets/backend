<?php

namespace Numbers\Backend\System\Modules\Model\Resource;
class APIMethodsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\Resource\APIMethods::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_rsrcapimeth_resource_id','sm_rsrcapimeth_method_code'];
    /**
     * Resource #
     *
     *
     *
     * {domain{resource_id}}
     *
     * @var int|null Domain: resource_id Type: integer
     */
    public int|null $sm_rsrcapimeth_resource_id = 0 {
                        get => $this->sm_rsrcapimeth_resource_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrcapimeth_resource_id', $value);
                            $this->sm_rsrcapimeth_resource_id = $value;
                        }
                    }

    /**
     * Timestamp
     *
     *
     *
     * {domain{timestamp_now}}
     *
     * @var string|null Domain: timestamp_now Type: timestamp
     */
    public string|null $sm_rsrcapimeth_timestamp = 'now()' {
                        get => $this->sm_rsrcapimeth_timestamp;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrcapimeth_timestamp', $value);
                            $this->sm_rsrcapimeth_timestamp = $value;
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
    public string|null $sm_rsrcapimeth_method_code = null {
                        get => $this->sm_rsrcapimeth_method_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrcapimeth_method_code', $value);
                            $this->sm_rsrcapimeth_method_code = $value;
                        }
                    }

    /**
     * Method Name
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_rsrcapimeth_method_name = null {
                        get => $this->sm_rsrcapimeth_method_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrcapimeth_method_name', $value);
                            $this->sm_rsrcapimeth_method_name = $value;
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
    public int|null $sm_rsrcapimeth_inactive = 0 {
                        get => $this->sm_rsrcapimeth_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_rsrcapimeth_inactive', $value);
                            $this->sm_rsrcapimeth_inactive = $value;
                        }
                    }
}
