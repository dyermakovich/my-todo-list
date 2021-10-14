import axios from 'axios';

const baseUrl = '/api';

function onErrorResponse(error) {
    console.log(error);
};

function getPathNormalized(path){
    if(typeof(path) === 'string') {
        if(path[0] !== '/') {
            path = '/' + path;
        }
        return path;
    }
    return '';
};

function fetchCollection(path) {
    return axios.get(baseUrl + getPathNormalized(path))
        .then(response => {
            debugger;
            if (response.status === 200 && response.data && response.data['hydra:member'] ) {
                return response.data['hydra:member'];
            }
            return [];
        }).catch(error => {
            onErrorResponse(error);
            return [];
        });
};

function addEntity(path, data) {
    return axios.post(baseUrl + getPathNormalized(path), data)
        .then(response => {
            if (response.status === 201 && response.data && response.data.id !== undefined) {
                return response.data.id;
            }
            return null;
        }).catch(error => {
            onErrorResponse(error);
            return null;
        });
};

function deleteEntity(path, id) {
    return axios.delete(baseUrl + getPathNormalized(path) + '/' + id)
        .then(response => {
            if(response.status === 204) {
                return true;
            }
            return false;
        }).catch(error => {
            onErrorResponse(error);
            return false;
        });
};

function updateEntity(path, id, data) {
    return axios.put(baseUrl + getPathNormalized(path) + '/' + id, data)
        .then(response => {
            if(response.status === 200) {
                return true;
            }
            return false;
        }).catch(error => {
            onErrorResponse(error);
            return false;
        });
};

export default {
    findTasks() {
        return fetchCollection('tasks');
    },
    addTask(title, description = null ) {
        return addEntity('tasks', {title: title, description: description});
    },
    deleteTask(id) {
        return deleteEntity('tasks', id);
    },
    updateTask(id, title, description = null){
        return updateEntity('tasks', id, {
            title: title, description: description
        });
    }
}

