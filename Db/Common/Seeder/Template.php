<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

use Numbers\Backend\Db\Common\Seeder\Base;

class Numbers_Backend_Db_Common_Seeder_Template extends Base
{
    /**
     * Db link
     *
     * @var string
     */
    public $db_link = '[[db_link]]';

    /**
     * Developer
     *
     * @var string
     */
    public $developer = '[[developer]]';

    /**
     * Seeder name
     *
     * @var string
     */
    public $seeder_name = '[[seeder_name]]';

    /**
     * Seed up
     *
     * Throw exceptions if something fails!!!
     */
    public function up()
    {
        /*[[seed_up]]*/
    }
}
