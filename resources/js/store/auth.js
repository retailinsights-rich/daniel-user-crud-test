import { defineStore } from 'pinia';
import axios from '@/axios';
import router from '@/router';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        errors: {},
        loggedIn: false,
        isAdmin: false,
    }),
    persist: true,
    actions: {
        async fetchUser() {
            try {
                const { data } = await axios.get('/api/user')
                this.loggedIn = data.loggedIn;
                this.user = data.user;
                this.isAdmin = data.is_admin;
                this.mustResetPassword = data.must_reset_password;
                this.errors = {}
                
            } catch (error) {
                this.errors = error.response.data.errors
                this.loggedIn = false;
                this.user = null;
                this.isAdmin = false;
                this.mustResetPassword = false;

            }    
            
            return this.user;
        },
        async login(credentials) {
            try {
                await axios.get('/sanctum/csrf-cookie')
                await axios.post('/login', credentials)

                router.push('/dashboard')
            } catch (error) {
                this.errors = error.response.data.errors
            }
        },
        async register(data) {
            try {
                await axios.get('/sanctum/csrf-cookie')
                await axios.post('/register', data)
                
                router.push('/dashboard')
            } catch (error) {
                this.errors = error.response.data.errors
            }
        },
        async logout() {
            router.push('/login')

            setTimeout(async () => {
                await axios.post('/logout')
                this.user = null
            }, 100)
        },
        async changePassword(data) {
            try {
                const response = await axios.put('/user/password', data);

                return {Status: 'Success', response: response}
            } catch (error) {
                this.errors = error.response.data.errors

                let message = '';
                let i = 1;
                if (error.response.data.errors.current_password) {
                    error.response.data.errors.current_password.forEach(string => {
                        message = `${message}    ${i}: ${string}`
                        i++;
                    }) 
                }
                if (error.response.data.errors.password) {
                    error.response.data.errors.password.forEach(string => {
                        message = `${message}    ${i}: ${string}`
                        i++;
                    }) 
                }
                if (error.response.data.errors.password_confirmation) {
                    error.response.data.errors.password_confirmation.forEach(string => {
                        message = `${message}    ${i}: ${string}`
                        i++;
                    }) 
                }

                return {
                    Status: 422,
                    message: message
                }
            }
        },
        async updateEmailAndName(data) {
            try {
                const response = await axios.put('/api/user/update-email-name', data);

                return response.data;
            } catch (error) {
                this.errors = error.response.data.errors

                let message = '';
                let i = 1;
                if (error.response.data.errors.email) {
                    error.response.data.errors.email.forEach(string => {
                        message = `${message}    ${i}: ${string}`
                        i++;
                    }) 
                }
                if (error.response.data.errors.password) {
                    error.response.data.errors.password.forEach(string => {
                        message = `${message}    ${i}: ${string}`
                        i++;
                    }) 
                }

                return {
                    Status: 422,
                    message: message
                }
            }
        },
    },
})