<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'

const page = usePage()
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-4">

                <!-- FLASH SUCCESS -->
                <div
                    v-if="page.props.flash?.success"
                    class="p-4 rounded bg-green-100 text-green-700 border border-green-200"
                >
                    {{ page.props.flash.success }}
                </div>

                <!-- FLASH ERROR -->
                <div
                    v-if="page.props.flash?.error"
                    class="p-4 rounded bg-red-100 text-red-700 border border-red-200"
                >
                    {{ page.props.flash.error }}
                </div>

                <!-- MAIN CARD -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        You're logged in!
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>