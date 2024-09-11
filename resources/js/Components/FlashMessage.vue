<script setup>
import { ref, onMounted } from 'vue';

defineProps({
  message: String,
  type: String
});

const isVisible = ref(false);

console.log("message: ", message);

const hideMessage = () => {
  isVisible.value = false;
}

onMounted(() => {
  isVisible.value = true;
});
</script>

<template>
    <div>Heeeey</div>
    <div v-if="isVisible" :class="['flash-message', `flash-${type}`]">
      <div> {{ message }} </div>
      <button @click="hideMessage">&times;</button>
    </div>
</template>

<style scoped>
.flash-message {
  font-size: .95em;
  width: 600px;
  color: #fff;
  padding: 1rem;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  gap: 10px;
  margin-bottom: 10px;
  align-items: center;
}

.flash-success {
  background-color: #4caf50; 
}

.flash-error,
.flash-danger {
  background-color: #ff5252; 
}

.flash-warning {
  background-color: #ffab40; 
}
</style>