<script>
export default {
  props: {
    settings: Object
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
                    <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
