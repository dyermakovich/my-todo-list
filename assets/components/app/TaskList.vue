<template>
  <ul class="list-group col">
    <li class="list-group-item">
      <task-list-new-item
          v-on:task-enter="onEnterTask"
          v-on:task-edition-stop="stopTaskEdition"
      ></task-list-new-item>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-start" v-for="task in sortedTasks">
      <task-list-item
          v-bind:task="task"
          v-bind:currently-edited-task="currentlyEditedTask"
          v-on:task-edition-stop="stopTaskEdition"
          v-on:task-save="onSaveTask"
          v-on:task-dblclick="onDblClickTask"
          v-on:task-delete="onDeleteTask"
          v-on:task-move="moveTask"
      ></task-list-item>
    </li>
  </ul>
</template>

<script>
import TaskListItem from "./TaskListItem";
import TaskListNewItem from "./TaskListNewItem";
import api from './../../api';

export default {
  name: "TaskList",
  components: {TaskListNewItem, TaskListItem},
  data() {
    return {
      currentlyEditedTask: null,
      tasks: []
    };
  },
  computed: {
    sortedTasks() {
      return this.tasks;
      /* return this.tasks.sort((a, b) => {
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
          return 1
              ;
        }
        return 0;
      }); */
    }
  },
  mounted() {
    this.loadTasks();
  },
  methods: {
    getTaskById: function(id) {
      return this.tasks.find(task => task.id === id);
    },
    getTaskByIndex: function(index) {
      if(index < 0 || index >= this.tasks.length) {
        return null;
      }
      return this.tasks[index];
    },
    getTaskIndex: function(task) {
      return this.tasks.indexOf(task);
    },
    /* getTaskByOrder: function(order) {
      return this.tasks.find(task => task.order === order);
    }, */
    moveTask: function(id, down = true) {
      const task1 = this.getTaskById(id);
      const index1 = this.getTaskIndex(task1);
      const index2 = index1 + ( down ? 1 : -1 );
      const task2 = this.getTaskByIndex(index2);

      if(task1 && task2){
        const aux = this.tasks[index2];
        this.tasks[index2] = this.tasks[index1];
        this.tasks[index1] = aux;
        api.moveTask(task1.id, task2.id).then(()=> this.loadTasks());
      }
    },
    /* moveTask: function(id, down = true) {
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
    }, */
    stopTaskEdition: function() {
      this.currentlyEditedTask = null;
    },
    onDblClickTask: function(id){
      this.currentlyEditedTask = id;
    },
    onSaveTask: function(id, event) {
      api.updateTask(id, event.currentTarget.value)
          .then((result) => {
            this.stopTaskEdition();
            this.loadTasks();
          });

    },
    onDeleteTask: function( id ) {
      if(confirm("Continue deleting the task?")) {
        api.deleteTask(id).then((result) => {
          if(result){
            this.loadTasks();
          }
        });
      }
    },
    loadTasks: function() {
      api.findTasks().then(tasks => this.tasks = tasks);
    },
    getNextGravity: function(){
      return this.tasks.reduce((pv, cv) => {
        return Math.max(pv, cv.gravity);
      }, 0) + 1;
    },
    onEnterTask: function(event) {
      if( event ) {
        let input = event.currentTarget;
        api.addTask(this.getNextGravity(), input.value).then(() => {
          this.loadTasks();
          input.value = "";
        });
      }
    }
  }
}
</script>

<style scoped>

</style>