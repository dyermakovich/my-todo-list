<template>
  <input type="text" class="form-control" placeholder="Enter new task"
         v-on:keyup.enter="$emit('task-enter', $event)"
         v-on:focusin="$emit('task-edition-stop')"
  >
</template>

<script>
export default {
  name: "TaskListNewItem",
  emits: ["task-enter", "task-edition-stop"]
}
</script>

<style scoped>
</style>