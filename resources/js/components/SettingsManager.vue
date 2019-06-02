<template>
  <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-3 col-xl-2">
      <div class="card mb-2">
        <div class="card-body">
          <h5 class="card-title text-uppercase text-black-50">Settings</h5>
          <ul class="nav flex-column">
            <li class="nav-item mb-1" v-for="group in groups" :key="group.id">
              <a
                @click="activeGroupId = group.id"
                :href="`#${group.id}`"
                :class="{ 'text-primary' : activeGroupId === group.id , 'text-muted' : activeGroupId !== group.id }"
              >{{ group.name}}</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-9 col-xl-10">
      <div class="card">
        <div class="card-header">{{ activeGroup.name}}</div>
        <div class="card-body">
          <div v-for="field in activeGroup.fields" :key="field.full_key">
            <component
              :is="field.component"
              :field="field"
              :languages="languages"
              :key="field.full_key"
            ></component>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import axios from "axios";

const files = require.context("./fields", true, /\.vue$/i);
let fields = [];
files.keys().map(key => {
  let name = key
    .split("/")
    .pop()
    .split(".")[0];
  fields.push(name);
  Vue.component(name, files(key).default);
});

export default {
  name: "SettingsManager",
  data() {
    return {
      availableFields: fields,
      languages: [],
      groups: [],
      activeGroupId: null,
      activeGroup: {}
    };
  },
  watch: {
    async activeGroupId(val) {
      if (val) {
        let response = await axios.get("/api/laravel-settings/groups/" + val);
        this.activeGroup = response.data;
      } else {
        this.activeGroup = {};
      }
    }
  },
  async created() {
    let response = await axios.get("/api/laravel-settings/groups");

    this.groups = response.data.groups;
    this.languages = response.data.languages;

    if (this.groups.length > 0) this.activeGroupId = this.groups[0].id;
  }
};
</script>

<style scoped>
</style>