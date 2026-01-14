<template>
    <div>
        <div v-if="value.length">
                <span v-for="item in value">
                    <a @click="handleAttachmentClick(item.full_url)"
                       :title="generateTitle(item)"
                       data-placement="top"
                       data-toggle="tooltip"
                    >
                         <app-icon class="size-20 mr-2" name="paperclip" @click.prevent="downloadFile(item)"/>
                    </a>
                </span>
        </div>
        <div v-else> -</div>
    </div>
</template>

<script>
import CoreLibrary from "../../../../core/helpers/CoreLibrary";
import {urlGenerator} from "../../../Helper/Helper";

export default {
    name: "AttachmentComponent",
    extends: CoreLibrary,
    props: ['rowData', 'tableId', 'value'],
    mounted() {
        this.initializeTooltip();
    },
    methods: {
        handleAttachmentClick(path) {
           window.open(urlGenerator(path), '_blank');
        },
        generateTitle(item) {
            if (item.path.includes("*")) {
                const title = item.path.split('/').pop().split("*");
                return title[0] + title[2]
            } else {
                return item.path.split('/').pop().split("*")
            }
        },
        downloadFile(file) {
            window.location = urlGenerator(file.full_url);
        }
    }
}
</script>
