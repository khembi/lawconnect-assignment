<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormInput extends Component
{
    /**
     * The input type.
     *
     * @var string
     */
    public $type;

    /**
     * The input name.
     *
     * @var string
     */
    public $name;

    /**
     * The input label.
     *
     * @var string
     */
    public $label;

    /**
     * The input placeholder.
     *
     * @var string|null
     */
    public $placeholder;

    /**
     * Whether the input is required.
     *
     * @var bool
     */
    public $required;

    /**
     * The input value.
     *
     * @var mixed
     */
    public $value;

    /**
     * Create a new component instance.
     *
     * @param string $type
     * @param string $name
     * @param string $label
     * @param string|null $placeholder
     * @param bool $required
     * @param mixed $value
     * @return void
     */
    public function __construct(
        $type,
        $name,
        $label,
        $placeholder = null,
        $required = false,
        $value = null
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-input');
    }
} 