<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref, reactive } from 'vue';

import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css'; // You can change the theme as needed

const props = defineProps({
    user: Object,
    settings: Object
});

/** get current logged in user data */
const user = usePage().props.auth.user;
const { id } = user;
const UserID = id;



/** define variables */
const clickbankAccounts = ref({});
const activeCampaignAccounts = ref({});
const activeCampaignLists = ref({});
const selectedOptionClickbank = ref('');

const is_active_clickbank_dropdown = ref(false);
const is_active_campaign_list_dropdown = ref(false);






/** add campaing form data */
const AddCampaignFromData = ref({
    'name' : '',
    'desc' : '',
    'user_id' : UserID,
    'clickbank_id' : '',
    'clickbank_item_id' : '',
    'clickbank_item_site_name' : '',
    'active_campaign_id' : '',
    'active_campaign_list_id' : ''
});

// errors
const errors = ref({
    'name' : '',
    'desc' : ''
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





onMounted(() => {
    getClickbankAccounts();
    getActiveCampaignAccounts();
    getActiveCampaignLists();
    getClickBankAllAccounts();
});






const addCampaign = async () => {
    console.log(AddCampaignFromData);
    errors.value = {};
    try {
        const resp = await axios.post('/api/v1/store-campaign', AddCampaignFromData.value, {
            headers: {
                'Content-Type': 'application/json', // Adjust if sending other formats like `multipart/form-data`
            }
        });
        console.log(resp);
        if(resp.data.resp.id){
            showToast('success',resp.data.message);
        }else{
            showToast('error',"Somtheing wen't wrong!");
        }
    } catch (error) {
        // console.error(error.response.data.errors);
        errors.value.name = error.response.data.errors?.name ? error.response.data.errors.name[0] : '';
        errors.value.desc = error.response.data.errors?.desc ? error.response.data.errors.desc[0] : '';
    }

}





/** get all clickbank accounts */
const getClickbankAccounts = async () => {
    try {
        const resp = await axios.get(`/api/v1/get-accounts/clickbank`);
        // console.log(resp.data.data);
        clickbankAccounts.value = resp.data.data;
    } catch (error) {
        console.log(error);
    }
}

/** get all active campaign accounts */
const getActiveCampaignAccounts = async () => {
    try {
        const resp = await axios.get('/api/v1/get-accounts/activecampaign');
        // console.log(resp.data.data);
        activeCampaignAccounts.value = resp.data.data;
    } catch (error) {
        console.log(error);
    }
}



/** get activecampaing lists */
const getActiveCampaignLists = async () => {
    try {
        const resp = await axios.get('/api/v1/get-active-campaing/lists');
        // console.log('active campaing lists data ',resp.data.data.lists);
        activeCampaignLists.value = resp.data.data.lists;
    } catch (error) {
        console.log(error);
    }

}




const clickBankOptionHandler = (event) =>{
    is_active_clickbank_dropdown.value = true;
    AddCampaignFromData.clickbank_id = event.target.value;
    // console.log('clickbank Target Value:', event.target.value);
}



const activeCampaignHandler = (event) => {
    // console.log('active campaing event');

    is_active_campaign_list_dropdown.value = true;
    AddCampaignFromData.active_campaign_id = event.target.value;
    // console.log('active campaign Target Value:', event.target.value);

}


const activeCampaignListHandler = (event) =>{
    AddCampaignFromData.active_campaign_list_id = event.target.value;
    // console.log('active campaing list id value ',event.target.value);

}






const getClickBankAllAccounts = async () => {
    try {
        let endpoint = 'one';
        const response = await axios.get(`/api/v1/clickbank/get/${endpoint}/accounts`);
        console.log(response.data);
    } catch (error) {
        console.log(error);
    }
}

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
                    <form @submit.prevent="addCampaign">

                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter Name" v-model="AddCampaignFromData.name">
                            <small id="" class="form-text text-danger" v-if="errors.name">{{ errors.name }}</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="desc">Description</label>
                            <input type="text" class="form-control" id="desc" placeholder="Enter Description" v-model="AddCampaignFromData.desc"/>
                            <small id="" class="form-text text-danger" v-if="errors.desc">{{ errors.desc }}</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="desc">ClickBank</label>
                            <select name="" id="" class="w-100 rounded" style="border-color: #dee2e6;" @change="clickBankOptionHandler" >
                                <option value="">Select ClickBank Account</option>
                                <option v-for="(clickbankitem,index) in clickbankAccounts" :key="index" :value="clickbankitem.id">
                                    {{ clickbankitem.name }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group mb-3 d-flex align-items-center" v-if="is_active_clickbank_dropdown" >
                            <label for="item_id" class="w-20" >Item ID</label>
                            <input type="text" class="form-control w-50" id="item_id" placeholder="Enter item id separated by comma (12,13,55,25,23)" v-model="AddCampaignFromData.clickbank_item_id" />
                            <label for="site_name" class="w-40 text-center" >Site Name</label>
                            <input type="text" class="form-control w-50" id="site_name" placeholder="Enter site name" v-model="AddCampaignFromData.clickbank_item_site_name" />
                        </div>


                        <div class="form-group mb-3">
                            <label for="desc">Active Campaign</label>
                            <select name="" id="" class="w-100 rounded" style="border-color: #dee2e6;" @change="activeCampaignHandler" >
                                <option value="">Select ActiveCampaign Account</option>
                                <option v-for="(item,index) in activeCampaignAccounts" :key="item.id" :value="item.id" >
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group mb-3" v-if="is_active_campaign_list_dropdown" >
                            <label for="desc">Active Campaign Lists</label>
                            <select name="" id="" class="w-100 rounded" style="border-color: #dee2e6;" @change="activeCampaignListHandler" >
                                <option value="">Select ActiveCampaign Account</option>
                                <option v-for="(item,index) in activeCampaignLists" :key="item.id" :value="item.id" >
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
