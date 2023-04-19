<template>
  <div>
    <Head title="Dashboard Statistics" />
    <h1 class="mb-4 text-2xl font-bold">Dashboard Statistics</h1>
    <div class="container mx-auto px-20">
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 my-2 w-full">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" href="/users/">
                <div class="metric-card bg-red-300  hover:bg-red-400 border border-gray-200 dark:border-gray-800 rounded-lg p-4 max-w-72 w-full">
                    <div class="flex items-center text-gray-900 dark:text-gray-100 ">Users
                    </div>
                <p class="mt-2 text-2xl font-bold spacing-sm text-black dark:text-white" >{{ counts.users }}</p>
                </div>
            </Link>

            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" href="/picks/">
                <div class="metric-card bg-orange-300  hover:bg-orange-400 border border-gray-200 dark:border-gray-800 rounded-lg p-4 max-w-72 w-full">
                    <div class="flex items-center text-gray-900 dark:text-gray-100 ">Picks
                    </div>
                <p class="mt-2 text-2xl font-bold spacing-sm text-black dark:text-white" >{{ counts.picks }}</p>
                </div>
            </Link>

            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" href="/gratitude/">
                <div class="metric-card bg-yellow-300  hover:bg-yellow-400 border border-gray-200 dark:border-gray-800 rounded-lg p-4 max-w-72 w-full">
                    <div class="flex items-center text-gray-900 dark:text-gray-100 ">Gratitude
                    </div>
                <p class="mt-2 text-2xl font-bold spacing-sm text-black dark:text-white" >{{ counts.gratitude }}</p>
                </div>
            </Link>

            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" href="/users/">
                <div class="metric-card bg-green-300  hover:bg-green-400 border border-gray-200 dark:border-gray-800 rounded-lg p-4 max-w-72 w-full">
                    <div class="flex items-center text-gray-900 dark:text-gray-100 ">Gifts
                    </div>
                <p class="mt-2 text-2xl font-bold spacing-sm text-black dark:text-white" >{{ counts.gifts }}</p>
                </div>
            </Link>
        </div>
        </div>
    </div>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import Layout from '@/Shared/Layout.vue'

export default {
  components: {
    Head,
    Link
  },
  layout: Layout,
  props: {
    counts: Object,
  },
}


</script>
