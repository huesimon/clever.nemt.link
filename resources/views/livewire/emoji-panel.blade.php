<div
class="relative"
x-data="{
    reactions: {
        '👏': {
            reactions: []
        },
        '🍔': {
            reactions: []
        },
        '🍟': {
            reactions: []
        }
    },
    myReactions: {
        '👏': {
            reactions: []
        },
        '🍔': {
            reactions: []
        },
        '🍟': {
            reactions: []
        }
    },

    randomId(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min
    },
    sendReaction(id, reaction) {
        $wire.userReacted(id, reaction)
    },


    init() {
        Echo.channel('reactions')
            .listen('UserReacted', (e) => {
                this.reactions[e.emoji].reactions.push(e.randomId)
                setTimeout(() => {
                    this.reactions[e.emoji].reactions.splice(this.reactions[e.emoji].reactions.indexOf(e.randomId), 1)
                }, 1000);

            })
    },
}"
>
    <li>
        <div class="bg-gray-800 w-full rounded-full p-4">
            <ul class="flex flex-row justify-between">
                <li
                    x-on:click="sendReaction(randomId(1, 999999999999999), '👏')" class="bg-gray-600 rounded-full p-2">
                    👏</li>
                <li
                    x-on:click="sendReaction(randomId(1, 999999999999999), '🍔')"
                    class="bg-gray-600 rounded-full p-2">🍔</li>
                <li
                    x-on:click="sendReaction(randomId(1, 999999999999999), '🍟')"
                    class="bg-gray-600 rounded-full p-2">🍟</li>
            </ul>
        </div>
    </li>
    <div class="absolute top-0">
        <template x-for="(reaction, index) in Object.keys(reactions)" :key="index">
            <div x-show="reactions[reaction].reactions.length" class="flex flex-row">
                <template x-for="emoji in reactions[reaction].reactions" :key="index">
                    <div x-text="reaction" class="bg-gray-800 rounded-full p-2"></div>
                </template>
            </div>
        </template>
    </div>
</div>
