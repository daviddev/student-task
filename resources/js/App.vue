<template>

    <div class="main" :class="{'with-sidebar': sidebarIsVisible}">
        <app-sidebar v-if="sidebarIsVisible"/>
        <div class="main__content">
            <router-view />
        </div>
    </div>

</template>

<script type="text/babel">
    import { mapGetters } from 'vuex';
    import AppSidebar from '@/components/layout/Sidebar.vue';

    export default {
        components: { AppSidebar },
        computed: {
            ...mapGetters(['user']),
            sidebarIsVisible() {
                return this.user && this.$route.name !== 'Login';
            },
        },
    }
</script>
