<template>

    <el-dialog
        custom-class="generate-report-modal"
        visible :append-to-body="false"
        :close-on-click-modal="false"
        @update:visible="close"
    >
        <div slot="title" class="dialog-header">
            <h3>Generate Report</h3>
        </div>

        <div class="generate-report-modal__content" :element-loading-text="loadingText" v-loading="loading">
            <el-form
                ref="form"
                status-icon label-position="top"
                :model="report"
                :rules="rules"
            >
                <el-row :gutter="10">
                    <el-col :span="12">
                        <el-form-item label="Student" prop="studentId">
                            <el-select
                                placeholder="Student"
                                filterable no-data-text="No Data"
                                v-model="report.studentId"
                            >
                                <el-option
                                    v-for="student in students"
                                    :key="student.id"
                                    :value="student.id"
                                    :label="student.fullName"
                                />
                            </el-select>
                        </el-form-item>
                    </el-col>

                    <el-col :span="12">
                        <el-form-item label="Split session in minutes" prop="split">
                            <el-select placeholder="Split session in minutes" v-model="report.split">
                                <el-option
                                    v-for="split in splitOptions"
                                    :key="split.id"
                                    :value="split.id"
                                    :label="split.label"
                                />
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>

                <el-row :gutter="10">
                    <el-col :span="12">
                        <el-form-item label="Start From" prop="startTime">
                            <date-picker format="YYYY-MM-DD HH:mm" type="datetime" placeholder="Start From" v-model="report.startTime"/>
                        </el-form-item>
                    </el-col>

                    <el-col :span="12">
                        <el-form-item label="To Date" prop="endTime">
                            <date-picker format="YYYY-MM-DD HH:mm" type="datetime" placeholder="To Date" v-model="report.endTime"/>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>
        </div>

        <div slot="footer" class="dialog-footer">
            <el-button type="default" size="small" @click="close">
                Cancel
            </el-button>

            <el-button type="primary" size="small" @click="validate">
                Generate
            </el-button>
        </div>
    </el-dialog>

</template>

<script type="text/babel">
    import DatePicker from 'vue2-datepicker';
    import apiStudents from '@/api/students.js';
    import handleApiMixin from '@/mixins/handle-api-mixin.js';

    import validation from './validation.js';
    import moment from 'moment';
    import _ from 'lodash';

    export default {
        name: 'generate-report-modal',
        mixins: [handleApiMixin],
        components: {
            DatePicker
        },
        data() {
            return {
                loadingData: false,
                generatingReport: false,
                students: [],
                report: {},
                splitOptions: [
                    {
                        id: 2,
                        label: '2 Min'
                    },
                    {
                        id: 5,
                        label: '5 Min'
                    },
                    {
                        id: 10,
                        label: '10 Min'
                    },
                    {
                        id: 15,
                        label: '15 Min'
                    },
                ],
            }
        },
        created() {
            this.initStudents();
        },
        computed: {
            rules() {
                return validation;
            },
            loading() {
                return this.loadingData || this.generatingReport;
            },
            loadingText() {
                if (this.loadingData) return 'Loading...';
                if (this.generatingReport) return 'Generating Report...';

                return null;
            }
        },
        methods: {
            close() {
                this.$emit('update:visible', false);
            },
            validate() {
                return this.$refs.form.validate()
                    .then(() => this.generate())
                    .catch(() => {});
            },
            downloadReport(file) {
                const url = window.URL.createObjectURL(new Blob([file]));
                const link = document.createElement('a');

                link.href = url;
                link.setAttribute('download', 'reports.zip');
                document.body.appendChild(link);
                link.click();

                link.parentNode.removeChild(link);
                window.URL.revokeObjectURL(url);
            },
            generate() {
                this.generatingReport = true;

                let data = _.cloneDeep(this.report);
                data.startTime = moment(data.startTime).format('YYYY-MM-DD HH:mm');
                data.endTime = moment(data.endTime).format('YYYY-MM-DD HH:mm');
                delete data.studentId;

                return apiStudents
                    .generateReport(this.report.studentId, data)
                    .then(response => {
                        this.downloadReport(response.data);
                        this.close();
                        this.generatingReport = false;
                    })
                    .catch(async response => {
                        const result = await response.response.data.text();
                        response.message = JSON.parse(result).message;

                        this.handleMessage('error', response);
                        this.generatingReport = false;
                    });
            },
            initStudents() {
                this.loadingData = true;

                return apiStudents
                    .index({})
                    .then(response => {
                        this.students = response.data.data;
                        this.loadingData = false;
                    })
                    .catch(response => {
                        this.handleMessage('error', response);
                        this.loadingData = false;
                    });
            },
        },
    }
</script>
