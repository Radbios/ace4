<template>
    <Teleport to="body">
        <Transition name="modal-fade">
            <div v-if="show" class="modal-backdrop" @click.self="emit('close')" aria-modal="true" role="dialog">
                <div class="modal-content-container shadow-2xl rounded-lg">

                    <div class="modal-header bg-primary text-white p-4 rounded-t-lg">
                        <h3 class="mb-0 fw-bold">
                            <i class="fas fa-user-circle me-2"></i> Detalhes do Usuário: {{ user?.name || 'N/A' }}
                        </h3>
                        <button type="button" class="btn-close-white" @click="emit('close')"
                            aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div v-if="user">

                            <h5 class="text-primary mb-3"><i class="fas fa-id-card me-2"></i> Informações Básicas</h5>
                            <div class="row g-3 border-bottom pb-3 mb-4">
                                <div class="col-md-6">
                                    <p class="mb-1 fw-bold text-dark"><i
                                            class="fas fa-envelope me-2 text-secondary"></i>
                                        Email:</p>
                                    <p class="ms-4 text-muted">{{ user.email || 'Não Informado' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 fw-bold text-dark"><i
                                            class="fas fa-id-badge me-2 text-secondary"></i>
                                        CPF:</p>
                                    <p class="ms-4 text-muted">{{ user.cpf || 'Não Informado' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 fw-bold text-dark"><i class="fas fa-phone me-2 text-secondary"></i>
                                        Telefone:</p>
                                    <p class="ms-4 text-muted">{{ user.phone || 'Não Informado' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 fw-bold text-dark"><i
                                            class="fas fa-shield-alt me-2 text-secondary"></i>
                                        Perfil:</p>
                                    <p class="ms-4">
                                        <span class="badge text-uppercase" :class="getRoleBadge(user.role?.name)">
                                            {{ user.role?.name || user.role || 'Indefinido' }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <h5 class="text-primary mb-3"><i class="fas fa-map-marked-alt me-2"></i> Localização</h5>
                            <div class="row g-3 mb-4">

                                <div class="col-md-6">
                                    <p class="mb-1 fw-bold text-dark"><i
                                            class="fas fa-location-arrow me-2 text-secondary"></i>
                                        CEP:</p>
                                    <p class="ms-4 text-muted">{{ user.cep || 'Não Informado' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <p class="mb-1 fw-bold text-dark"><i class="fas fa-city me-2 text-secondary"></i>
                                        Cidade:</p>
                                    <p class="ms-4 text-muted">{{ user.city || 'Não Informado' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <p class="mb-1 fw-bold text-dark"><i class="fas fa-flag me-2 text-secondary"></i>
                                        Estado:</p>
                                    <p class="ms-4 text-muted">{{ user.state || 'Não Informado' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 fw-bold text-dark"><i class="fas fa-globe me-2 text-secondary"></i>
                                        País:</p>
                                    <p class="ms-4 text-muted">{{ user.country || 'Não Informado' }}</p>
                                </div>
                            </div>

                            <h5 class="text-primary mb-3 border-top pt-3"><i class="fas fa-cogs me-2"></i> Status</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="mb-1 fw-bold text-dark"><i
                                            class="fas fa-power-off me-2 text-secondary"></i>
                                        Status de Ativação:</p>
                                    <p class="ms-4">
                                        <span
                                            :class="{'badge bg-success': user.is_activate, 'badge bg-danger': !user.is_activate}">
                                            {{ user.is_activate ? 'ATIVO' : 'INATIVO' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6" v-if="user.created_at">
                                    <p class="mb-1 fw-bold text-dark"><i class="fas fa-clock me-2 text-secondary"></i>
                                        Membro Desde:</p>
                                    <p class="ms-4 text-muted">{{ user.created_at }}</p>
                                </div>
                            </div>

                        </div>
                        <div v-else class="text-center text-danger p-5">
                            <i class="fas fa-exclamation-triangle me-2"></i> Usuário não encontrado ou dados não
                            carregados.
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-end p-3 border-t bg-light rounded-b-lg">
                        <button type="button" class="btn btn-secondary" @click="emit('close')">
                            <i class="fas fa-times me-1"></i> Fechar
                        </button>
                    </div>

                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
    import {watch} from 'vue';

    const props = defineProps({
        show: {
            type: Boolean,
            default: false,
        },
        user: {
            type: Object,
            default: null,
        },
    });

    const emit = defineEmits(['close']);

    const getRoleBadge = (roleName) => {
        switch (roleName?.toLowerCase()) {
            case 'admin':
            case 'administrador':
                return 'bg-danger text-white fw-bold';
            case 'manager':
            case 'gerente':
            case 'coordinator':
            case 'coordenador':
                return 'bg-warning text-dark fw-bold';
            case 'collaborator':
            case 'colaborador':
            case 'student':
            case 'estudante':
                return 'bg-success text-white fw-bold';
            default:
                return 'bg-secondary text-white';
        }
    };

    watch(() => props.show, (newValue) => {
        if (newValue) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = null;
        }
    });
</script>

<style scoped>
    .btn-close-white:before {
        content: "\00d7";
    }

    .btn-close-white {
        background: transparent;
        border: 0;
        color: white;
        font-size: 1.5rem;
        opacity: 0.8;
        cursor: pointer;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 2000;
    }

    .modal-content-container {
        background: white;
        border-radius: 0.5rem;
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        transform: translateY(0);
        transition: transform 0.3s ease-out;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-fade-enter-from,
    .modal-fade-leave-to {
        opacity: 0;
    }

    .modal-fade-enter-active,
    .modal-fade-leave-active {
        transition: opacity 0.3s ease;
    }

    .modal-fade-enter-from .modal-content-container,
    .modal-fade-leave-to .modal-content-container {
        transform: translateY(-50px);
    }

    .bg-primary {
        background-color: #007bff !important;
    }

    .text-primary {
        color: #007bff !important;
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
    }

    .bg-warning {
        background-color: #ffc107 !important;
    }

    .bg-secondary {
        background-color: #6c757d !important;
    }
</style>
