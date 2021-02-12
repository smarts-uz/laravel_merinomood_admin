<template>
    <div>
        <button @click="toggle" class="py-3 text-blue-600
                flex items-center justify-between
                cursor-pointer w-full">
            <p v-if="!isExpended">Показать</p>
            <p v-else>Cкрыть</p>
        </button>

        <div :style="{height: `${height}px`}" class="overflow-hidden h-0 sidebar__submenu"
             ref="submenu">
            <div v-if="html" v-html="submenu"></div>
            <div v-else>{{ submenu }}</div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ExtendContent",
        props: ['submenu','html'],
        data() {
            return {
                height: 0,
                isExpended: false,
            }
        },
        methods: {
            toggle(e) {
                let expandedHeight = this.$refs.submenu.scrollHeight
                this.isExpended = !this.isExpended
                this.height = this.height ? 0 : expandedHeight

                return
            }
        },
    }
</script>

<style lang="scss" scoped>
    .sidebar {
        &__submenu {
            transition: all .3s ease;
        }
    }
</style>
