<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormTextarea extends Component
{
    /**
     * The textarea name.
     *
     * @var string
     */
    public $name;

    /**
     * The textarea label.
     *
     * @var string
     */
    public $label;

    /**
     * The textarea placeholder.
     *
     * @var string|null
     */
    public $placeholder;

    /**
     * Whether the textarea is required.
     *
     * @var bool
     */
    public $required;

    /**
     * The textarea value.
     *
     * @var mixed
     */
    public $value;

    /**
     * The number of rows.
     *
     * @var int
     */
    public $rows;

    /**
     * Create a new component instance.
     *
     * @param string $name
     * @param string $label
     * @param string|null $placeholder
     * @param bool $required
     * @param mixed $value
     * @param int $rows
     * @return void
     */
    public function __construct(
        $name,
        $label,
        $placeholder = null,
        $required = false,
        $value = null,
        $rows = 4
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->value = $value;
        $this->rows = $rows;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-textarea');
    }
} 