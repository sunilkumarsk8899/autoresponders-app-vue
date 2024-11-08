<script setup>
defineProps({
    route: {
        type: String,
        default: '#'
    },
});
</script>

<template>
    <Link
        :href="route"
        class="btn btn-secondary rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
    >
        <slot />
    </Link>
</template>
