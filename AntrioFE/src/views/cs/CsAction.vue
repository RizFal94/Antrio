<template>
    <div class="p-6 max-w-xl mx-auto space-y-10">
      <div class="text-center space-y-4">
        <h1 class="text-green-600 text-3xl font-bold mb-12">ANTREAN SAAT INI</h1>
        <div v-if="currentQueue" class="bg-white rounded-xl shadow-md p-6 space-y-2">
          <div class="text-4xl font-bold text-green-600">{{ currentQueue.urutan }}</div>
          <div class="text-lg text-gray-700">{{ currentQueue.layanan?.nama ?? '-' }}</div>
          <div class="text-sm text-gray-500">Masuk: {{ currentQueue.tanggal }}</div>
        </div>
        <div v-else class="bg-gray-100 rounded-xl p-6 text-gray-500">
          Tidak ada antrean yang sedang dilayani
        </div>
      </div>
  
      <div class="flex justify-center gap-4">
        <button @click="skipQueue" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition">
          Skip
        </button>
        <button @click="ambilBerikutnya" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">
          Antrean Berikutnya
        </button>
        <button @click="completeQueue" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
          Selesai
        </button>
      </div>
    </div>
</template>
  
<script setup>
  import { ref } from 'vue'
  import axios from 'axios'

  const baseUrl = import.meta.env.VITE_API_BASE_URL;
  
  const currentQueue = ref(null)
  
  const ambilBerikutnya = async () => {
    try {
      const response = await axios.get(`${baseUrl}/antrian/berikutnya`)
      currentQueue.value = response.data.data
    } catch (error) {
      currentQueue.value = null
      console.error('Gagal ambil antrean:', error.response?.data?.message || error.message)
    }
  }
  
  const skipQueue = async () => {
    if (!currentQueue.value) return
  
    try {
      await axios.post(`${baseUrl}/antrian/skip/${currentQueue.value.id}`)
      currentQueue.value = null
    } catch (error) {
      console.error('Gagal skip antrean:', error.response?.data?.message || error.message)
    }
  }
  
  const completeQueue = async () => {
    if (!currentQueue.value) return
  
    try {
      await axios.post(`${baseUrl}/antrian/selesai/${currentQueue.value.id}`)
      currentQueue.value = null
    } catch (error) {
      console.error('Gagal menyelesaikan antrean:', error.response?.data?.message || error.message)
    }
  }
</script>
  