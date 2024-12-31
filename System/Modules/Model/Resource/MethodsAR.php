<?php

namespace Numbers\Backend\System\Modules\Model\Resource;
class MethodsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Modules\Model\Resource\Methods::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_method_code'];
    /**
     * Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_method_code = null {
                        get => $this->sm_method_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_method_code', $value);
                            $this->sm_method_code = $value;
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
    public string|null $sm_method_name = null {
                        get => $this->sm_method_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_method_name', $value);
                            $this->sm_method_name = $value;
                        }
                    }
}
