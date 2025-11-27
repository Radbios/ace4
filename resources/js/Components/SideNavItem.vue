<template>
    <li class="sidenav-item" :class="{ 'sidenav-active': $page.url.startsWith(href) }">
        <Link :href="href" class="sidenav-link">
        <i :class="[iconClass, 'me-3']"></i>
        <span>{{ label }}</span>
        <slot name="badge"></slot>
        </Link>
    </li>
</template>
<script setup>

    import {Link, usePage} from '@inertiajs/vue3';

    const props = defineProps({
        href: {
            type: String,
            required: true,
        },
        label: {
            type: String,
            required: true,
        },
        iconClass: {
            type: String,
            required: true,
        },
    });

    const $page = usePage();
</script>

<style scoped>
    .sidenav-item {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .sidenav-link {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        border-radius: 5px;
        margin: 0 10px;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .sidenav-item.sidenav-active .sidenav-link {
        color: #fff !important;
        background-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 10px 0 rgba(0, 0, 0, 0.1);
    }

    .sidenav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }
</style>
