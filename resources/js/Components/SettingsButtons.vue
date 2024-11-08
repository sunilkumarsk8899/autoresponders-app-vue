<script setup>
import { Link } from '@inertiajs/vue3';
import ButtonLink from './ButtonLink.vue';
</script>
<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div
                class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
            >
                <div class="p-6 text-gray-900 d-flex gap-3">
                    <Link
                        :href="route('settings.getresponse')"
                        :class="[
                            'rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white',
                            route().current('settings.getresponse') ? 'btn btn-info' : 'btn btn-secondary'
                        ]"
                    >
                        Get Response
                    </Link>

                    <Link
                        :href="route('settings.clickbank')"
                        :class="[
                            'rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white',
                            route().current('settings.clickbank') ? 'btn btn-info' : 'btn btn-secondary'
                        ]"
                    >
                        Click Bank
                    </Link>

                    <Link
                        :href="route('settings.activecampaign')"
                        :class="[
                            'rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white',
                            route().current('settings.activecampaign') ? 'btn btn-info' : 'btn btn-secondary'
                        ]"
                    >
                        Active Campaign
                    </Link>


                </div>
            </div>
        </div>
    </div>
</template>
