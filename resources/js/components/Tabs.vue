<template>
    <div>
        <div class="tabs" v-if="showLocales">
            <ul class="list-reset flex mb-2">
                <li class="-mb-px mr-1" v-for="tab in tabs" :class="{ 'text-gray-700': tab.isActive, 'text-gray-500': !tab.isActive }" >
                    <a class="inline-block font-bold cursor-pointer mr-2 animate-text-color select-none border-primary"
                       @click.prevent="selectTab(tab.locale.key)">
                        {{ tab.locale.label }}
                    </a>
                </li>
            </ul>
        </div>

        <div class="tabs-details">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Tabs",
        props: {
            showLocales: {
                type: Boolean,
                default: true
            }
        },
        data() {
            return {tabs: [] };
        },

        mounted() {
            this.tabs = this.$children;

            this.tabs[0].isActive = true
        },
        methods: {
            selectTab(key) {
                this.tabs.forEach(tab => {
                    tab.isActive = (tab.locale.key == key);
                });
            }
        }
    }
</script>

<style scoped>

</style>
