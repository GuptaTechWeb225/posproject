<template>
    <div class="content-wrapper">
        <app-page-top-section :title="'Download Details Data'"/>

        <div class="row">
            <div class="col-2 mb-primary" v-for="val in value">
                <a v-bind:href="SUPPLIER_EXPORT+val">
                    <div class="card card-with-shadow border-0 file-card">
                        <div class="card-body text-center">
                            <div class="icon-box mx-auto mb-primary">
                                <app-icon name="download"/>
                            </div>
                            <p class="default-font-color mb-0">{{ $t('sheet') }} - {{ val }}</p>
                            <p class="font-size-90 mb-0">
                                <span class="default-font-color">{{ $t('rows') }}:</span> <span
                                class="text-muted">{{ val * sheetNumber - perSheet + 1 }} - {{ val * perSheet }}</span>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import {SUPPLIER_EXPORT} from "../../../Config/ApiUrl-CPB";

export default {
    name: "SuppliersExportLists",
    props: ['sheetLists', 'perSheet'],
    data() {
        return {
            value: Math.ceil(this.sheetLists),
            sheetNumber: this.perSheet,
            SUPPLIER_EXPORT
        }
    },
}
</script>

<style scoped lang="scss">
.file-card {
    transition: 0.1s ease-in-out;

    .icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 100%;
        background-color: var(--base-color);

        svg {
            width: 22px;
            height: 22px;
            color: #4466F2;
        }
    }

    &:hover {
        transform: scale(1.02);
    }
}
</style>