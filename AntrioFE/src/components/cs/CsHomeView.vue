<template>
  <div class="flex min-h-screen">
    <CsSidebar />
    <main class="flex-1 ml-72 min-h-screen bg-gray-100 transition-all duration-300 overflow-x-hidden">
      <div class="max-w-full px-5 py-6 md:max-w-full md:px-4">
        <router-view />
      </div>
    </main>
  </div>
</template>

<script setup>
  import CsSidebar from '@/components/cs/CsSidebar.vue'
</script>
