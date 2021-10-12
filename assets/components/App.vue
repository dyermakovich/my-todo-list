<template>
  <div class="row" style="margin-top: 20px">
    <div class="col"></div>
    <ul class="list-group col">
      <li class="list-group-item">
        <input type="text" class="form-control" placeholder="Enter new task"
           v-on:keyup.enter="onEnterTask"
           v-on:focusin="stopTaskEdition">
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-start" v-for="task in sortedTasks">
        <template v-if="currentlyEditedTask === task.id">
          <input type="text" class="form-control" placeholder="Enter task title"
                 v-bind:value="task.title"
                 v-on:change="onSaveTask(task.id, $event)"
                 v-on:focusout="stopTaskEdition">
        </template>
        <template v-else>
          <span v-on:dblclick="onDblClickTask(task.id)" style="cursor: pointer">{{ task.title }}</span>
          <div>
            <button type="button" class="btn btn-link" v-on:click="onDblClickTask(task.id)">Edit</button>
            <button type="button" class="btn btn-link" v-on:click="onDeleteTask(task.id)">Delete</button>
            <button type="button" class="btn btn-link" v-on:click="moveTask(task.id, false)">Up</button>
            <button type="button" class="btn btn-link" v-on:click="moveTask(task.id, true)">Down</button>
          </div>
        </template>
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
  computed: {
    sortedTasks() {
      return this.tasks.sort((a, b) => {
        if(a.order === undefined) {
          a.order = a.id;
        }
        if(b.order === undefined) {
          b.order = b.id;
        }
        if(a.order < b.order) {
          return -1;
        }
        if(a.order > b.order) {
          return 1;
        }
        return 0;
      });
    }
  },
  methods: {
    getTaskById: function(id) {
      return this.tasks.find(task => task.id === id);
    },
    getTaskByOrder: function(order) {
      return this.tasks.find(task => task.order === order);
    },
    moveTask: function(id, down = true) {
      const task = this.getTaskById(id);

      if(task){
        const nextTaskOrder = down ? task.order + 1 : task.order - 1;
        const nextTask = this.getTaskByOrder(nextTaskOrder);

        if(nextTask) {
          let aux = nextTask.order;
          nextTask.order = task.order;
          task.order = aux;
        }
      }
    },
    stopTaskEdition: function() {
      this.currentlyEditedTask = null;
    },
    onDblClickTask: function(id){
      this.currentlyEditedTask = id;
    },
    onSaveTask: function(id, event) {
      const task = this.getTaskById(id);

      if( task && event ) {
        task.title = event.currentTarget.value;
      }

      this.stopTaskEdition();
    },
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