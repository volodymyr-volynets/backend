<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

use Numbers\Backend\Db\Common\Migration\Base;

class Numbers_Backend_Db_Common_Migration_Template extends Base
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
     * Migrate up
     *
     * Throw exceptions if something fails!!!
     */
    public function up()
    {
        /*[[migrate_up]]*/
    }

    /**
     * Migrate down
     *
     * Throw exceptions if something fails!!!
     */
    public function down()
    {
        /*[[migrate_down]]*/
    }
}
