<template>
    <Head title="GESTIC - Atualizar senha" />
    <form @submit.prevent="submit">
        <div class="mb-4 text-center text-muted">
            <p>Preencha os campos abaixo para definir sua nova senha.</p>
        </div>
        <div class="form-outline mb-4">
            <input type="email" id="email" class="form-control" v-model="form.email" autocomplete="username" disabled />
            <label class="form-label" for="email">E-mail</label>
        </div>
        <div v-if="form.errors.email" class="text-danger mb-3">
            {{ form.errors.email }}
        </div>

        <div class="form-outline mb-4">
            <input type="password" id="password" class="form-control" v-model="form.password" required
                autocomplete="new-password" />
            <label class="form-label" for="password">Nova Senha</label>
        </div>
        <div v-if="form.errors.password" class="text-danger mb-3">
            {{ form.errors.password }}
        </div>

        <div class="form-outline mb-4">
            <input type="password" id="password_confirmation" class="form-control" v-model="form.password_confirmation"
                required autocomplete="new-password" />
            <label class="form-label" for="password_confirmation">Confirmar Nova Senha</label>
        </div>
        <div v-if="form.errors.password_confirmation" class="text-danger mb-3">
            {{ form.errors.password_confirmation }}
        </div>

        <div class="d-flex justify-content-center mt-4">
            <button type="submit" class="btn btn-primary btn-block" :disabled="form.processing">
                Redefinir Senha
            </button>
        </div>
    </form>
</template>

<script setup>
    import {useForm} from '@inertiajs/vue3'
    import AuthLayout from '@/Layouts/AuthLayout.vue'
    import {onMounted, nextTick} from 'vue'
    import {route} from 'ziggy-js'
    import {Head} from '@inertiajs/vue3'

    defineOptions({
        layout: AuthLayout,
    })

    const props = defineProps({
        email: {type: String, required: true},
        token: {type: String, required: true},
    })
    const form = useForm({
        token: props.token,
        email: props.email,
        password: '',
        password_confirmation: '',
    })

    const submit = () => {
        form.post(route('password.update'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        })
    }

    const initializeMDBForms = () => {
        if (typeof mdb !== 'undefined' && mdb.Input) {
            const fields = document.querySelectorAll('.form-outline')

            fields.forEach((formOutline) => {
                new mdb.Input(formOutline).init()
            })

            nextTick(() => {
                fields.forEach((formOutline) => {
                    new mdb.Input(formOutline).update()
                })
            })
        }
    }

    onMounted(() => {
        initializeMDBForms()
    })
</script>
