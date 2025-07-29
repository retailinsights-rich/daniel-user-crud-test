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
    
            <form @submit.prevent="submit" class="w-3/5">
                <div class="flex justify-between w-full">
                    <div style="width: 48%;">
                        <label class="text-sm">Organization Name</label>
                        <v-text-field
                            v-model="form.organizationName"
                            placeholder="Enter Name"
                            variant="outlined"
                            class="mt-2 border-radius"
                            
                            style="border-radius: 20px;"
                            :error-messages="errors.organizationName"
                        ></v-text-field>
                    </div>
                    
                    <div style="width: 48%;">
                        <label class="text-sm">Admin Name</label>
                        <v-text-field
                            v-model="form.name"
                            placeholder="Enter Name"
                            variant="outlined"
                            class="mt-2 border-radius"
                            
                            style="border-radius: 20px;"
                            :error-messages="errors.name"
                        ></v-text-field>
                    </div>
                </div>

                <label class="text-sm">Admin Email</label>
                <v-text-field
                    v-model="form.email"
                    placeholder="Example@gmail.com"
                    variant="outlined"
                    class="mt-2 border-radius"
                    
                    style="border-radius: 20px;"
                    :error-messages="errors.email"
                ></v-text-field>

                <label class="text-sm">Password</label>
                <v-text-field
                    v-model="form.password"
                    :append-inner-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="show1 ? 'text' : 'password'"
                    placeholder="exmaple@345!"
                    name="password"
                    @click:append-inner="show1 = !show1"
                    variant="outlined"
                    class="mt-3 rounded-2xl"
                    :error-messages="errors.password"
                    
                ></v-text-field>

                <label class="text-sm">Confirm Password</label>
                <v-text-field
                    v-model="form.password_confirmation"
                    :append-inner-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="show2 ? 'text' : 'password'"
                    placeholder="************"
                    name="password"
                    @click:append-inner="show2 = !show2"
                    variant="outlined"
                    class="mt-3 rounded-2xl"
                    :error-messages="errors.password_confirmation"
                    
                ></v-text-field>

                <button class="w-full bg-blue-600 text-white p-2 rounded-lg mt-10">Create Account</button>

                <p class="mt-6 text-center flex items-center justify-center gap-2">
                    Already have an account? 
                    <span class="font-semibold text-blue-500 cursor-pointer hover:text-blue-600" @click="login">Log In</span>
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
    name: 'Register',
    setup() {
        const auth = useAuthStore();
        
        const errors = computed({
            get: () => auth.errors,
        });

        const register = auth.register;

        return { errors, register };
    },

    data() {
        return {
            form: {
                organizationName: '',
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            show1: false,
            show2: false,
        }
    },
    async mounted() {
        //
    },
    methods: {
        submit() {
            this.register(this.form)
        },
        login() {
            router.push('/login')
        }
    },
};
</script>


<style>
    .v-field {
        border-radius: 10px !important; /* Adjust size here */
    }
</style>