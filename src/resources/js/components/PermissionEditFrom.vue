<template>
  <admin-form :id="id" base-url="/api/permission">
    <input-form-field name="name" label="Név"></input-form-field>
    <text-form-field name="description" label="Leírás"></text-form-field>
    <user-group-form-field name="user_group_ids" label="Felhasználói csoportok"></user-group-form-field>
    <form-button label="Mentés"></form-button>
  </admin-form>
</template>

<script>

import AdminForm from "../../../../../admin/src/resources/js/components/form/AdminForm.vue";
import InputFormField from "../../../../../admin/src/resources/js/components/form/InputFormField.vue";
import TextFormField from "../../../../../admin/src/resources/js/components/form/TextFormField.vue";
import UserGroupFormField from "./UserGroupFormField.vue";
import FormButton from "../../../../../admin/src/resources/js/components/form/FormButton.vue";

export default {
  name: "PermissionEditFrom",
  components: {
    AdminForm,
    InputFormField,
    TextFormField,
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
