<template>
  <div>
    <Head title="Users" />
    <h1 class="mb-8 text-3xl font-bold">Users</h1>
    <div class="flex items-center justify-between mb-6">
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
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">#ID</th>
            <th class="pb-4 pt-6 px-6">Avatar</th>
            <th class="pb-4 pt-6 px-6">Username</th>
            <th class="pb-4 pt-6 px-6">Email</th>
            <th class="pb-4 pt-6 px-6">Posts</th>
            <th class="pb-4 pt-6 px-6">Followings</th>
            <th class="pb-4 pt-6 px-6">Followers</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="users in users.data" :key="users.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/users/${users.id}/edit`">
                {{ users.id }}
                <icon v-if="users.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/users/${users.id}/edit`" tabindex="-1">
                <img v-if="users.avatar !== null" :src=users.avatar width="50" height="50"/>
                <img v-else src="user.png" width="50" height="50"/>
              </Link>
            </td>
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
              <Link class="flex items-center px-4" :href="`/users/${users.id}/edit`" tabindex="-1">
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
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
  },
}
</script>
