<template>

    <el-card shadow="always" class="sessions" :element-loading-text="loadingText" v-loading="loading">
        <template slot="header">
            <h1>Sessions</h1>

            <div class="actions">
                <el-select
                    placeholder="Search by Student"
                    filterable clearable no-data-text="No Data"
                    :value="query.studentId"
                    @change="handleSearchChange"
                >
                    <el-option
                        v-for="student in students"
                        :key="student.id"
                        :value="student.id"
                        :label="student.fullName"
                    />
                </el-select>

                <el-button type="primary" size="medium" @click="openReportTemplateModal">
                    <i class="el-icon-document"/> Report Template
                </el-button>

                <el-button type="primary" size="medium" @click="openGenerateReportModal">
                    <i class="el-icon-document-add"/> Generate Report
                </el-button>

                <el-button type="primary" size="medium" @click="openAddSessionModal">
                    <i class="el-icon-plus"/> Add Session
                </el-button>
            </div>
        </template>

        <el-table :data="sessions" style="width: 100%" empty-text="No Data To Display">
            <el-table-column label="Id" prop="id"></el-table-column>
            <el-table-column label="Student" prop="studentFullName"></el-table-column>
            <el-table-column label="Rate" prop="rate" width="300">
                <template slot-scope="scope">
                    <el-rate
                        v-model="scope.row.rate"
                        :max="10" disabled
                        :rateColors="rateColors"
                    />
                </template>
            </el-table-column>
            <el-table-column label="Date" prop="date"></el-table-column>
            <el-table-column label="Duration" prop="duration"></el-table-column>
            <el-table-column label="Type" prop="type">
                <template slot-scope="scope">
                    {{ types[scope.row.type] }}
                </template>
            </el-table-column>
            <el-table-column label="Operations">
                <template v-if="scope.row.canRate" slot-scope="scope">
                    <el-button icon="el-icon-star-off" type="success" circle @click="openRateSessionModal(scope.row.id)"/>
                </template>
            </el-table-column>
        </el-table>

        <el-pagination
            v-if="sessions.length > 0"
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
    import apiSessions from '@/api/sessions.js';
    import apiStudents from '@/api/students.js';
    import handleApiMixin from '@/mixins/handle-api-mixin.js';

    import Modals from './modals';

    export default {
        name: 'sessions',
        mixins: [handleApiMixin],
        components: {
            Modals
        },
        data() {
            return {
                loading: false,
                loadingText: 'Loading...',
                initialized: false,
                query: {
                    studentId: null,
                    page: null,
                },
                paginationTotal: null,
                types: {
                    'one-time': 'One Time',
                    repeated: 'Repeated'
                },
                rateColors: ['#99A9BF', '#F7BA2A', '#FF9900'],
                sessions: [],
                students: [],
            }
        },
        created() {
            this.initData();
        },
        methods: {
            handlePageChange(page) {
                this.query.page = page;
                this.initData();
            },
            handleSearchChange(value) {
                if (!value) value = null;

                this.query.studentId = value;
                this.initData();
            },
            initData() {
                this.loading = true;

                return apiSessions
                    .index(this.query)
                    .then(response => {
                        if (!this.initialized) {
                            this.initStudents();
                        }

                        return response;
                    })
                    .then(response => {
                        this.sessions = response.data.data;
                        this.paginationTotal = response.data.lastPage;
                        this.loading = false;
                        this.initialized = true;
                    })
                    .catch(response => {
                        this.handleMessage('error', response);
                        this.loading = false;
                    });
            },
            initStudents() {
                return apiStudents
                    .index({})
                    .then(response => {
                        this.students = response.data.data;
                    })
                    .catch(response => {
                        this.handleMessage('error', response);
                    });
            },
            openReportTemplateModal() {
                this.$refs.modals.showReportTemplateModal();
            },
            openGenerateReportModal() {
                this.$refs.modals.showGenerateReportModal();
            },
            openAddSessionModal() {
                this.$refs.modals.showAddSessionModal();
            },
            openRateSessionModal(id) {
                this.$refs.modals.showRateSessionModal(true, id);
            }
        }
    }
</script>
