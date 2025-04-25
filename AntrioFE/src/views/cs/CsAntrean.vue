<template>
  <div class="px-6 py-4">
    <div class="flex justify-between items-center mb-12 mt-4">
      <h1 class="text-green-600 text-2xl font-bold">DAFTAR ANTREAN</h1>

      <!-- <div class="border border-green-600 rounded px-4 py-1 flex items-center">
        <span class="text-gray-500 mr-2">Date</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div> -->
    </div>

    <div class="flex border-b">
      <div
        v-for="tab in tabs"
        :key="tab"
        @click="activeTab = tab"
        class="flex-1 text-center pb-4 px-4 font-medium cursor-pointer"
        :class="{
          'border-b-2 border-green-600 text-green-600': activeTab === tab,
          'text-green-900': activeTab !== tab
        }"
      >
        {{ tab }}
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'CsAntrean',
    data() {
      return {
        tabs: ['Menunggu', 'Selesai', 'Skip'],
        activeTab: 'Menunggu'
      }
    }
  }
</script>
