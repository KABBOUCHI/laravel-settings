<template>
  <div class="form-group mb-4">
    <div class="d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center" style="flex:1">
        <h4 class="mr-2">{{ field.name }}</h4>
        <small class="mr-2" v-if="field.translatable">🌐</small>

        <small
          class="text-muted text-monospace text-indigo"
          style="font-size:12px"
        >setting('{{ field.full_key }}')</small>
      </div>
      <div class="d-flex align-items-center">
        <nav class="nav justify-content-end align-items-baseline mr-2" v-if="field.translatable">
          <a
            class="nav-link"
            style="padding: 0.25rem .5rem;"
            href="#"
            :class="{ 'text-muted' : activeLang !== key }"
            v-for="(lang, key) in languages"
            @click.prevent="activeLang = key"
          >{{ lang }}</a>
        </nav>

        <button class="btn btn-outline-primary btn-sm" @click="save">Save</button>
      </div>
    </div>
    <p v-if="field.meta.helpText" class="text-muted">{{ field.meta.helpText }}</p>
    <div class="d-flex">
      <input
        type="file"
        class="form-control"
        style="max-height : 100%"
        @change="(e) => this.value[this.activeLang] = e.target.files[0]"
      >
    </div>
  </div>
</template>

<script>
export default {
  props: ["field", "languages"],
  name: "FileField",
  data() {
    return {
      value: {},
      meta: this.field.meta || {},
      activeLang: "en"
    };
  },
  created() {
    this.value = {
      en: null
    };
  },
  methods: {
    save() {
      let data = new FormData();
      data.append("meta", this.meta);

      console.log(this.value["en"]);

      for (let lang in this.value)
        if (this.value[lang]) data.set(`value[${lang}]`, this.value[lang]);

      axios.post("/api/laravel-settings/settings/" + this.field.full_key, data);
    }
  }
};
</script>

<style scoped>
</style>