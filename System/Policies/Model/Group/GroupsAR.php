<?php

namespace Numbers\Backend\System\Policies\Model\Group;
class GroupsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\System\Policies\Model\Group\Groups::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_polgrogroup_tenant_id','sm_polgrogroup_sm_polgroup_id','sm_polgrogroup_child_sm_polgroup_tenant_id','sm_polgrogroup_child_sm_polgroup_id'];
    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_polgrogroup_tenant_id = NULL {
                        get => $this->sm_polgrogroup_tenant_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgrogroup_tenant_id', $value);
                            $this->sm_polgrogroup_tenant_id = $value;
                        }
                    }

    /**
     * Group #
     *
     *
     *
     * {domain{group_id}}
     *
     * @var int|null Domain: group_id Type: integer
     */
    public int|null $sm_polgrogroup_sm_polgroup_id = NULL {
                        get => $this->sm_polgrogroup_sm_polgroup_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgrogroup_sm_polgroup_id', $value);
                            $this->sm_polgrogroup_sm_polgroup_id = $value;
                        }
                    }

    /**
     * Child Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_polgrogroup_child_sm_polgroup_tenant_id = NULL {
                        get => $this->sm_polgrogroup_child_sm_polgroup_tenant_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgrogroup_child_sm_polgroup_tenant_id', $value);
                            $this->sm_polgrogroup_child_sm_polgroup_tenant_id = $value;
                        }
                    }

    /**
     * Child Group #
     *
     *
     *
     * {domain{group_id}}
     *
     * @var int|null Domain: group_id Type: integer
     */
    public int|null $sm_polgrogroup_child_sm_polgroup_id = NULL {
                        get => $this->sm_polgrogroup_child_sm_polgroup_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgrogroup_child_sm_polgroup_id', $value);
                            $this->sm_polgrogroup_child_sm_polgroup_id = $value;
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
    public int|null $sm_polgrogroup_inactive = 0 {
                        get => $this->sm_polgrogroup_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_polgrogroup_inactive', $value);
                            $this->sm_polgrogroup_inactive = $value;
                        }
                    }
}
