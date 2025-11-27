<template>
    <div class="dashboard-wrapper">
        <nav id="sidenav-1" class="sidenav shadow-3 fixed-left" :class="{ 'sidenav-hidden': !isSideNavOpen }"
            data-mdb-fixed="true">
            <a href="/" class="sidenav-header">
                <span class="fs-4 text-white">{{ appName }}</span>
            </a>

            <ul class="sidenav-menu">

                <li>
                    <h6 class="text-white mx-3 mt-3 mb-1">NAVEGAÇÃO</h6>
                </li>
                <SideNavItem :href="route('dashboard')" label="Início" iconClass="fas fa-home" />

                <li>
                    <h6 class="text-white mx-3 mt-3 mb-1">Informações</h6>
                </li>

                <SideNavItem :href="route('projects.index')" label="Projetos" iconClass="fas fa-list-check" />

                <template v-if="hasRole(['admin', 'coordinator'])">
                    <SideNavItem :href="route('collaborators.index')" label="Colaboradores"
                        iconClass="fas fa-user-friends" />

                    <SideNavItem :href="route('collaborators.heatmap')" label="Parceiros"
                        iconClass="fas fa-globe-americas" />
                </template>

                <template v-if="hasRole('admin')">
                    <li>
                        <h6 class="text-white mx-3 mt-3 mb-1">ADMINISTRAÇÃO</h6>
                    </li>

                    <SideNavItem :href="route('users.index')" label="Usuários" iconClass="fas fa-users" />
                </template>
            </ul>
        </nav>
        <main id="main-content-area" :style="{ marginLeft: isSideNavOpen ? '240px' : '0' }" class="main-content">
            <nav class="navbar navbar-light bg-white shadow-0 border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" @click="toggleSideNav">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div></div>

                    <div class="d-flex align-items-center">

                        <div class="d-none d-md-block text-end me-3">
                            <p class="mb-0 fw-bold">{{ userName }}</p>
                            <span class="badge bg-primary">{{ userRole.name }}</span>
                        </div>

                        <div class="dropdown">
                            <a class="d-flex align-items-center hidden-arrow" href="#" id="navbarDropdownMenuAvatar"
                                role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                                <li>
                                    <Link href="route('profile.edit')" class="dropdown-item">
                                    <i class="fas fa-user-cog me-2"></i>Meu Perfil
                                    </Link>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <Link :href="route('logout')" method="post" as="button"
                                        class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Sair
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="content-body p-4">
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
    import {ref, onMounted, computed} from 'vue';
    import {Link, usePage} from '@inertiajs/vue3';
    import SideNavItem from '@/Components/SideNavItem.vue';
    import {route} from 'ziggy-js';
    import {useAuthRole} from '@/Composables/useAuthRole';
    const page = usePage();
    const isSideNavOpen = ref(true);

    const appName = computed(() => page.props.appName || 'Dashboard');
    const user = computed(() => page.props.auth?.user);
    const {userRole, hasRole} = useAuthRole();
    const userName = computed(() => user.value?.name || 'Usuário');

    const toggleSideNav = () => {
        isSideNavOpen.value = !isSideNavOpen.value;
    };

    const initializeMDB = () => {
        if (typeof mdb !== 'undefined') {
            if (mdb.Sidenav) {
                const sidenavEl = document.getElementById('sidenav-1');
                if (!mdb.Sidenav.getInstance(sidenavEl)) {
                    new mdb.Sidenav(sidenavEl, {
                        sidenav: {
                            mode: 'side'
                        }
                    });
                }
            }

            if (mdb.Dropdown) {
                const dropdownEl = document.getElementById('navbarDropdownMenuAvatar');
                if (dropdownEl && !mdb.Dropdown.getInstance(dropdownEl)) {
                    new mdb.Dropdown(dropdownEl);
                }
            }
        }
    };

    onMounted(() => {
        initializeMDB();
    });
</script>

<style scoped>
    .sidenav-header {
        display: block;
        padding: 20px 15px;
        background-color: #272727;
        text-decoration: none;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px -6px #000;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .sidenav-menu {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 10px 0;
        margin: 0;
    }

    .dashboard-wrapper {
        display: flex;
        min-height: 100vh;
    }

    .sidenav {
        position: fixed;
        height: 100vh;
        width: 240px;
        transition: transform 0.3s ease-in-out;
        z-index: 1030;
        background-color: #333;
    }

    .sidenav-hidden {
        transform: translateX(-100%);
    }

    .main-content {
        flex-grow: 1;
        transition: margin-left 0.3s ease-in-out;
    }

    @media (max-width: 767px) {

        .d-md-block {
            display: none !important;
        }
    }
</style>
