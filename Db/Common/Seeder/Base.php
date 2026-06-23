<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Seeder;

use Numbers\FakeNames\FakeNames\FakerFactory;

abstract class Base
{
    /**
     * Db link
     *
     * @var string
     */
    public $db_link;

    /**
     * Db Object
     *
     * @var object
     */
    public $db_object;

    /**
     * Developer
     *
     * @var string
     */
    public $developer;

    /**
     * Data
     *
     * @var array
     */
    public $data = [];

    /**
     * Seeder name
     *
     * @var string
     */
    public $seeder_name;

    /**
     * Fake names
     *
     * @var FakerFactory;
     */
    public FakerFactory $names;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        // initialize db object
        if ($this->db_link) {
            $this->db_object = new \Db($this->db_link);
        }
        $this->names = FakerFactory::create();
    }

    /**
     * Seed up
     */
    abstract public function up();
}
