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
const selected = ref(null)

/* =======================
   ACTIONS
======================= */

function fetchData() {
    router.get(route('activityLogs'), {
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
    selected.value = row
    showDetail.value = true
}
</script>

<template>
    <Head title="Activity Logs" />

    <AuthenticatedLayout>

        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Activity Logs
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-4">

                <!-- SEARCH -->
                <div class="bg-white p-4 rounded shadow flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search user / action / model..."
                        class="border rounded px-3 py-2 w-full sm:w-80"
                    />

                    <div class="text-sm text-gray-500">
                        Total: {{ page.props.logs.total }}
                    </div>
                </div>

                <!-- TABLE -->
                <div class="bg-white shadow rounded overflow-x-auto">

                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left">Waktu</th>
                                <th class="p-3 text-left">User</th>
                                <th class="p-3 text-left">Action</th>
                                <th class="p-3 text-left">Model</th>
                                <th class="p-3 text-left">Deskripsi</th>
                                <th class="p-3 text-right">Detail</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="row in page.props.logs.data"
                                :key="row.id"
                                class="border-t hover:bg-gray-50">

                                <td class="p-3 text-xs text-gray-500">
                                    {{ row.created_at }}
                                </td>

                                <td class="p-3">
                                    {{ row.user?.name || 'System' }}
                                </td>

                                <td class="p-3">
                                    <span class="px-2 py-1 rounded text-xs"
                                          :class="{
                                              'bg-green-100 text-green-700': row.action==='create',
                                              'bg-yellow-100 text-yellow-700': row.action==='update',
                                              'bg-red-100 text-red-700': row.action==='delete',
                                              'bg-blue-100 text-blue-700': row.action==='login'
                                          }">
                                        {{ row.action }}
                                    </span>
                                </td>

                                <td class="p-3">
                                    {{ row.model }} #{{ row.model_id }}
                                </td>

                                <td class="p-3 text-sm text-gray-600">
                                    {{ row.description }}
                                </td>

                                <td class="p-3 text-right">
                                    <button
                                        class="text-blue-600"
                                        @click="openDetail(row)">
                                        View
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="flex justify-center gap-2">

                    <button
                        v-for="link in page.props.logs.links"
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

            <div class="bg-white p-6 rounded shadow w-full max-w-lg space-y-4 max-h-[80vh] overflow-auto">

                <h3 class="text-lg font-bold">
                    Activity Detail
                </h3>

                <div class="text-sm space-y-1">
                    <p><b>User:</b> {{ selected.user?.name }}</p>
                    <p><b>Action:</b> {{ selected.action }}</p>
                    <p><b>Model:</b> {{ selected.model }} #{{ selected.model_id }}</p>
                    <p><b>IP:</b> {{ selected.ip_address }}</p>
                    <p><b>Time:</b> {{ selected.created_at }}</p>
                </div>

                <!-- OLD VALUES -->
                <div v-if="selected.old_values">
                    <h4 class="font-semibold text-red-600 mb-1">
                        Old Values
                    </h4>
                    <pre class="bg-red-50 p-2 rounded text-xs overflow-auto">
{{ JSON.stringify(selected.old_values, null, 2) }}
                    </pre>
                </div>

                <!-- NEW VALUES -->
                <div v-if="selected.new_values">
                    <h4 class="font-semibold text-green-600 mb-1">
                        New Values
                    </h4>
                    <pre class="bg-green-50 p-2 rounded text-xs overflow-auto">
{{ JSON.stringify(selected.new_values, null, 2) }}
                    </pre>
                </div>

                <div class="flex justify-end">
                    <button
                        class="px-4 py-2 bg-gray-200 rounded"
                        @click="showDetail=false">
                        Tutup
                    </button>
                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>
