<template>

    <Head title="GESTIC - Dashboard" />
    <div class="container-fluid pb-compensation">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">🏠 Visão Geral</h2>
        </div>
        <div class="row g-4 mb-5">
            <DashboardKpiCard icon="fas fa-clipboard-list" title="Projetos Totais" :value="stats.totalProjects"
                color="primary" />
            <DashboardKpiCard icon="fas fa-users-gear" title="Colaboradores Alocados" :value="stats.totalCollaborators"
                color="success" />
            <DashboardKpiCard icon="fas fa-user-tie" title="Coordenadores Registrados" :value="stats.totalCoordinators"
                color="info" />
            <DashboardKpiCard icon="fas fa-dollar-sign" title="Orçamento Total Projetos"
                :value="formatCurrency(stats.totalProjectPrice)" color="warning" />
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card shadow mb-4 h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Projetos por Categoria</h6>
                    </div>
                    <div class="card-body">
                        <DoughnutChart :data="stats.categoryDistribution" />
                        <p class="text-muted mt-3 text-center">
                            Distribuição percentual do total de projetos agrupados por categoria.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow mb-4 h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Orçamento por Categoria</h6>
                    </div>
                    <div class="card-body">
                        <BarChart :data="stats.categoryPrice" :currencyFormatter="formatCurrency" />
                        <p class="text-muted mt-3 text-center">
                            Soma do orçamento por cada categoria de projeto.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {Head} from '@inertiajs/vue3'
    import {defineProps, computed} from 'vue'
    import DashboardKpiCard from '@/Components/DashboardKpiCard.vue'

    import DoughnutChart from '@/Components/Charts/DoughnutChart.vue'
    import BarChart from '@/Components/Charts/BarChart.vue'

    const props = defineProps({
        data: {
            type: Object,
            required: true,
        },
    })

    const stats = computed(() => {
        const raw = props.data

        const totalProjects = raw.count_projects || 0
        const totalCollaborators = raw.count_collaborators || 0
        const totalCoordinators = raw.count_coordinators || 0
        const totalProjectPrice = raw.price_projects || 0

        const categoryDistribution = {}
        const categoryPrice = {}

        if (Array.isArray(raw.projects)) {
            raw.projects.forEach(p => {
                categoryDistribution[p.category] = parseInt(p.total) || 0
                categoryPrice[p.category] = parseFloat(p.price) || 0
            })
        }

        return {
            totalProjects,
            totalCollaborators,
            totalCoordinators,
            totalProjectPrice,
            categoryDistribution,
            categoryPrice,
        }
    })

    const formatCurrency = value =>
        new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL',
        }).format(parseFloat(value) || 0)
</script>

<style scoped>
    .card-header {
        background-color: transparent;
        border-bottom: 1px solid #e3e6f0;
    }

    .chart-area {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
