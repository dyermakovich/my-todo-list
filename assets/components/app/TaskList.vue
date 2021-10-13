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
import axios from "axios";

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
  mounted() {
    this.loadTasks();
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
    getNewId: function() {
      let result = null;

      for(let i = 0; i < this.tasks.length; ++i) {
        let task = this.tasks[i];
        if( result === null || task.id > result ) {
          result = task.id;
        }
      }

      return result === null ? 1 : result + 1 ;
    },
    loadTasks: function() {
      let self = this;
      axios.get('/api/tasks').then(function(response){
        console.log(response);

        if(response.status === 200 && response.data) {
          self.tasks = response.data['hydra:member'];
        }

      }).catch(function(error){
        console.log(error);
      })
    },
    onEnterTask: function(event) {
      if( event ) {
        /* this.tasks.push({
          id: this.getNewId(),
          title: event.currentTarget.value
        }); */

        let self = this;

        axios.post('/api/tasks', {
          title: event.currentTarget.value
        }).then(function(response){
          if(response.status === 201){
            self.loadTasks();
          }
        }).catch(function(error){
          console.log(error);
        });

        event.currentTarget.value = "";
      }
    }
  }
}
</script>

<style scoped>

</style>