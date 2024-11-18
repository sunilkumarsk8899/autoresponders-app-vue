<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref, reactive } from 'vue';

import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css'; // You can change the theme as needed

import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css';


const props = defineProps({
    user: Object,
    settings: Object
});

/** get current logged in user data */
const user = usePage().props.auth.user;
const { id } = user;
const UserID = id;



/** define variables */
const selectedOptionClickbank = ref(''); /** selected clickbank account */
const clickbankAccounts = ref({}); /** clickbank account data */
const is_active_clickbank_account_dropdown = ref(false); /** clickbank account dropdown */
const is_active_clickbank_product_dropdown = ref(false); /** clickbank product dropdown */
const clickBankGetAllAccounts = ref({});

const activeCampaignAccounts = ref({}); /** active campaign account data */
const activeCampaignLists = ref({}); /** active campaign list data */
const is_active_campaign_list_dropdown = ref(false); /** active campaign list dropdown */

const clickBankApiID = ref('');

/** get data by apis */
const clickbankAllAccounts = ref('');
const clickbankAllOrders = ref('');
const clickbank_products = ref('');








/** add campaing form data */
const AddCampaignFromData = ref({
    'name' : '',
    'desc' : '',
    'user_id' : UserID,
    'clickbank_id' : '',
    'clickbank_account_name' : '',
    'selected_clickbank_products_name' : [],
    'active_campaign_id' : '',
    'active_campaign_list_id' : ''
});

const selectedClickbankProducts = ref([]);

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
});





/** add campaign form submit */
const addCampaign = async () => {
    const item = selectedClickbankProducts.value.map(order => order['@sku']);
    AddCampaignFromData.value.selected_clickbank_products_name = item;
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





/**============================== get all clickbank accounts from db ==============================*/
const getClickbankAccounts = async () => {
    try {
        const type = 'clickbank';
        const resp = await axios.get(`/api/v1/get-accounts/${type}`);
        clickbankAccounts.value = resp.data.data;
    } catch (error) {
        console.log(error);
    }
}

/**============================== get all active campaign accounts from db ==============================*/
const getActiveCampaignAccounts = async () => {
    try {
        const type = 'activecampaign';
        const resp = await axios.get(`/api/v1/get-accounts/${type}`);
        activeCampaignAccounts.value = resp.data.data;
    } catch (error) {
        console.log(error);
    }
}
/**============================== get all active campaign accounts from db ==============================*/






/**============================== get activecampaing lists if seleted active campaing dropdown start ==============================*/
const getActiveCampaignLists = async (id) => {
    try {
        const resp = await axios.get(`/api/v1/get-active-campaing/lists/${id}`);
        activeCampaignLists.value = resp.data.data.lists;
    } catch (error) {
        console.log(error);
    }
}
/**============================== get activecampaing lists end ==============================*/



/**============================== get clickbank all account details start ==============================*/
const getClickBankAllAccounts = async () => {
    try {
        let apiID = clickBankApiID.value;
        const response = await axios.get(`/api/v1/clickbank/get/${apiID}/accounts`);
        // console.log('all accounts',response.data);
        clickbankAllAccounts.value = response.data.response.accountData;
    } catch (error) {
        console.log(error);
    }
}
/**============================== get clickbank all account details end ==============================*/



/**============================== get clickbank all account details start ==============================*/
const getClickBankAllOrders = async () => {
    try {
        let apiID = clickBankApiID.value;
        const response = await axios.get(`/api/v1/clickbank/products/${apiID}/orders`);
        // clickbankAllOrders.value = response.data.response.orderData;
        console.log('all order ',response.data);

    } catch (error) {
        console.log(error);
    }
}
/**============================== get clickbank all account details end ==============================*/




/** get product by account */
const getClickBankAllProductsByAccount = async () => {
    try {
        let apiID = clickBankApiID.value;
        let account = AddCampaignFromData.value.clickbank_account_name;
        // console.log('form data ',AddCampaignFromData.value);

        const response = await axios.get(`/api/v1/clickbank/get/${apiID}/products/${account}`);
        clickbank_products.value = response.data.response.products.product;
        // console.log('all products by account ',response.data.response.products.product);

    } catch (error) {
        console.log(error);
    }
}











/**============================== click bank get details start ==============================*/

const clickBankOptionHandler = (event) =>{ /** get clickbank list & show next account & product dropdown */
    is_active_clickbank_account_dropdown.value = true;
    AddCampaignFromData.value.clickbank_id = event.target.value;
    clickBankApiID.value = event.target.value;
    getClickBankAllAccounts();
}

const clickBankAccountOptionHandler = (event) => { /** clickbank account id get in dropdown */
    is_active_clickbank_product_dropdown.value = true;
    AddCampaignFromData.value.clickbank_account_name = event.target.value;
    // getClickBankAllOrders();
    getClickBankAllProductsByAccount();
}

/**============================== click bank get details end ==============================*/




/**=========================== get active campaign account details start ===========================*/

const activeCampaignHandler = (event) => { /** get active campaign id and show next active campaign list id dropdown */
    is_active_campaign_list_dropdown.value = true;
    const activeCampaignId = event.target.value;
    AddCampaignFromData.value.active_campaign_id = event.target.value;
    getActiveCampaignLists(activeCampaignId);
}

const activeCampaignListHandler = (event) =>{ /** get activecampaign list id */
    AddCampaignFromData.value.active_campaign_list_id = event.target.value;
}

/**=========================== get active campaign account details end ===========================*/





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




                        <!--=========================================== click bank details start ===========================================-->
                        <!-- clickbank api accounts -->
                        <div class="form-group mb-3" >
                            <label for="desc">ClickBank API Accounts</label>
                            <select name="" id="" class="w-100 rounded" style="border-color: #dee2e6;" @change="clickBankOptionHandler" >
                                <option value="">Select ClickBank API Account</option>
                                <option v-for="(clickbankitem,index) in clickbankAccounts" :key="index" :value="clickbankitem.id">
                                    {{ clickbankitem.name }}
                                </option>
                            </select>
                        </div>

                        <!-- users accounts -->
                        <div class="form-group mb-3" v-if="is_active_clickbank_account_dropdown" >
                            <label for="desc">ClickBank User Accounts</label>
                            <select name="" id="" class="w-100 rounded" style="border-color: #dee2e6;" @change="clickBankAccountOptionHandler" >
                                <option value="">Select ClickBank User Account</option>
                                <option v-for="(clickbankAccountItem,index) in clickbankAllAccounts" :key="index" :value="clickbankAccountItem.nickName">
                                    {{ clickbankAccountItem.nickName }}
                                </option>
                            </select>
                        </div>

                        <!-- user products -->
                        <div class="form-group mb-3" v-if="is_active_clickbank_product_dropdown">
                            <label for="clickbank-products">ClickBank Products</label>
                            <Multiselect
                            v-model="selectedClickbankProducts"
                            :options="clickbank_products"
                            :multiple="true"
                            track-by="@sku"
                            label="@sku"
                            placeholder="Select ClickBank Products"
                            class="w-100"
                            />
                        </div>



                        <!--=========================================== click bank details end ===========================================-->




                        <!--=========================================== active details start ===========================================-->
                        <!-- api accounts -->
                        <div class="form-group mb-3">
                            <label for="desc">Active API Campaign</label>
                            <select name="" id="" class="w-100 rounded" style="border-color: #dee2e6;" @change="activeCampaignHandler" >
                                <option value="">Select ActiveCampaign Account</option>
                                <option v-for="(item,index) in activeCampaignAccounts" :key="item.id" :value="item.id" >
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>

                        <!-- selected api account lists -->
                        <div class="form-group mb-3" v-if="is_active_campaign_list_dropdown" >
                            <label for="desc">Active Campaign Lists</label>
                            <select name="" id="" class="w-100 rounded" style="border-color: #dee2e6;" @change="activeCampaignListHandler" >
                                <option value="">Select ActiveCampaign Account</option>
                                <option v-for="(item,index) in activeCampaignLists" :key="item.id" :value="item.id" >
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>
                        <!--=========================================== active details end ===========================================-->




                    <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
