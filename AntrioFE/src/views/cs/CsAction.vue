<template>
  <div class="p-6 max-w-xl mx-auto space-y-10">
    <div class="text-center space-y-4">
      <h1 class="text-green-600 text-3xl font-bold mb-12">ANTREAN SAAT INI</h1>
      <div v-if="currentQueue" class="bg-white rounded-xl shadow-md p-6 space-y-2 max-w-sm mx-auto items-center justify-center min-h-[300px]">
        <div class="text-4xl font-bold text-gray-800 m-8">
          {{ (currentQueue.customer_service?.prefix || '') + ' ' + currentQueue.urutan.toString().padStart(3, '0') }}
        </div>
        <div class="text-lg font-medium text-gray-700 mb-15">
          {{ currentQueue.customer_service?.service ?? '-' }}
        </div>
        <div class="text-sm text-gray-500 mb-8">
          Masuk: {{ formatTime(currentQueue.created_at) }}
        </div>
      </div>
      <div 
        v-else class="bg-gray-100 rounded-xl p-6 text-gray-500 flex items-center justify-center min-h-[300px]"
      >
        Tidak ada antrean yang sedang dilayani
      </div>
    </div>

    <div class="flex justify-center gap-4">
      <button
        @click="skipQueue"
        :disabled="!currentQueue"
        class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Skip
      </button>
      <button
        @click="ambilBerikutnya" :disabled="currentQueue"
        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition"
      >
        Layani Antrean
      </button>
      <button
        @click="completeQueue"
        :disabled="!currentQueue"
        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Selesai
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const baseUrl = import.meta.env.VITE_API_BASE_URL
const currentQueue = ref(null)

const savedQueue = localStorage.getItem('currentQueue')
if (savedQueue) {
  try {
    currentQueue.value = JSON.parse(savedQueue)
  } catch {
    localStorage.removeItem('currentQueue')
  }
}

const getAuthHeaders = () => {
  const token = localStorage.getItem('access_token')
  return {
    headers: {
      Authorization: `Bearer ${token}`
    }
  }
}

const ambilBerikutnya = async () => {
  try {
    const response = await axios.post(
      `${baseUrl}/customer-service/antrian/berikutnya`,
      {},
      getAuthHeaders()
    )

    currentQueue.value = response.data.data

    if (currentQueue.value) {
      localStorage.setItem('currentQueue', JSON.stringify(currentQueue.value))
    } else {
      alert('Tidak ada antrean berikutnya.')
    }
  } catch (error) {
    currentQueue.value = null
    localStorage.removeItem('currentQueue')
    const msg = error.response?.data?.message || 'Gagal ambil antrean.'
    alert(msg)
    console.error(msg)
  }
}

const skipQueue = async () => {
  if (!currentQueue.value) return

  try {
    await axios.post(
      `${baseUrl}/customer-service/antrian/skip/${currentQueue.value.id}`,
      {},
      getAuthHeaders()
    )
    alert('Antrian berhasil di-skip.')
    currentQueue.value = null
    localStorage.removeItem('currentQueue')
  } catch (error) {
    const msg = error.response?.data?.message || 'Gagal skip antrean.'
    alert(msg)
    console.error(msg)
  }
}

const completeQueue = async () => {
  if (!currentQueue.value) return

  try {
    await axios.post(
      `${baseUrl}/customer-service/antrian/selesai/${currentQueue.value.id}`,
      {},
      getAuthHeaders()
    )
    alert('Antrian berhasil diselesaikan.')
    currentQueue.value = null
    localStorage.removeItem('currentQueue')
  } catch (error) {
    const msg = error.response?.data?.message || 'Gagal menyelesaikan antrean.'
    alert(msg)
    console.error(msg)
  }
}

const formatTime = (datetimeString) => {
  const date = new Date(datetimeString)
  const hours = String(date.getHours()).padStart(2, '0')
  const minutes = String(date.getMinutes()).padStart(2, '0')
  return `${hours}:${minutes}`
}
</script>
