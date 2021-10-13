<template>
  <div class="row" style="margin-top: 20px">
    <div class="col"></div>
    <div class="col"><task-list></task-list></div>
    <div class="col"></div>
  </div>
</template>

<script>
import TaskList from "./app/TaskList";
export default {
  components: {TaskList}
};
</script>

<style>
</style>