<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';

import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css'; // You can change the theme as needed

import Swal from 'sweetalert2';

defineProps({
    // settingsArray: {
    //     type: Array,
    //     default: () => [],
    // },
    // settingsObject: {
    //     type: Object,
    //     default: () => ({}),
    // },
    settingsString: {
        type: String,
        default: '',
    },
    is_page: {
        type: String,
        default: 'clickbank'
    }
});


let loader = ref(false);



onMounted(() => {
      console.log('mounted');
      getClickBankData();
      getActiveCampaignData();
});





// Use the toast plugin
const toast = useToast();
const showToast = (type='success',msg) => {
    // Show a toast notification
    toast.open({
        message: msg,
        type: type, // You can use other types like info, error, warning
        duration: 3000,  // Auto-hide after 3 seconds
        position: 'top-right', // Toast position
    });
};





// click bank error var
const clickbank_errors = ref({});
const activecampaign_errors = ref({});



// click bank form fields
const ClickBankFormData = ref({
    clickbank_apiurl: [''],
    clickbank_captain_key: [''],
    type: 'clickbank',
    clickbank_id: [],
});





// click bank form fields
const ActiveCampaignFormData = ref({
    activecampaign_apiurl: [''],
    activecampaign_apikey: [''],
    activecampaign_list_id: [''],
    type: 'activecampaign',
    activecampaign_id: [],
});





// Clear previous errors
clickbank_errors.value = {
    clickbank_apiurl: [],
    clickbank_captain_key: []
};

// active
activecampaign_errors.value = {
    activecampaign_apiurl: [],
    activecampaign_apikey: [],
    activecampaign_list_id: []
}
// Validate form fields
let hasErrors = false;




const insertSettings = async (errors,formData) => {
    try {
        const response = await axios.post('/api/v1/add-setting', formData.value);
        console.log('Data saved:', response.data);
        errors.value = {};
        showToast('success',`Add ${formData.value.type} Setting saved successfully`);
    } catch (error) {
        console.error('Error saving data:', error.response.data);
        errors.value = error.response.data.errors;
        showToast('error',"Somthing wen't wrong!");
    } finally {
        //loading = false; // Set loading to false after the request is complete
    }
}


// click bank insert data
const ClickBankSubmitForm = async () => {
    insertSettings(clickbank_errors,ClickBankFormData);
};



// active campaign insert data
const ActiveCampaignSubmitForm = () =>{
    insertSettings(activecampaign_errors,ActiveCampaignFormData)
}




// click bank add more
const addMoreFieldsClickBank = () => {
    ClickBankFormData.value.clickbank_apiurl.push('');
    ClickBankFormData.value.clickbank_captain_key.push('');
};

// active campaign add more fields
const addMoreFieldsActiveCampaign = () =>{
    ActiveCampaignFormData.value.activecampaign_apiurl.push('');
    ActiveCampaignFormData.value.activecampaign_apikey.push('');
    ActiveCampaignFormData.value.activecampaign_list_id.push('');
}





const deleteConfirmation = async () =>{
    const result = await Swal.fire({
            title: 'Are you sure?',
            text: 'This action will permanently delete the setting!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
        });
    return result.isConfirmed;
}




// remove fields
const removeFieldSet = async (index, clickbank_id) => {
        ClickBankFormData.value.clickbank_apiurl.splice(index, 1);
        ClickBankFormData.value.clickbank_captain_key.splice(index, 1);
    try {
        if (await deleteConfirmation()) {
            const response = await axios.post('/api/v1/delete-setting', { id: clickbank_id });
            console.log('Data deleted:', response.data);
            clickbank_errors.value = {};
            ClickBankFormData.value.clickbank_apiurl.splice(index, 1);
            ClickBankFormData.value.clickbank_captain_key.splice(index, 1);
            ClickBankFormData.value.clickbank_id.splice(index, 1);
            showToast('success', 'Delete ClickBank Setting successfully');
        }
    } catch (error) {
        console.error('Error deleting data:', error.response);
        // clickbank_errors.value = error.response.data.errors;
        showToast('error', "Something went wrong!");
    }
};


// active campaign remove fields
const removeFieldSetActiveCampaign = async (index, id) => {
    try {
        if (await deleteConfirmation()) {
            const response = await axios.post('/api/v1/delete-setting', { id: id });
            console.log('Data deleted:', response.data);
            clickbank_errors.value = {};
            ActiveCampaignFormData.value.activecampaign_apiurl.splice(index, 1);
            ActiveCampaignFormData.value.activecampaign_apikey.splice(index, 1);
            ActiveCampaignFormData.value.activecampaign_list_id.splice(index, 1);
            showToast('success', 'Delete Active Campaign Setting successfully');
        }
    } catch (error) {
        console.error('Error deleting data:', error.response);
        // clickbank_errors.value = error.response.data.errors;
        showToast('error', "Something went wrong!");
    }
};




// get clickbank data
const getClickBankData = async () => {
    try {
        const response = await axios.get('/api/v1/clickbank/get-settings');
        // Assuming response.data.data is an array of multiple settings
        if (response.data.data && Array.isArray(response.data.data)) {
          // Reset form data in case there's existing data
          ClickBankFormData.value.clickbank_apiurl = [];
          ClickBankFormData.value.clickbank_captain_key = [];

          // Populate form fields with API response
          response.data.data.forEach((item, index) => {
            ClickBankFormData.value.clickbank_apiurl.push(item.url);
            ClickBankFormData.value.clickbank_captain_key.push(item.key);
            ClickBankFormData.value.clickbank_id.push(item.id);
          });
        }
        // console.log('Data fetched successfully:', response.data.data);

    }catch(error){
        console.log('Error getting data',error);
    }
}


// get active campaign data
const getActiveCampaignData = async () => {
    try {
        const response = await axios.get('/api/v1/activecampaign/get-settings');
        // Assuming response.data.data is an array of multiple settings
        if (response.data.data && Array.isArray(response.data.data)) {
          // Reset form data in case there's existing data
          ActiveCampaignFormData.value.activecampaign_apiurl = [];
          ActiveCampaignFormData.value.activecampaign_apikey = [];
          ActiveCampaignFormData.value.activecampaign_list_id = [];

          // Populate form fields with API response
          response.data.data.forEach((item, index) => {

            ActiveCampaignFormData.value.activecampaign_apiurl.push(item.url);
            ActiveCampaignFormData.value.activecampaign_apikey.push(item.key);
            ActiveCampaignFormData.value.activecampaign_list_id.push(item.list_id);
            ActiveCampaignFormData.value.activecampaign_id.push(item.id);
          });
        }
        console.log('Data fetched active campaign successfully:', response.data.data);
    }catch(error){
        console.log('Error getting data',error);
    }
}



</script>
<template>
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div v-if="loading" class="loader">Loading...</div>
        <div
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
        >
            <div class="p-6 gap-3">

                <div class="row">

                    <!-- click bank -->
                    <div class="col" v-if="is_page == 'clickbank'">
                        <div class="row mb-3">
                            <div class="col text-right">
                                <button @click.prevent="addMoreFieldsClickBank" class="btn btn-primary clickbank_add_more">Add More</button>
                            </div>
                        </div>
                        <form @submit.prevent="ClickBankSubmitForm" id="clickbank_form">
                            <div v-for="(field, index) in ClickBankFormData.clickbank_apiurl" :key="index" class="form-group mb-3">
                                <input type="hidden" >
                                <label :for="'clickbank-apiurl-' + index">API URL {{ index + 1 }}</label>
                                <input type="text" class="form-control mb-3"
                                    v-model="ClickBankFormData.clickbank_apiurl[index]"
                                    :name="'clickbank_apiurl[' + index + ']'"
                                    :id="'clickbank-apiurl-' + index"
                                    placeholder="Enter API URL">

                                    <!-- <div class="errors">
                                        <small v-if="clickbank_errors.clickbank_apiurl" class="form-text text-danger">
                                            {{ clickbank_errors.clickbank_apiurl[index] }}
                                        </small>
                                    </div> -->

                                <label :for="'clickbank-captain-key-' + index">CLICKBANK CAPTAIN KEY {{ index + 1 }}</label>
                                <input type="text" class="form-control mb-3"
                                    v-model="ClickBankFormData.clickbank_captain_key[index]"
                                    :name="'clickbank_captain_key[' + index + ']'"
                                    :id="'clickbank-captain-key-' + index"
                                    placeholder="Enter Clickbank Captain Key">

                                    <!-- <div class="errors">
                                        <small v-if="clickbank_errors.clickbank_captain_key" class="form-text text-danger">
                                            {{ clickbank_errors.clickbank_captain_key[index] }}
                                        </small>
                                    </div> -->

                                <!-- Show the delete button only if there is more than one field set -->
                                <button v-if="ClickBankFormData.clickbank_apiurl.length > 1"
                                        type="button"
                                        @click="removeFieldSet(index,ClickBankFormData.clickbank_id[index])"
                                        class="btn btn-danger w-100 mb-5">Delete</button>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>

                    <!-- active campaing -->
                    <div class="col" v-else-if="is_page == 'activecampaign'">
                        <div class="row mb-3">
                            <div class="col text-right">
                                <button @click.prevent="addMoreFieldsActiveCampaign" class="btn btn-primary activecampaign_add_more">Add More</button>
                            </div>
                        </div>
                        <form @submit.prevent="ActiveCampaignSubmitForm" id="activecampaign_form">
                            <div v-for="(field, index) in ActiveCampaignFormData.activecampaign_apiurl" :key="index" class="form-group mb-3">

                                <label :for="'activecampaign-apiurl-' + index">API URL {{ index + 1 }}</label>
                                <input type="text" class="form-control mb-3"
                                    v-model="ActiveCampaignFormData.activecampaign_apiurl[index]"
                                    :name="'activecampaign_apiurl[' + index + ']'"
                                    :id="'activecampaign-apiurl-' + index"
                                    placeholder="Enter API URL">


                                <label :for="'activecampaign-captain-key-' + index">API KEY {{ index + 1 }}</label>
                                <input type="text" class="form-control mb-3"
                                    v-model="ActiveCampaignFormData.activecampaign_apikey[index]"
                                    :name="'activecampaign_captain_key[' + index + ']'"
                                    :id="'activecampaign-captain-key-' + index"
                                    placeholder="Enter activecampaign Captain Key">


                                <!-- <label :for="'activecampaign-captain-key-' + index">API LIST ID {{ index + 1 }}</label>
                                <input type="text" class="form-control mb-3"
                                    v-model="ActiveCampaignFormData.activecampaign_list_id[index]"
                                    :name="'activecampaign_captain_key[' + index + ']'"
                                    :id="'activecampaign-captain-key-' + index"
                                    placeholder="Enter activecampaign Captain Key"> -->

                                <button v-if="ActiveCampaignFormData.activecampaign_apiurl.length > 1"
                                        type="button"
                                        @click="removeFieldSetActiveCampaign(index,ActiveCampaignFormData.activecampaign_id[index])"
                                        class="btn btn-danger w-100 mb-5">Delete</button>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>

                    <!-- <div class="col" v-else>
                        this is click bank section
                    </div> -->

                </div>



            </div>
        </div>
    </div>
</template>
