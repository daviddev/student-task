<template>

    <el-dialog
        custom-class="rate-session-modal"
        visible :append-to-body="true"
        :close-on-click-modal="false"
        @update:visible="close"
    >
        <div slot="title" class="dialog-header">
            <h3>Rate Session</h3>
        </div>

        <div class="rate-session-modal__content" :element-loading-text="savingText" v-loading="saving">
            <el-rate
                v-model="session.rate"
                :max="10"
                :colors="colors"
            />
        </div>

        <div slot="footer" class="dialog-footer">
            <el-button type="default" size="small" @click="close">
                Cancel
            </el-button>

            <el-button type="primary" size="small" @click="save">
                Rate
            </el-button>
        </div>
    </el-dialog>

</template>

<script type="text/babel">
    import apiSessions from '@/api/sessions.js';
    import handleApiMixin from '@/mixins/handle-api-mixin.js';

    export default {
        name: 'rate-session-modal',
        mixins: [handleApiMixin],
        data() {
            return {
                saving: false,
                savingText: 'Rating Student...',
                colors: ['#99A9BF', '#F7BA2A', '#FF9900'],
                session: {}
            }
        },
        props: {
            sessionId: {
                type: Number,
                required: true
            }
        },
        methods: {
            close() {
                this.$emit('update:visible', false);
            },
            save() {
                this.saving = true;

                return apiSessions
                    .update(this.sessionId,  this.session)
                    .then(response => {
                        this.handleMessage('success', response);
                        this.$emit('saved');
                        this.close();
                        this.saving = false;
                    })
                    .catch(response => {
                        this.handleMessage('error', response);
                        this.saving = false;
                    });
            },
        },
    }
</script>
