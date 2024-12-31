<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\ShellCommand\Class2\ShellCommand;

use Object\Traits\EnumTrait;
use Object_Enum_LocAttribute;

enum Statuses: int
{
    use EnumTrait;

    #[Object_Enum_LocAttribute('NF.Form.New', 'New')]
    case New = 10;

    #[Object_Enum_LocAttribute('NF.Form.InProgress', 'In Progress')]
    case InProgress = 20;

    #[Object_Enum_LocAttribute('NF.Form.Completed', 'Completed')]
    case Completed = 30;

    #[Object_Enum_LocAttribute('NF.Form.Errored', 'Errored')]
    case Errored = 40;

    #[Object_Enum_LocAttribute('NF.Form.Canceled', 'Canceled')]
    case Canceled = 50;
}
