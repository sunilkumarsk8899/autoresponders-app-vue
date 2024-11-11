<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css'; // You can change the theme as needed

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



// click bank form fields
const ClickBankFormData = ref({
    clickbank_apiurl: [''],
    clickbank_captain_key: [''],
    type: 'clickbank'
});



// Clear previous errors
clickbank_errors.value = {
    clickbank_apiurl: [],
    clickbank_captain_key: []
};
// Validate form fields
let hasErrors = false;



// click bank insert data
const ClickBankSubmitForm = async () => {
    // Clear previous errors
    clickbank_errors.value = {
        clickbank_apiurl: [],
        clickbank_captain_key: []
    };
    // Validate form fields
    let hasErrors = false;



    ClickBankFormData.value.clickbank_apiurl.forEach((value, index) => {
        if (!value) {
            // Ensure the index exists before assigning an error
            if (!clickbank_errors.value.clickbank_apiurl[index]) {
                clickbank_errors.value.clickbank_apiurl[index] = 'API URL is required';
                hasErrors = true;
            }
        }
    });

    ClickBankFormData.value.clickbank_captain_key.forEach((value, index) => {
        if (!value) {
            // Ensure the index exists before assigning an error
            if (!clickbank_errors.value.clickbank_captain_key[index]) {
                clickbank_errors.value.clickbank_captain_key[index] = 'Captain Key is required';
                hasErrors = true;
            }
        }
    });

    console.log(hasErrors);

    // Stop submission if there are validation errors
    if (hasErrors) {
        showToast('error','Fields is required');
        return;
    }


    try {
        const response = await axios.post('/api/v1/add-setting', ClickBankFormData.value);
        console.log('Data saved:', response.data);
        clickbank_errors.value = {};
        showToast('success','Add ClickBank Setting saved successfully');
    } catch (error) {
        console.error('Error saving data:', error.response.data);
        clickbank_errors.value = error.response.data.errors;
    }
};




// click bank add more
const addMoreFieldsClickBank = () => {
    ClickBankFormData.value.clickbank_apiurl.push('');
    ClickBankFormData.value.clickbank_captain_key.push('');
};




// remove fields
const removeFieldSet = (index) => {
    ClickBankFormData.value.clickbank_apiurl.splice(index, 1);
    ClickBankFormData.value.clickbank_captain_key.splice(index, 1);
};



</script>
<template>
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
        >
            <div class="p-6 gap-3">

                <div class="row">

                    <!-- get response -->
                    <div class="col" v-if="is_page == 'getresponse'">
                        <form @submit.prevent="submitForm">
                            <div class="form-group mb-3">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                                <small id="emailHelp" class="form-text text-danger"></small>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>

                    <!-- click bank -->
                    <div class="col" v-else-if="is_page == 'clickbank'">
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

                                    <div class="errors">
                                        <small v-if="clickbank_errors.clickbank_apiurl" class="form-text text-danger">
                                            {{ clickbank_errors.clickbank_apiurl[index] }}
                                        </small>
                                    </div>

                                <label :for="'clickbank-captain-key-' + index">CLICKBANK CAPTAIN KEY {{ index + 1 }}</label>
                                <input type="text" class="form-control mb-3"
                                    v-model="ClickBankFormData.clickbank_captain_key[index]"
                                    :name="'clickbank_captain_key[' + index + ']'"
                                    :id="'clickbank-captain-key-' + index"
                                    placeholder="Enter Clickbank Captain Key">

                                    <div class="errors">
                                        <small v-if="clickbank_errors.clickbank_captain_key" class="form-text text-danger">
                                            {{ clickbank_errors.clickbank_captain_key[index] }}
                                        </small>
                                    </div>

                                <!-- Show the delete button only if there is more than one field set -->
                                <button v-if="ClickBankFormData.clickbank_apiurl.length > 1"
                                        type="button"
                                        @click="removeFieldSet(index)"
                                        class="btn btn-danger w-100 mb-5">Delete</button>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>

                    <!-- active campaing -->
                    <div class="col" v-else-if="is_page == 'activecampaign'">
                        <form @submit.prevent="submitForm">
                            <div class="form-group mb-3">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                                <small id="emailHelp" class="form-text text-danger"></small>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
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
