<template>
    <div class="w-screen h-screen flex flex-col md:flex-row">
        <div class="hidden md:block w-full md:w-1/2 p-6">
            <div class="rounded-xl h-full w-full relative overflow-hidden">
                <img class="absolute inset-0 w-full h-full object-cover" src="/images/login.jpg" alt="Logo" />
            </div>
        </div>
  
      <!-- Right form side -->
      <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-6">
            <div class="text-2xl text-white bg-indigo-500 p-4 rounded-full mb-6">
                <span>DG</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-semibold mb-4 text-center">Welcome Back!</h2>
            <p class="text-center text-gray-500 mb-1">Please enter your credentials to access your</p>
            <p class="text-center text-gray-500 mb-6">account.</p>
    
            <form @submit.prevent="submit" class="w-full max-w-md">
                <label class="text-sm">Email</label>
                <v-text-field
                    v-model="form.email"
                    placeholder="example@gmail.com"
                    name="email"
                    variant="outlined"
                    class="mt-2"
                    style="border-radius: 10px;"
                    :error-messages="errors.email"
                />
        
                <label class="text-sm">Password</label>
                <v-text-field
                    v-model="form.password"
                    :append-inner-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="show1 ? 'text' : 'password'"
                    placeholder="************"
                    name="password"
                    @click:append-inner="show1 = !show1"
                    variant="outlined"
                    class="mt-2 mb-2"
                    style="border-radius: 10px;"
                    :error-messages="errors.password"
                />
        
                <button class="w-full bg-blue-600 text-white p-2 rounded-lg mt-6 hover:bg-blue-700 transition">
                    Login
                </button>
        
                <p class="mt-6 text-center text-sm">
                    Don’t have an account yet?
                    <span class="font-semibold text-blue-500 cursor-pointer hover:text-blue-600" @click="signUp"> Sign Up</span>
                </p>
        
            </form>
        </div>
    </div>
</template>

<script>

import { useAuthStore } from '@/store/auth';
import router from '@/router';
import { computed } from 'vue';

export default {
    name: 'Login',
    setup() {
        const auth = useAuthStore();
        
        const errors = computed({
            get: () => auth.errors,
        });
       
        const login = auth.login;

        return { errors, login };
    },
    data() {
        return {
            form: {
                email: null,
                password: null,
                remember: false
            },
            show1: false,
        }
    },
    async mounted() {
        //
    },
    methods: {
        submit() {
            this.login(this.form)
        },
        signUp() {
            router.push('/register')
        }
    },
};
</script>


<style>
    .v-field {
        border-radius: 10px !important; /* Adjust size here */
    }
</style>