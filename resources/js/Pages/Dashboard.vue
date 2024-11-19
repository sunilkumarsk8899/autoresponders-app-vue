<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import ModelBox from '@/Components/ModelBox.vue';





import Swal from 'sweetalert2';
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



import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css'; // You can change the theme as needed
import axios from 'axios';
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




/** get campaign data */
const campaigns = ref([]);

/** get logged in user data */
const currentUserData = usePage().props.auth.user;
const userid = currentUserData.id;

const reloadCampaigns = ref(false); // Reactive property to trigger reload



/** edit */

const editHandle = (id) =>{
    console.log(id);

}

/** onmounted */
onMounted(() => {
    get_campaigns();
});

watch([reloadCampaigns], async () => {
    await get_campaigns();
    console.log('refresh');
});

/** get campaigns */
const get_campaigns = async () => {
    const response = await axios.get(`/api/v1/get-campaigns/${userid}`);
    campaigns.value = response.data.campaigns;
}



/** delete campaign */
const deleteHandle = async (id) => {
    try {
        const result = await deleteConfirmation();
        if(result){
            const response = await axios.delete(`/api/v1/delete-campaign/${id}`);
            showToast('success',response.data.message);
            get_campaigns();
        }
    } catch (error) {
        console.error('Error:', error.response ? error.response.data : error.message);
        showToast('error',error.message);
    }
};


const campaing_start_handler = async (id) => {
    const resp = await axios.put(`/api/v1/start/${id}/campaign`);
    reloadCampaigns.value = !reloadCampaigns.value; /** this is only for reload get data function */
    showToast('success',`${resp.data.campaign.name} campaign ${resp.data.is_start ? 'Start' : 'Stop'} successfully`);
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div
                class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
            >
                <div class="p-6 text-gray-900 d-flex gap-3">

                    <Link
                        :href="route('campaign.add')"
                        :class="[
                            'rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white',
                            route().current('campaign.add') ? 'btn btn-info' : 'btn btn-secondary'
                        ]"
                    >
                        Campaing Add
                    </Link>


                </div>
            </div>
        </div>
    </div>

        <div class="container">
            <div class="row mt-5">
                <!-- <div class="col">
                    <Link
                        :href="route('campaign.add')"
                        :class="[
                            'rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white',
                            route().current('campaign.add') ? 'btn btn-info' : 'btn btn-secondary'
                        ]"
                    >
                        Campaing Add
                    </Link>
                </div> -->
            <div class="col-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col" colspan="3" class="text-center" >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item,index) in campaigns" :key="index">
                            <th scope="row">{{ item.id }}</th>
                            <td>{{ item.name }}</td>
                            <td>{{ item.description }}</td>
                            <td class="text-center" >
                                <!-- <button class="btn btn-primary" @click="editHandle(item.id)"> EDIT </button> -->
                                <Link
                                    :href="route('campaign.edit', { id : item.id })"
                                    :class="[
                                        'rounded-md px-3 py-2 text-white ring-1 ring-transparent transition btn btn-secondary'
                                    ]"
                                >
                                    Edit
                                </Link>
                            </td>
                            <td class="text-center" >
                                <button class="btn btn-danger" @click="deleteHandle(item.id)" >Delete</button>
                            </td>
                            <td class="text-center" >
                                <!-- <Link
                                    :href="route('campaign.start', { camid : item.id })"
                                    :class="[
                                        'rounded-md px-3 py-2 text-white ring-1 ring-transparent transition btn btn-secondary'
                                    ]"
                                >
                                    Start
                                </Link> -->
                                <button
                                    :class="`btn btn-${item.start == 1 ? 'info text-white' : 'success'}`"
                                    @click="campaing_start_handler(item.id)">
                                    {{ item.start == 1 ? 'Stop' : 'Start' }}
                                </button>

                            </td>
                        </tr>
                    </tbody>
                    </table>
            </div>
        </div>
        </div>




    </AuthenticatedLayout>
</template>
