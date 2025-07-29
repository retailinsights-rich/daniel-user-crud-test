<template>
    <v-card>
        <v-card-text class="px-4 flex flex-col justify-between" style="max-height: 1100px;">
            <div>
                <div class="pt-2">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-xl font-semibold">{{ userId ? 'Update' : 'Create' }} User</p>
                            <p v-if="!userId" class="text-gray-500 text-sm">New users will be given the temporary password <span class="font-semibold">Temp1234!</span>.</p>
                            <p v-if="!userId" class="text-gray-500 text-sm"> Upon Logging in they will be asked to switch their password.</p>
                        </div>

                        <div>
                            <div class="text-gray-500 text-sm flex justify-center items-center rounded gap-3 px-5 py-2 font-semibold cursor-pointer transition duration-150 hover:bg-gray-100" @click="closeDialog">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="height: 1.2px;" class="bg-gray-200 w-full my-3"></div>
            </div>

            <div class="flex-grow flex flex-col gap-4">
                <div class="w-3/4">
                    <span class="text-xs font-semibold ml-3">Name</span>
                    <v-text-field v-model="form.name" variant="outlined" placeholder="Enter Name" :error-messages="errors.name"></v-text-field>
                </div>
                
                <div class="w-3/4">
                    <span class="text-xs font-semibold">Email</span>
                    <v-text-field v-model="form.email" variant="outlined" placeholder="Enter Email" :error-messages="errors.email"></v-text-field>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="flex gap-2 items-center w-1/4">
                        <div>
                            <v-switch
                                v-model="form.isAdmin"
                                color="primary"
                                hide-details
                            ></v-switch>
                        </div>

                        <div>
                            <p class="text-xs font-semibold">Admin</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="pb-2">
                <div style="height: 1.2px;" class="bg-gray-200 w-full mb-3 mt-6"></div>
                <div class="pt-2 flex items-end justify-end">
                    <div 
                        class="font-semibold px-4 py-3 rounded-lg text-white flex items-center gap-2 cursor-pointer transition duration-150 bg-blue-500 hover:bg-blue-600"
                        @click="submit"
                    >
                        <i class="fa-regular fa-floppy-disk"></i> {{ userId ? 'Update' : 'Create' }} User
                    </div>
                </div>
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
import axios from '@/axios';

export default {
    name: 'CreateUpdateUserDialog',
    emits: ['user-created', 'user-updated', 'close-dialog'],
    props: {
        userId: {
            type: Number,
            default: null
        },
    },
    data() {
        return {
            form: {
                name: '',
                email: '',
                isAdmin: false,
            },
            errors: [],
        }
    },
    async mounted() {
        if (this.userId) {
            await this.fetchUser();
        }
    },
    methods: { 
        submit() {
            if (this.userId) {
                this.updateUser();
            } else {
                this.createUser();
            }
        },
        async fetchUser() {
            try {
                const response = await axios.get(`/api/users/show/${this.userId}`)
                this.form.name = response.data.user.name;
                this.form.email = response.data.user.email;
                this.form.isAdmin = response.data.user.is_admin;

            } catch (error) {
                console.error('Fetch user failed:', error)

            }
        },
        async createUser() {
            try {
                const formData = new FormData()
                formData.append('name', this.form.name)
                formData.append('email', this.form.email)
                formData.append('is_admin', this.form.isAdmin)

                await axios.post('/api/users/create-user', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })

                this.$emit('user-created');

            } catch (error) {
                console.error('Error:', error)
                this.errors = error.response.data.errors
            }
        },
        async updateUser() {
            try {
                const formData = new FormData()
                formData.append('name', this.form.name)
                formData.append('email', this.form.email)
                formData.append('is_admin', this.form.isAdmin)

                await axios.post(`/api/users/update/${this.userId}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })

                this.$emit('user-updated');

            } catch (error) {
                console.error('Error:', error)
                this.errors = error.response.data.errors
            }
        },

        closeDialog() {
            this.$emit('close-dialog');
        }
    },
};
</script>

<style scoped>
    /* :deep(.custom-combobox .v-field__input) {
        padding: 0;
    }
    :deep(.custom-combobox .v-field__append-inner) {
        padding-top: 14px;
    }
    :deep(.custom-combobox .v-field__clearable) {
        padding-top: 14px;
    }
    :deep(.custom-input input) {
        padding: 0;
    }
    :deep(.custom-input .v-input__append) {
        padding: 0 !important;
        display: flex;
        align-items: center;
    }
    .vue-tel-input {
        display: flex;
        flex-direction: row-reverse;
    }
    :deep(.vue-tel-input .vti__input) {
        flex-grow: 1;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        border-radius: 8px !important;
        padding-right: 12px !important;
        padding-left: 12px !important;
        border-width: thin !important;
        border-style: solid !important;
        border-color: rgb(238, 238, 238) !important;
        height: 48px !important;
    }
    :deep(.vue-tel-input .vti__dropdown) {
        display: none !important;
    } */

</style>