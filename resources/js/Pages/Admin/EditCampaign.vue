
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';


import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css';


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




onMounted(() => {
    console.log('run mouted');
    getSingleCampaign();
    getClickbankAccounts();
    getActiveCampaignAccounts();
});




const errors = ref({
    'name' : '',
    'description' : ''
});




const editFormData = ref({
    'name' : '',
    'description' : '',
    'user_id' : UserID,
    'clickbank_id' : '',
    'clickbank_account_name' : '',
    'selected_clickbank_products_name' : [],
    'active_campaign_id' : '',
    'active_campaign_list_id' : ''
});

const selectedClickbankProducts = ref([]);


/** get single cmapaign */
const getSingleCampaign = async () =>{
    try {
        const response = await axios.get(`/api/v1/get-campaigns/${campid}/single`);
        editFormData.value = response.data.campaign;
        console.log('get edit campaing data ',response.data.campaign,response.data.details);
        editFormData.value.clickbank_id = response.data.details.clickbank_id;
        editFormData.value.clickbank_account_name = response.data.details.clickbank_account_name;
        editFormData.value.selected_clickbank_products_name = response.data.details.selected_clickbank_products_name;
        editFormData.value.active_campaign_id = response.data.details.active_campaign_id;
        editFormData.value.active_campaign_list_id = response.data.details.active_campaign_list_id;



        /** get clickbank account */
        clickBankApiID.value = response.data.details.clickbank_id; /** get clickbank id */
        is_active_clickbank_account_dropdown.value = true; /** show account dropdown */
        getClickBankAllAccounts(); /** get all clickbank accounts */

        is_active_clickbank_product_dropdown.value = true;
        getClickBankAllProductsByAccount(); /** get all products by account */


        /** show active campaing list who user selected */
        is_active_campaign_list_dropdown.value = true;
        getActiveCampaignLists(editFormData.value.active_campaign_id);


        /** show clickbank products who user selected */
        const test = response.data.details.selected_clickbank_products_name.split(",");
        selectedClickbankProducts.value = test.map((sku) => {
            console.log(sku);
            return {
                '@sku': sku,
            };

        });



    } catch(errors) {
        console.log(errors);
    }
}


/** update campaign get data */
const editCampaign = async () => {
    const item = selectedClickbankProducts.value.map(order => order['@sku']);
    editFormData.value.selected_clickbank_products_name = item;
    errors.value = {}; // Reset errors before the request
    try {
        // Create a FormData instance and append form data
        const formData = new FormData();
        formData.append('name', editFormData.value.name);
        formData.append('description', editFormData.value.description);
        formData.append('user_id', editFormData.value.user_id);
        formData.append('clickbank_id', editFormData.value.clickbank_id);
        formData.append('clickbank_account_name', editFormData.value.clickbank_account_name);
        formData.append('selected_clickbank_products_name', editFormData.value.selected_clickbank_products_name);
        formData.append('active_campaign_id', editFormData.value.active_campaign_id);
        formData.append('active_campaign_list_id', editFormData.value.active_campaign_list_id);
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
    let apiID = clickBankApiID.value;
    try {
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
    let apiID = clickBankApiID.value;
        let account = editFormData.value.clickbank_account_name;
        // console.log('form data ',editFormData.value);

        // console.log('get clickbank product',apiID,account);
    try {
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
    editFormData.value.clickbank_id = event.target.value;
    clickBankApiID.value = event.target.value;
    getClickBankAllAccounts();
}

const clickBankAccountOptionHandler = (event) => { /** clickbank account id get in dropdown */
    is_active_clickbank_product_dropdown.value = true;
    editFormData.value.clickbank_account_name = event.target.value;
    // getClickBankAllOrders();
    selectedClickbankProducts.value = [];
    getClickBankAllProductsByAccount();
}

/**============================== click bank get details end ==============================*/




/**=========================== get active campaign account details start ===========================*/

const activeCampaignHandler = (event) => { /** get active campaign id and show next active campaign list id dropdown */
    is_active_campaign_list_dropdown.value = true;
    const activeCampaignId = event.target.value;
    editFormData.value.active_campaign_id = event.target.value;
    getActiveCampaignLists(activeCampaignId);
    // console.log('active campaing list id',activeCampaignId);

}

const activeCampaignListHandler = (event) =>{ /** get activecampaign list id */
    editFormData.value.active_campaign_list_id = event.target.value;
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


                        <!--=========================================== click bank details start ===========================================-->
                        <!-- clickbank api accounts -->

                        <div class="form-group mb-3">
                            <label for="desc">ClickBank API Accounts</label>
                            <select
                                name="clickbank_account"
                                id="clickbank_account"
                                class="w-100 rounded"
                                style="border-color: #dee2e6;"
                                @change="clickBankOptionHandler"
                                v-model="editFormData.clickbank_id">
                                <option value="">Select ClickBank API Account</option>
                                <option
                                    v-for="(clickbankitem, index) in clickbankAccounts"
                                    :key="index"
                                    :value="clickbankitem.id">
                                    {{ clickbankitem.name }}
                                </option>
                            </select>
                        </div>


                        <!-- users accounts -->
                        <div class="form-group mb-3" v-if="is_active_clickbank_account_dropdown" >
                            <label for="desc">ClickBank User Accounts</label>
                            <select
                                name=""
                                id=""
                                class="w-100 rounded"
                                style="border-color: #dee2e6;"
                                @change="clickBankAccountOptionHandler"
                                v-model="editFormData.clickbank_account_name"
                            >
                                <option value="">Select ClickBank User Account</option>
                                <option
                                    v-for="(clickbankAccountItem,index) in clickbankAllAccounts"
                                    :key="index"
                                    :value="clickbankAccountItem.nickName">
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
                            <select name="" id="" class="w-100 rounded" style="border-color: #dee2e6;" @change="activeCampaignHandler" v-model="editFormData.active_campaign_id" >
                                <option value="">Select ActiveCampaign Account</option>
                                <option v-for="(item,index) in activeCampaignAccounts" :key="item.id" :value="item.id" >
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>

                        <!-- selected api account lists -->
                        <div class="form-group mb-3" v-if="is_active_campaign_list_dropdown" >
                            <label for="desc">Active Campaign Lists</label>
                            <select name="" id="" class="w-100 rounded" style="border-color: #dee2e6;" @change="activeCampaignListHandler" v-model="editFormData.active_campaign_list_id" >
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



