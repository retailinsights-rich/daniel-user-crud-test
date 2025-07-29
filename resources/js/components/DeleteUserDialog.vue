<template>
    <v-card>
        <v-card-text class="px-4 flex flex-col justify-between overflow-hidden" style="height:300px;">
            <div>
                <div class="pt-2">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-xl font-semibold">Delete User</p>
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

            <div class="flex-grow flex flex-col gap-3">
                <p class="text-center text-red-600">Are you sure you want to delete this user?</p>
                <p class="text-center text-gray-500">This action is permanent and cannot be undone.</p>
            </div>
            
            <div>
                <div style="height: 1.2px;" class="bg-gray-200 w-full mb-3 mt-6"></div>
                <div class=" pt-2 flex items-end justify-end">
                    <div 
                        :class="`hover:bg-red-600 font-semibold px-4 py-3 rounded-lg text-white flex items-center gap-2 cursor-pointer transition duration-150 bg-red-500`"
                        @click="deleteUser"
                    >
                        <i class="fa-regular fa-trash-can"></i>
                        <span class="text-sm">Delete User</span>
                    </div>
                </div>
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
import axios from '@/axios';
export default {
    name: 'DeleteUserDialog',
    props: {
        userId: {
            type: Number,
            default: null
        },
    },
    emits: ['close-dialog', 'user-deleted'],
    data() {
        return {
            // 
        }
    },
    async mounted() {
        // 
    },
    methods: { 
        // Post Calls
        async deleteUser() {
            try {
                const response = await axios.post(`/api/users/delete/${this.userId}`, {})

                this.$emit('user-deleted');

            } catch (error) {
                console.error('Delete user failed:', error)
                alert('An error occurred while deleting user.')

            }
            
            this.closeDialog();
        },

        // Dialogs
        closeDialog() {
            this.$emit('close-dialog');
        }
    },
};
</script>

<style scoped>
  
</style>