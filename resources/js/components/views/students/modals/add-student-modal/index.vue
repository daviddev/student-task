<template>

    <el-dialog
        custom-class="add-student-modal"
        visible :append-to-body="true"
        :close-on-click-modal="false"
        @update:visible="close"
    >
        <div slot="title" class="dialog-header">
            <h3>Add Student</h3>
        </div>

        <div class="add-student-modal__content" :element-loading-text="savingText" v-loading="loading">
            <el-form
                ref="form"
                status-icon label-position="top"
                :model="student"
                :rules="rules"
            >
                <el-row :gutter="10">
                    <el-col :span="8">
                        <el-form-item label="First Name" prop="firstName">
                            <el-input placeholder="First Name" v-model="student.firstName"/>
                        </el-form-item>
                    </el-col>

                    <el-col :span="8">
                        <el-form-item label="Middle Name" prop="middleName">
                            <el-input placeholder="Middle Name" v-model="student.middleName"/>
                        </el-form-item>
                    </el-col>

                    <el-col :span="8">
                        <el-form-item label="Last Name" prop="lastName">
                            <el-input placeholder="Last Name" v-model="student.lastName"/>
                        </el-form-item>
                    </el-col>
                </el-row>

                <el-row :gutter="10">
                    <el-col :span="12">
                        <el-form-item label="Email" prop="email">
                            <el-input placeholder="Email" v-model="student.email"/>
                        </el-form-item>
                    </el-col>

                    <el-col :span="12">
                        <el-form-item label="Phone Number" prop="phoneNumber">
                            <el-input placeholder="Phone Number" v-model="student.phoneNumber"/>
                        </el-form-item>
                    </el-col>
                </el-row>

                <el-row :gutter="10">
                    <el-col :span="12">
                        <el-form-item label="Gender" prop="gender">
                            <el-select placeholder="Gender" v-model="student.gender">
                                <el-option
                                    v-for="gender in genders"
                                    :key="gender.id"
                                    :value="gender.id"
                                    :label="gender.label"
                                />
                            </el-select>
                        </el-form-item>
                    </el-col>

                    <el-col :span="12">
                        <el-form-item label="Birth date" prop="birthdate">
                            <date-picker format="YYYY-MM-DD" type="date" placeholder="Birth date" v-model="student.birthdate"/>
                        </el-form-item>
                    </el-col>
                </el-row>

                <el-form-item label="Available Days" prop="availability">
                    <el-checkbox :indeterminate="isIndeterminate" v-model="allDaysIsChecked" @change="handleCheckAllChange">Check all</el-checkbox>
                    <el-checkbox-group v-model="checkedDays" @change="handleCheckedDaysChange">
                        <el-checkbox v-for="day in days" :label="day" :key="day">
                            {{ day }}
                        </el-checkbox>
                    </el-checkbox-group>
                </el-form-item>
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
    import DatePicker from 'vue2-datepicker';
    import apiStudents from '@/api/students.js';
    import handleApiMixin from '@/mixins/handle-api-mixin.js';
    import validation from './validation.js';

    import convertKeys from '@/helpers/convert-keys';

    export default {
        name: 'add-student-modal',
        mixins: [handleApiMixin],
        components: {
            DatePicker
        },
        data() {
            return {
                loading: false,
                savingText: 'Adding Student...',
                genders: [
                    {
                        id: 'male',
                        label: 'Male'
                    },
                    {
                        id: 'female',
                        label: 'Female'
                    },
                ],
                days: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                checkedDays: [],
                allDaysIsChecked: false,
                isIndeterminate: true,
                student: {
                    availability: {
                        Monday: false,
                        Tuesday: false,
                        Wednesday: false,
                        Thursday: false,
                        Friday: false,
                        Saturday: false,
                        Sunday: false,
                    }
                },
            }
        },
        computed: {
            rules() {
                return validation;
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
                this.loading = true;

                let data = convertKeys.toSnake(this.student);
                data.availability = this.student.availability;

                return apiStudents
                    .store(data)
                    .then(response => {
                        this.handleMessage('success', response);
                        this.$emit('saved');
                        this.close();
                        this.loading = false;
                    })
                    .catch(response => {
                        this.handleMessage('error', response);
                        this.loading = false;
                    });
            },
            handleCheckAllChange(value) {
                this.checkedDays = value ? this.days : [];
                this.isIndeterminate = false;
                this.setStudentAvailability(this.checkedDays);
            },
            handleCheckedDaysChange(value) {
                let checkedCount = value.length;
                this.allDaysIsChecked = checkedCount === this.days.length;
                this.isIndeterminate = checkedCount > 0 && checkedCount < this.days.length;
                this.setStudentAvailability(value);
            },
            setStudentAvailability(availableDays) {
                Object.keys(this.student.availability).forEach(day => {
                    this.student.availability[day] = availableDays.includes(day);
                })
            },
        },
    }
</script>
