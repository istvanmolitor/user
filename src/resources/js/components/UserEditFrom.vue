<template>
    <admin-form :id="id" base-url="/api/user">
        <checkbox-form-field name="is_banned" label="Kitiltva"></checkbox-form-field>
        <input-form-field type="email" name="email" label="E-mail" :disabled="id!==null"></input-form-field>
        <input-form-field name="name" label="Név"></input-form-field>
        <input-form-field type="password" name="password" label="Jelszó" v-if="id===null"></input-form-field>
        <input-form-field type="password" name="password_confirm" label="Jelszó ismét"
                          v-if="id===null"></input-form-field>
        <user-group-form-field name="user_group_ids" label="Felhasználói csoportok"></user-group-form-field>
        <form-button label="Mentés"></form-button>
    </admin-form>
</template>

<script>


import AdminForm from "../../../../../admin/src/resources/js/components/form/AdminForm.vue";
import InputFormField from "../../../../../admin/src/resources/js/components/form/InputFormField.vue";
import CheckboxFormField from "../../../../../admin/src/resources/js/components/form/CheckboxFormField.vue";
import UserGroupFormField from "./UserGroupFormField.vue";
import FormButton from "../../../../../admin/src/resources/js/components/form/FormButton.vue";

export default {
    name: "UserEditFrom",
    components: {
        AdminForm,
        InputFormField,
        CheckboxFormField,
        UserGroupFormField,
        FormButton
    },
    props: {
        id: {
            type: Number,
            require: false,
            default: null
        }
    }
}
</script>

<style scoped>

</style>
