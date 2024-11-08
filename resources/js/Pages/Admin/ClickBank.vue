<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import SettingsButtons from '@/Components/SettingsButtons.vue';
import SettingsBody from '@/Components/SettingsBody.vue';

const props = defineProps({
    settings : Array,
    user : Object
});

</script>

<template>
    <Head :title="props.settings['title']" />

    <AuthenticatedLayout>

        <SettingsButtons />
        <SettingsBody settings-string="This is a clickbank" is_page="clickbank" />

    </AuthenticatedLayout>
</template>


