<template>
    <Head title="GESTIC - Login" />
    <form @submit.prevent="submit">

        <div class="form-outline mb-4">
            <input type="email" id="email" class="form-control" v-model="form.email" autocomplete="username" />
            <label class="form-label" for="email">E-mail</label>
        </div>
        <div v-if="form.errors.email" class="text-danger mb-3">
            {{ form.errors.email }}
        </div>

        <div class="form-outline mb-4">
            <input type="password" id="password" class="form-control" v-model="form.password"
                autocomplete="current-password" />
            <label class="form-label" for="password">Senha</label>
        </div>
        <div v-if="form.errors.password" class="text-danger mb-3">
            {{ form.errors.password }}
        </div>

        <div class="row mb-4">
            <div class="col d-flex justify-content-start">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="rememberMe" v-model="form.remember"
                        checked />
                    <label class="form-check-label" for="rememberMe">
                        Lembrar-me
                    </label>
                </div>
            </div>

            <div class="col text-end">
                <Link :href="route('password.request')">Esqueceu a senha?</Link>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block mb-4" :disabled="form.processing">
            Entrar
        </button>

    </form>
</template>

<script setup>
    import {useForm, Link} from '@inertiajs/vue3';
    import AuthLayout from '@/Layouts/AuthLayout.vue';
    import {onMounted} from 'vue';
    import {route} from 'ziggy-js';
    import {Head} from '@inertiajs/vue3'
    defineOptions({
        layout: AuthLayout,
    });

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = () => {
        form.post(route('authenticate'), {
            onFinish: () => form.reset('password'),
        });
    };

    const initializeMDBForms = () => {
        if (typeof mdb !== 'undefined' && mdb.Input) {
            document.querySelectorAll('.form-outline').forEach((formOutline) => {
                new mdb.Input(formOutline).init();
            });
        }
    };

    onMounted(() => {
        initializeMDBForms();
    });
</script>
