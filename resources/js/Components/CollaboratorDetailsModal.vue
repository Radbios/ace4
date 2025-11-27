<template>
    <Teleport to="body">
        <Transition name="modal-fade">
            <div v-if="show" class="modal-backdrop" @click.self="$emit('close')" aria-modal="true" role="dialog">
                <div class="modal-content-container shadow-2xl rounded-lg">
                    <div class="modal-header bg-primary text-white p-4 rounded-t-lg">
                        <h3 class="mb-0 fw-bold">
                            Detalhes: {{ user?.name || 'Carregando...' }}
                        </h3>
                        <button type="button" class="btn-close-white" @click="emitClose" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div v-if="user && user.name">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-light p-3">
                                    <h5 class="mb-0 text-dark">
                                        <i class="fas fa-user-circle me-2 text-primary"></i> Informações Pessoais
                                    </h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item d-flex justify-content-between p-2">
                                            <strong class="text-secondary">Nome:</strong>
                                            <span>{{ user.name }}</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between p-2">
                                            <strong class="text-secondary">Email:</strong>
                                            <span>{{ user.email }}</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between p-2">
                                            <strong class="text-secondary">CPF:</strong>
                                            <span>{{ user.cpf || 'N/A' }}</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between p-2">
                                            <strong class="text-secondary">Telefone:</strong>
                                            <span>{{ user.phone || 'N/A' }}</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between p-2">
                                            <strong class="text-secondary">Localização:</strong>
                                            <span>{{ user.city }} ({{ user.state }})</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between p-2">
                                            <strong class="text-secondary">CEP:</strong>
                                            <span>{{ user.cep || 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm">
                                <div class="card-header bg-light p-3">
                                    <h5 class="mb-0 text-dark">
                                        <i class="fas fa-folder-open me-2 text-primary"></i> Projetos
                                        ({{user.collaborators_count}})
                                    </h5>
                                </div>
                                <div class="card-body p-3 collaborators-container">
                                    <div v-if="user.collaborators?.length">
                                        <div v-for="collaborator in user.collaborators" :key="collaborator.id"
                                            class="p-3 mb-3 border rounded shadow-sm-light bg-white">

                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="text-primary fw-bold mb-0 text-truncate me-3">
                                                    {{ collaborator.project?.name }}
                                                </h6>
                                                <span class="badge bg-success rounded-pill text-sm">{{
                                                    collaborator.role}}</span>
                                            </div>

                                            <div class="row text-sm g-2">
                                                <div class="col-12 col-md-6">
                                                    <strong class="text-secondary">Data de Início:</strong>
                                                    <span class="ms-1 text-muted">{{ formatDate(collaborator.created_at)
                                                        }}</span>
                                                </div>

                                                <div class="col-12 col-md-6 text-md-end">
                                                    <strong class="text-secondary">Bolsa:</strong>
                                                    <span class="ms-1 text-success fw-bold">{{
                                                        formatCurrency(collaborator.scholarship) }}</span>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <strong class="text-secondary">Coordenador:</strong>
                                                    <span class="ms-1 text-muted">{{
                                                        collaborator.project?.coordinator.name
                                                        }}</span>
                                                </div>

                                                <div class="col-12 mt-2 pt-2 border-top text-muted text-truncate"
                                                    style="max-height: 4.5rem;">
                                                    {{ collaborator.project?.description?.slice(0, 200) }}...
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div v-else class="text-center text-muted p-4">
                                        Nenhuma alocação de projeto encontrada.
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div v-else class="text-center text-muted p-4">
                            Carregando detalhes do colaborador...
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-end p-3 border-t">
                        <button type="button" class="btn btn-secondary" @click="emitClose">
                            Fechar
                        </button>
                    </div>

                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
    import {ref, watch} from 'vue';

    const props = defineProps({
        show: {type: Boolean, required: true},
        user: {
            type: Object,
            default: null,
        }
    });

    const emit = defineEmits(['close']);

    const formatCurrency = (v) => {
        const n = parseFloat(v);
        if (isNaN(n)) return "R$ 0,00";
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL',
            minimumFractionDigits: 2
        }).format(n);
    };

    const formatDate = (dateString) => {
        if (!dateString) return 'N/A';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR');
        } catch (e) {
            console.error("Erro ao formatar data:", e);
            return dateString;
        }
    };

    const emitClose = () => {
        emit('close');
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
        box-sizing: border-box;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #3f51b5;
        border-bottom: 1px solid #e9ecef;

        position: sticky;
        top: 0;
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        position: sticky;
        bottom: 0;
        z-index: 10;
        background-color: white;
        border-top: 1px solid #e9ecef;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.05);
    }

    .bg-primary {
        background-color: #3f51b5 !important;
    }

    .text-primary {
        color: #3f51b5 !important;
    }

    .text-secondary {
        color: #6c757d !important;
    }

    .btn-close-white {
        background: transparent;
        border: 0;
        color: white;
        font-size: 1.5rem;
        opacity: 0.8;
        cursor: pointer;
        line-height: 1;
    }

    .btn-close-white:hover {
        opacity: 1;
    }

    .btn-close-white:before {
        content: "\00d7";
    }

    .card-header {
        border-bottom: 1px solid #e9ecef;
    }

    .list-group-item {
        border: none;
    }

    .list-group-item:nth-child(even) {
        background-color: #f8f9fa;
    }

    .collaborators-container {
        max-height: 40vh;
        overflow-y: auto;
        padding-right: 1rem;
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

    @media (max-width: 768px) {
        .modal-content-container {
            max-width: 95%;
            margin: 1rem;
        }

        .text-md-end {
            text-align: left !important;
        }
    }

    .text-sm {
        font-size: 0.875rem;
    }
</style>
