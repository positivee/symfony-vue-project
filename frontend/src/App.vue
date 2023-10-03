
<template>
  <AllForms :key="reloadKey" />

  <Form @created="forceRerender" />
</template>


<script setup lang="ts">
import Form from "./components/Form.vue";
import AllForms from "./components/AllFroms.vue";
import { ref } from 'vue';

const reloadKey = ref(0);

const forceRerender = () => {
  reloadKey.value += 1;
};

</script>

<style></style>
