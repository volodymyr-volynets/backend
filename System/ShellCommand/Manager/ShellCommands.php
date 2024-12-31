<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

use Helper\Cmd;
use Numbers\Backend\System\ShellCommand\Class2\ShellCommands;

// command line parameters
$command = $argv[1];
$arguments = explode(' ', $argv[2]);
$type = $arguments[0];
$subtype = $arguments[1] ?? '';
unset($arguments[0], $arguments[1]);

if (in_array($type, ['list'])) {
    $skip_confirmation = true;
}

// must change working directory to public_html
chdir('public_html');

// autoloading composer first
if (file_exists('../libraries/vendor/autoload.php')) {
    require('../libraries/vendor/autoload.php');
}

// running application
require('../libraries/vendor/numbers/framework/Application.php');
Application::run(['__run_only_bootstrap' => 2]);

// disable debug
Debug::$debug = false;

// increase in memory and unlimited execution time
ini_set('memory_limit', '2048M');
set_time_limit(0);

// confirmation whether to run the script
if (empty($skip_confirmation) || $skip_confirmation == 2) {
    $type_new = $type;
    if ($type[0] == '\\') {
        $type_new = array_reverse(explode('\\', $type))[0];
    }
    if (!Cmd::confirm("Conitune operation \"$type_new\"?")) {
        exit;
    }
}

// define result variable to keep scripts messages
$result = [
    'success' => false,
    'error' => [],
    'hint' => []
];

// we need to put command into application
Application::set('manager.enabled', true);
Application::set('manager.command.type', $command . '_' . $type);
Application::set('manager.command.mode', 'commit');
Application::set('manager.command.full', $command . '_' . $type);

// wrapping everything into try-catch block for system exceptions
try {
    // process command
    $modules = $commands = [];
    require_if_exists('../application/Miscellaneous/Commands/AllModules.php', true, $modules);
    require_if_exists('../application/Miscellaneous/Commands/AllCommands.php', true, $commands);
    $module_code = strtoupper($subtype);
    switch (strtolower($type)) {
        case 'list':
            $groupped = (new Array2($commands))->group(['module_code', 'command'])->toArray();
            foreach ($groupped as $k => $v) {
                if ($module_code && $module_code != $k) {
                    continue;
                }
                Cmd::writeln(GENERAL, $modules[$k]['name'] . ':');
                foreach ($v as $k2 => $v2) {
                    Cmd::writeln(GENERAL, "\t" . $k2 . ': ' . $v2['name'] . ' ' . $v2['description']);
                }
            }
            break;
        default:
            if (!isset($commands[$type])) {
                throw new Exception('Unknown command!');
            }
            $class = $commands[$type]['model'];
            /** @var ShellCommands $model */
            $model = new $class();
            $result = $model->runCurrentCommand();
    }
    // error label
    error:
        // hint
        if (!empty($result['hint'])) {
            echo "\n" . Cmd::colorString(implode("\n", $result['hint']), null, null, false) . "\n\n";
        }
    // if we did not succeed
    if (!empty($result['error'])) {
        echo "\n" . Cmd::colorString(implode("\n", $result['error']), 'red', null, true) . "\n\n";
        exit;
    }
} catch (Exception $e) {
    echo "\n" . Cmd::colorString($e->getMessage(), 'red', null, true) . "\n\n" . $e->getTraceAsString() . "\n\n";
    exit;
}

// success message
$seconds = Format::timeSeconds(microtime(true) - Application::get('application.system.request_time'));
echo "\nOperation \"$type\" completed in {$seconds} seconds!\n\n";
