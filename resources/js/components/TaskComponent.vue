<template>
    <div class="row">
        <div class="col-md-3 mb-5 ml-auto">
            <button @click="createModal" class="btn btn-primary btn-block">Add New Task</button>
        </div>
        <div class="col-md-12">
            <div class="row border bg-primary text-white text-center">
                <div class="col-md-1 p-3 border-right">id</div>
                <div class="col-md-3 p-3 border-right">Name</div>
                <div class="col-md-4 p-3 border-right">Body</div>
                <div class="col-md-2 p-3 border-right"></div>
                <div class="col-md-2 p-3"></div>
            </div>
            <div class="row border text-center" v-for="(task, index) in tasks" :key="index">
                <div class="col-md-1 border-right pt-2">{{ index +1 }}</div>
                <div class="col-md-3 border-right pt-2">{{ task.name }}</div>
                <div class="col-md-4 border-right pt-2">{{ task.body }}</div>
                <div class="col-md-2 border-right">
                    <button class="btn btn-warning" @click="updateModal(index)">Edit</button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger" @click="deleteTask(index)">Delete</button>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" id="createTaskModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="bg-info text-white shadow rounded p-3">
                        <div class="row">
                            <div class="col-md-11">
                                <h5 class="text-center">Create New Task</h5>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-6 mx-auto" v-if="errors.length > 0">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-danger" v-for="(error, index) in errors" :key="index">{{ error }}</li>
                                </ul>
                        </div>
                        <div class="form-group">
                          <label for="name">Name</label>
                          <input type="text" v-model="task.name" name="name" id="name" class="form-control" placeholder="Name of the task">
                        </div>
                        <div class="form-group">
                          <label for="description">Description</label>
                          <input type="text" v-model="task.body" name="description" id="description" class="form-control" placeholder="Description of the task">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" @click="updateTask" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="updateTaskModalLabel" id="updateTaskModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="bg-info text-white shadow rounded p-3">
                        <div class="row">
                            <div class="col-md-11">
                                <h5 class="text-center">Update Task</h5>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-6 mx-auto" v-if="errors.length > 0">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-danger" v-for="(error, index) in errors" :key="index">{{ error }}</li>
                                </ul>
                        </div>
                        <div class="form-group">
                          <label for="name">Name</label>
                          <input type="text" v-model="arrayUpdateTask.name" name="name" id="updateName" class="form-control" placeholder="Name of the task">
                        </div>
                        <div class="form-group">
                          <label for="description">Description</label>
                          <input type="text" v-model="arrayUpdateTask.body" name="description" id="updateDescription" class="form-control" placeholder="Description of the task">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" @click="updateTask" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                task:{
                    name: '',
                    body: ''
                },
                tasks: [],
                errors: [],
                uri: '/tasks',
                arrayUpdateTask: []
            }
        },
        methods: {
            createModal(){
                $('#createTaskModal').modal('show');
            },

            updateModal(index){
                this.errors = [];
                $('#updateTaskModal').modal('show');
                this.arrayUpdateTask = this.tasks[index];
            },
            createTask(){
                axios.post(this.uri, {name: this.task.name, body: this.task.body}).then(response => {
                    this.tasks.push(response.data.task);
                    $('#createTaskModal').modal('hide');
                }).catch(error => {
                    this.errors = [];
                    if(error.response.data.errors.name){
                        this.errors.push(error.response.data.errors.name[0]);
                    }

                    if(error.response.data.errors.body){
                        this.errors.push(error.response.data.errors.body[0]);
                    }

                });
            },
            updateTask(){
                axios.patch(`${this.uri}/${this.arrayUpdateTask.id}`, { name: this.arrayUpdateTask.name, body: this.arrayUpdateTask.body }).then(response => {
                    $('#updateTaskModal').modal('hide');
                }).catch(error => {
                    this.errors = [];
                    if(error.response.data.errors.name){
                        this.errors.push(error.response.data.errors.name[0]);
                    }

                    if(error.response.data.errors.body){
                        this.errors.push(error.response.data.errors.body[0]);
                    }
                });
            },
            deleteTask(index){
                let confirmBox = confirm('Are you sure?');
                if(confirmBox){
                    axios.delete(`${this.uri}/${this.tasks[index].id}`).then(response => {
                        this.$delete(this.tasks, index);
                    }).catch(error => {
                        console.error('Could not delete for some reason');
                    });
                }
            },
            loadTasks() {
                axios.get(this.uri).then(response => {
                    this.tasks = response.data.tasks;
                });
            }
        },
        mounted() {
            this.loadTasks();
        }
    }
</script>
