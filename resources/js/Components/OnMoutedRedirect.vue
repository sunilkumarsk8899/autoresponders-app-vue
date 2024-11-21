<script setup >
import { router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

onMounted(() => {
    console.log('mouted register page');

   router.visit('/login') ;
});
</script>
