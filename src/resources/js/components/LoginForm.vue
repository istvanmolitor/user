<template>
  <simple-form method="post" action-url="/login" :values="formValues">
    <input-form-field type="email" name="email" label="E-mail"></input-form-field>
    <input-form-field type="password" name="password" label="Jelszó"></input-form-field>
    <form-button label="Bejelentkezés"></form-button>
  </simple-form>
</template>

<script>

import SimpleForm from "../../../../../admin/src/resources/js/components/form/SimpleForm.vue";
import InputFormField from "../../../../../admin/src/resources/js/components/form/InputFormField.vue";
import FormButton from "../../../../../admin/src/resources/js/components/form/FormButton.vue";

export default {
  name: "LoginForm",
  components: {
    SimpleForm,
    InputFormField,
    FormButton
  },
  data() {
    return {
      'formValues': {
        'email': '',
        'password': '',
      }
    };
  }
}
</script>

<style scoped>

</style>
