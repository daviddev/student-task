<template>

    <div>
        <add-session-modal
            v-if="addSessionModal"
            :visible.sync="addSessionModal"
            @saved="$emit('refresh')"
        />

        <report-template-modal
            v-if="reportTemplateModal"
            :visible.sync="reportTemplateModal"
            @saved="$emit('refresh')"
        />

        <generate-report-modal
            v-if="generateReportModal"
            :visible.sync="generateReportModal"
            @saved="$emit('refresh')"
        />

        <rate-session-modal
            v-if="rateSessionModal"
            :session-id="sessionId"
            :visible.sync="rateSessionModal"
            @saved="$emit('refresh')"
        />
    </div>

</template>

<script type="text/babel">
    import AddSessionModal from './add-session-modal';
    import ReportTemplateModal from './ReportTemplateModal.vue';
    import GenerateReportModal from './generate-report-modal';
    import RateSessionModal from './RateSessionModal.vue';

    export default {
        components: {
            AddSessionModal,
            ReportTemplateModal,
            GenerateReportModal,
            RateSessionModal
        },
        data() {
            return {
                sessionId: null,
                addSessionModal: false,
                reportTemplateModal: false,
                generateReportModal: false,
                rateSessionModal: false
            }
        },
        methods: {
            showAddSessionModal(bool = true) {
                this.addSessionModal = bool;
            },
            showReportTemplateModal(bool = true) {
                this.reportTemplateModal = bool;
            },
            showGenerateReportModal(bool = true) {
                this.generateReportModal = bool;
            },
            showRateSessionModal(bool = true, id) {
                this.rateSessionModal = bool;
                this.sessionId = id;
            },
        }
    }
</script>
