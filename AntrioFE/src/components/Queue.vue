<template>
    <div class="flex-1 bg-green-50 p-6">
      <div class="relative mb-8">
        <input 
          type="text" 
          placeholder="Cari" 
          class="w-64 rounded-full border border-gray-300 pl-10 pr-4 py-2 focus:outline-none focus:ring-1 focus:ring-green-500"
        />
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
      
      <div class="grid grid-cols-4 text-gray-700 font-medium pb-2">
        <div>Nomor Antrean</div>
        <div>Jadwal</div>
        <div>Layanan</div>
        <div>Status</div>
      </div>
      
      <div class="space-y-2">
        <!-- Queue items will be populated dynamically -->
      </div>
    </div>
  </template>
    
  <script>
  export default {
    name: 'Queue',
    data() {
      return {
      }
    }
  }
  </script>