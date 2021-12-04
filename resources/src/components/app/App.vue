<template>
  <div class="container">
    <ContentSearch />
    <ContentSearchBody />
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import ContentSearchBody from "../content-search-body/ContentSearchBody.vue";
import ContentSearch from "../content-search/ContentSearch.vue";

export default defineComponent({
    components: {
        ContentSearchBody,
        ContentSearch
      },
    mounted(): void{}
});


</script>
<style lang="scss" src="./style.scss"></style>
