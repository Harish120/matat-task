<template>
  <div class="q-pa-lg flex flex-center">
    <q-pagination
      v-model="currentPage"
      :max="lastPage"
      @input="updatePage"
      input
    />
  </div>
</template>

<script setup>
import { defineProps, defineEmits, ref, watch } from 'vue';

const props = defineProps({
  currentPage: {
    type: Number,
    required: true,
  },
  lastPage: {
    type: Number,
    required: true,
  },
});

const currentPage = props.currentPage
const lastPage = props.lastPage

const emit = defineEmits(['update:currentPage']);

// Emit page change to parent component
const updatePage = (page) => {
  emit('update:currentPage', page);
};
</script>
