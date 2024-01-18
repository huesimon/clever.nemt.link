<?php

namespace App\Livewire\HelloWorld;

use Livewire\Component;

class LazyLoading extends Component
{

    public function mount()
    {
        sleep(1);
        // dd('mount');
    }

    public function render()
    {
        return view('livewire.hello-world.lazy-loading');
    }

    public function placeholder()
    {
        return <<<'HTML'
            <div>
                This is a placeholder
            </div>
        HTML;
    }
}
