<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormSelect extends Component
{
    /**
     * The select name.
     *
     * @var string
     */
    public $name;

    /**
     * The select label.
     *
     * @var string
     */
    public $label;

    /**
     * Whether the select is multiple.
     *
     * @var bool
     */
    public $multiple;

    /**
     * Whether the select is required.
     *
     * @var bool
     */
    public $required;

    /**
     * The options for the select.
     *
     * @var array
     */
    public $options;

    /**
     * The selected values.
     *
     * @var array
     */
    public $selected;

    /**
     * Create a new component instance.
     *
     * @param string $name
     * @param string $label
     * @param bool $multiple
     * @param bool $required
     * @param array $options
     * @param array $selected
     * @return void
     */
    public function __construct(
        $name,
        $label,
        $multiple = false,
        $required = false,
        $options = [],
        $selected = []
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->multiple = $multiple;
        $this->required = $required;
        $this->options = $options;
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-select');
    }
} 