<template>
  <div class="px-6 py-4">
    <div class="flex justify-between items-center mb-12 mt-4">
      <h1 class="text-green-600 text-2xl font-bold">DAFTAR ANTREAN</h1>
    </div>

    <div class="flex mb-6">
      <div
        v-for="tab in tabs"
        :key="tab"
        @click="changeTab(tab)"
        class="flex-1 text-center pb-4 px-4 font-medium cursor-pointer"
        :class="{
          'border-b-3 border-green-600 text-green-600 text-1xl': activeTab === tab,
          'text-green-900 text-1xl': activeTab !== tab
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
              <th class="px-4 py-2 border-b">No</th>
              <th class="px-4 py-2 border-b">Layanan</th>
              <th class="px-4 py-2 border-b">Nomor Urutan</th>
              <th class="px-4 py-2 border-b">Tanggal</th>
              <th class="px-4 py-2 border-b">Waktu</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, index) in antrean"
              :key="item.id"
              class="hover:bg-gray-50"
            >
              <td class="px-4 py-2 border-b">{{ index + 1 }}</td>
              <td class="px-4 py-2 border-b">
                {{ item.customer_service?.service?.service || '-' }}
              </td>
              <td class="px-4 py-2 border-b">
                {{ item.customer_service?.service?.prefix || '' }}{{ item.urutan.toString().padStart(3, '0') }}
              </td>
              <td class="px-4 py-2 border-b">
                {{ item.tanggal }}
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

const baseUrl = import.meta.env.VITE_API_BASE_URL

const tabs = ['MENUNGGU', 'DILAYANI', 'SELESAI', 'SKIP']
const activeTab = ref('MENUNGGU')
const antrean = ref([])
const loading = ref(false)

const fetchData = async () => {
  loading.value = true
  let endpoint = ''

  switch (activeTab.value) {
    case 'MENUNGGU':
      endpoint = `${baseUrl}/admin/antrian/belum-terlayani`
      break
    case 'DILAYANI':
      endpoint = `${baseUrl}/admin/antrian/dilayani`
      break
    case 'SELESAI':
      endpoint = `${baseUrl}/admin/antrian/terlayani`
      break
    case 'SKIP':
      endpoint = `${baseUrl}/admin/antrian/dilewati`
      break
  }

  try {
    const response = await axios.get(endpoint)
    antrean.value = (response.data.data || []).sort((a, b) => {
      const dateA = new Date(a.tanggal)
      const dateB = new Date(b.tanggal)
      return dateB - dateA
    })
  } catch (error) {
    console.error('Gagal memuat data antrean admin:', error)
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
