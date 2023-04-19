<template>
  <div>
    <Head :title="form.username" />
    <h1 class="mb-4 text-2xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/users">User</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.username }}
    </h1>
    <div
    class="bg-white shadow-xl rounded-lg text-gray-900 mb-5 pb-1">
        <div class="rounded-t-lg h-32 overflow-hidden">
            <img v-if="user.cover != null" class="object-cover object-top w-full" :src='user.cover' alt='Cover'>
            <img v-else class="object-cover object-top w-full" src="/cover.png" alt='Cover'>
        </div>
        <div class="mx-auto w-32 h-32 relative -mt-16 border-4 border-white rounded-full overflow-hidden">
            <img v-if="user.avatar != null" class="object-cover object-center h-32" :src='user.avatar' alt='Avatar'>
            <img v-else src="/user.png" class="object-cover object-center h-32" alt='Avatar'/>
        </div>
    </div>
    <trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore"> This user has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input type="file" @input="form.avatar_file = $event.target.files[0]"  :error="form.errors.avatar" class="pb-8 pr-6 w-full lg:w-1/2" label="Avatar" />

            <text-input type="file" @input="form.cover_file = $event.target.files[0]"  :error="form.errors.cover" class="pb-8 pr-6 w-full lg:w-1/2" label="Cover" />

          <text-input v-model="form.username" :error="form.errors.username" class="pb-8 pr-6 w-full lg:w-1/2" label="Username" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />

            <text-input v-model="form.password" type="password" :error="form.errors.password" class="pb-8 pr-6 w-full lg:w-1/2" label="Password" />
            <text-input v-model="form.password_confirmation" type="password" :error="form.errors.password" class="pb-8 pr-6 w-full lg:w-1/2" label="Password Confirmation" />

            <text-input v-model="form.bio" :error="form.errors.bio" class="pb-8 pr-6 w-full lg:w-1/2" label="Bio" />
            <select-input v-model="form.gender" :error="form.errors.gender" class="pb-8 pr-6 w-full lg:w-1/2" label="Gender" >
            <option :class="{'selected': form.gender == 1}" value="1">Male</option>
            <option :class="{'selected': form.gender == 0}" value="0">Female</option>
            </select-input>



          <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="Phone" />
          <text-input v-model="form.website" :error="form.errors.website" class="pb-8 pr-6 w-full lg:w-1/2" label="Website" />

          <!-- <text-input v-model="birthday" type="date"  :error="form.errors.birthday" class="pb-8 pr-6 w-full lg:w-1/2" label="Birthday" /> -->

          <text-input v-model="form.address" :error="form.errors.address" class="pb-8 pr-6 w-full" label="Address" />

          <text-input v-model="form.city" :error="form.errors.city" class="pb-8 pr-6 w-full lg:w-1/2" label="City" />

          <select-input v-model="form.type" :error="form.errors.gender" class="pb-8 pr-6 w-full lg:w-1/2" label="Type" >
                <option value="user">User</option>
                <option :class="{'selected': isAdmin}" value="admin">Admin</option>
          </select-input>

        </div>

        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!user.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete User</button>
          <button v-if="user.status  == 'active'" class="text-red-600 hover:underline ml-4" tabindex="-1" type="button" @click="disable">Disable User</button>
          <button v-if="user.status  == 'disable'" class="text-green-600 hover:underline ml-4" tabindex="-1" type="button" @click="active">Active User</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update User</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
// import { VueDatePicker } from '@mathieustan/vue-datepicker';
// import '@mathieustan/vue-datepicker/dist/vue-datepicker.min.css';

import moment from 'moment'

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    user: Object,
  },
  remember: 'form',
  data() {
    return {
        isAdmin: this.user.type === 'admin' ? true : false,
        form: this.$inertia.form({
            username: this.user.username,
            email: this.user.email,
            password:'',
            password_confirmation:'',
            bio: this.user.bio,
            type: this.user.type,
            gender: this.user.gender == "male" ? 1 : (this.user.gender == "female") ? 0 : null,
            phone: this.user.phone,
            website: this.user.website,
            // birthday: this.user.birthday ? new Date(this.user.birthday).toISOString().substr(0, 10): null,
            avatar_file: null,
            cover_file: null,
            address: this.user.address,
            city: this.user.city,
            status: this.user.status,
        }),
    }
  },
  computed: {
    birthday: {
        get() {
            return this.form.birthday;
        },
        set(newValue) {
            this.form.birthday = new Date(newValue).toISOString().substr(0, 10);
        },
    },
  },
  methods: {
    update() {
      this.form.post(`/users/${this.user.id}?_method=put`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this user?')) {
        this.$inertia.delete(`/users/${this.user.id}`)
      }
    },
    disable() {
      if (confirm('Are you sure you want to disable this user?')) {
        this.$inertia.delete(`/users/${this.user.id}/disable`)
      }
    },
    active() {
      if (confirm('Are you sure you want to active this user?')) {
        this.$inertia.put(`/users/${this.user.id}/active`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this user?')) {
        this.$inertia.put(`/users/${this.user.id}/restore`)
      }
    },
  },
}
</script>
