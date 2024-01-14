<div>
    <div class="">
        <div class="flex flex-row space-x-4">
            <textarea
            wire:model.live="message"
            class="border border-gray-200 rounded-md p-4 m-2 w-full"
            placeholder="Enter your feedback here..."
            ></textarea>
            <button
            wire:click="submitFeedback"
            class="border border-gray-200 rounded-md p-4 m-2 bg-blue-500 text-white"
            >Submit</button>
        </div>
    </div>
    <div class="flex flex-row space-y-4">
        <ul>
            @foreach ($feedbacks as $feedback)
            <li
            class="flex flex-col border border-gray-200 rounded-md p-4 m-2 hover:bg-gray-100"
            >
            <span>
                {{ $feedback->message }}
            </span>
            @if ($feedback->answer)
            <span>
                Reply: {{ $feedback->answer }}
            </span>
            @endif
        </li>
            @endforeach
        </ul>
    </div>
</div>
