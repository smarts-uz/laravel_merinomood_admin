<template>
    <detail-field :field="field">
        <template v-slot:translatable>
            <Tabs ref="tabs">
                <div v-for="(item, locale) in $locales">
                    <Tab :locale="{key: locale, label: item}">
                        <template v-if="field.value">
                            <div v-if="field.html" v-html="field.value[locale]"></div>
                            <template v-else>
                                {{ field.value[locale] }}
                            </template>
                        </template>
                        <template v-else>
                            <i class="dripicons-minus text-gray-600" title="Значение отсутствует" />
                        </template>
                    </Tab>
                </div>
            </Tabs>
        </template>

        <template v-if="field.value">
            <div v-if="field.html" v-html="field.value"></div>
            <template v-else>
                {{ field.value }}
            </template>
        </template>
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
