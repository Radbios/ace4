<template>

    <Head title="GESTIC - Projetos" />

    <div class="container-fluid pb-compensation">

        <h2 class="mb-4">📋 Gerenciamento de Projetos</h2>

        <form @submit.prevent="filterProjects" class="mb-5 p-4 shadow-lg bg-white rounded-3">
            <div class="row g-4 align-items-end">

                <div class="col-md-6 col-lg-3">
                    <div class="form-outline">
                        <input type="text" id="search" class="form-control" v-model="form.search"
                            @keyup.enter="filterProjects" />
                        <label class="form-label" for="search">Buscar por Nome/Descrição</label>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <label class="form-label" for="coordinator_filter">Coordenador</label>
                    <select id="coordinator_filter" class="form-select" v-model="form.coordinator_id"
                        @change="filterProjects">
                        <option value="all">Todos os Coordenadores</option>
                        <option v-if="hasRole('coordinator')" value="my">Meus Projetos</option>
                        <option v-for="coord in coordinators" :key="coord.id" :value="coord.id">
                            {{ coord.name }}
                        </option>
                    </select>
                </div>

                <div class="col-md-6 col-lg-3">
                    <label class="form-label" for="category_filter">Categoria</label>
                    <select id="category_filter" class="form-select" v-model="form.category" @change="filterProjects">
                        <option value="all">Todas as Categorias</option>
                        <option v-for="cat in categories" :key="cat" :value="cat">
                            {{ cat }}
                        </option>
                    </select>
                </div>

                <div class="col-md-6 col-lg-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i> Aplicar
                    </button>
                    <button type="button" @click="resetFilters" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i> Limpar
                    </button>
                </div>

            </div>
        </form>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col" v-for="project in projects?.data" :key="project.id">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-subtitle mb-2 text-dark text-uppercase">
                            <i class="fas fa-tag me-1"></i>
                            {{ project.category }}
                        </h6>
                        <h5 class="card-title text-primary fw-bold mb-3">{{ project.name }}</h5>
                        <p class="card-text text-truncate-3">
                            {{ project.description }}
                        </p>
                        <div class="mt-auto pt-3 border-top">
                            <p class="mb-1 text-dark">
                                <i class="fas fa-user-tie me-2 text-secondary"></i>
                                Coordenador: {{ project.coordinator?.name }}
                            </p>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top-0 text-end">
                        <button type="button" @click="openDetailsModal(project)" class="btn btn-sm btn-outline-primary">
                            Ver Mais <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>

                </div>
            </div>

            <div v-if="projects?.data && projects.data.length === 0" class="col-12">
                <div class="alert alert-info text-center">
                    Nenhum projeto encontrado com os filtros atuais.
                </div>
            </div>
        </div>

        <div class="mt-4" v-if="projects?.links && projects?.data && projects.data.length > 0">
            <Pagination :data="projects" />
        </div>
    </div>

    <ProjectDetailsModal :show="showModal" :project="selectedProject" @close="showModal = false" />
</template>

<script setup>
    import {Head, Link, router} from '@inertiajs/vue3';
    import {onMounted, reactive, ref, nextTick} from 'vue';
    import {route} from 'ziggy-js';
    import Pagination from '@/Components/Pagination.vue';
    import ProjectDetailsModal from '@/Components/ProjectDetailsModal.vue';
    import {useAuthRole} from '@/Composables/useAuthRole';

    const {userRole, hasRole} = useAuthRole();

    const props = defineProps({
        projects: {type: Object, default: () => ({data: [], links: []})},
        filters: {type: Object, default: () => ({search: '', coordinator_id: 'all', category: 'all'})},
        coordinators: {type: Array, default: () => []},
        categories: {type: Array, default: () => []},
    });

    const form = reactive({
        search: props.filters.search ?? '',
        coordinator_id: props.filters.coordinator_id || 'all',
        category: props.filters.category || 'all',
    });

    const showModal = ref(false);
    const selectedProject = ref(null);

    const openDetailsModal = (project) => {
        selectedProject.value = project;
        showModal.value = true;
    };

    const filterProjects = () => {
        const params = Object.keys(form).reduce((acc, key) => {
            if (form[key] && form[key] !== 'all') {
                acc[key] = form[key];
            }
            return acc;
        }, {});

        router.get(route('projects.index'), params, {
            preserveState: true,
            replace: true,
            only: ['projects', 'filters', 'coordinators', 'categories'],
            onFinish: () => {
                updateMDBInputs();
            }
        });
    };

    const resetFilters = () => {
        form.search = '';
        form.coordinator_id = 'all';
        form.category = 'all';
        filterProjects();
    };

    const updateMDBInputs = () => {
        if (typeof mdb !== 'undefined' && mdb.Input) {
            nextTick(() => {
                document.querySelectorAll('.form-outline').forEach((formOutline) => {
                    const mdbInstance = mdb.Input.getInstance(formOutline) || new mdb.Input(formOutline);
                    mdbInstance.update();
                });
            });
        }
    }

    onMounted(() => {
        updateMDBInputs();
    });

    updateMDBInputs();
</script>

<style scoped>
    .text-truncate-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 1rem;
    }

    .pb-compensation {
        padding-bottom: 70px !important;
    }
</style>
