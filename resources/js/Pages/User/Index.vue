<template>

    <Head title="GESTIC - Usuários" />
    <div class="container-fluid pb-compensation">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">🔑 Gerenciamento de Usuários</h2>
            <button type="button" @click="openCreateModal" class="btn btn-success d-flex align-items-center shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Criar Novo Usuário
            </button>
        </div>

        <form @submit.prevent="applyFilters" class="mb-5 p-4 shadow-lg bg-white rounded-3">
            <div class="row g-4 align-items-end">

                <div class="col-md-6 col-lg-4">
                    <div class="form-outline">
                        <input type="text" id="search" class="form-control" v-model="form.search"
                            @keyup.enter="applyFilters" />
                        <label class="form-label" for="search">Buscar Nome, Email, CPF, Tel</label>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <label class="form-label select-label" for="roleFilter">Filtrar por Perfil</label>
                    <select id="roleFilter" class="form-select" v-model="form.role" @change="applyFilters">
                        <option :value="null">Todos os Perfis</option>

                        <option v-for="role in roles" :key="role.id" :value="role.id">
                            {{ role.name }}
                        </option>

                    </select>
                </div>

                <div class="col-md-6 col-lg-3">
                    <label class="form-label select-label" for="statusFilter">Filtrar por Status</label>
                    <select id="statusFilter" class="form-select" v-model="form.is_activate" @change="applyFilters">
                        <option :value="null">Todos os Status</option>
                        <option :value="1">Ativo</option>
                        <option :value="0">Inativo</option>
                    </select>
                </div>

                <div class="col-md-12 col-lg-2 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2 flex-grow-1">
                        <i class="fas fa-search me-1"></i> Filtrar
                    </button>
                    <button type="button" @click="resetFilters" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>
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
                                    <th @click="handleSort('cpf')"
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3 cursor-pointer sortable-header"
                                        :class="{ 'text-info': form.sort_by === 'cpf' }">
                                        CPF
                                        <i :class="getSortIcon('cpf')" class="ms-1"></i>
                                    </th>

                                    <th @click="handleSort('name')"
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 cursor-pointer sortable-header"
                                        :class="{ 'text-info': form.sort_by === 'name' }">
                                        Nome / Email
                                        <i :class="getSortIcon('name')" class="ms-1"></i>
                                    </th>

                                    <th @click="handleSort('role')"
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 cursor-pointer sortable-header"
                                        :class="{ 'text-info': form.sort_by === 'role' }">
                                        Perfil
                                        <i :class="getSortIcon('role')" class="ms-1"></i>
                                    </th>

                                    <th @click="handleSort('phone')"
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 cursor-pointer sortable-header"
                                        :class="{ 'text-info': form.sort_by === 'phone' }">
                                        Telefone
                                        <i :class="getSortIcon('phone')" class="ms-1"></i>
                                    </th>

                                    <th @click="handleSort('is_activate')"
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 cursor-pointer sortable-header"
                                        :class="{ 'text-info': form.sort_by === 'is_activate' }">
                                        Status
                                        <i :class="getSortIcon('is_activate')" class="ms-1"></i>
                                    </th>

                                    <th class="text-secondary opacity-7">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users.data" :key="user.id">
                                    <td class="ps-3">
                                        <p class="text-xs font-weight-bold mb-0">{{ user.cpf || 'N/A' }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ user.name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ user.email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" :class="getRoleBadge(user.role?.name)">
                                            {{ user.role?.name || 'Indefinido' }}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ user.phone || 'N/A' }}</p>
                                    </td>
                                    <td>
                                        <button @click="toggleStatus(user)" class="btn btn-sm mb-0 p-2 me-2"
                                            :class="getStatusClasses(user.is_activate)">
                                            <i class="fas me-1"
                                                :class="user.is_activate ? 'fa-check-circle' : 'fa-times-circle'"></i>
                                            {{ getStatusText(user.is_activate) }}
                                        </button>
                                    </td>
                                    <td class="align-middle">
                                        <button @click="openEditModal(user)"
                                            class="btn btn-sm btn-warning text-white mb-0 me-2">
                                            <i class="fas fa-edit"></i> Atualizar
                                        </button>

                                        <button @click="openModal(user)"
                                            class="btn btn-sm btn-info text-white mb-0 me-2">
                                            <i class="fas fa-eye"></i> Ver Mais
                                        </button>
                                        <button @click="confirmDelete(user)"
                                            class="btn btn-sm btn-danger text-white mb-0">
                                            <i class="fas fa-trash-alt"></i> Deletar
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="!users.data || users.data.length === 0">
                                    <td colspan="6" class="text-center py-4 text-secondary">
                                        Nenhum usuário encontrado com os filtros aplicados.
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

    <UserDetailsModal :user="selectedUser" :show="showModal" @close="closeModal" />

    <UserCreateModal :show="showCreateModal" :roles="roles" @close="closeCreateModal" />
    <UserEditModal :show="showEditModal" :roles="roles" :initialUser="selectedUserForEdit" @close="closeEditModal" />
</template>

<script setup>
    import {reactive, onMounted, nextTick, ref} from 'vue';
    import {router} from '@inertiajs/vue3';
    import UserEditModal from '@/Components/UserEditModal.vue';
    import {Head} from '@inertiajs/vue3'
    const route = window.route;


    const showEditModal = ref(false);
    const selectedUserForEdit = ref(null);

    import Pagination from '@/Components/Pagination.vue';
    import UserDetailsModal from '@/Components/UserDetailsModal.vue';
    import UserCreateModal from '@/Components/UserCreateModal.vue';

    const props = defineProps({
        users: {
            type: Object,
            required: true,
            default: () => ({links: [], data: []}),
        },
        filters: {
            type: Object,
            default: () => ({search: null, role: null, is_activate: null, sort_by: 'name', sort_direction: 'asc'}),
        },
        roles: {
            type: Array,
            default: () => ([]),
        },
    });

    let rawStatus = props.filters.is_activate;
    let rawRole = props.filters.role;

    const form = reactive({
        search: props.filters.search || null,
        role: rawRole === null || rawRole === undefined || rawRole === ''
            ? null
            : Number(rawRole),
        is_activate: rawStatus === null || rawStatus === undefined || rawStatus === ''
            ? null
            : Number(rawStatus),
        sort_by: props.filters.sort_by || 'name',
        sort_direction: props.filters.sort_direction || 'asc',
    });

    const showModal = ref(false);
    const selectedUser = ref(null);

    const showCreateModal = ref(false);

    function openEditModal(user) {
        selectedUserForEdit.value = user;
        showEditModal.value = true;
    }

    function closeEditModal() {
        showEditModal.value = false;
        selectedUserForEdit.value = null;
    }

    function openModal(user) {
        selectedUser.value = JSON.parse(JSON.stringify(user));
        showModal.value = true;
    }

    function closeModal() {
        showModal.value = false;
        selectedUser.value = null;
    }

    function openCreateModal() {
        showCreateModal.value = true;
    }

    function closeCreateModal() {
        showCreateModal.value = false;
    }

    const handleSort = (column) => {
        if (form.sort_by === column) {
            form.sort_direction = form.sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            form.sort_by = column;
            form.sort_direction = 'asc';
        }

        applyFilters();
    };

    const getSortIcon = (column) => {
        if (form.sort_by !== column) {
            return 'fas fa-sort text-secondary';
        }

        return form.sort_direction === 'asc'
            ? 'fas fa-sort-up text-info'
            : 'fas fa-sort-down text-info';
    };

    const applyFilters = () => {
        const queryParams = {};

        if (form.search) {
            queryParams.search = form.search;
        }

        if (form.role !== null) {
            queryParams.role = String(form.role);
        }

        if (form.is_activate !== null) {
            queryParams.is_activate = form.is_activate;
        }

        if (form.sort_by) {
            queryParams.sort_by = form.sort_by;
            queryParams.sort_direction = form.sort_direction;
        }

        router.get(
            window.location.pathname,
            queryParams,
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                onFinish: () => {
                    updateMDBInputs();
                }
            }
        );
    };

    const resetFilters = () => {
        form.search = null;
        form.role = null;
        form.is_activate = null;
        form.sort_by = 'name';
        form.sort_direction = 'asc';
        applyFilters();
    };

    const getStatusText = (isActivate) => {
        const isActive = isActivate === true || isActivate === 1;
        return isActive ? 'Ativo' : 'Inativo';
    };

    const getStatusClasses = (isActivate) => {
        const isActive = isActivate === true || isActivate === 1;
        return isActive
            ? 'btn-success text-white'
            : 'btn-danger text-white';
    };

    const toggleStatus = (user) => {
        if (typeof route === 'undefined' || !route) {
            console.error("A função 'route' do Ziggy não está disponível.");
            return;
        }
        router.put(
            route('users.toggle_status', {user: user.id}),
            {},
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    console.log(`Status do usuário ${user.id} atualizado com sucesso via Inertia.`);
                },
                onError: (errors) => {
                    console.error("Erro ao tentar atualizar o status:", errors);
                }
            }
        );
    };

    const confirmDelete = (user) => {

        if (typeof route === 'undefined' || !route) {
            console.error("Erro: Função de rota do Ziggy não definida. Não é possível deletar.");
            return;
        }
        router.delete(
            route('users.destroy', {user: user.id}),
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (message) => {
                    console.log(message)
                    console.log(`Usuário ${user.id} deletado com sucesso.`);
                },
                onError: (errors) => {
                    console.error("Erro ao tentar deletar o usuário:", errors);
                }
            }
        );
    };

    const getRoleBadge = (role) => {
        switch (role?.toLowerCase()) {
            case 'admin':
                return 'bg-danger text-white font-weight-bold';
            case 'coordinator':
                return 'bg-warning text-dark font-weight-bold';
            case 'student':
                return 'bg-success text-white font-weight-bold';
            default:
                return 'bg-secondary text-white';
        }
    };

    const updateMDBInputs = () => {
        if (typeof mdb !== 'undefined' && mdb.Input) {
            nextTick(() => {
                document.querySelectorAll('.form-outline').forEach((formOutline) => {
                    const mdbInstance = mdb.Input.getInstance(formOutline) || new mdb.Input(formOutline);
                    mdbInstance.update();
                });
                document.querySelectorAll('.form-select').forEach((select) => {
                    if (typeof mdb.Select !== 'undefined') {
                        const selectInstance = mdb.Select.getInstance(select) || new mdb.Select(select);
                        selectInstance.update();
                    }
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
    .sortable-header {
        cursor: pointer;
        transition: color 0.15s ease-in-out;
    }

    .pb-compensation {
        padding-bottom: 70px !important;
    }
</style>
