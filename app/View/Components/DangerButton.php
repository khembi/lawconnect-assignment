<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DangerButton extends Component
{
    /**
     * The button type.
     *
     * @var string
     */
    public $type;

    /**
     * The URL if the button should be a link.
     *
     * @var string|null
     */
    public $href;

    /**
     * Create a new component instance.
     *
     * @param string $type
     * @param string|null $href
     * @return void
     */
    public function __construct($type = 'button', $href = null)
    {
        $this->type = $type;
        $this->href = $href;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.danger-button');
    }
} 