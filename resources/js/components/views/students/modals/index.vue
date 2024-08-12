<template>

    <div>
        <add-student-modal
            v-if="addStudentModal"
            :visible.sync="addStudentModal"
            @saved="$emit('refresh')"
        />
    </div>

</template>

<script type="text/babel">
    import AddStudentModal from './add-student-modal';

    export default {
        components: {
            AddStudentModal
        },
        data() {
            return {
                addStudentModal: false,
            }
        },
        methods: {
            showAddStudentModal(bool = true) {
                this.addStudentModal = bool;
            },
        }
    }
</script>
