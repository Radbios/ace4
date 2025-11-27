<template>

    <Head title="GESTIC - Esqueci a senha" />
    <form @submit.prevent="submit">
        <div class="mb-4 text-center text-muted">
            <p>
                Esqueceu sua senha? Sem problemas. Apenas nos informe seu endereço de e-mail e enviaremos um link de
                redefinição de senha que permitirá que você escolha uma nova.
            </p>
        </div>

        <div v-if="status" class="alert alert-success mb-4" role="alert">
            {{ status }}
        </div>

        <div class="form-outline mb-4">
            <input type="email" id="email" class="form-control" v-model="form.email" required autofocus
                autocomplete="username" />
            <label class="form-label" for="email">E-mail</label>
        </div>

        <div v-if="form.errors.email" class="text-danger mb-3">
            {{ form.errors.email }}
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary btn-block" :disabled="form.processing">
                Enviar Link de Redefinição
            </button>
        </div>

        <div class="text-center mt-3">
            <Link :href="route('login')">Lembrei a senha? Voltar para o Login</Link>
        </div>
    </form>
</template>

<script setup>
    import {useForm, Link} from '@inertiajs/vue3'
    import AuthLayout from '@/Layouts/AuthLayout.vue'
    import {onMounted} from 'vue'
    import {route} from 'ziggy-js'

    import {Head} from '@inertiajs/vue3'
    defineOptions({
        layout: AuthLayout,
    })

    const props = defineProps({
        status: String,
    })

    const form = useForm({
        email: '',
    })

    const submit = () => {
        form.post(route('password.email'))
    }

    const initializeMDBForms = () => {
        if (typeof mdb !== 'undefined' && mdb.Input) {
            document.querySelectorAll('.form-outline').forEach((formOutline) => {
                new mdb.Input(formOutline).init()
            })
        }
    }

    onMounted(() => {
        initializeMDBForms()
    })
</script>
