<template>
    <Navigation />

    <div class="flex flex-col justify-center items-center p-20">
        <LoginForm />

        <p class="text-center text-sm text-gray-600 mt-6">
          Belum punya akun?
          <router-link to="/register" class="text-blue-600 hover:underline">Daftar di sini</router-link>
        </p>
    </div>
</template>

<script setup>

    import LoginForm from '@/components/LoginForm.vue';
    import Navigation from '@/components/Navigation.vue';

</script>