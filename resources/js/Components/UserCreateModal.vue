<template>
    <Teleport to="body">
        <Transition name="modal-fade">
            <div v-if="show" class="modal-backdrop" @click.self="emitClose" aria-modal="true" role="dialog">
                <div ref="modalContainerRef" class="modal-content-container shadow-2xl rounded-lg">
                    <div class="modal-header bg-success text-white p-4 rounded-t-lg">
                        <h3 class="mb-0 fw-bold">
                            <i class="fas fa-user-plus me-2"></i> Criar Novo Usuário
                        </h3>
                        <button type="button" class="btn-close-white" @click="emitClose"></button>
                    </div>

                    <div class="modal-body p-4">
                        <form @submit.prevent="submitForm" class="row g-4">
                            <div class="col-12 mt-4">
                                <h4 class="mb-3">Informações Gerais</h4>
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
                                    <label class="form-label" for="cpf">CPF (Opcional)</label>
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
                                <select id="role" class="form-select" v-model="form.role" required>
                                    <option v-for="role in roles" :key="role.id" :value="role.name">
                                        {{ role.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.role" class="invalid-feedback">
                                    {{ form.errors.role }}
                                </div>
                            </div>

                            <div class="col-12">
                                <hr class="my-2">
                                <h4 class="mb-3">Endereço</h4>
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
                                <h4 class="mb-3">Segurança</h4>
                            </div>

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="password" id="password" class="form-control" v-model="form.password"
                                        required :class="{ 'is-invalid': form.errors.password }" />
                                    <label class="form-label" for="password">Senha</label>
                                </div>
                                <div v-if="form.errors.password" class="invalid-feedback d-block">
                                    {{ form.errors.password }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="password" id="password_confirmation" class="form-control"
                                        v-model="form.password_confirmation" required
                                        :class="{ 'is-invalid': form.errors.password_confirmation }" />
                                    <label class="form-label" for="password_confirmation">Confirmar Senha</label>
                                </div>
                                <div v-if="form.errors.password_confirmation" class="invalid-feedback d-block">
                                    {{ form.errors.password_confirmation }}
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
                        <button type="button" class="btn btn-success d-flex align-items-center" @click="submitForm"
                            :disabled="form.processing">
                            <span v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>
                            <i v-else class="fas fa-check me-2"></i>Criar Usuário
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
    import {ref, watch, nextTick} from "vue";
    import {useForm, router} from "@inertiajs/vue3";
    const route = window.route;
    const props = defineProps({
        show: Boolean,
        roles: {
            type: Array,
            required: true,
            default: () => []
        }
    });
    const emit = defineEmits(["close"]);
    const modalContainerRef = ref(null);

    const form = useForm({
        name: "",
        email: "",
        cpf: "",
        phone: "",
        role: "colaborador",
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
    }

    const submitForm = () => {
        form.post(route("users.store"), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                emitClose();
                router.reload({only: ["users"]});
            },
            onError: () => {
            },
            onFinish: () => nextTick(updateMDBInputs),
        });
    };

    const updateMDBInputs = () => {
        if (!window.mdb || !window.mdb.Input) return;
        if (!modalContainerRef.value) return;

        const elements = modalContainerRef.value.querySelectorAll(".form-outline");

        elements.forEach((el) => {
            if (!el) return;

            try {
                const instance =
                    window.mdb.Input.getInstance(el) || new window.mdb.Input(el);

                instance.update();
            } catch (e) {
                console.warn("Erro ao atualizar MDB Input:", e);
            }
        });
    };

    watch(
        () => props.show,
        (val) => {
            document.body.style.overflow = val ? "hidden" : "";

            if (val) {
                nextTick(updateMDBInputs);
            } else {
                form.clearErrors();
                form.reset();
            }
        }
    );
</script>

<style scoped>
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

    .btn-close-white {
        background: transparent;
        border: 0;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
    }

    .modal-fade-enter-from,
    .modal-fade-leave-to {
        opacity: 0;
    }

    .modal-fade-enter-active,
    .modal-fade-leave-active {
        transition: opacity 0.3s;
    }
</style>
