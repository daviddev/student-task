<template>

    <el-menu
        :default-active="$route.path"
        class="app-sidebar"
        background-color="#545c64"
        text-color="#fff"
        active-text-color="#ffd04b"
        :router="true"
    >
        <el-menu-item index="/">
            <i class="el-icon-school"/> Students
        </el-menu-item>

        <el-menu-item index="/sessions">
            <i class="el-icon-date"/> Sessions
        </el-menu-item>

        <el-submenu index="3" class="user-name">
            <template slot="title">
                <i class="el-icon-user"/>
                {{ user.firstName }} {{ user.lastName }}
            </template>

            <el-menu-item index="3" @click="logout">
                <i class="el-icon-turn-off"/> Logout
            </el-menu-item>
        </el-submenu>
    </el-menu>

</template>

<script type="text/babel">
    import { mapGetters } from 'vuex';
    import apiAuth from '@/api/auth.js';
    import handleApiMixin from '@/mixins/handle-api-mixin.js';

    export default {
        name: 'app-sidebar',
        mixins: [handleApiMixin],
        computed: {
            ...mapGetters(['user'])
        },
        methods: {
            logout() {
                return apiAuth
                    .logout()
                    .then(response => {
                        this.$store.commit('setUser', null);
                        this.$store.commit('setToken', null);
                        this.$router.push({name: 'Login'});
                    })
                    .catch(response => {
                        this.handleMessage('error', response)
                    });
            }
        }
    }
</script>
