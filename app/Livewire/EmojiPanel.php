<?php

namespace App\Livewire;

use Livewire\Component;
use App\Events\UserReacted;
use Livewire\Attributes\On;

class EmojiPanel extends Component
{

    public function userReacted(string $randomId, string $emoji)
    {
        UserReacted::dispatch($randomId, $emoji);
    }

    #[On('echo:reactions,UserReacted')]
    public function echo()
    {

    }

    public function render()
    {
        return view('livewire.emoji-panel');
    }
}
