<template>
  <div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-green-600 text-2xl font-bold mb-6">DAFTAR LAYANAN</h1>

    <div class="mb-4 flex justify-end mr-10">
      <button
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
        @click="bukaForm(null)"
      >
        + Tambah Layanan
      </button>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white shadow-md rounded-xl">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="py-3 px-4 text-left">ID</th>
            <th class="py-3 px-4 text-left">NAMA LAYANAN</th>
            <th class="py-3 px-4 text-left">PREFIX</th>
            <th class="py-3 px-4 flex justify-end mr-20">AKSI</th>
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
            <td class="py-2 px-4 space-x-2 flex justify-end mr-10">
              <button
                class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500"
                @click="bukaForm(service)"
              >
                Edit
              </button>
              <button
                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
                @click="openDeleteConfirm(service)"
              >
                Hapus
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div
      v-if="formTerbuka"
      class="fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50"
      style="background-color: rgba(0, 0, 0, 0.2);"
    >
      <div class="bg-white p-6 rounded-xl w-full max-w-lg">
        <h2 class="text-xl font-bold mb-4">{{ formData.id ? 'Edit' : 'Tambah' }} Layanan</h2>
        <form @submit.prevent="submitForm">
          <div class="mb-4">
            <label class="block mb-1" for="serviceInput">Nama Layanan</label>
            <input
              id="serviceInput"
              v-model="formData.service"
              type="text"
              required
              class="w-full border rounded p-2"
            />
          </div>
          <div class="mb-4">
            <label class="block mb-1" for="prefixInput">Prefix</label>
            <input
              id="prefixInput"
              v-model="formData.prefix"
              type="text"
              maxlength="5"
              required
              class="w-full border rounded p-2 uppercase"
              @input="formData.prefix = formData.prefix.toUpperCase()"
            />
          </div>
          <div class="mb-4">
            <label class="block mb-1">Gambar (opsional)</label>
            <label
              for="imageUpload"
              class="inline-block bg-blue-600 text-white px-3 py-1 rounded cursor-pointer hover:bg-blue-700 transition"
            >
              Pilih Gambar
            </label>
            <input
              id="imageUpload"
              type="file"
              accept="image/jpeg,image/png,image/jpg"
              @change="handleFileUpload"
              class="hidden"
            />
            <p class="text-sm text-gray-500 mt-1" v-if="formData.image">
              File dipilih: {{ formData.image.name }}
            </p>
          </div>
          <div class="flex justify-end space-x-2">
            <button
              type="button"
              @click="tutupForm"
              class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition"
            >
              Batal
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
            >
              {{ formData.id ? 'Update' : 'Simpan' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <div
      v-if="showDeleteConfirm"
      class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50"
      style="background-color: rgba(0, 0, 0, 0.2);"
    >
      <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full text-center">
        <p class="mb-4 text-lg">Apakah Anda yakin ingin menghapus layanan</p>
        <strong>{{ selectedServiceToDelete?.service }}</strong> ?
        <div class="flex justify-center gap-4 mt-4">
          <button
            @click="confirmDelete"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition w-18"
          >
            Ya
          </button>
          <button
            @click="cancelDelete"
            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition w-18"
          >
            Tidak
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'

const baseUrl = import.meta.env.VITE_API_BASE_URL || ''

const services = ref([])
const formTerbuka = ref(false)
const showDeleteConfirm = ref(false)
const selectedServiceToDelete = ref(null)

const formData = reactive({
  id: null,
  service: '',
  prefix: '',
  image: null,
})

const fetchServices = async () => {
  try {
    const res = await axios.get(`${baseUrl}/tampilkan-service`)
    services.value = (res.data.data || []).sort((a, b) => a.id - b.id)
  } catch (err) {
    console.error('Gagal memuat data layanan:', err)
  }
}

const handleFileUpload = (e) => {
  const file = e.target.files[0]
  if (file && ['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
    formData.image = file
  } else {
    alert('Format gambar harus jpeg, jpg, atau png')
    e.target.value = null
  }
}

const bukaForm = (service) => {
  if (service) {
    formData.id = service.id
    formData.service = service.service
    formData.prefix = service.prefix
  } else {
    formData.id = null
    formData.service = ''
    formData.prefix = ''
  }
  formData.image = null
  formTerbuka.value = true
}

const tutupForm = () => {
  formTerbuka.value = false
}

const submitForm = async () => {
  try {
    const form = new FormData()
    form.append('service', formData.service)
    form.append('prefix', formData.prefix)
    if (formData.image) form.append('image', formData.image)

    if (formData.id) {
      form.append('_method', 'PUT')
      await axios.post(`${baseUrl}/admin/update-service/${formData.id}`, form, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      alert('Layanan berhasil diupdate')
    } else {
      await axios.post(`${baseUrl}/admin/tambah-service`, form, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      alert('Layanan berhasil ditambahkan')
    }

    tutupForm()
    fetchServices()

  } catch (err) {
    if (err.response && err.response.data && err.response.data.message) {
      alert('Error: ' + JSON.stringify(err.response.data.message))
    } else {
      alert('Terjadi kesalahan saat menyimpan data')
    }
    console.error(err)
  }
}

const openDeleteConfirm = (service) => {
  selectedServiceToDelete.value = service
  showDeleteConfirm.value = true
}

const cancelDelete = () => {
  selectedServiceToDelete.value = null
  showDeleteConfirm.value = false
}

const confirmDelete = async () => {
  try {
    await axios.delete(`${baseUrl}/admin/hapus-service/${selectedServiceToDelete.value.id}`)
    alert('Layanan berhasil dihapus')
    fetchServices()
  } catch (err) {
    alert('Gagal menghapus layanan')
    console.error(err)
  } finally {
    cancelDelete()
  }
}

onMounted(() => {
  fetchServices()
})
</script>
