<template>
    <div class="text-center mt-10">
      <h1 class="text-3xl font-semibold mb-6">Nomor Antrian</h1>
  
      <div class="bg-gray-100 shadow-xl rounded-xl inline-block p-8 text-center">
        <div class="text-gray-500 mb-2">{{ tanggalFormatted }} <span class="float-right">{{ jam }}</span></div>
        <h2 class="text-lg font-medium mb-1">Nomor Antrian Anda</h2>
        <p class="text-5xl font-bold text-black tracking-widest">{{ kode }}</p>
        <h3 class="text-lg mt-2 font-semibold text-gray-700">{{ layanan }}</h3>
        <p class="mt-2 text-sm text-gray-500">Silakan menunggu<br />Hingga nomor antrian anda dipanggil</p>
        <p class="mt-3 font-semibold">Terima Kasih</p>
      </div>
  
      <button @click="selesai" class="bg-green-600 text-white px-6 py-2 rounded mt-6 hover:bg-green-700">
        Selesai
      </button>
    </div>
  </template>
  
<script setup>
    import { useRoute, useRouter } from 'vue-router'
    
    const route = useRoute()
    const router = useRouter()
    
    const kode = route.query.kode || ''
    const tanggal = route.query.tanggal || ''
    const jam = route.query.jam || ''
    const layanan = route.query.layanan || ''
    
    const tanggalFormatted = new Date(tanggal).toLocaleDateString('id-ID', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    })
    
    const selesai = () => {
        router.push('/')
    }
</script>
  