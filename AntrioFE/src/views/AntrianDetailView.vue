<template>
  <Navigation/>
    <div class="text-center mt-10">
      <h1 class="text-2xl font-semibold mb-6">Nomor Antrian Anda</h1>
  
      <div class="bg-gray-100 shadow-2xl rounded-2xl inline-block p-8 text-center w-[350px]">
        <div class="text-gray-500 mb-4 text-base flex justify-center gap-4">
          <span>{{ tanggalFormatted }}</span>
          <span>{{ jam }}</span>
        </div>
        <p class="text-5xl font-semibold text-black tracking-widest mb-8 mt-8">{{ kode }}</p>
        <h3 class="text-xl mt-2 font-semibold text-gray-700 mb-4">{{ layanan }}</h3> <br>
        <p class="mt-2 text-sm text-gray-500 leading-relaxed">
          Silakan menunggu<br />Hingga nomor antrian anda dipanggil
        </p>
      </div>
      <br>
      <button @click="selesai" class="bg-green-600 text-white px-6 py-2 rounded mt-6 hover:bg-green-700">
        Selesai
      </button>
    </div>
  </template>
  
<script setup>
    import { useRoute, useRouter } from 'vue-router'
    import Navigation from '@/components/Navigation.vue'
    
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
  