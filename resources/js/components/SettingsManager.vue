<template>
    <div class="row">
        <div class="col-md-3">
            <h4 class=" text-uppercase text-black-50">
                Settings
            </h4>
            <div class="nav flex-column nav-pills mb-4">
                <a
                        v-for="group in groups"
                        :key="group.id"
                        @click="activeGroupId = group.id"
                        class="nav-link"
                        :href="`#${group.id}`"
                        :class="{ 'active' : activeGroupId === group.id }">
                    {{ group.name}}
                </a>

            </div>
        </div>
        <div class="col-md-9">

            <div class="card">
                <div class="card-header">{{ activeGroup.name}}</div>
                <div class="card-body">
                    <div v-for="field in activeGroup.fields" :key="field.full_key">
                        <component :is="field.component" :field="field" :languages="languages"
                                   :key="field.full_key"></component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import axios from 'axios'

    const files = require.context('./fields', true, /\.vue$/i);
    let fields = []
    files.keys().map(key => {
        let name = key.split('/').pop().split('.')[0];
        fields.push(name);
        Vue.component(name, files(key).default)
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
            }
        },
        watch: {
            async activeGroupId(val) {
                if (val) {
                    let response = await axios.get('/api/laravel-settings/groups/' + val);
                    this.activeGroup = response.data;
                } else {
                    this.activeGroup = {}
                }
            }
        },
        async created() {
            let response = await axios.get('/api/laravel-settings/groups');

            this.groups = response.data.groups;
            this.languages = response.data.languages;

            if (this.groups.length > 0)
                this.activeGroupId = this.groups[0].id;
        }
    }
</script>

<style scoped>

</style>