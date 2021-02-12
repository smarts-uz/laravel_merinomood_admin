export default {
    data() {
        return {
            checkedItems: [],
            checkAllChecker: 'notChecked',
            isCheckedAll: 'notChecked'
        }
    },

    methods: {
        check(checkedId, checkedCondition) {
            if (checkedCondition == 'fullyChecked') { //check item
                this.checkedItems.push(checkedId)

                if (this.checkedItems.length !== this.resources.resources.length) //checked partly
                {
                    this.checkAllChecker = 'partlyChecked'
                    this.isCheckedAll = ''
                    return
                }

                if (this.checkedItems.length === this.resources.resources.length) //checked all
                {
                    this.checkAllChecker = 'fullyChecked'
                    return
                }
            }

            if (checkedCondition == 'notChecked') { //uncheck item
                let index = this.checkedItems.indexOf(checkedId)
                if (index !== -1) this.checkedItems.splice(index, 1)

                if (this.checkedItems.length !== 0) //unchecked partly
                {
                    this.checkAllChecker = 'partlyChecked'
                    this.isCheckedAll = ''
                    return
                }

                if (this.checkedItems.length === 0) //unchecked all
                {
                    this.checkAllChecker = 'notChecked'
                }
            }

        },

        updateCheckboxes() {
            this.checkedItems = []
            this.checkAllChecker = 'notChecked'
            this.isCheckedAll = 'notChecked'
        }
    },

    watch: {
        checkAllChecker(newValue) {
            if (newValue === 'fullyChecked') {
                this.checkedItems.length = 0 //emptying, just in case it’s not empty)
                this.resources.resources.forEach((item) => {
                    this.checkedItems.push(item.id)
                })
                this.isCheckedAll = 'fullyChecked';
                return
            }

            if (newValue === 'notChecked') {
                this.checkedItems.length = 0; //fastest way to emptying array ;P
                this.isCheckedAll = 'notChecked';
            }
        },
    }
}
