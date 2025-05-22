<template>
  <div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-green-600 text-2xl font-bold mb-6">DAFTAR LAYANAN</h1>

    <div class="mb-4">
      <button
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
        @click="tambahService"
      >
        + Tambah Layanan
      </button>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white shadow-md rounded-xl">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="py-3 px-4 text-left">ID</th>
            <th class="py-3 px-4 text-left">Nama Layanan</th>
            <th class="py-3 px-4 text-left">Prefix</th>
            <th class="py-3 px-4 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="service in services"
            :key="service.id"
            class="border-t hover:bg-gray-50"
          >
            <td class="py-2 px-4">{{ service.id }}</td>
            <td class="py-2 px-4">{{ service.service }}</td>
            <td class="py-2 px-4">{{ service.prefix }}</td>
            <td class="py-2 px-4 space-x-2">
              <button
                class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 transition"
                @click="editService(service)"
              >
                Edit
              </button>
              <button
                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition"
                @click="hapusService(service.id)"
              >
                Hapus
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>

import { ref, onMounted } from 'vue'
import axios from 'axios'

const baseUrl = import.meta.env.VITE_API_BASE_URL
const services = ref([])

const fetchServices = async () => {
  try {
    const response = await axios.get(`${baseUrl}/service`)
    services.value = response.data.data
  } catch (error) {
    console.error('Gagal memuat data layanan:', error)
  }
}

const tambahService = () => {
  const nama = prompt('Masukkan nama layanan:')
  if (!nama) return

  axios.post(`${baseUrl}/tambah-service`, { service: nama })
    .then(() => {
      fetchServices()
    })
    .catch((err) => {
      alert('Gagal menambah layanan.')
      console.error(err)
    })
}

const editService = (service) => {
  const namaBaru = prompt('Edit nama layanan:', service.service)
  if (!namaBaru || namaBaru === service.service) return

  axios.put(`${baseUrl}/service/${service.id}`, { service: namaBaru })
    .then(() => {
      fetchServices()
    })
    .catch((err) => {
      alert('Gagal mengedit layanan.')
      console.error(err)
    })
}

const hapusService = (id) => {
  if (confirm('Yakin ingin menghapus layanan ini?')) {
    axios.delete(`${baseUrl}/service/${id}`)
      .then(() => {
        fetchServices()
      })
      .catch((err) => {
        alert('Gagal menghapus layanan.')
        console.error(err)
      })
  }
}

onMounted(fetchServices)
</script>
