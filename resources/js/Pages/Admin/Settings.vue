<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import SettingsButtons from '@/Components/SettingsButtons.vue';


const props = defineProps({
    settings : Array,
    user : Object
});

</script>

<template>
    <Head :title="props.settings['title']" />

    <AuthenticatedLayout>
        <!-- <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Settings
            </h2>
        </template> -->

        <SettingsButtons />
    </AuthenticatedLayout>
</template>


