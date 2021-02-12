<template>
    <detail-field :field="field">
        <template v-slot:translatable>
            <Tabs ref="tabs">
                <div v-for="(item, locale) in $locales">
                    <Tab :locale="{key: locale, label: item}">
                        <template v-if="field.html" v-html="field.value[locale]"></template>
                        <template v-else>
                            {{ field.value[locale] }}
                        </template>
                    </Tab>
                </div>
            </Tabs>
        </template>

        <template v-if="field.html" v-html="field.value"></template>
        <template v-else>
            {{ field.value }}
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
