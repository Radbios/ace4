<template>
  <div class="auth-layout d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 450px;">
      <h3 class="card-title text-center mb-4">
        <i class="fas fa-lock me-2 text-primary"></i>
        Acesso ao Sistema
      </h3>
      <slot />
    </div>
  </div>
</template>

<script setup>

</script>

<style scoped>
.auth-layout {
  background-color: #f0f2f5 !important;
}
.card {
  border: none;
  border-radius: 10px;
}
</style>
