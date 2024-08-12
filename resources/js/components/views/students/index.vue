<template>

    <el-card shadow="always" class="students" :element-loading-text="loadingText" v-loading="loading">
        <template slot="header">
            <h1>Students</h1>

            <div class="actions">
                <el-input
                    :value="query.search"
                    size="medium"
                    placeholder="Type to search"
                    @input="handleSearchInput"
                />

                <el-button type="primary" size="medium" @click="addStudent">
                    <i class="el-icon-plus"/> Add Student
                </el-button>
            </div>
        </template>

        <el-table :data="students" style="width: 100%" empty-text="No Data To Display">
            <el-table-column label="Id" prop="id" width="60"></el-table-column>
            <el-table-column label="First Name" prop="firstName"></el-table-column>
            <el-table-column label="Middle Name" prop="middleName"></el-table-column>
            <el-table-column label="Last Name" prop="lastName"></el-table-column>
            <el-table-column label="Email" prop="email"></el-table-column>
            <el-table-column label="Phone Number" prop="phoneNumber"></el-table-column>
            <el-table-column label="Gender" prop="gender">
                <template slot-scope="scope">
                    {{ genders[scope.row.gender] }}
                </template>
            </el-table-column>
            <el-table-column label="Birth date" prop="birthdate"></el-table-column>
            <el-table-column label="Availability" prop="availability" width="400">
                <span slot-scope="scope" class="student-availability">
                    <el-tag v-for="item in displayedAvailability(scope.row)" :key="item">{{ item }}</el-tag>
                </span>
            </el-table-column>
            <el-table-column label="Operations">
                <template slot-scope="scope">
                    <el-upload
                        ref="upload"
                        action="''"
                        :show-file-list="false"
                        :auto-upload="true"
                        :http-request="importData"
                        :data="{ id: scope.row.id }"
                    >
                        <el-button icon="el-icon-upload" type="primary" circle></el-button>
                    </el-upload>
                </template>
            </el-table-column>
        </el-table>

        <el-pagination
            v-if="students.length > 0"
            background layout="prev, pager, next"
            :page-count="paginationTotal"
            @current-change="handlePageChange"
        />

        <modals
            ref="modals"
            @refresh="initData"
        />
    </el-card>

</template>

<script type="text/babel">
    import apiStudents from '@/api/students.js';
    import handleApiMixin from '@/mixins/handle-api-mixin.js';

    import Modals from './modals';

    export default {
        name: 'students',
        mixins: [handleApiMixin],
        components: {
            Modals
        },
        data() {
            return {
                loadingData: false,
                uploadingFile: false,
                students: [],
                query: {
                    search: null,
                    page: null,
                },
                paginationTotal: null,
                genders: {
                    male: 'Male',
                    female: 'Female'
                }
            }
        },
        created() {
            this.initData();
        },
        computed: {
            loading() {
                return this.loadingData || this.uploadingFile;
            },
            loadingText() {
                if (this.loadingData) return 'Loading...';
                if (this.uploadingFile) return 'Uploading...';

                return null;
            }
        },
        methods: {
            handlePageChange(page) {
                this.query.page = page;
                this.initData();
            },
            initData() {
                this.loadingData = true;

                return apiStudents
                    .index(this.query)
                    .then(response => {
                        this.students = response.data.data;
                        this.paginationTotal = response.data.lastPage;
                        this.loadingData = false;
                    })
                    .catch(response => {
                        this.handleMessage('error', response);
                        this.loadingData = false;
                    });
            },
            importData(e) {
                this.uploadingFile = true;

                let formData = new FormData();
                formData.append('file', e.file || e);

                return apiStudents
                    .importData(e.data.id, formData)
                    .then(response => {
                        this.handleMessage('success', response);
                        this.uploadingFile = false;
                    })
                    .catch(response => {
                        this.handleMessage('error', response);
                        this.uploadingFile = false;
                    });
            },
            addStudent() {
                this.$refs.modals.showAddStudentModal();
            },
            handleSearchInput(search) {
                if (!search) search = null;

                this.query.search = search;
                this.initData();
            },
            displayedAvailability(student) {
                return Object.keys(student.availability).filter(key => student.availability[key] === true);
            }
        }
    }
</script>
