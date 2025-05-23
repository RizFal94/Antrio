<template>
  <div class="px-6 py-4">
    <div class="flex justify-between items-center mb-12 mt-4">
      <h1 class="text-green-600 text-2xl font-bold">DAFTAR ANTREAN</h1>
    </div>

    <div class="flex border-b mb-6">
      <div
        v-for="tab in tabs"
        :key="tab"
        @click="changeTab(tab)"
        class="flex-1 text-center pb-4 px-4 font-medium cursor-pointer"
        :class="{
          'border-b-2 border-green-600 text-green-600': activeTab === tab,
          'text-green-900': activeTab !== tab
        }"
      >
        {{ tab }}
      </div>
    </div>

    <div v-if="loading" class="text-center text-gray-500">Memuat data...</div>

    <div v-else>
      <div v-if="antrean.length === 0" class="text-center text-gray-500">
        Tidak ada data antrean.
      </div>
      <div v-else class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg">
          <thead class="bg-green-100 text-left">
            <tr>
              <th class="px-4 py-2 border-b">ID</th>
              <th class="px-4 py-2 border-b">Layanan</th>
              <th class="px-4 py-2 border-b">Nomor Urutan</th>
              <th class="px-4 py-2 border-b">Waktu</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="item in antrean"
              :key="item.id"
              class="hover:bg-gray-50"
            >
              <td class="px-4 py-2 border-b">{{ item.id }}</td>
              <td class="px-4 py-2 border-b">
                {{ item.customer_service?.service?.service|| '-' }}
              </td>
              <td class="px-4 py-2 border-b">
                {{ item.customer_service?.service?.prefix || '' }}{{ item.urutan.toString().padStart(3, '0') }}
              </td>
              <td class="px-4 py-2 border-b">
                {{ formatTime(item.created_at) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const baseUrl = import.meta.env.VITE_API_BASE_URL;

const tabs = ['Menunggu', 'Dilayani', 'Selesai', 'Skip']
const activeTab = ref('Menunggu')
const antrean = ref([])
const loading = ref(false)

const fetchData = async () => {
  loading.value = true
  let endpoint = ''

  switch (activeTab.value) {
    case 'Menunggu':
      endpoint = `${baseUrl}/antrian/belum-terlayani`
      break
    case 'Dilayani':
      endpoint = `${baseUrl}/antrian/dilayani`
      break
    case 'Selesai':
      endpoint = `${baseUrl}/antrian/sudah-terlayani`
      break
    case 'Skip':
      endpoint = `${baseUrl}/antrian/dilewati`
      break
  }

  try {
    const response = await axios.get(endpoint)
    antrean.value = response.data.data || []
  } catch (error) {
    console.error('Gagal memuat data antrean:', error)
    antrean.value = []
  } finally {
    loading.value = false
  }
}

const changeTab = (tab) => {
  activeTab.value = tab
  fetchData()
}

const formatTime = (datetimeString) => {
  const date = new Date(datetimeString)
  const hours = String(date.getHours()).padStart(2, '0')
  const minutes = String(date.getMinutes()).padStart(2, '0')
  return `${hours}:${minutes}`
}

onMounted(fetchData)
</script>
