<template>
  <Navigation />

  <div class="flex justify-center">
    <div class="flex flex-col items-center">
      <h2 class="text-3xl font-bold mb-2">SELAMAT DATANG</h2>
      <h3 class="text-1xl font-normal text-green-500">Silahkan Pilih Layanan</h3>
    </div>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 p-8">
    <div
      class="rounded-lg overflow-hidden h-60 cursor-pointer hover:-translate-y-1 transition-transform duration-300 m-3 shadow-lg"
      v-for="service in formData.service"
      :key="service.id"
      @click="openConfirm(service)"
    >
      <div class="h-full flex flex-col justify-between bg-white">
        <div class="h-48 w-full overflow-hidden">
          <img
            v-if="service.image"
            :src="`${baseImageUrl}/storage/${service.image}`"
            alt="Gambar Layanan"
            class="w-full h-full object-cover"
          />
        </div>
        <div class="p-4 text-center bg-green-500">
          <h1 class="font-bold text-white text-lg">{{ service.service }}</h1>
        </div>
      </div>
    </div>
  </div>

  <div
    v-if="showConfirm"
    class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50"
    style="background-color: rgba(0, 0, 0, 0.2);"
  >
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full h-50 text-center">
      <p class="mb-4 text-lg">
        Apakah Anda yakin ingin mengambil antrian untuk layanan
      </p>
      <strong>{{ selectedService.service }}</strong> ?
      <br>
      <div class="flex justify-center gap-4 mt-4">
        <button
          @click="confirmAntrian"
          class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition w-24"
        >
          Ya
        </button>
        <button
          @click="closeConfirm"
          class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition w-24"
        >
          Tidak
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Navigation from '@/components/Navigation.vue'
import axios from 'axios'

const router = useRouter()
const baseUrl = import.meta.env.VITE_API_BASE_URL
const baseImageUrl = import.meta.env.VITE_IMAGE_BASE_URL

const formData = reactive({
  service: [],
})

const kodeAntrian = ref(null)

const showConfirm = ref(false)
const selectedService = ref(null)

const openConfirm = (service) => {
  selectedService.value = service
  showConfirm.value = true
}

const closeConfirm = () => {
  showConfirm.value = false
  selectedService.value = null
}

const confirmAntrian = () => {
  if (!selectedService.value) return

  axios
    .post(
      `${baseUrl}/ambil-antrian`,
      { service_id: selectedService.value.id },
      { headers: { 'Content-Type': 'application/json' } }
    )
    .then((response) => {
      showConfirm.value = false
      router.push({
        name: 'antrian-detail',
        query: {
          tanggal: response.data.tanggal,
          jam: response.data.jam,
          kode: response.data.kode_antrian,
          layanan: selectedService.value.service,
        },
      })
    })
    .catch((error) => {
      showConfirm.value = false
      console.error('Gagal mengambil antrian:', error)
      alert('Terjadi kesalahan saat mengambil antrian.')
    })
}

onMounted(() => {
  axios
    .get(`${baseUrl}/tampilkan-service`)
    .then((response) => {
      formData.service = response.data.data.sort((a, b) => a.id - b.id)
    })
    .catch((error) => {
      console.error('Error fetching services:', error)
    })
})
</script>
