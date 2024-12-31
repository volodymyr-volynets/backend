<?php

namespace Numbers\Backend\Db\Common\Model;
class MigrationsAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\Db\Common\Model\Migrations::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_migration_id'];
    /**
     * Migration #
     *
     *
     *
     *
     *
     * @var int|null Type: serial
     */
    public int|null $sm_migration_id = null {
                        get => $this->sm_migration_id;
                        set {
                            $this->setFullPkAndFilledColumn('sm_migration_id', $value);
                            $this->sm_migration_id = $value;
                        }
                    }

    /**
     * Db Link
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_migration_db_link = null {
                        get => $this->sm_migration_db_link;
                        set {
                            $this->setFullPkAndFilledColumn('sm_migration_db_link', $value);
                            $this->sm_migration_db_link = $value;
                        }
                    }

    /**
     * Type
     *
     *
     * {options_model{\Numbers\Backend\Db\Common\Model\Migration\Types}}
     * {domain{type_code}}
     *
     * @var string|null Domain: type_code Type: varchar
     */
    public string|null $sm_migration_type = null {
                        get => $this->sm_migration_type;
                        set {
                            $this->setFullPkAndFilledColumn('sm_migration_type', $value);
                            $this->sm_migration_type = $value;
                        }
                    }

    /**
     * Action
     *
     *
     * {options_model{\Numbers\Backend\Db\Common\Model\Migration\Actions}}
     * {domain{type_code}}
     *
     * @var string|null Domain: type_code Type: varchar
     */
    public string|null $sm_migration_action = null {
                        get => $this->sm_migration_action;
                        set {
                            $this->setFullPkAndFilledColumn('sm_migration_action', $value);
                            $this->sm_migration_action = $value;
                        }
                    }

    /**
     * Name
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_migration_name = null {
                        get => $this->sm_migration_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_migration_name', $value);
                            $this->sm_migration_name = $value;
                        }
                    }

    /**
     * Developer
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_migration_developer = null {
                        get => $this->sm_migration_developer;
                        set {
                            $this->setFullPkAndFilledColumn('sm_migration_developer', $value);
                            $this->sm_migration_developer = $value;
                        }
                    }

    /**
     * Inserted
     *
     *
     *
     *
     *
     * @var string|null Type: timestamp
     */
    public string|null $sm_migration_inserted = null {
                        get => $this->sm_migration_inserted;
                        set {
                            $this->setFullPkAndFilledColumn('sm_migration_inserted', $value);
                            $this->sm_migration_inserted = $value;
                        }
                    }

    /**
     * Rolled Back
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_migration_rolled_back = 0 {
                        get => $this->sm_migration_rolled_back;
                        set {
                            $this->setFullPkAndFilledColumn('sm_migration_rolled_back', $value);
                            $this->sm_migration_rolled_back = $value;
                        }
                    }

    /**
     * Legend
     *
     *
     *
     *
     *
     * @var mixed Type: json
     */
    public mixed $sm_migration_legend = null {
                        get => $this->sm_migration_legend;
                        set {
                            $this->setFullPkAndFilledColumn('sm_migration_legend', $value);
                            $this->sm_migration_legend = $value;
                        }
                    }

    /**
     * SQL Counter
     *
     *
     *
     * {domain{counter}}
     *
     * @var int|null Domain: counter Type: integer
     */
    public int|null $sm_migration_sql_counter = 0 {
                        get => $this->sm_migration_sql_counter;
                        set {
                            $this->setFullPkAndFilledColumn('sm_migration_sql_counter', $value);
                            $this->sm_migration_sql_counter = $value;
                        }
                    }

    /**
     * SQL Changes
     *
     *
     *
     *
     *
     * @var mixed Type: json
     */
    public mixed $sm_migration_sql_changes = null {
                        get => $this->sm_migration_sql_changes;
                        set {
                            $this->setFullPkAndFilledColumn('sm_migration_sql_changes', $value);
                            $this->sm_migration_sql_changes = $value;
                        }
                    }
}
