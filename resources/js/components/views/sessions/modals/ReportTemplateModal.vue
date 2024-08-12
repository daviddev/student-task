<template>

    <el-dialog
        custom-class="report-template-modal"
        visible :append-to-body="true"
        :close-on-click-modal="false"
        @update:visible="close"
    >
        <div slot="title" class="dialog-header">
            <h3>Report Template</h3>
        </div>

        <div class="report-template-modal__content" :element-loading-text="savingText" v-loading="saving">
            <quill-editor
                v-model="content"
                :options="editorOption"
            />
        </div>

        <div slot="footer" class="dialog-footer">
            <el-button type="default" size="small" @click="close">
                Cancel
            </el-button>

            <el-button type="primary" size="small" @click="save">
                Save
            </el-button>
        </div>
    </el-dialog>

</template>

<script type="text/babel">
    import { mapGetters } from 'vuex';

    import { quillEditor } from 'vue-quill-editor'
    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    import 'quill/dist/quill.bubble.css'

    import apiUsers from '@/api/users.js';
    import handleApiMixin from '@/mixins/handle-api-mixin.js';

    export default {
        name: 'report-template-modal',
        mixins: [handleApiMixin],
        components: { quillEditor },
        data() {
            return {
                saving: false,
                savingText: 'Saving...',
                session: {},
                editorOption: {
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote', 'code-block'],
                            [{ 'header': 1 }, { 'header': 2 }],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            [{ 'script': 'sub' }, { 'script': 'super' }],
                            [{ 'indent': '-1' }, { 'indent': '+1' }],
                            [{ 'direction': 'rtl' }],
                            [{ 'size': ['small', false, 'large', 'huge'] }],
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            [{ 'font': [] }],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'align': [] }],
                            ['clean'],
                            ['link']
                        ],
                        syntax: {
                            highlight: text => hljs.highlightAuto(text).value
                        }
                    }
                },
                content: null,
            }
        },
        computed: {
            ...mapGetters(['user'])
        },
        created() {
            this.content = this.user.reportTemplate;
        },
        methods: {
            close() {
                this.$emit('update:visible', false);
            },
            save() {
                this.saving = true;

                return apiUsers
                    .update({reportTemplate: this.content})
                    .then(response => {
                        this.user.reportTemplate = this.content;
                        this.$store.commit('setUser', this.user);
                        this.handleMessage('success', response);
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
