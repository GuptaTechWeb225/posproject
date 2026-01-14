<template>
    <modal id="coupon-modal"
           v-model="showModal"
           :loading="loading"
           :preloader="preloader"
           :scrollable="false"
           :title="generateModalTitle('coupon')"
           @submit="submitData">

        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : COUPONS'
            @submit.prevent="submitData">

            <app-form-group
                v-model="formData.name"
                :error-message="$errorMessage(errors, 'name')"
                :label="$t('name')"
                :placeholder="$placeholder('name')"
                :required="true"
            />

            <app-form-group
                :label="$t('start_at')"
                type="date"
                v-model="formData.start_at"
                :placeholder="$placeholder('start_at', '')"
                :required="true"
                :error-message="$errorMessage(errors, 'start_at')"
                date-mode="dateTime"
            />

            <app-form-group
                :label="$t('end_at')"
                type="date"
                v-model="formData.end_at"
                :placeholder="$placeholder('end_at', '')"
                :required="true"
                :error-message="$errorMessage(errors, 'end_at')"
                date-mode="dateTime"
            />

            <app-form-group
                v-model="formData.code"
                :error-message="$errorMessage(errors, 'code')"
                :label="$t('code')"
                :placeholder="$placeholder('code')"
                :required="false"
            />

            <div class="row">
                <div class="col-10">
                    <app-form-group
                        v-if="discountInput"
                        v-model="formData.discount"
                        type="number"
                        :error-message="$errorMessage(errors, 'discount')"
                        :label="$t('discount')"
                        :placeholder="$placeholder('discount')"
                        :required="true"
                    />

                    <app-form-group-selectable
                        v-else
                        type="multi-select"
                        :label="$t('discounts')"
                        list-value-field="name"
                        v-model="formData.discount_id"
                        :chooseLabel="$t('discounts')"
                        :error-message="$errorMessage(errors, 'discount_id')"
                        :fetch-url="SELECTABLE_DISCOUNTS"
                    />
                </div>
                <div class="col-2">
                    <div class="h-100 d-flex justify-content-end align-items-end">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" @click.prevent="discountInput=!discountInput">
                                Or
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </form>
    </modal>
</template>

<script>

import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import {COUPONS, SELECTABLE_DISCOUNTS} from "../../../Config/ApiUrl-CPB";
import moment from "moment/moment";

export default {
    name: "CouponCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    data() {
        return {
            discountInput: true,
            formData: {
                discount_id: []
            },
            COUPONS,
            SELECTABLE_DISCOUNTS
        }
    },
    methods: {
        afterSuccess({data}) {
            this.formData = {};
            $('#coupon-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'coupon-table');
        },
        afterSuccessFromGetEditData(response) {
            this.preloader = false;
            this.formData = response.data;
            this.formData.discount_id = this.getDiscountIds(this.formData.discounts);
        },
        submitData() {
            this.fieldStatus.isSubmit = true;
            this.loading = true;
            this.message = '';
            this.errors = {};
            this.formData.start_at = this.formData.start_at ? moment(this.formData.start_at).format('YYYY-MM-DD HH:mm:ss') : '';
            this.formData.end_at = this.formData.end_at ? moment(this.formData.end_at).format('YYYY-MM-DD HH:mm:ss') : '';
            this.save(this.formData);
        },
        getDiscountIds(discountsObj) {
            let discounts = [];
            discountsObj.filter((item) => {
                discounts.push(item.id);
            })
            return discounts;
        }
    },
}
</script>

