
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';


import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css'; // You can change the theme as needed
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






const props = defineProps({
    settings: Object

});




// Extract URL path parameters (e.g., `/campaign/123/edit`)
const pathSegments = window.location.pathname.split('/');
const campid = pathSegments[2];


const userData = usePage().props.auth.user;
const userID = userData.id;



onMounted(() => {
    console.log('mouted');
    getSingleCampaign();
});




const errors = ref({
    'name' : '',
    'description' : ''
});




const editFormData = ref({
    'name' : '',
    'description' : '',
    'user_id' : userID,
    'id' : campid
});


/** get single cmapaign */
const getSingleCampaign = async () =>{
    try {
        const response = await axios.get(`/api/v1/get-campaigns/${campid}/single`);
        editFormData.value = response.data.campaign;
        console.log(response.data.campaign);
    } catch(errors) {
        console.log(errors);
    }
}


/** update campaign get data */
const editCampaign = async () => {
    errors.value = {}; // Reset errors before the request
    try {
        // Create a FormData instance and append form data
        const formData = new FormData();
        formData.append('name', editFormData.value.name);
        formData.append('description', editFormData.value.description);
        formData.append('user_id', editFormData.value.user_id);
        formData.append('id', campid); // Make sure `campid` is defined and holds the correct ID

        // Make an API request to update the campaign
        const response = await axios.post('/api/v1/update/campaign', formData);
        console.log(response.data);

        // Trigger a toast notification upon success
        showToast('success', 'Campaign updated successfully!');
    } catch (error) {
        // Check if `error.response` exists to avoid runtime errors
        if (error.response && error.response.data) {
            console.log('errors', error.response.data);

            // Set error messages for specific fields if present
            errors.value.name = error.response.data.errors?.name ? error.response.data.errors.name[0] : '';
            errors.value.description = error.response.data.errors?.description ? error.response.data.errors.description[0] : '';
            showToast('error', 'All Fields are required');
        } else {
            // Handle cases where error response is not as expected
            console.error('An unexpected error occurred:', error);
            showToast('error', 'An unexpected error occurred. Please try again.');
        }
    }
};



</script>



<template>
    <Head :title=props.settings.title />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <Link
                            :href="route('dashboard')"
                            :class="[
                                'rounded-md px-3 py-2 text-white ring-1 ring-transparent transition',
                                route().current('campaign.add') ? 'btn btn-info' : 'btn btn-secondary'
                            ]"
                        >
                            Campaign List
                        </Link>

                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row mt-5">
                <div class="col">
                    <form @submit.prevent="editCampaign">
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter Name" v-model="editFormData.name">
                            <small id="" class="form-text text-danger" v-if="errors.name">{{ errors.name }}</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="desc">Description</label>
                            <input type="text" class="form-control" id="desc" placeholder="Enter Description" v-model="editFormData.description"/>
                            <small id="" class="form-text text-danger" v-if="errors.description">{{ errors.description }}</small>
                        </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>



