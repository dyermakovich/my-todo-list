<template>
  <template v-if="currentlyEditedTask === task.id">
    <input type="text" class="form-control" placeholder="Enter task title"
           v-bind:value="task.title"
           v-on:change="$emit('task-save', task.id, $event)"
           v-on:focusout="$emit('task-edition-stop')">
  </template>
  <template v-else>
    <span v-on:dblclick="$emit('task-dblclick', task.id)" class="task-title">{{ task.title }}</span>
    <div>
      <button type="button" class="btn btn-link" v-on:click="$emit('task-dblclick', task.id)">Edit</button>
      <button type="button" class="btn btn-link" v-on:click="$emit('task-delete', task.id)">Delete</button>
      <button type="button" class="btn btn-link" v-on:click="$emit('task-move', task.id, false)">Up</button>
      <button type="button" class="btn btn-link" v-on:click="$emit('task-move', task.id, true)">Down</button>
    </div>
  </template>
</template>

<script>
export default {
  name: "TaskListItem",
  emits: ["task-save", "task-edition-stop", "task-dblclick", "task-delete", "task-move" ],
  props: {
    task: Object,
    currentlyEditedTask: Number
  }
}
</script>

<style scoped>
  span {
    cursor: pointer;
  }
</style>