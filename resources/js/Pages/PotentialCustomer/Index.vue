<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const page = usePage()

/* =======================
   STATE
======================= */

const search = ref(page.props.filters?.search || '')
const showDetail = ref(false)
const showEdit = ref(false)
const showDelete = ref(false)
const selected = ref(null)
const errors = ref({})
const toast = ref({
    show: false,
    message: '',
    type: 'success'
})

/* =======================
   ACTIONS
======================= */

function fetchData() {
    router.get(route('lead.index'), {
        search: search.value
    }, {
        preserveState: true,
        replace: true
    })
}

watch(search, () => {
    fetchData()
})

function openDetail(row) {
    selected.value = { ...row }
    showDetail.value = true
}

function openEdit(row) {
    selected.value = { ...row }
    showEdit.value = true
}

function confirmDelete(row) {
    selected.value = row
    showDelete.value = true
}

function deleteData() {
    router.delete(route('lead.destroy', selected.value.id), {
        onSuccess: () => {
            showDelete.value = false
            showToast('Data berhasil dihapus')
        }
    })
}

function updateData() {
    router.put(route('lead.update', selected.value.id), selected.value, {
        preserveScroll: true,
        onError: (e) => {
            errors.value = e
        },
        onSuccess: () => {
            errors.value = {}
            showEdit.value = false
            showToast('Data berhasil diupdate')
        }
    })
}

function showToast(msg, type = 'success') {
    toast.value = {
        show: true,
        message: msg,
        type
    }

    setTimeout(() => {
        toast.value.show = false
    }, 2500)
}
</script>

<template>
    <Head title="Calon Customer" />

    <AuthenticatedLayout>

        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Calon Customer
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-4">

                <!-- FLASH -->
                <div v-if="page.props.flash?.success"
                     class="p-3 rounded bg-green-100 text-green-700">
                    {{ page.props.flash.success }}
                </div>

                <!-- SEARCH -->
                <div class="bg-white p-4 rounded shadow flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search nama / email / WA..."
                        class="border rounded px-3 py-2 w-full sm:w-80"
                    />

                    <div class="text-sm text-gray-500">
                        Total: {{ page.props.leads.total }}
                    </div>
                </div>

                <!-- TABLE -->
                <div class="bg-white shadow rounded overflow-x-auto">

                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left">Nama</th>
                                <th class="p-3 text-left">WA</th>
                                <th class="p-3 text-left">Email</th>
                                <th class="p-3 text-left">Lembaga</th>
                                <th class="p-3 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="row in page.props.leads.data"
                                :key="row.id"
                                class="border-t hover:bg-gray-50">

                                <td class="p-3">{{ row.nama }}</td>
                                <td class="p-3">{{ row.no_wa }}</td>
                                <td class="p-3">{{ row.email }}</td>
                                <td class="p-3">{{ row.nama_lembaga }}</td>

                                <td class="p-3 text-right space-x-2">

                                    <button
                                        class="text-blue-600"
                                        @click="openDetail(row)">
                                        Detail
                                    </button>

                                    <button
                                        class="text-yellow-600"
                                        @click="openEdit(row)">
                                        Edit
                                    </button>

                                    <button
                                        class="text-red-600"
                                        @click="confirmDelete(row)">
                                        Delete
                                    </button>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="flex justify-center gap-2">

                    <button
                        v-for="link in page.props.leads.links"
                        :key="link.label"
                        v-html="link.label"
                        :disabled="!link.url"
                        @click="router.visit(link.url, { preserveState: true })"
                        class="px-3 py-1 border rounded text-sm"
                        :class="{
                            'bg-blue-500 text-white': link.active,
                            'text-gray-400': !link.url
                        }"
                    />
                </div>

            </div>
        </div>

        <!-- DETAIL MODAL -->
        <div v-if="showDetail"
             class="fixed inset-0 bg-black/40 flex items-center justify-center">

            <div class="relative bg-white p-6 rounded shadow w-full max-w-md space-y-2">
                <button
                    class="absolute top-2 right-2 text-neutral-50 hover:text-gray-700 bg-red-500 px-3 py-1 rounded"
                    @click="showDetail=false">
                    ✕
                </button>

                <h3 class="text-lg font-bold">Detail Calon Customer</h3>

                <p><b>Nama:</b> {{ selected.nama }}</p>
                <p><b>WA:</b> {{ selected.no_wa }}</p>
                <p><b>Email:</b> {{ selected.email }}</p>
                <p><b>Nama Lembaga:</b> {{ selected.nama_lembaga }}</p>

                <button class="mt-4 px-4 py-2 bg-gray-200 rounded"
                        @click="showDetail=false">
                    Tutup
                </button>
            </div>
        </div>

        <!-- EDIT MODAL -->
        <div v-if="showEdit"
             class="fixed inset-0 bg-black/40 flex items-center justify-center">

            <div class="relative bg-white p-6 rounded shadow w-full max-w-md space-y-3">
                <button
                    class="absolute top-2 right-2 text-neutral-50 hover:text-gray-700 bg-red-500 px-3 py-1 rounded"
                    @click="showEdit=false">
                    ✕
                </button>

                <h3 class="text-lg font-bold">Edit Calon Customer</h3>

                <p><b>Nama *</b></p>
                <input v-model="selected.nama" class="border rounded w-full p-2" />
                <div v-if="errors.nama" class="text-red-500 text-sm">{{ errors.nama }}</div>

                <p><b>WA *</b></p>
                <input v-model="selected.no_wa" class="border rounded w-full p-2" />
                <div v-if="errors.no_wa" class="text-red-500 text-sm">{{ errors.no_wa }}</div>

                <p><b>Email *</b></p>
                <input v-model="selected.email" class="border rounded w-full p-2" />
                <div v-if="errors.email" class="text-red-500 text-sm">{{ errors.email }}</div>

                <p><b>Nama Lembaga</b></p>
                <input v-model="selected.nama_lembaga" class="border rounded w-full p-2" />
                <div v-if="errors.nama_lembaga" class="text-red-500 text-sm">{{ errors.nama_lembaga }}</div>

                <div class="flex justify-end gap-2">
                    <button @click="showEdit=false" class="px-3 py-1 border rounded">
                        Batal
                    </button>
                    <button @click="updateData" class="px-3 py-1 bg-blue-500 text-white rounded">
                        Simpan
                    </button>
                </div>
            </div>
        </div>

        <!-- DELETE MODAL -->
        <div v-if="showDelete"
             class="fixed inset-0 bg-black/40 flex items-center justify-center">

            <div class="relative bg-white p-6 rounded shadow w-full max-w-sm space-y-3">
                <button
                    class="absolute top-2 right-2 text-neutral-50 hover:text-gray-700 bg-red-500 px-3 py-1 rounded"
                    @click="showDelete=false">
                    ✕
                </button>

                <h3 class="text-lg font-bold">Hapus Data?</h3>
                <p class="text-sm text-gray-500">
                    Data tidak bisa dikembalikan.
                </p>

                <div class="flex justify-end gap-2">
                    <button @click="showDelete=false"
                            class="px-3 py-1 border rounded">
                        Batal
                    </button>

                    <button @click="deleteData"
                            class="px-3 py-1 bg-red-500 text-white rounded">
                        Hapus
                    </button>
                </div>
            </div>
        </div>

        <!-- TOAST -->
        <div v-if="toast.show"
            class="fixed top-5 right-5 z-50">

            <div
                class="px-4 py-3 rounded shadow text-white transition"
                :class="{
                    'bg-green-500': toast.type === 'success',
                    'bg-red-500': toast.type === 'error'
                }">

                {{ toast.message }}
            </div>
        </div>

    </AuthenticatedLayout>
</template>
