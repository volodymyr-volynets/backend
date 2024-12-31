<?php

namespace Numbers\Backend\Db\Common\Model;
class MetadataAR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\Db\Common\Model\Metadata::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_metadata_db_link','sm_metadata_type','sm_metadata_name'];
    /**
     * Db Link
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_metadata_db_link = null {
                        get => $this->sm_metadata_db_link;
                        set {
                            $this->setFullPkAndFilledColumn('sm_metadata_db_link', $value);
                            $this->sm_metadata_db_link = $value;
                        }
                    }

    /**
     * Type
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_metadata_type = null {
                        get => $this->sm_metadata_type;
                        set {
                            $this->setFullPkAndFilledColumn('sm_metadata_type', $value);
                            $this->sm_metadata_type = $value;
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
    public string|null $sm_metadata_name = null {
                        get => $this->sm_metadata_name;
                        set {
                            $this->setFullPkAndFilledColumn('sm_metadata_name', $value);
                            $this->sm_metadata_name = $value;
                        }
                    }

    /**
     * SQL Version
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_metadata_sql_version = null {
                        get => $this->sm_metadata_sql_version;
                        set {
                            $this->setFullPkAndFilledColumn('sm_metadata_sql_version', $value);
                            $this->sm_metadata_sql_version = $value;
                        }
                    }
}
