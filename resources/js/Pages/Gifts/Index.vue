<template>
  <div>
    <Head title="Gift" />
    <h1 class="mb-8 text-3xl font-bold">Gift</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
      </search-filter>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap border-collapse border border-slate-500 ">
        <thead>
          <tr class="bg-red text-left font-bold">
            <th class="border pb-4 pt-6 px-3">#ID</th>
            <th class="border pb-4 pt-6 px-3">Image</th>
            <th class="border pb-4 pt-6 px-3">Title</th>
            <th class="border pb-4 pt-6 px-3">Gift To</th>
            <th class="border pb-4 pt-6 px-3">Gift From</th>
            <th class="border pb-4 pt-6 px-3">Location</th>
            <th class="border pb-4 pt-6 px-3">Tags</th>
            <th class="border pb-4 pt-6 px-3">Date</th>
            <th class="border pb-4 pt-6 px-3">Likes</th>
            <th class="border pb-4 pt-6 px-3">Comments</th>
            <th class="border pb-4 pt-6 px-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="gifts in gifts.data" :key="gifts.id" class="focus-within:bg-gray-100 text-left border border-slate-700">
            <td class="border ml-2 text-center">
                {{ gifts.id }}
                <icon v-if="gifts.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </td>

            <td class="border ml-2 text-center">
                <div>
                    <img v-if="gifts.main_image !== null" class="cursor-pointer object-cover h-50 w-50 rounded mx-auto" :src=gifts.main_image width="50" height="50" @click="showImage(gifts.id)"/>
                    <img v-else src="post.png" width="50" height="50"/>
                </div>
            </td>
            <div v-if="IsShowImage == gifts.id" class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex" @click="hideImage()">
                <div class="relative w-auto my-6 mx-auto max-w-6xl">
                    <div class="relative p-6 flex-auto">
                        <img v-if="gifts.main_image !== null" :src=gifts.main_image width="800" height="400" />
                    </div>
                </div>
            </div>

            <td class="border ml-2 text-center">
                {{ gifts.title }}
                <icon v-if="gifts.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </td>

            <td class="border ml-2">
              <Link class="flex items-center px-3 py-4 focus:text-indigo-500 hover:text-indigo-500" :href="`/users/${gifts.user.id}/edit`">
                {{ gifts.user.username }}
                <icon v-if="gifts.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
              </Link>
            </td>

            <td class="border ml-2">
              <Link v-if="gifts.gift_from" class="flex items-center px-3 py-4 focus:text-indigo-500 hover:text-indigo-500" :href="`/users/${gifts.gift_from.id}/edit`">
                {{ gifts.gift_from.username }}
                <icon v-if="gifts.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
              </Link>
            </td>

            <td class="border ml-2 text-center">
                {{ gifts.location }}
                <icon v-if="gifts.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </td>
            <td class="border ml-2">
                <div v-for="tag in gifts.tags"
                        class="ml-1 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-green-200 text-green-700 rounded-full"
                    >
                    {{ tag }}
                </div>
            </td>
            <td class="border ml-2 text-center">
                {{ gifts.created_at }}
            </td>
            <td class="border ml-2 text-center">
                {{ gifts.likes }}
            </td>
            <td class="border ml-2 text center">
                {{ gifts.comments }}
            </td>
            <td class="border ml-2 text center">
                <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy(gifts.id)">Delete Gift</button>
            </td>
          </tr>
          <tr v-if="gifts.data.length === 0">
            <td class="px-3 py-4 border ml-2" colspan="4">No gifts found.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="gifts.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    gifts: Object,
  },
  data() {
    return {
      IsShowImage: false,
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/gifts', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    destroy(id) {
      if (confirm('Are you sure you want to delete this pick?')) {
        this.$inertia.delete(`/gifts/${id}`)
      }
    },
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    showImage(id){
      this.IsShowImage = id;
    },
    hideImage(id){
      this.IsShowImage = false;
    }
  },
}
</script>
