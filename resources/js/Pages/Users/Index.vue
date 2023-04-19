<template>
  <div>
    <Head title="Users" />
    <h1 class="mb-4 text-2xl font-bold">Users</h1>
    <div class="flex items-center justify-between mb-3">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
      </search-filter>
      <Link class="btn-indigo" href="/users/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;User</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="bg-red text-left font-bold">
            <th class="pb-4 pt-6 px-6">#ID</th>
            <th class="pb-4 pt-6 px-6">Avatar</th>
            <th class="pb-4 pt-6 px-6">Username</th>
            <th class="pb-4 pt-6 px-6">Email</th>
            <th class="pb-4 pt-6 px-6">Posts</th>
            <th class="pb-4 pt-6 px-6">Followings</th>
            <th class="pb-4 pt-6 px-6">Followers</th>
            <th class="pb-4 pt-6 px-6">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="users in users.data" :key="users.id" class="focus-within:bg-gray-100" :class="[
            users.status  == 'disable' ? 'bg-red-300 hover:bg-red-400' : 'hover:bg-gray-200 bg-gray-100'
          ]">
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/users/${users.id}/edit`">
                {{ users.id }}
                <icon v-if="users.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
              </Link>
            </td>
            <td class="border-t">
                <img v-if="users.avatar !== null" class="cursor-pointer" :src=users.avatar width="50" height="50" @click="showAvatar(users.id)"/>
                <img v-else src="user.png" width="50" height="50"/>
            </td>


            <div v-if="IsShowAvatar == users.id" class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex" @click="hideAvatar()">
                <div class="relative w-auto my-6 mx-auto max-w-6xl">
                    <div class="relative p-6 flex-auto">
                        <img v-if="users.avatar !== null" :src=users.avatar width="800" height="400" />
                    </div>
                </div>
            </div>

            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/users/${users.id}/edit`">
                {{ users.name }}
                <icon v-if="users.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/users/${users.id}/edit`">
                {{ users.email }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/users/${users.id}/edit`">
                {{ users.posts_count }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/users/${users.id}/edit`">
                {{ users.followings_count }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/users/${users.id}/edit`">
                {{ users.followers_count }}
              </Link>
            </td>
            <td class="w-px border-t">
                <button v-if="!users.deleted_at" class="button text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy(users.id)">Delete User</button>
                <button v-if="users.status  == 'active'" class="text-red-600 hover:underline ml-4" tabindex="-1" type="button" @click="disable(users.id)">Disable User</button>
                <button v-if="users.status  == 'disable'" class="text-green-600 hover:underline ml-4" tabindex="-1" type="button" @click="active(users.id)">Active User</button>
            </td>
          </tr>
          <tr v-if="users.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">No users found.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="users.links" />
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
    users: Object,
  },
  data() {
    return {
      IsShowAvatar: false,
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
        this.$inertia.get('/users', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    showAvatar(id){
      this.IsShowAvatar = id;
    },
    hideAvatar(id){
      this.IsShowAvatar = false;
    },
    destroy(id) {
      if (confirm('Are you sure you want to delete this user?')) {
        this.$inertia.delete(`/users/${id}`)
      }
    },
    disable(id) {
      if (confirm('Are you sure you want to disable this user?')) {
        this.$inertia.delete(`/users/${id}/disable`)
      }
    },
    active(id) {
      if (confirm('Are you sure you want to active this user?')) {
        this.$inertia.put(`/users/${id}/active`)
      }
    },
  },
}
</script>
