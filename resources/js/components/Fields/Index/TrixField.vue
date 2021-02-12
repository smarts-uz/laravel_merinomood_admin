<template>
    <div>
        <nav-link>
            <div class="text-blue-600">Показать</div>
            <template v-slot:submenu>
                <div v-html="getValue"></div>
            </template>
        </nav-link>
    </div>
</template>

<script>
    import NavLink from 'Vendor/js/components/NavLink'
    import IndexField from 'Vendor/js/Mixins/IndexField'

    export default {
        mixins: [IndexField],
        components: {
            NavLink,
        }
    }
</script>
