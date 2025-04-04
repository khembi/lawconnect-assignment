<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Tag as TagModel;

class Tag extends Component
{
    /**
     * The tag model.
     *
     * @var TagModel
     */
    public $tag;

    /**
     * Whether this tag is active/highlighted.
     *
     * @var bool
     */
    public $active;

    /**
     * Create a new component instance.
     *
     * @param TagModel $tag
     * @param bool $active
     * @return void
     */
    public function __construct(TagModel $tag, $active = false)
    {
        $this->tag = $tag;
        $this->active = $active;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tag');
    }
} 