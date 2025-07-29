<template>
    <div class="p-8 max-w-md mx-auto">
        <h2 class="text-xl font-bold mb-4">Reset Your Password</h2>

        <v-text-field v-model="newPassword" label="New Password" type="password" variant="outlined" :error-messages="errors.password" />
        <v-text-field v-model="confirmPassword" label="Confirm Password" type="password" variant="outlined" :error-messages="errors.password_confirmation" />

        <v-btn color="primary" @click="submit">Reset Password</v-btn>
    </div>
</template>

<script>
import axios from '@/axios'

export default {
    data() {
        return {
            newPassword: '',
            confirmPassword: '',
            errors: {},
        };
    },
    methods: {
        async submit() {
            try {
                await axios.post('/api/users/reset-password', {
                    password: this.newPassword,
                    password_confirmation: this.confirmPassword,
                });

                this.$router.push('/dashboard');
            } catch (error) {
                this.errors = error.response.data.errors;
            }
        },
    },
};
</script>