<template>
  <div class="mb-3" v-if="this.hasParentFormData(dataName)">
    <fieldset>
      <legend>{{ label }}</legend>
      <div class="row">
        <div v-for="permission in this.getParentFormData(dataName)" class="col-4">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" :id="'permission_' + permission.id"
                   v-model="permission.value" @change="updateFieldValue">
            <label class="form-check-label" :for="'permission_' + permission.id">{{ permission.name }}</label>
          </div>
        </div>
      </div>
    </fieldset>
  </div>
</template>

<script>

import FormFieldMixin from "../../../../../admin/src/resources/js/mixins/FormFieldMixin";

export default {
  name: "UserGroupFormField",
  mixins: [FormFieldMixin],
  props: {
    dataName: {
      type: String,
      default: 'permissions',
      required: false
    }
  },
  mounted() {
    this.updateFieldValue();
  },
  methods: {
    getPermissionIds() {
      let data = this.getParentFormData(this.dataName);
      if (data === null) {
        return [];
      }
      return data.filter(permission => {
        return permission.value;
      }).map(permission => {
        return permission.id;
      });
    },
    updateFieldValue() {
      this.setParentFormValue(this.name, this.getPermissionIds());
    }
  }
}
</script>

<style scoped>

</style>
