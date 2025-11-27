<template>
    <div class="col-xl-3 col-md-6">
        <div :class="['card', 'shadow-sm', `border-start-${color}`, 'h-100']">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto me-auto">
                        <h6 :class="['text-xs', 'fw-bold', `text-${color}`, 'text-uppercase', 'mb-1']">
                            {{ title }}
                        </h6>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ value }}</div>
                    </div>
                    <div class="col-auto">
                        <i :class="[icon, 'fa-2x', 'text-gray-300']"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {defineProps} from 'vue';

    defineProps({
        icon: {type: String, required: true},
        title: {type: String, required: true},
        value: {type: [String, Number], required: true},
        color: {type: String, default: 'primary'},
    });
</script>

<style scoped>
    .border-start-primary {
        border-left: 0.25rem solid #4e73df !important;
    }

    .border-start-success {
        border-left: 0.25rem solid #1cc88a !important;
    }

    .border-start-info {
        border-left: 0.25rem solid #36b9cc !important;
    }

    .border-start-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }

    .text-xs {
        font-size: 0.7rem;
    }

    .text-gray-300 {
        color: #dddfeb !important;
    }

    .card {
        border-radius: 0.35rem;
    }

    .card-body {
        flex: 1 1 auto;
    }
</style>
