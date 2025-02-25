<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\ShellCommand\Class2;

use Helper\Cmd;
use Numbers\Backend\System\ShellCommand\Model\ShellCommand\RunsAR;
use Numbers\Backend\System\ShellCommand\Class2\ShellCommand\Statuses;
use Object\Error\ResultException;
use Object\Data\Common;

class ShellCommands
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $decription;

    /**
     * @var string
     */
    public $command;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var array
     */
    public $columns = [];

    /**
     * @var array
     */
    protected $values = [];

    /**
     * @var RunsAR|null
     */
    protected ?RunsAR $run_ar = null;

    /**
     * @var int|null
     */
    protected ?int $sm_shellcomrun_id = null;

    /**
     * Constructor
     *
     * @param array $parameters
     * @param array $options
     *      bool skip_input_processing
     */
    public function __construct(array $parameters = [], array $options = [])
    {
        Cmd::$output = '';
        // ask for input if not skipped
        if (empty($options['skip_input_processing'])) {
            if (!empty($this->columns)) {
                $this->columns = Common::processDomainsAndTypes($this->columns);
                $this->values = $this->ask(GENERAL, $this->columns);
            }
        } else {
            $this->values = $parameters;
        }
        // if we have a tenant we need to se it
        $tenant = false;
        foreach ($this->values as $k => $v) {
            if (str_ends_with($k, 'tenant_id')) {
                $tenant = $k;
            }
        }
        if ($tenant !== false) {
            \Tenant::setOverrideTenantId($this->values[$tenant]);
        }
        // register a run
        $this->run_ar = new RunsAR();
        $result = $this->run_ar->fill([
            'sm_shellcomrun_tenant_id' => \Tenant::id(),
            'sm_shellcomrun_status_id' => Statuses::New->value,
            'sm_shellcomrun_start_timestamp' => \Format::now('timestamp'),
            'sm_shellcomrun_finish_timestamp' => null,
            'sm_shellcomrun_percent_complete' => 0,
            'sm_shellcomrun_user_id' => 0,
            'sm_shellcomrun_shellcommand_code' => $this->code,
            'sm_shellcomrun_shellcommand_name' => $this->name,
            'sm_shellcomrun_shell_output' => '',
            'sm_shellcomrun_inactive' => 0,
        ])->merge();
        if (!$result['success']) {
            throw new ResultException($result);
        } else {
            $this->sm_shellcomrun_id = $result['new_serials']['sm_shellcomrun_id'];
        }
    }

    /**
     * Run current command
     *
     * @param array $options
     * @return array
     */
    public function runCurrentCommand(array $options = []): array
    {
        try {
            $execution_result = $this->execute($this->values, $options);
            $status = Statuses::Completed;
            Cmd::writeln(SUCCESS, 'Success!!!');
        } catch (\Throwable $e) {
            $status = Statuses::Errored;
            throw new \Exception($e->getMessage() . ', file:' . $e->getFile() . ', line:' . $e->getLine());
        } finally {
            $this->run_ar = new RunsAR();
            $result = $this->run_ar->fill([
                'sm_shellcomrun_tenant_id' => \Tenant::id(),
                'sm_shellcomrun_id' => $this->sm_shellcomrun_id,
                'sm_shellcomrun_finish_timestamp' => \Format::now('timestamp'),
                'sm_shellcomrun_percent_complete' => 100,
                'sm_shellcomrun_status_id' => $status->value,
                'sm_shellcomrun_shell_output' => Cmd::$output,
            ])->merge();
            Cmd::$output = '';
            if (!$result['success']) {
                throw new ResultException($result);
            }
        }
        return $execution_result;
    }

    /**
     * Execute
     *
     * @param array $parameters
     * @param array $options
     * @return array
     */
    protected function execute(array $parameters, array $options = []): array
    {
        return [
            'success' => true,
            'error' => []
        ];
    }

    /**
     * Ask
     *
     * @param string $type
     * @param string|array $columns
     * @param array $option
     * @return mixed
     */
    public function ask(string $type, string|array $columns, array $option = []): mixed
    {
        $result = [];
        $flag_single_column = false;
        if (is_string($columns)) {
            $name = $columns;
            $columns = ['__single_column' => ['required' => true, 'name' => $name, 'type' => 'text']];
            $flag_single_column = true;
        }
        foreach ($columns as $k => $v) {
            $result[$k] = Cmd::ask($v['name'] . ' (' . ($v['domain'] ?? $v['type']) . ') ?', [
                'type' => $type,
                'mandatory' => $v['required'] ?? false,
            ]);
            // php type should be available
            if ($v['php_type']) {
                settype($result[$k], $v['php_type']);
            }
        }
        if ($flag_single_column) {
            return $result['__single_column'];
        }
        return $result;
    }

    /**
     * Writeln
     *
     * @param string $type - one of defined constants
     * @param mixed $data
     * @param array $options
     * @return void
     */
    public function writeln(string $type = GENERAL, mixed $data = null, array $options = []): void
    {
        Cmd::writeln($type, $data, $options);
    }
}
