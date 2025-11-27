<template>
    <div v-if="data" class="fixed-pagination-footer shadow-lg">
        <div class="container-fluid d-flex justify-content-center align-items-center h-100">
            <nav class="d-flex align-items-center">
                <ul class="pagination pagination-sm m-0">

                    <li class="page-item" :class="{ 'disabled': data.current_page === 1 }">
                        <button class="page-link" @click.prevent="data.current_page !== 1 ? goToPage(1) : null"
                            :disabled="data.current_page === 1" aria-label="Primeira Página">
                            <i class="fas fa-angle-double-left"></i>
                        </button>
                    </li>

                    <li class="page-item" :class="{ 'disabled': !data.prev_page_url }">
                        <Link class="page-link" :href="data.prev_page_url || '#'" :preserve-state="true"
                            aria-label="Anterior" :class="{ 'pointer-events-none': !data.prev_page_url }" tabindex="-1"
                            :disabled="!data.prev_page_url">
                        &laquo; Anterior
                        </Link>
                    </li>


                    <li v-for="(link, index) in data.links" :key="index" class="page-item" :class="{
                        'active': link.active,
                        'disabled': !link.url && !link.active && !link.label.includes('Previous') && !link.label.includes('Next'),
                    }">
                        <Link v-if="!isNaN(link.label) || link.label === '...'" class="page-link"
                            :href="link.url || '#'" :preserve-state="true" :tabindex="!link.url ? -1 : 0"
                            :class="{ 'pointer-events-none': !link.url && !link.active }" v-html="link.label" />
                    </li>

                    <li class="page-item" :class="{ 'disabled': !data.next_page_url }">
                        <Link class="page-link" :href="data.next_page_url || '#'" :preserve-state="true"
                            aria-label="Próxima" :class="{ 'pointer-events-none': !data.next_page_url }" tabindex="-1"
                            :disabled="!data.next_page_url">
                        Próxima &raquo;
                        </Link>
                    </li>

                    <li class="page-item" :class="{ 'disabled': data.current_page === data.last_page }">
                        <button class="page-link"
                            @click.prevent="data.current_page !== data.last_page ? goToPage(data.last_page) : null"
                            :disabled="data.current_page === data.last_page" aria-label="Última Página">
                            <i class="fas fa-angle-double-right"></i>
                        </button>
                    </li>

                </ul>
            </nav>
        </div>
    </div>
</template>

<script setup>
    import {Link, router} from '@inertiajs/vue3';

    const props = defineProps({
        data: {
            type: Object,
            required: true,
            default: () => ({links: [], current_page: 1, last_page: 1}),
        },
    });

    const goToPage = (pageNumber) => {
        const firstLink = props.data.links.find(link => link.label.includes('Previous') || link.label === '1');
        let url = firstLink ? firstLink.url : null;

        if (!url) {
            url = window.location.href;
        }

        const newUrl = new URL(url);
        newUrl.searchParams.set('page', pageNumber);

        router.get(newUrl.toString(), {}, {
            preserveState: true,
            preserveScroll: true,
        });
    };
</script>

<style scoped>
    .fixed-pagination-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1020;
        background-color: white;
        border-top: 1px solid #e0e0e0;
        height: 60px;
        width: 100%;
    }

    .fixed-pagination-footer .container-fluid {
        padding-top: 5px;
        padding-bottom: 5px;
        height: 100%;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .page-item.disabled .page-link {
        cursor: not-allowed;
    }
</style>
