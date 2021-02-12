<template>
    <detail-field :field="field">
        <template v-slot:translatable>
            <Tabs ref="tabs">
                <div v-for="(item, locale) in $locales">
                    <Tab :locale="{key: locale, label: item}">
                        <div v-if="field.value">
                            <div class="trix-content" v-html="field.value[locale]"></div>
                        </div>
                        <div v-else>
                            <i class="dripicons-minus text-gray-600" title="Значение отсутствует" />
                        </div>
                    </Tab>
                </div>
            </Tabs>
        </template>

        <div v-if="field.value" v-html="field.value"></div>
        <template v-else>
            <i class="dripicons-minus text-gray-600" title="Значение отсутствует" />
        </template>
    </detail-field>
</template>

<script>
    import DetailField from 'Vendor/js/Mixins/DetailField'
    import Tabs from 'Vendor/js/components/Tabs'
    import Tab from 'Vendor/js/components/Tab'

    export default {
        mixins: [DetailField],
        components: {Tab, Tabs}
    }
</script>

<style lang="scss">
    .trix-content {
        .attachment {
            @apply w-1/2 block m-auto;
        }
    }
</style>

