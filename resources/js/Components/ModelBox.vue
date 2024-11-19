<template>

<!-- Trigger/Open The Modal -->
<button id="myBtn" @click="openModal">{{ buttonText }}</button>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header text-center">
      <span class="close" @click="closeModal">&times;</span>
      <div class="row w-100">
        <div class="col text-center">
            <h2 class="text-light mt-2" >Campaign Edit</h2>
        </div>
      </div>
    </div>


    <div class="modal-body mt-3 mb-3">
        <form>
            <div class="form-group row mb-3">
                <label for="name" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" id="name" placeholder="Enter Campaign Name here" v-model="editCampaignFormData.name" >
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="description" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" id="description" placeholder="Enter Description here" v-model="editCampaignFormData.description" >
                </div>
            </div>
            <div class="form-group row mb-3">
                <div class="col-sm-10">
                <button type="submit" class="btn btn-primary" @click.prevent="updateCampaign">Update</button>
                </div>
            </div>
        </form>
    </div>



  </div>

</div>



</template>





<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const props = defineProps({
    buttonText: {
        type: String,
        required: true,
    },
    editData:{
        type: Object,
        required: true
    },
    isModalOpen: {
        type: Boolean
    }
});

const editCampaignFormData = ref({
    name: '',
    description: ''
});

onMounted(() => {
    console.log(props.editData);
    editCampaignFormData.value.name = props.editData.name;
    editCampaignFormData.value.description = props.editData.description;
    if(props.editData){
        document.getElementById('myModal').style.display = 'block';
    }
});



/** submit form */
const updateCampaign = () => {
    console.log(editCampaignFormData.value);
}


const openModal = () => {
    document.getElementById('myModal').style.display = 'block';
    console.log('edit Data', props.editData);
}
const closeModal = () => {
    document.getElementById('myModal').style.display = 'none';
}



</script>


<style scoped>
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  padding: 0;
  border: 1px solid #888;
  width: 40%;
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
  -webkit-animation-name: animatetop;
  -webkit-animation-duration: 0.4s;
  animation-name: animatetop;
  animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:0; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:0; opacity:1}
}

/* The Close Button */
.close {
  color: white;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.modal-header {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}
p,h2,label{
    color: #000;
}
</style>
