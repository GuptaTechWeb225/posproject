<template>
    <div class="content-wrapper">
        <form ref="form" data-url="test-component" enctype="multipart/form-data">
            <div class="card card-with-shadow border-0 mb-primary">
                <div class="card-header primary-card-color p-primary">
                    <h4>General Inputs</h4>
                </div>
                <div class="card-body">
                    <!--Text-->
                    <div class="form-group">
                        <app-input
                            type="text"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="testFields.textValue"
                        />
                    </div>

                    <div class="form-group">
                        <app-input
                            type="search-and-select"
                            :options="{
                                url: 'https://loan.free.mockoapp.net/list', // this url will hit every search action
                                modifire: (v) => {
                                    return { id: v.id, value: v.name }
                                },
                                multiple: true,
                                per_page: 10, // default 10, you can change it any number. min 10 encourage to use
                                queryName: 'last_name', // default 'search', this key will use for query build link '../endpoind?last_name=shi&moreparam...'
                                loader: 'app-pre-loader', // default app-overlay-loder
                                params: {
                                    'type': 'type1',
                                    'isWanted': true
                                } // params object will be appended with your url after search param like '../endpoint?last_name=shishir&type=type1&isWanted=true'
                            }"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="testFields.searchAndSelect"
                        />
                    </div>

                    <!--Email-->
                    <div class="form-group">
                        <app-input
                            type="email"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="testFields.emailValue"
                        />
                    </div>

                    <!--Number-->
                    <div class="form-group">
                        <app-input
                            type="number"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="testFields.phone"
                        />
                    </div>

                    <!--Password-->
                    <div class="form-group">
                        <app-input
                            type="password"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="testFields.password"
                        />
                    </div>

                    <!--Currency-->
                    <div class="form-group">
                        <app-input
                            type="currency"
                            @focusin="focusInShow"
                            @mousemove="mouseMoveShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="currency"
                        />
                    </div>

                    <!--Decimal-->
                    <div class="form-group">
                        <app-input
                            type="decimal"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="decimalNumber"
                        />
                    </div>

                    <!--Tel Input-->
                    <div class="form-group">
                        <app-input
                            type="tel-input"
                            v-model="testNumber"
                            placeholder="Enter phone number"
                            :required="true"
                        />
                    </div>
                </div>
            </div>

            <div class="card card-with-shadow border-0 mb-primary">
                <div class="card-header primary-card-color p-primary">
                    <h4>Checkbox, Radios & Switches</h4>
                </div>
                <div class="card-body">
                    <!--Switch-->
                    <div class="form-group">
                        <app-input
                            type="switch"
                            :label="$t('Switch')"
                            v-model="switchValue"
                        />
                    </div>

                    <!--Checkbox-->
                    <div class="form-group">
                        <app-input
                            type="checkbox"
                            v-model="checkboxValue"
                            :list="multiSelectOptionList"
                        />
                    </div>

                    <!--Radio-->
                    <div class="form-group">
                        <app-input
                            type="radio"
                            radio-checkbox-name="test"
                            @focusin="focusInShow"
                            @mousemove="mouseMoveShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="radioBtn"
                        />
                    </div>

                    <!--Radio Buttons-->
                    <div class="form-group">
                        <app-input
                            type="radio-buttons"
                            @focusin="focusInShow"
                            @mousemove="mouseMoveShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="radioButtons"
                        />
                    </div>
                </div>
            </div>

            <div class="card card-with-shadow border-0 mb-primary">
                <div class="card-header primary-card-color p-primary">
                    <h4>Selections</h4>
                </div>
                <div class="card-body">
                    <!--Select-->
                    <div class="form-group">
                        <app-input
                            type="select"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="selectValue"
                        />
                    </div>

                    <!--Search & Select-->
                    <div class="form-group">
                        <app-input
                            type="search-select"
                            :loaded-per-time="5"
                            v-model="searchSelectValue"
                            :list="multiSelectOptionList"
                            placeholder="Search and select"
                            :required="true"
                        />
                    </div>

                    <!--Advance Search Select-->
                    <div class="form-group">
                        <app-input
                            type="advance-search-select"
                            :loaded-per-time="5"
                            v-model="advanceSearchSelectValue"
                            fetch-url="test-search-select"
                            list-class="yessss"
                            placeholder="Search and select"
                            list-value-field="name"
                            :required="true"
                        />
                    </div>

                    <!--Multiselect-->
                    <div class="form-group">
                        <app-input
                            type="multi-select"
                            v-model="multiSelect"
                            @input="getMultiSelectValue"
                            @deletedId="afterDeleteId"
                            @addedId="afterAddId"
                            :list="multiSelectOptionList"
                        />
                    </div>

                    <!--Multi create-->
                    <div class="form-group">
                        <app-input
                            type="multi-create"
                            v-model="multiCreate"
                            :list="multiCreateOptionList"
                            list-value-field="name"
                            placeholder="Add variation here"
                            :multi-create-preloader="multiPreloader"
                            @storeData="testStoreData"
                        />
                    </div>

                    <!--Smart Select-->
                    <div class="form-group">
                        <app-input
                            label="smart-select"
                            type="smart-select"
                            :list="multiSelectOptionList"
                            v-model="smartSelect"
                        />
                    </div>
                </div>
            </div>

            <div class="card card-with-shadow border-0 mb-primary">
                <div class="card-header primary-card-color p-primary">
                    <h4>Pickers</h4>
                </div>
                <div class="card-body">
                    <!--Timepicker-->
                    <div class="form-group">
                        <app-input
                            type="time"
                            v-model="testFields.timeValue"
                        />
                    </div>

                    <!--Datepicker-->
                    <div class="form-group">
                        <app-input
                            type="date"
                            v-model="dateValue"
                        />
                    </div>
                </div>
            </div>

            <div class="card card-with-shadow border-0 mb-primary">
                <div class="card-header primary-card-color p-primary">
                    <h4>File Uploads</h4>
                </div>
                <div class="card-body">
                    <!--Custom File-->
                    <div class="form-group">
                        <app-input
                            type="custom-file-upload"
                            :label="$t('change_image')"
                            v-model="customFile"
                        />
                    </div>

                    <!--File-->
                    <div class="form-group">
                        <app-input
                            type="file"
                            v-model="fileValue"
                            :label="'Choose file'"
                        />
                    </div>

                    <!--Dropzone-->
                    <div class="form-group">
                        <app-input
                            type="dropzone"
                            v-model="dropzoneValue"
                        />
                    </div>
                </div>
            </div>

            <div class="card card-with-shadow border-0 mb-primary">
                <div class="card-header primary-card-color p-primary">
                    <h4>Text area & Editors</h4>
                </div>
                <div class="card-body">
                    <!--Textarea-->
                    <div class="form-group">
                        <app-input
                            type="textarea"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="textarea"
                        />
                    </div>

                    <!--Text Editor-->
                    <div class="form-group">
                        <app-input
                            type="text-editor"
                            :minimal="true"
                            v-model="textEditor"
                        />
                    </div>

                    <!--Markdown-->
                    <div class="form-group">
                        <app-input
                            type="markdown"
                            v-model="markdownValue"
                        />
                    </div>

                    <!--Markdown Viewer-->
                    <markdown-viewer
                        :value="markdownValue"
                    />
                </div>
            </div>

            <button class="btn btn-primary" type="submit" @click.prevent="submitData">Submit</button>
            <button class="btn btn-secondary" type="button" @click.prevent="changeData">Change Value(s)</button>
        </form>
    </div>
</template>

<script>
import {FormMixin} from "../../mixins/form/FormMixin.js";

export default {
    name: "AddEditForm",
    mixins: [FormMixin],
    data() {
        return {
            demo: 1,
            switchValue: false,
            markdownValue: "# Sani",
            searchSelectValue: '',
            advanceSearchSelectValue: {id: 1, name: 'Sani Khan'},
            testNumber: '',
            test: '',
            testName: '',
            testPass: '',
            testPass1: '',
            decimalNumber: 300,
            emailErrorMessage: '',
            errorMessage: '',
            imageUrl: "/images/core.png",
            showError: false,
            dataLoaded: false,
            isRange: false,
            dateValue: new Date(),
            dateTimeValue: new Date(),
            rangeValue: {
                start: new Date(),
                end: new Date()
            },
            timeValue: "12:00",
            smartSelect: 1,
            selectValue: 2,
            multiSelect: [11],
            multiPreloader: false,
            textarea: "This is Fardin",
            currency: 5000,
            radioButtons: 3,
            radioBtn: 1,
            checkboxValue: [],
            textEditor: "<h1>hi there, how are you </h1>",
            fileValue: '',
            customFile: '',
            dropzoneValue: [
                '/images/error-401.png',
                '/images/error-403.png',
                '/images/error-404.png',
            ],
            testFields: {
                timeValue: "12:15 PM",
                phone: 1796426502,
                textValue: "Sani",
                searchAndSelect: '1, 3',
                emailValue: 'sani@gain.media',
                confirmedPassword: '',
                password: '',
                password2: '',
                date1: new Date("2020-04-08"),
                date2: null
            },
            testArray: [
                {
                    name: "fardin",
                },
                {
                    name: "ahsan",
                },
            ],
            passwordArr: [
                {
                    password: '',
                },
                {
                    password: '',
                },
            ],
            multiSelectOptionList: [
                {id: 1, value: 'Cricket'},
                {id: 2, value: 'Football'},
                {id: 3, value: 'Badminton'},
                {id: 4, value: 'Option 4'},
                {id: 5, value: 'Option 5'},
                {id: 6, value: 'Option 6'},
                {id: 7, value: 'Option 7'},
                {id: 8, value: 'Option 8'},
                {id: 9, value: 'Option 9'},
                {id: 10, value: 'Option 10'},
                {id: 11, value: 'Option 11'},
                {id: 12, value: 'Option 12'},
                {id: 13, value: 'Option 13'},
                {id: 14, value: 'Option 14'},
                {id: 15, value: 'Option 15'},
                {id: 16, value: 'Option 16'},
                {id: 17, value: 'Option 17'},
                {id: 18, value: 'Option 18'},
                {id: 19, value: 'Option 19'},
                {id: 20, value: 'Option 20'},

            ],

            // Multi Create
            multiCreate: [],
            multiCreateOptionList: [
                {id: 1, name: 'Cricket'},
                {id: 2, name: 'Football',},
                {id: 3, name: 'Kabadi'},
                {id: 4, name: 'Hadudu'}
            ]
        };
    },
    created() {
        // this.dateValue = new Date("2020-04-08");
        this.getData();
    },
    methods: {
        getMultiSelectValue(value) {
        },
        afterAddId(val) {
        },
        afterDeleteId(val) {
        },
        changeData() {
            this.multiSelect = [4, 5];
            this.multiCreate = [2];
            this.searchSelectValue = 3;
            this.isRange = true;
            this.markdownValue = "# Shahed"
        },
        focusInShow() {
        },
        mouseMoveShow() {
        },
        focusOutShow() {
        },
        showClickEvent() {
        },
        showInputEventValue(value) {
        },
        submitData() { 
            let formData = new FormData();
            formData.append("custom_file", this.customFile);
            formData.append("dropzoneValue", this.dropzoneValue);


            this.save(formData);

            
        },

        // Multi create
        testStoreData(arg) {
            this.multiPreloader = true;
            this.addNewMultiCreateOption(arg);
        },
        addNewMultiCreateOption(value) {
            this.axiosPost({
                url: 'store-multi-create-option',
                data: {name: value}
            }).then(response => {
                this.multiCreateOptionList = response.data.list;
                this.multiCreate.push(response.data.new_id);
            }).finally(() => {
                this.multiPreloader = false;
            });
        },

        // Hooks
        beforeSubmit(fields) {
            let formData = new FormData();
        },
        afterSubmit() {
        },
        afterSuccess(res) {
            this.customFile = ''
        },
        afterError(res) {
            this.testName = 3;
            this.showError = true;
            this.errorMessage = "Given email is invalid";
        },
        getData() {
            let instance = this,
                url = "test-component";
            instance
                .axiosGet(url)
                .then(response => {
                    instance.dataLoaded = true;
                    this.fields = response.data;

                    this.customFile = response.data.customFile;
                })
                .catch(({response}) => {
                    if (instance.isFunction(instance.afterErrorFromGetEditData))
                        instance.afterErrorFromGetEditData(response);
                });
        }
    }
}
</script>
