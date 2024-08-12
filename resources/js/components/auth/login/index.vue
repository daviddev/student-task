<template>

    <el-card shadow="always" class="login" :element-loading-text="loadingText" v-loading="loading">
        <span slot="header" class="title">
            Login
        </span>

        <el-form
            ref="form"
            status-icon label-position="top"
            :model="user"
            :rules="rules"
        >
            <el-form-item label="Email" prop="email">
                <el-input placeholder="Email" v-model="user.email"/>
            </el-form-item>

            <el-form-item label="Password" prop="password">
                <el-input placeholder="Password" type="password" autocomplete="off" v-model="user.password"/>
            </el-form-item>
        </el-form>

        <el-button type="primary" @click="validate">
            Log In
        </el-button>
    </el-card>

</template>

<script type="text/babel">
    import apiAuth from '@/api/auth.js';
    import handleApiMixin from '@/mixins/handle-api-mixin.js';

    import validation from './validation.js';

    export default {
        name: 'login',
        mixins: [handleApiMixin],
        data() {
            return {
                loading: false,
                loadingText: 'Authenticating...',
                user: {}
            }
        },
        computed: {
            rules() {
                return validation;
            }
        },
        methods: {
            validate() {
                return this.$refs.form.validate()
                    .then(() => this.login())
                    .catch(() => {});
            },
            login() {
                this.loading = true;

                return apiAuth
                    .login(this.user)
                    .then(response => {
                        this.$store.commit('setUser', response.data.data.user);
                        this.$store.commit('setToken', response.data.data.token);
                        this.$router.push({name: 'Students'});
                    })
                    .catch(response => {
                        this.handleMessage('error', response);
                        this.loading = false;
                    });
            }
        }
    }
</script>
