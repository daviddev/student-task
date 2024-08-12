<template>

    <el-dialog
        custom-class="add-session-modal"
        visible :append-to-body="true"
        :close-on-click-modal="false"
        @update:visible="close"
    >
        <div slot="title" class="dialog-header">
            <h3>Add Session</h3>
        </div>

        <div class="add-session-modal__content" :element-loading-text="loadingText" v-loading="loading">
            <el-form
                ref="form"
                status-icon label-position="top"
                :model="session"
                :rules="rules"
            >
                <el-row :gutter="10">
                    <el-col :span="12">
                        <el-form-item label="Student" prop="studentId">
                            <el-select
                                placeholder="Student"
                                filterable no-data-text="No Data"
                                v-model="session.studentId"
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
                        <el-form-item label="Type" prop="type">
                            <el-select placeholder="Type" v-model="session.type">
                                <el-option
                                    v-for="type in types"
                                    :key="type.id"
                                    :value="type.id"
                                    :label="type.label"
                                />
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>

                <el-row :gutter="10">
                    <el-col :span="12">
                        <el-form-item label="Start Time" prop="date">
                            <date-picker format="YYYY-MM-DD HH:mm" type="datetime" placeholder="Start Time" v-model="session.date"/>
                        </el-form-item>
                    </el-col>

                    <el-col :span="12">
                        <el-form-item label="Duration" prop="duration">
                            <el-input-number :min="1" :max="15" placeholder="Duration" v-model="session.duration"/>
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
                Add
            </el-button>
        </div>
    </el-dialog>

</template>

<script type="text/babel">
    import apiSessions from '@/api/sessions.js';
    import apiStudents from '@/api/students.js';
    import handleApiMixin from '@/mixins/handle-api-mixin.js';
    import DatePicker from 'vue2-datepicker';

    import validation from './validation.js';
    import moment from 'moment';
    import _ from 'lodash';

    export default {
        name: 'add-session-modal',
        mixins: [handleApiMixin],
        components: {
            DatePicker
        },
        data() {
            return {
                loadingData: false,
                savingData: false,
                types: [
                    {
                        id: 'one-time',
                        label: 'One Time'
                    },
                    {
                        id: 'repeated',
                        label: 'Repeated'
                    },
                ],
                students: [],
                session: {},
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
                return this.loadingData || this.savingData;
            },
            loadingText() {
                if (this.loadingData) return 'Loading...';
                if (this.savingData) return 'Adding Student...';

                return null;
            }
        },
        methods: {
            close() {
                this.$emit('update:visible', false);
            },
            validate() {
                return this.$refs.form.validate()
                    .then(() => this.save())
                    .catch(() => {});
            },
            save() {
                this.savingData = true;

                let data = _.cloneDeep(this.session);
                data.date = moment(data.date).format('YYYY-MM-DD HH:mm');

                return apiSessions
                    .store(data)
                    .then(response => {
                        this.handleMessage('success', response);
                        this.$emit('saved');
                        this.close();
                        this.savingData = false;
                    })
                    .catch(response => {
                        this.handleMessage('error', response);
                        this.savingData = false;
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
