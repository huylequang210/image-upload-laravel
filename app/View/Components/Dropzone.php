<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Dropzone extends Component
{

    public $param;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($param)
    {
        $this->param = $param;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.dropzone');
    }
}
