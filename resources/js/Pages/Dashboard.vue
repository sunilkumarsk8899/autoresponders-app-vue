<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import ModelBox from '@/Components/ModelBox.vue';

/** get campaign data */
const campaigns = ref([]);

/** get logged in user data */
const currentUserData = usePage().props.auth.user;
const userid = currentUserData.id;


/** edit */

const setEdeditHandleitData = (id) =>{
    console.log(id);

}

/** onmounted */
onMounted(() => {
    get_campaigns();
});

/** get campaigns */
const get_campaigns = async () => {
    const response = await axios.get(`/api/v1/get-campaigns/${userid}`);
    campaigns.value = response.data.campaigns;
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
                                <button class="btn btn-primary" @click="editHandle(item.id)"> EDIT </button>
                            </td>
                            <td class="text-center" >
                                <button class="btn btn-danger">Delete</button>
                            </td>
                            <td class="text-center" >
                                <button class="btn btn-success">Start</button>
                            </td>
                        </tr>
                    </tbody>
                    </table>
            </div>
        </div>
        </div>




    </AuthenticatedLayout>
</template>
