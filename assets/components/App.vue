<template>
  <div class="row" style="margin-top: 20px">
    <div class="col"></div>
    <ul class="list-group col">
      <li class="list-group-item">
        <input type="text" class="form-control" placeholder="Enter new task" v-on:keyup.enter="onEnterTask">
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-start" v-for="task in tasks">
        <span>{{ task.title }}</span>
        <div>
          <button type="button" class="btn btn-link">Edit</button>
          <button type="button" class="btn btn-link" v-on:click="onDeleteTask( task.id )">Delete</button>
        </div>
      </li>
    </ul>
    <div class="col"></div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      currentlyEditedTask: null,
      tasks: [
          { id: 1, title: "Task 1" },
          { id: 2, title: "Task 2" },
          { id: 3, title: "Task 3" },
      ]
    };
  },
  methods: {
    onDeleteTask: function( id ) {
      if(confirm("Continue deleting the task?")) {
        this.tasks = this.tasks.filter(task => task.id !== id);
      }
    },
    onEnterTask: function(event) {
      if( event ) {
        this.tasks.push({
          id: this.tasks.length + 1,
          title: event.currentTarget.value
        });

        event.currentTarget.value = "";
      }
    }
  }
};
</script>

<style>
</style>