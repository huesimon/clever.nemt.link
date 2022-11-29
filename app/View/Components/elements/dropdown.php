<?php

namespace App\View\Components\elements;

use Illuminate\View\Component;

class dropdown extends Component
{
    private $links;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->links = [
            // [
            //     'url' => route('logout'),
            //     'text' => 'Logout',
            // ]
        ];

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.elements.dropdown', [
            'links' => $this->links,
        ]);
    }
}
