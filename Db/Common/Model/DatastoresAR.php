<?php

namespace Numbers\Backend\Db\Common\Model;
class DatastoresAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\Db\Common\Model\Datastores::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_datastore_tenant_id','sm_datastore_code'];
    /**
     * Tenant #
     *
     *
     *
     * {domain{tenant_id}}
     *
     * @var int|null Domain: tenant_id Type: integer
     */
    public int|null $sm_datastore_tenant_id = NULL {
                        get => $this->sm_datastore_tenant_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_datastore_tenant_id', $value);
                            $this->sm_datastore_tenant_id = $value;
                        }
                    }

    /**
     * Code
     *
     *
     *
     * {domain{lcode}}
     *
     * @var string|null Domain: lcode Type: varchar
     */
    public string|null $sm_datastore_code = null {
                        get => $this->sm_datastore_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_datastore_code', $value);
                            $this->sm_datastore_code = $value;
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
    public string|null $sm_datastore_name = null {
                        get => $this->sm_datastore_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_datastore_name', $value);
                            $this->sm_datastore_name = $value;
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
    public string|null $sm_datastore_module_code = null {
                        get => $this->sm_datastore_module_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_datastore_module_code', $value);
                            $this->sm_datastore_module_code = $value;
                        }
                    }

    /**
     * Table Prefix
     *
     *
     *
     * {domain{lcode}}
     *
     * @var string|null Domain: lcode Type: varchar
     */
    public string|null $sm_datastore_table_prefix = null {
                        get => $this->sm_datastore_table_prefix;
                        set {
                            $this->setFullPkAndFilledColumn('sm_datastore_table_prefix', $value);
                            $this->sm_datastore_table_prefix = $value;
                        }
                    }

    /**
     * Table Suffix
     *
     *
     *
     * {domain{lcode}}
     *
     * @var string|null Domain: lcode Type: varchar
     */
    public string|null $sm_datastore_table_suffix = null {
                        get => $this->sm_datastore_table_suffix;
                        set {
                            $this->setFullPkAndFilledColumn('sm_datastore_table_suffix', $value);
                            $this->sm_datastore_table_suffix = $value;
                        }
                    }

    /**
     * Column Prefix
     *
     *
     *
     * {domain{lcode}}
     *
     * @var string|null Domain: lcode Type: varchar
     */
    public string|null $sm_datastore_column_prefix = null {
                        get => $this->sm_datastore_column_prefix;
                        set {
                            $this->setFullPkAndFilledColumn('sm_datastore_column_prefix', $value);
                            $this->sm_datastore_column_prefix = $value;
                        }
                    }

    /**
     * Table Name
     *
     *
     *
     * {domain{lcode}}
     *
     * @var string|null Domain: lcode Type: varchar
     */
    public string|null $sm_datastore_table_name = null {
                        get => $this->sm_datastore_table_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_datastore_table_name', $value);
                            $this->sm_datastore_table_name = $value;
                        }
                    }

    /**
     * Table Tenant
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_datastore_table_tenant = 0 {
                        get => $this->sm_datastore_table_tenant;
                        set {
                            $this->setFullPkAndFilledColumn('sm_datastore_table_tenant', $value);
                            $this->sm_datastore_table_tenant = $value;
                        }
                    }

    /**
     * Table Module
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_datastore_table_module = 0 {
                        get => $this->sm_datastore_table_module;
                        set {
                            $this->setFullPkAndFilledColumn('sm_datastore_table_module', $value);
                            $this->sm_datastore_table_module = $value;
                        }
                    }

    /**
     * Readonly
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_datastore_table_readonly = 0 {
                        get => $this->sm_datastore_table_readonly;
                        set {
                            $this->setFullPkAndFilledColumn('sm_datastore_table_readonly', $value);
                            $this->sm_datastore_table_readonly = $value;
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
    public int|null $sm_datastore_inactive = 0 {
                        get => $this->sm_datastore_inactive;
                        set {
                            $this->setFullPkAndFilledColumn('sm_datastore_inactive', $value);
                            $this->sm_datastore_inactive = $value;
                        }
                    }
}
