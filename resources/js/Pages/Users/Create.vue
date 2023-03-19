<template>
  <div>
    <Head title="Create Organization" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/organizations">Organizations</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
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

          <text-input v-model="birthday" type="date"  :error="form.errors.birthday" class="pb-8 pr-6 w-full lg:w-1/2" label="Birthday" />
          <text-input v-model="form.city" :error="form.errors.city" class="pb-8 pr-6 w-full lg:w-1/2" label="City" />

            <text-input v-model="form.address" :error="form.errors.address" class="pb-8 pr-6 w-full" label="Address" />

            <select-input v-model="form.type" :error="form.errors.gender" class="pb-8 pr-6 w-full lg:w-1/2" label="Type" >
                <option class="selected" value="user">User</option>
                <option value="admin">Admin</option>
            </select-input>
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create User</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
        form: this.$inertia.form({
            username: null,
            email: null,
            password: null,
            password_confirmation:null,
            bio: null,
            gender: null,
            phone: null,
            website: null,
            birthday: null,
            type: null,
            avatar_file: null,
            cover_file: null,
            address: null,
            city: null,
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
    store() {
      this.form.post('/users')
    }
  }
}
</script>
