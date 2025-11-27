<template>
    <Head title="GESTIC - Colaboradores" />
    <div class="container-fluid pb-compensation">

        <h2 class="mb-4">👥 Gerenciamento de Colaboradores</h2>

        <form @submit.prevent="applyFilters" class="mb-5 p-4 shadow-lg bg-white rounded-3">
            <div class="row g-4 align-items-end">

                <div class="col-md-6 col-lg-3">
                    <div class="form-outline">
                        <input type="text" id="search" class="form-control" v-model="form.search"
                            @keyup.enter="applyFilters" />
                        <label class="form-label" for="search">Buscar por Nome/Email</label>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <label class="form-label" for="project_filter">Filtrar por Projeto</label>
                    <select class="form-select" id="project_filter" v-model.number="form.project_id"
                        @change="applyFilters">
                        <option :value="null">Todos os Projetos</option>
                        <option v-for="project in projects" :key="project.id" :value="project.id">
                            {{ project.name }}
                        </option>
                    </select>
                </div>

                <div class="col-md-6 col-lg-3"></div>

                <div class="col-md-6 col-lg-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i> Aplicar Filtros
                    </button>
                    <button type="button" @click="resetFilters" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i> Limpar
                    </button>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th @click="handleSort('name')"
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 cursor-pointer sortable-header"
                                        :class="{ 'text-info': form.sort_by === 'name' }">
                                        Nome / Email
                                        <i :class="getSortIcon('name')" class="ms-1"></i>
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Telefone
                                    </th>

                                    <th @click="handleSort('collaborators_count')"
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 cursor-pointer sortable-header"
                                        :class="{ 'text-info': form.sort_by === 'collaborators_count' }">
                                        Nº de Alocações
                                        <i :class="getSortIcon('collaborators_count')" class="ms-1"></i>
                                    </th>

                                    <th @click="handleSort('collaborators_sum_scholarship')"
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 cursor-pointer sortable-header"
                                        :class="{ 'text-info': form.sort_by === 'collaborators_sum_scholarship' }">
                                        Bolsa Total
                                        <i :class="getSortIcon('collaborators_sum_scholarship')" class="ms-1"></i>
                                    </th>

                                    <th @click="handleSort('country')"
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 cursor-pointer sortable-header"
                                        :class="{ 'text-info': form.sort_by === 'country' }">
                                        País
                                        <i :class="getSortIcon('country')" class="ms-1"></i>
                                    </th>

                                    <th class="text-secondary opacity-7">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users.data" :key="user.id">
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ user.name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ user.email }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ user.phone || 'N/A' }}</p>
                                    </td>

                                    <td>
                                        <span class="badge bg-info text-dark border border-2 border-info font-weight-bold">
                                            {{ user.collaborators_count }}
                                        </span>
                                    </td>

                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">R$
                                            {{ user.collaborators_sum_scholarship
                                                ? user.collaborators_sum_scholarship.toFixed(2).replace('.', ',')
                                                : '0,00'
                                            }}
                                        </p>
                                    </td>

                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold mb-0">{{ user.country }}</span>
                                    </td>

                                    <td class="align-middle">
                                        <button @click="openModal(user)" class="btn btn-sm btn-info text-white mb-0">
                                            Ver Mais
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4" v-if="users?.links && users?.data && users.data.length > 0">
            <Pagination :data="users" />
        </div>

    </div>

    <CollaboratorDetailsModal :user="selectedCollaborator" :show="showModal" @close="closeModal" />
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3'
import { reactive, onMounted, nextTick, ref } from 'vue'
import Pagination from '@/Components/Pagination.vue'
import CollaboratorDetailsModal from '@/Components/CollaboratorDetailsModal.vue'

const props = defineProps({
    users: {
        type: Object,
        required: true,
        default: () => ({ links: [], data: [] }),
    },
    projects: {
        type: Array,
        required: true,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ search: null, project_id: null, sort_by: 'name', sort_direction: 'asc' }),
    }
})

const form = reactive({
    search: props.filters.search || null,
    project_id: props.filters.project_id || null,
    sort_by: props.filters.sort_by || 'name',
    sort_direction: props.filters.sort_direction || 'asc',
})

const showModal = ref(false)
const selectedCollaborator = ref(null)

function openModal(user) {
    selectedCollaborator.value = user
    showModal.value = true
}

function closeModal() {
    showModal.value = false
}

const handleSort = (column) => {
    if (form.sort_by === column) {
        form.sort_direction = form.sort_direction === 'asc' ? 'desc' : 'asc'
    } else {
        form.sort_by = column
        form.sort_direction = 'asc'
    }
    applyFilters()
}

const getSortIcon = (column) => {
    if (form.sort_by !== column) {
        return 'fas fa-sort text-secondary'
    }
    return form.sort_direction === 'asc'
        ? 'fas fa-sort-up text-info'
        : 'fas fa-sort-down text-info'
}

const applyFilters = () => {
    const queryParams = {}

    if (form.search) queryParams.search = form.search
    if (form.project_id !== null && form.project_id !== '') queryParams.project_id = form.project_id

    queryParams.sort_by = form.sort_by
    queryParams.sort_direction = form.sort_direction

    router.get(window.location.pathname, queryParams, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onFinish: () => updateMDBInputs()
    })
}

const resetFilters = () => {
    form.search = null
    form.project_id = null
    form.sort_by = 'name'
    form.sort_direction = 'asc'
    applyFilters()
}

const updateMDBInputs = () => {
    if (typeof mdb !== 'undefined' && mdb.Input) {
        nextTick(() => {
            document.querySelectorAll('.form-outline').forEach((formOutline) => {
                const mdbInstance = mdb.Input.getInstance(formOutline) || new mdb.Input(formOutline)
                mdbInstance.update()
            })
        })
    }
}

onMounted(() => {
    updateMDBInputs()
})

updateMDBInputs()
</script>

<style scoped>
.sortable-header {
    cursor: pointer;
    transition: color 0.15s ease-in-out;
}

.bg-gradient-primary {
    background-image: linear-gradient(195deg, #42424a, #191919) !important;
}

.pb-compensation {
    padding-bottom: 70px !important;
}
</style>
