<script setup>
import { ref } from 'vue';
import axios from 'axios';

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

// click bank error var
const clickbank_errors = ref({});

// click bank form fields
const ClickBankFormData = ref({
    clickbank_apiurl: [[]],
    clickbank_captain_key: [[]],
    type: 'clickbank'
});

// click bank insert data
const ClickBankSubmitForm = async () => {
    try {
        console.log(ClickBankFormData.value);
        return;

        const response = await axios.post('/api/v1/add-setting', ClickBankFormData.value);
        console.log('Data saved:', response.data);
        // Optionally, handle success (e.g., clear form, show message, etc.)
        clickbank_errors.value = {};
    } catch (error) {
        console.error('Error saving data:', error.response.data);
        clickbank_errors.value = error.response.data.errors;
    }
};


// click bank add more
let count = 1;
const addMoreFieldsClickBank = () => {
    // Create the new HTML for the fields
    const html = `
        <h3>Field Set (${count})</h3>
        <div class="form-group mb-3">
            <label for="clickbank-apiurl">API URL</label>
            <input type="text" class="form-control" v-model="ClickBankFormData.clickbank_apiurl[${count}][${count}]" name="clickbank_apiurl[]" id="clickbank-apiurl-${count}" placeholder="Enter API URL">
        </div>
        <div class="form-group mb-3">
            <label for="clickbank-captain-key">CLICKBANK CAPTAIN KEY</label>
            <input type="text" class="form-control" v-model="ClickBankFormData.clickbank_captain_key[${count}][${count}]" name="clickbank_captain_key[]" id="clickbank-captain-key-${count}" placeholder="Enter Clickbank Captain Key">
        </div>
    `;

    // Prepend the new HTML inside the form
    $('#clickbank_form').prepend(html);

    // ClickBankFormData.value.push({ clickbank_apiurl: '', clickbank_captain_key: '' });
    // ClickBankFormData.value.clickbank_apiurl.push('');
    // ClickBankFormData.value.clickbank_captain_key.push('');

    count++;
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
                            <div class="form-group mb-3">
                                <label for="clickbank-apiurl">API URL</label>
                                <input type="text" class="form-control" v-model="ClickBankFormData.clickbank_apiurl[0][0]" name="clickbank_apiurl[0]" id="clickbank-apiurl" placeholder="Enter api url">
                                <small id="" class="form-text text-danger" v-if="clickbank_errors.clickbank_apiurl">{{ clickbank_errors.clickbank_apiurl[0] }}</small>
                            </div>
                            <div class="form-group mb-3">
                                <label for="clickbank-captain-key">CLICKBANK CAPTAIN KEY</label>
                                <input type="text" class="form-control" v-model="ClickBankFormData.clickbank_captain_key[0][0]" name="clickbank_captain_key[0]" id="clickbank-captain-key" placeholder="Enter clickbank captain key">
                                <small id="" class="form-text text-danger" v-if="clickbank_errors.clickbank_captain_key">{{ clickbank_errors.clickbank_captain_key[0] }}</small>
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
