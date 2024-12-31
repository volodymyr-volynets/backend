<?php

namespace Numbers\Backend\IP\Simple\Model;
class IPv4AR extends \Object\ActiveRecord {



    /**
     * @var string
     */
    public string $object_table_class = \Numbers\Backend\IP\Simple\Model\IPv4::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_ipver4_start','sm_ipver4_end'];
    /**
     * Start
     *
     *
     *
     *
     *
     * @var int|null Type: bigint
     */
    public int|null $sm_ipver4_start = 0 {
                        get => $this->sm_ipver4_start;
                        set {
                            $this->setFullPkAndFilledColumn('sm_ipver4_start', $value);
                            $this->sm_ipver4_start = $value;
                        }
                    }

    /**
     * End
     *
     *
     *
     *
     *
     * @var int|null Type: bigint
     */
    public int|null $sm_ipver4_end = 0 {
                        get => $this->sm_ipver4_end;
                        set {
                            $this->setFullPkAndFilledColumn('sm_ipver4_end', $value);
                            $this->sm_ipver4_end = $value;
                        }
                    }

    /**
     * Country Code
     *
     *
     *
     * {domain{country_code}}
     *
     * @var string|null Domain: country_code Type: char
     */
    public string|null $sm_ipver4_country_code = null {
                        get => $this->sm_ipver4_country_code;
                        set {
                            $this->setFullPkAndFilledColumn('sm_ipver4_country_code', $value);
                            $this->sm_ipver4_country_code = $value;
                        }
                    }

    /**
     * Province
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_ipver4_province = null {
                        get => $this->sm_ipver4_province;
                        set {
                            $this->setFullPkAndFilledColumn('sm_ipver4_province', $value);
                            $this->sm_ipver4_province = $value;
                        }
                    }

    /**
     * City
     *
     *
     *
     * {domain{name}}
     *
     * @var string|null Domain: name Type: varchar
     */
    public string|null $sm_ipver4_city = null {
                        get => $this->sm_ipver4_city;
                        set {
                            $this->setFullPkAndFilledColumn('sm_ipver4_city', $value);
                            $this->sm_ipver4_city = $value;
                        }
                    }

    /**
     * Latitude
     *
     *
     *
     * {domain{geo_coordinate}}
     *
     * @var float|null Domain: geo_coordinate Type: numeric
     */
    public float|null $sm_ipver4_latitude = NULL {
                        get => $this->sm_ipver4_latitude;
                        set {
                            $this->setFullPkAndFilledColumn('sm_ipver4_latitude', $value);
                            $this->sm_ipver4_latitude = $value;
                        }
                    }

    /**
     * Longitude
     *
     *
     *
     * {domain{geo_coordinate}}
     *
     * @var float|null Domain: geo_coordinate Type: numeric
     */
    public float|null $sm_ipver4_longitude = NULL {
                        get => $this->sm_ipver4_longitude;
                        set {
                            $this->setFullPkAndFilledColumn('sm_ipver4_longitude', $value);
                            $this->sm_ipver4_longitude = $value;
                        }
                    }
}
