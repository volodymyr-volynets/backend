<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Resource;

use Object\ActiveRecord;

class ActionsAR extends ActiveRecord
{
    /**
     * @var string
     */
    public string $object_table_class = Actions::class;

    /**
     * @var array
     */
    public array $object_table_pk = ['sm_action_id'];
    /**
     * Action #
     *
     *
     *
     * {domain{action_id}}
     *
     * @var int|null Domain: action_id Type: smallint
     */
    public int|null $sm_action_id = 0 {
        get => $this->sm_action_id;
        set {
            $this->setFullPkAndFilledColumn('sm_action_id', $value);
            $this->sm_action_id = $value;
        }
    }

    /**
     * Code
     *
     *
     *
     * {domain{code}}
     *
     * @var string|null Domain: code Type: varchar
     */
    public string|null $sm_action_code = null {
        get => $this->sm_action_code;
        set {
            $this->setFullPkAndFilledColumn('sm_action_code', $value);
            $this->sm_action_code = $value;
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
    public string|null $sm_action_name = null {
        get => $this->sm_action_name;
        set {
            $this->setFullPkAndFilledColumn('sm_action_name', $value);
            $this->sm_action_name = $value;
        }
    }

    /**
     * Icon
     *
     *
     *
     * {domain{icon}}
     *
     * @var string|null Domain: icon Type: varchar
     */
    public string|null $sm_action_icon = null {
        get => $this->sm_action_icon;
        set {
            $this->setFullPkAndFilledColumn('sm_action_icon', $value);
            $this->sm_action_icon = $value;
        }
    }

    /**
     * Parent #
     *
     *
     *
     * {domain{action_id}}
     *
     * @var int|null Domain: action_id Type: smallint
     */
    public int|null $sm_action_parent_action_id = 0 {
        get => $this->sm_action_parent_action_id;
        set {
            $this->setFullPkAndFilledColumn('sm_action_parent_action_id', $value);
            $this->sm_action_parent_action_id = $value;
        }
    }

    /**
     * Prohibitive
     *
     *
     *
     *
     *
     * @var int|null Type: boolean
     */
    public int|null $sm_action_prohibitive = 0 {
        get => $this->sm_action_prohibitive;
        set {
            $this->setFullPkAndFilledColumn('sm_action_prohibitive', $value);
            $this->sm_action_prohibitive = $value;
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
    public int|null $sm_action_inactive = 0 {
        get => $this->sm_action_inactive;
        set {
            $this->setFullPkAndFilledColumn('sm_action_inactive', $value);
            $this->sm_action_inactive = $value;
        }
    }
}
