<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';





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



/** get props */
const props = defineProps({
    settings: Object
});


/** onmounted */
onMounted(() => {
    getClickBankAllProductsOrders();
    getClickBankAllAccounts();
});


const getClickBankOdersData = ref({});



/** get click bank all products orders */
const getClickBankAllProductsOrders = async () => {
    try {
        let endpoint = 'one';
        const response = await axios.get(`/api/v1/clickbank/products/${endpoint}/orders`);
        getClickBankOdersData.value = response.data.response.orderData;
        console.log(getClickBankOdersData.value);
    } catch (error) {
        console.log(error);

    }
}




/** get click bank all Accounts */
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
    <Head :title=props.settings?.title />

    <AuthenticatedLayout>

        <!-- links start -->
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

                        <Link
                            :href="route('dashboard')"
                            :class="[
                                'rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white',
                                route().current('campaign.add') ? 'btn btn-info' : 'btn btn-secondary'
                            ]"
                        >
                            Campaing List
                        </Link>


                    </div>
                </div>
            </div>
        </div>
        <!-- links end -->

        <div class="container">
            <div class="row mt-5">

                <!-- clickbank start -->
                <div class="col-12">
                    <div class="custom-dropdown">
                        <button class="custom-dropdown-btn">ClickBank</button>
                        <div class="custom-dropdown-content">
                            <a href="#">Option 1</a>
                            <a href="#">Option 2</a>
                            <a href="#">Option 3</a>
                        </div>
                    </div>
                </div>
                <!-- clickbank end -->

            </div>
        </div>




    </AuthenticatedLayout>
</template>
