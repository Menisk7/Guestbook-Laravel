
<script setup>
import {Head, Link} from '@inertiajs/inertia-vue3';
import Guestbook from "@/Pages/Guestbook.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
})
</script>



<template>
    <div class="row justify-content-center min-h-screen">
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</Link>

            <template v-else>
                <Link :href="route('login')" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</Link>
                <Link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</Link>
            </template>
        </div>

        <div class="">Create party</div>
        <div class="flex flex-col">
            <form>
                <div class="">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" v-model="name">
                </div>
                <div class="">
                    <label for="text">Users</label>
                    <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"  v-model="userIds" multiple>
                        <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" :disabled="isSaving || this.name.length < 1" @click.prevent="save">Save</button>
            </form>
        </div>
        <button @click="increment()" class="bg-blue-200 p-2">increment variable in vuex store</button>
        <div class="flex flex-col p-8 pr-20 pl-20">
            <p class=" break-all">{{state}}</p>



            <p class=" break-all">{{users[0].data[0].email}}</p>

            <p class="break-all">{{parties.id}}</p>

        </div>


    </div>
</template>

<script>
import remove from 'lodash/remove';
import Axios from 'axios'

export default {
    data() {
        return {
            users: [],
            parties: [],
            name: '',
            userIds: [],
            isSaving: false,
        };
    },
    computed: { state() { return this.$store.state.count } },

    methods: {

        increment() {
            this.$store.commit('increment')
            console.log(this.$store.state.count)
            console.log(this.$store.state.users)
            console.log(this.$store.state.parties)
        },

        async save()
        {
            this.isSaving = true;
            try {
                const { data } = await Axios.post('/api/parties', {
                    name: this.name,
                    user_ids: this.userIds,
                });

                this.name = '';
                this.userIds = [];

                this.parties.push(data.data);
            } catch (e) {
                alert('There was an error');
                throw e;
            } finally {
                this.isSaving = false;
            }
        },

        async deleteParty(id)
        {
            try {
                await Axios.delete(`/api/parties/${id}`);
            } catch (e) {
                alert('There was an error');
                throw e;
            }

            this.parties = remove(this.parties, (party) => {
                return party.id !== id;
            });
        },
    },

    async mounted()
    {
        const parties = await Axios.get('/api/parties');
        const users = await Axios.get('/api/users');


        this.$store.commit('addUser', users);
        this.$store.commit('addParties', parties);

        this.parties = this.$store.state.parties;
        this.users = this.$store.state.users;

    },
}
</script>
