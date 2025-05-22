<template>
  <Navigation />

  <div class="flex justify-center">
    <div class="flex flex-col items-center">
      <h2 class="text-4xl font-medium mb-2">Selamat Datang</h2>
      <h3 class="text-2xl font-normal text-green-500">Silahkan Pilih Layanan</h3>
    </div>
  </div>

  <!-- Services Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 p-8">
    <div
      class="rounded-lg overflow-hidden h-60 cursor-pointer hover:-translate-y-1 transition-transform duration-300 m-3 shadow-lg"
      v-for="service in formData.service"
      :key="service.id"
      @click="klikService(service)"
    >
      <div class="h-full flex flex-col justify-between bg-white">
        <!-- Gambar layanan -->
        <img
          v-if="service.image"
          :src="`${baseUrl}/storage/${service.image}`"
          alt="Gambar Layanan"
          class="w-full h-36 object-cover"
        />
        <div class="p-4 text-center bg-green-500">
          <h1 class="font-bold text-white text-lg">{{ service.service }}</h1>
        </div>
      </div>
    </div>
  </div>

  <!-- Jika ada kode antrian, tampilkan -->
  <div v-if="kodeAntrian" class="text-center mt-6">
    <h2 class="text-2xl font-semibold text-gray-800">Kode Antrian Anda:</h2>
    <p class="text-3xl text-green-600 font-bold mt-2">{{ kodeAntrian }}</p>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Navigation from '@/components/Navigation.vue'
import axios from 'axios'

const router = useRouter()
const baseUrl = import.meta.env.VITE_API_BASE_URL

const formData = reactive({
  service: [],
})

const kodeAntrian = ref(null)

const klikService = (service) => {
  axios
    .post(`${baseUrl}/ambil-antrian`, {
      service_id: service.id,
    })
    .then((response) => {
      router.push({
        name: 'antrian-detail',
        query: {
          tanggal: response.data.tanggal,
          jam: response.data.jam,
          kode: response.data.kode_antrian,
          layanan: service.service,
        },
      })
    })
    .catch((error) => {
      console.error('Gagal mengambil antrian:', error)
      alert('Terjadi kesalahan saat mengambil antrian.')
    })
}

onMounted(() => {
  axios
    .get(`${baseUrl}/service`)
    .then((response) => {
      formData.service = response.data.data
    })
    .catch((error) => {
      console.error('Error fetching services:', error)
    })
})
</script>
