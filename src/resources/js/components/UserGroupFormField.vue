<template>
  <div>
    <div class="mb-3" v-if="this.hasParentFormData(dataName)">
      <fieldset>
        <legend>{{ label }}</legend>
        <div class="row">
          <div v-for="userGroup in this.getParentFormData(dataName)" class="col-4">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" :id="'user_group_' + userGroup.id"
                     v-model="userGroup.value" @change="updateFieldValue">
              <label class="form-check-label" :for="'user_group_' + userGroup.id">{{ userGroup.name }}</label>
            </div>
          </div>
        </div>
      </fieldset>
    </div>
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
      default: 'userGroups',
      required: false
    }
  },
  mounted() {
    this.updateFieldValue();
  },
  methods: {
    getUserGroupIds() {
      let data = this.getParentFormData(this.dataName);
      if (data === null) {
        return [];
      }
      return data.filter(userGroup => {
        return userGroup.value;
      }).map(userGroup => {
        return userGroup.id;
      });
    },
    updateFieldValue() {
      this.setParentFormValue(this.name, this.getUserGroupIds());
    }
  }
}
</script>

<style scoped>

</style>
