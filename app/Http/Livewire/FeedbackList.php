<?php

namespace App\Http\Livewire;

use App\Models\Feedback;
use Livewire\Component;

class FeedbackList extends Component
{
    public $feedbacks;

    public $message;

    public function submitFeedback()
    {
        Feedback::create([
            'message' => $this->message,
            'topic' => 'General'
        ]);

        $this->message = '';

        $this->feedbacks = Feedback::all()->sortByDesc('created_at');
    }

    public function mount()
    {
        $this->feedbacks = Feedback::all()->sortByDesc('created_at');
    }

    public function render()
    {
        return view('livewire.feedback-list');
    }
}
