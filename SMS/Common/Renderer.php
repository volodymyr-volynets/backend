<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\SMS\Common;

use Object\Form\Base;

class Renderer
{
    /**
     * @var Base
     */
    private ?Base $object;

    /**
     * Render
     *
     * @param Base $object
     * @return string
     */
    public function render(Base & $object): string
    {
        $this->object = $object;
        $result = [];
        // order containers based on order column
        array_key_sort($this->object->data, ['order' => SORT_ASC], ['order' => SORT_NUMERIC]);
        foreach ($this->object->data as $k => $v) {
            $result[] = $this->renderContainer($k);
        }
        return implode("\n", $result);
    }

    private function renderContainer($container_link): string
    {
        if (!empty($this->object->data[$container_link]['options']['custom_renderer'])) {
            $method = \Factory::method($this->object->data[$container_link]['options']['custom_renderer'], $this->object->form_parent);
            // important to use $this if its the same class
            if ($method[0] == $this->object->form_class) {
                $method[0] = & $this->object->form_parent;
            } elseif (!is_object($method[0])) {
                $method[0] = \Factory::model($method[0], true);
            }
            $this->object->misc_settings['currently_rendering'] = $container_link;
            return call_user_func_array($method, [& $this->object]);
        } else {
            throw new \Exception('Not implemented!');
        }
    }
}
