<template>
    <app-form-group
        :class="{'loading-opacity disabled': loading}"
        :page="page"
        :type="type"
        v-model="model"
        v-bind="propsFor()"
        v-on="$listeners"
        :list="arrays"
        :form-group-class="formGroupClass"
        @input="$emit('input', $event)"
    />
</template>

<script>
import _ from "lodash";
import {kebabCase} from "../../common/Helper/Support/TextHelper";
import {axiosGet} from "../../common/Helper/AxiosHelper";
import {addChooseInSelectArray} from "../../common/Helper/Support/FormHelper";

export default {
    name: "AppFromGroupSelectable",
    props: {
        page: {
            required: false,
            default: 'modal'
        },
        type: {
            required: false,
            default: 'search-select'
        },
        value: {
            required: true,
            default: function () {
                return '';
            }
        },
        chooseLabel: {
            default: false
        },
        fetchUrl: {
            type: String,
            required: true
        },
        formGroupClass: {
            type: String
        }
    },
    data() {
        return {
            model: [],
            arrays: [],
            loading: true
        }
    },
    methods: {
        propsFor() {
            let props = this.$attrs;
            const cTor = this.$options.components['app-input']
            if (cTor) {
                let keys = Object.keys(cTor.options.props)
                return _.pick(props, keys.map(key => {
                    return kebabCase(key)
                }))
            }

        },
        getData() {
            this.loading = true;
            axiosGet(this.fetchUrl).then(response => {
                if (this.chooseLabel) {
                    const valueField = this.$attrs['list-value-field'] ? this.$attrs['list-value-field'] : 'name';
                    this.arrays = addChooseInSelectArray(response.data, valueField, this.chooseLabel)
                } else {
                    this.arrays = response.data
                }
            }).finally(() => {
                this.loading = false;
            })
        }
    },
    watch: {
        value: {
            handler: function (value) {
                this.model = value;
            },
            immediate: true
        },
        fetchUrl: {
            handler: function () {
                this.getData();
            },
            immediate: true
        }
    }
}
</script>
