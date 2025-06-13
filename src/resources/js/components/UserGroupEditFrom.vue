<template>
  <admin-form :id="id" base-url="/api/user-group" :values="formValues" :init-data="formData">
    <input-form-field name="name" label="Név"></input-form-field>
    <text-form-field name="description" label="Leírás"></text-form-field>
    <permission-form-field name="permission_ids" label="Jogosultságok"></permission-form-field>
    <form-button label="Mentés"></form-button>
  </admin-form>
</template>

<script>


import AdminForm from "../../../../../admin/src/resources/js/components/form/AdminForm.vue";
import InputFormField from "../../../../../admin/src/resources/js/components/form/InputFormField.vue";
import TextFormField from "../../../../../admin/src/resources/js/components/form/TextFormField.vue";
import PermissionFormField from "./PermissionFormField.vue";
import FormButton from "../../../../../admin/src/resources/js/components/form/FormButton.vue";

export default {
  name: "UserGroupEditFrom",
  components: {
    AdminForm,
    InputFormField,
    TextFormField,
    PermissionFormField,
    FormButton
  },
  props: {
    id: {
      type: Number,
      require: false,
      default: null
    }
  },
  data() {
    return {
      'formValues': {
        'name': '',
        'description': '',
        'permission_ids': []
      },
      'formData': {
        permissions: []
      }
    }
  }
}
</script>

<style scoped>

</style>
