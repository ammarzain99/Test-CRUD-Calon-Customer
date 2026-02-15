<script setup>
import { reactive, ref } from "vue"
import { router } from "@inertiajs/vue3"

const form = reactive({
  nama:"",
  no_wa:"",
  email:"",
  nama_lembaga:""
})

const success = ref(false)

const submit = ()=>{
  router.post("/leads",form,{
    onSuccess:()=> success.value=true
  })
}
</script>

<template>
    <div class="absolute top-4 right-4">
      <button class="bg-blue-600 text-white px-4 py-2 rounded">
        Login
      </button>
      <button class="bg-blue-600 text-white px-4 py-2 rounded">
        Register
      </button>
    </div>
    <div class="min-h-screen bg-gray-500 flex items-center">

        <div class="bg-white p-8 rounded shadow max-w-md mx-auto w-full">

        <h1 class="text-2xl font-bold mb-2">Pendaftaran</h1>
        <p class="text-gray-500 mb-4">Isi data untuk dihubungi</p>

        <div v-if="success"
            class="bg-green-100 text-green-700 p-2 rounded mb-3">
        Berhasil dikirim ✔
        </div>

        <input v-model="form.nama" placeholder="Nama"
            class="input"/>
        <input v-model="form.no_wa" placeholder="No WA"
            class="input"/>
        <input v-model="form.email" placeholder="Email"
            class="input"/>
        <input v-model="form.nama_lembaga" placeholder="Lembaga"
            class="input"/>

        <button @click="submit"
            class="w-full bg-blue-600 text-white py-2 rounded mt-3">
        Kirim
        </button>

        </div>
    </div>
</template>

<style>
.input{
    @apply w-full border rounded px-3 py-2 mb-2;
}
</style>
