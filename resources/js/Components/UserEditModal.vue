<template>
    <Teleport to="body">
        <Transition name="modal-fade">
            <div v-if="show" class="modal-backdrop" @click.self="emitClose" aria-modal="true" role="dialog">
                <div ref="modalContainerRef" class="modal-content-container shadow-2xl rounded-lg">

                    <div class="modal-header bg-primary text-white p-4 rounded-t-lg">
                        <h3 class="mb-0 fw-bold">
                            <i :class="isCreating ? 'fas fa-user-plus' : 'fas fa-user-pen'" class="me-2"></i>
                            {{ modalTitle }}
                        </h3>
                        <button type="button" class="btn-close-white" @click="emitClose" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body p-4">
                        <form @submit.prevent="submitForm" class="row g-4">
                            <div class="col-12">
                                <h4 class="mb-3 text-primary"><i class="fas fa-id-card me-2"></i> Informações Gerais
                                </h4>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="name" class="form-control" v-model="form.name" required
                                        :class="{ 'is-invalid': form.errors.name }" />
                                    <label class="form-label" for="name">Nome Completo</label>
                                    <div v-if="form.errors.name" class="invalid-feedback">
                                        {{ form.errors.name }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="email" id="email" class="form-control" v-model="form.email" required
                                        :class="{ 'is-invalid': form.errors.email }" />
                                    <label class="form-label" for="email">Email</label>
                                    <div v-if="form.errors.email" class="invalid-feedback">
                                        {{ form.errors.email }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-outline">
                                    <input type="text" id="cpf" class="form-control" v-model="form.cpf"
                                        :class="{ 'is-invalid': form.errors.cpf }" />
                                    <label class="form-label" for="cpf">CPF</label>
                                    <div v-if="form.errors.cpf" class="invalid-feedback">
                                        {{ form.errors.cpf }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-outline">
                                    <input type="text" id="phone" class="form-control" v-model="form.phone"
                                        :class="{ 'is-invalid': form.errors.phone }" />
                                    <label class="form-label" for="phone">Telefone (Opcional)</label>
                                    <div v-if="form.errors.phone" class="invalid-feedback">
                                        {{ form.errors.phone }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <select id="role" class="form-select" v-model="form.role" required
                                    :class="{ 'is-invalid': form.errors.role }">
                                    <option value="" disabled>Selecione a Permissão</option>

                                    <option v-for="role in roles" :key="role.name" :value="role.name">
                                        {{ role.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.role" class="invalid-feedback">
                                    {{ form.errors.role }}
                                </div>
                            </div>

                            <div class="col-12">
                                <hr class="my-2">
                                <h4 class="mb-3 text-primary"><i class="fas fa-map-marked-alt me-2"></i> Localização
                                </h4>
                            </div>

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="cep" class="form-control" v-model="form.cep"
                                        :class="{ 'is-invalid': form.errors.cep }" />
                                    <label class="form-label" for="cep">CEP</label>
                                    <div v-if="form.errors.cep" class="invalid-feedback">
                                        {{ form.errors.cep }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="city" class="form-control" v-model="form.city"
                                        :class="{ 'is-invalid': form.errors.city }" />
                                    <label class="form-label" for="city">Cidade</label>
                                    <div v-if="form.errors.city" class="invalid-feedback">
                                        {{ form.errors.city }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="state" class="form-control" v-model="form.state"
                                        :class="{ 'is-invalid': form.errors.state }" />
                                    <label class="form-label" for="state">Estado</label>
                                    <div v-if="form.errors.state" class="invalid-feedback">
                                        {{ form.errors.state }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="country" class="form-control" v-model="form.country"
                                        :class="{ 'is-invalid': form.errors.country }" />
                                    <label class="form-label" for="country">País</label>
                                    <div v-if="form.errors.country" class="invalid-feedback">
                                        {{ form.errors.country }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <hr class="my-2">
                                <h4 class="mb-3 text-primary"><i class="fas fa-lock me-2"></i> Segurança</h4>
                            </div>

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="password" id="password" class="form-control" v-model="form.password"
                                        :required="isCreating" :class="{ 'is-invalid': form.errors.password }" />
                                    <label class="form-label" for="password">
                                        {{ isCreating ? 'Senha *' : 'Nova Senha (Opcional)' }}
                                    </label>
                                    <div v-if="form.errors.password" class="invalid-feedback">
                                        {{ form.errors.password }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="password" id="password_confirmation" class="form-control"
                                        v-model="form.password_confirmation" :required="isCreating"
                                        :class="{ 'is-invalid': form.errors.password_confirmation }" />
                                    <label class="form-label" for="password_confirmation">Confirmar Senha</label>
                                    <div v-if="form.errors.password_confirmation" class="invalid-feedback">
                                        {{ form.errors.password_confirmation }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="form-check form-switch d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" id="is_activate"
                                        v-model="form.is_activate" />
                                    <label class="form-check-label ms-3 fw-bold text-dark" for="is_activate">
                                        Usuário Ativo
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer d-flex justify-content-between p-3 border-t bg-light rounded-b-lg">
                        <button type="button" class="btn btn-secondary" @click="emitClose">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        <button type="button" class="btn btn-primary d-flex align-items-center" @click="submitForm"
                            :disabled="form.processing">
                            <span v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>
                            <i v-else class="fas fa-save me-2"></i>{{ isCreating ? 'Criar Usuário' : 'Salvar Alterações'
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
    import {ref, watch, nextTick, computed} from "vue";
    import {useForm, router} from "@inertiajs/vue3";
    const route = window.route;

    const props = defineProps({
        show: Boolean,
        initialUser: {
            type: Object,
            default: null,
        },
        roles: {
            type: Array,
            required: true,
            default: () => [],
        }
    });
    const emit = defineEmits(["close"]);
    const modalContainerRef = ref(null);

    const isCreating = computed(() => !props.initialUser || !props.initialUser.id);

    const modalTitle = computed(() =>
        isCreating.value ? 'Criar Novo Usuário' : `Editar Usuário: ${props.initialUser?.name || 'Carregando...'}`
    );

    const updateMDBInputs = () => {
        if (!window.mdb || !window.mdb.Input) return;
        if (!modalContainerRef.value) return;

        const textElements = modalContainerRef.value.querySelectorAll(".form-outline");
        textElements.forEach((el) => {
            if (!el) return;
            try {
                const instance =
                    window.mdb.Input.getInstance(el) || new window.mdb.Input(el);
                instance.update();
            } catch (e) {
                console.warn("Erro ao atualizar MDB Input:", e);
            }
        });

        if (window.mdb && window.mdb.Select) {
            const selectEl = modalContainerRef.value.querySelector("#role");
            if (selectEl) {
                const instance = window.mdb.Select.getInstance(selectEl) || new window.mdb.Select(selectEl);
                instance.update();
            }
        }
    };

    const form = useForm({
        name: "",
        email: "",
        cpf: "",
        phone: "",
        role: "",
        cep: "",
        city: "",
        state: "",
        country: "",
        password: "",
        password_confirmation: "",
        is_activate: true,
    });

    function emitClose() {
        emit("close");
        form.defaults().reset();
    }

    const submitForm = () => {
        const options = {
            preserveScroll: true,
            onSuccess: () => {
                form.reset('password', 'password_confirmation');
                emitClose();
                router.reload({only: ["users"]});
            },
            onError: () => nextTick(updateMDBInputs),
            onFinish: () => nextTick(updateMDBInputs),
        };

        if (isCreating.value) {
            form.post(route("users.store"), options);
        } else {
            if (!props.initialUser || !props.initialUser.id) {
                console.error("Erro: ID do usuário não fornecido para atualização.");
                return;
            }
            form.patch(route("users.update", props.initialUser.id), options);
        }
    };

    watch(() => props.initialUser, (newUser) => {
        form.clearErrors();

        if (newUser) {
            form.defaults({
                name: newUser.name || "",
                email: newUser.email || "",
                cpf: newUser.cpf || "",
                phone: newUser.phone || "",
                role: newUser.role?.name || newUser.role || "",

                cep: newUser.cep || "",
                city: newUser.city || "",
                state: newUser.state || "",
                country: newUser.country || "",
                password: "",
                password_confirmation: "",
                is_activate: newUser.is_activate ?? true,
            }).reset();
        } else {
            form.defaults
                ({
                    role: "",
                }).reset();
        }

        nextTick(updateMDBInputs);

    }, {immediate: true});

    watch(
        () => props.show,
        (val) => {
            document.body.style.overflow = val ? "hidden" : "";

            if (val) {
                nextTick(updateMDBInputs);
            }
        }
    );
</script>

<style scoped>
    .text-primary {
        color: #007bff !important;
    }

    .bg-primary {
        background-color: #007bff !important;
    }

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
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 2000;
    }

    .modal-content-container {
        background: white;
        border-radius: 8px;
        width: 95%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
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
        transition: opacity 0.3s;
    }

    .form-outline .form-control:not(.placeholder-shown)~.form-label,
    .form-outline .form-control:focus~.form-label,
    .form-outline .form-select:not([size]):not([multiple])~.form-label {
        transform: translateY(-1rem) scale(0.8);
        background-color: white;
        padding: 0 0.2rem;
        margin-left: 0.5rem;
    }

    .form-check-input:checked {
        background-color: var(--bs-primary, #007bff);
        border-color: var(--bs-primary, #007bff);
    }
</style>
