<template>
    <Teleport to="body">
        <Transition name="modal-fade">
            <div v-if="show" class="modal-backdrop" @click.self="$emit('close')" aria-modal="true" role="dialog">
                <div class="modal-content-container shadow-2xl rounded-lg">

                    <div class="modal-header bg-primary text-white p-4 rounded-t-lg">
                        <h3 class="mb-0 fw-bold">{{ project?.name || 'Detalhes do Projeto' }}</h3>
                        <button type="button" class="btn-close-white" @click="$emit('close')"
                            aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div v-if="project">

                            <h5 class="text-primary mb-2">Descrição</h5>
                            <p class="text-muted border-bottom pb-3 mb-4">{{ project.description }}</p>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <p class="mb-1 fw-bold text-dark"><i class="fas fa-tag me-2 text-secondary"></i>
                                        Categoria:</p>
                                    <p class="ms-4 text-muted">{{ project.category }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1 fw-bold text-dark"><i
                                            class="fas fa-users-cog me-2 text-secondary"></i> Máx. Colaboradores:</p>
                                    <p class="ms-4 text-muted">{{ project.max_collaborators || 'Não definido' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1 fw-bold text-dark"><i
                                            class="fas fa-user-tie me-2 text-secondary"></i> Coordenador:</p>
                                    <p class="ms-4 text-muted">{{ project.coordinator?.name || 'N/A' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1 fw-bold text-dark"><i
                                            class="fas fa-envelope me-2 text-secondary"></i> Email Coordenador:</p>
                                    <p class="ms-4 text-muted">{{ project.coordinator?.email || 'N/A' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1 fw-bold text-dark"><i
                                            class="fas fa-sync-alt me-2 text-secondary"></i> É Assinatura?</p>
                                    <p class="ms-4 text-muted">
                                        <span
                                            :class="{'badge bg-success': project.is_subscription, 'badge bg-danger': !project.is_subscription}">
                                            {{ project.is_subscription ? 'Sim' : 'Não' }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <h5 class="text-primary my-4 border-top pt-4"><i class="fas fa-user-friends me-2"></i>
                                Colaboradores Atuais ({{ project.collaborators ? project.collaborators.length : 0 }})
                            </h5>
                            <div class="collaborators-list">
                                <ul class="list-group" v-if="project.collaborators && project.collaborators.length > 0">
                                    <li v-for="collab in project.collaborators" :key="collab.id"
                                        class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-user-circle me-2 text-secondary"></i>
                                            <strong>{{ collab.user.name }}</strong>
                                            <small class="text-muted ms-3">({{ collab.role || 'Função não definida'
                                                }})</small>
                                        </div>
                                        <span class="badge bg-info text-dark rounded-pill">{{ collab.user.email }}</span>
                                    </li>
                                </ul>
                                <div v-else class="text-center text-muted p-3 border rounded">
                                    Nenhum colaborador associado a este projeto ainda.
                                </div>
                            </div>

                        </div>
                        <div v-else class="text-center text-danger p-5">
                            Projeto não encontrado.
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-end p-3 border-t">
                        <button type="button" class="btn btn-secondary" @click="$emit('close')">
                            Fechar
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
        project: {
            type: Object,
            default: null,
        },
    });

    const emit = defineEmits(['close']);

    watch(() => props.show, (newValue) => {
        if (newValue) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = null;
        }
    });
</script>

<style scoped>
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
        border-bottom: 1px solid #e9ecef;
    }

    .btn-close-white {
        background: transparent;
        border: 0;
        color: white;
        font-size: 1.5rem;
        opacity: 0.8;
        cursor: pointer;
    }

    .btn-close-white:before {
        content: "\00d7";
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
</style>
