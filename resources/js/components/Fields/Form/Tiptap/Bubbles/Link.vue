<template>
    <div>
        <div class="absolute transition duration-200
                    z-40 p-1 bg-white text-sm invisible opacity-0
                    text-white flex items-center rounded tiptap-link-bubble"
             :class="{active: isLinkActive}"
             :style="isLinkActive ? getPosition() : {left: 0, top: 0}">
            <div class="px-1 border-r">
                <button @click.prevent="openPopup(getMarkAttrs('link'))"
                        class="px-2 py-1 text-gray-700 text-sm rounded hover:bg-gray-300">
                    Edit link
                </button>
            </div>
            <div class="px-1 border-r">
                <a :href="getMarkAttrs('link').href" target="_blank"
                   class="flex items-center p-1 rounded hover:bg-gray-300">
                    <i class="text-gray-700 text-lg inline-flex dripicons-exit"></i>
                </a>
            </div>
            <div class="px-1">
                <button class="flex items-center p-1 rounded hover:bg-gray-300"
                        @click.prevent="commands.link({href: null})">
                    <i class="text-gray-700 text-lg inline-flex dripicons-link-broken"></i>
                </button>
            </div>
        </div>

        <transition>
            <div class="absolute w-64 bg-white p-2 z-50 rounded tiptap-link-popup transition-all duration-200"
                 :style="getPosition()"
                 v-if="payload.popup"
                 v-click-outside="close" @keydown.esc="close">
                <div class="flex items-center py-1 px-2 border-b border-gray-400 mb-1">
                    <i class="text-gray-700 inline-flex text-sm dripicons-link mr-2"></i>
                    <input type="text" class="text-gray-700 py-1 px-2
                    focus:outline-none w-full text-sm" ref="linkInput"
                           v-model="link"
                           @keypress.enter.prevent="setLink"
                           placeholder="Ссылка">
                </div>
                <div class="flex items-center py-1 px-2">
                    <i class="text-gray-700 inline-flex text-sm dripicons-align-justify mr-2"></i>
                    <input type="text" class="text-gray-700 py-1 px-2
                    focus:outline-none w-full text-sm"
                           @keypress.enter.prevent="setLink"
                           v-model="text" placeholder="Текст"
                           :disabled="!creating">
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    import BubbleMixin from './Bubble'

    export default {
        mixins: [BubbleMixin],

        data() {
            return {
                text: '',
                link: '',
                creating: false,
            }
        },

        computed: {
            isLinkActive() {
                return this.menu.isActive && this.isActive.link()
            }
        },

        methods: {
            openPopup(link = null) {
                if (!link && this.isActive.link()) {
                    return
                }

                this.text = link ? link.text : this.selectedText()
                this.link = link ? link.href : ''
                this.payload.popup = true

                if (!this.text && !this.link) {
                    this.creating = true
                }

                this.$nextTick(() => {
                    this.$refs.linkInput.focus()
                })
            },

            setLink() {
                if (!this.isLink()) {
                    return
                }

                if (this.creating) {
                    this.createLink()
                } else {
                    this.commands.link({href: this.link, text: this.text})
                }

                this.close()

                this.editor.focus()
            },

            createLink() {
                const {schema, state: {tr}, view} = this.editor

                const node = schema.text(this.text, [schema.marks.link.create({href: this.link, text: this.text})])
                view.dispatch(tr.replaceSelectionWith(node, false))
            },

            close() {
                this.payload.popup = false
                this.text = ''
                this.link = ''

                this.creating = false

                this.$nextTick(() => {
                    this.clearSelection()
                })
            },

            isLink() {
                return this.link.match(/(https?:\/\/[^\s]+)/g)
            },
        },

        watch: {
            'payload.popup'(popup) {
                if (popup) {
                    this.openPopup()
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .tiptap-field {
        .tiptap-link-popup, .tiptap-link-bubble {
            box-shadow: rgba(9, 30, 66, 0.31) 0 0 1px, rgba(9, 30, 66, 0.25) 0 4px 8px -2px;
            will-change: transform, opacity;
        }

        .tiptap-link-bubble {
            transform: scale(.8);
            &.active {
                @apply opacity-100 visible;
                transform: scale(1);
            }
        }

        .tiptap-link-popup {
            input[disabled] {
                opacity: .4;
            }
            &.v-enter, &.v-leave-to {
                opacity: 0;
                transform: scale(.8);
            }
        }
    }
</style>
