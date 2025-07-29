<template>
    <v-alert
        v-model="alertBool"
        :color="alertType"
        :icon="`$${alertType}`"
        style="position: fixed !important;"
        class="top-0 left-0 right-0 z-50 mx-auto w-full"
        closable
    >
        <span>{{ alertMessage }}</span>
    </v-alert>

    <div class="h-full w-full border h-screen px-10">
        <div class="flex justify-between items-center px-3" style="height: 120px;">
            <div>
                <p class="text-2xl font-semibold mb-2">User List</p>
            </div>
            <div class="flex gap-4 items-center" v-if="isAdmin">
                <div class="bg-blue-600 text-white px-4 py-2 rounded cursor-pointer hover:bg-blue-500" @click="dialogs.createUser = true">
                    Create User
                </div>
            </div>
        </div>

        <div class="rounded-lg px-3" style="height: calc(100vh - 120px);">
            <div style="border: 1px rgb(238, 238, 238) solid;" class="rounded-lg bg-white">
                <div class="flex items-center px-3 py-4 text-gray-600 font-bold text-sm bg-blue-100 rounded-t-lg" style="height: 45px;">
                    <span class="w-1/4">Name</span>
                    <span class="w-1/4">Email</span>
                    <span class="w-1/4"></span>
                    <div class="w-1/4 flex justify-end" v-if="isAdmin">
                        <span>Actions</span>
                    </div>
                </div>
                
                <div style="height: calc(100vh - 365px);" class="overflow-y-auto">
                    <div v-for="(user, index) in users" :key="user.id" :class="`flex items-center px-3 py-4 border-b border-r border-l text-gray-600 text-sm ${index % 2 === 0 ? 'bg-gray-100': ''}`">                        
                        <div class="w-1/4 flex flex-col">
                            <span>{{ user.name }}</span>
                        </div>
                        
                        <div class="w-1/4 flex flex-col">
                            <span>{{ user.email }}</span>
                        </div>
                        
                        <div class="w-1/4 flex flex-col">
                            <div v-if="user.is_admin" class="text-blue-600 text-xs inline-flex flex-wrap py-1 px-2 rounded-full flex items-center justify-center" style="background-color: rgba(39,102,235,.15);">
                                <span>Admin</span>
                            </div>
                        </div>

                        <div class="w-1/4 flex justify-end" v-if="isAdmin">
                            <v-menu v-model="userMenues[user.id].menuOpen" :close-on-content-click="false">
                                <template #activator="{ props }">
                                    <div
                                        :class="`rounded-full flex items-center justify-center cursor-pointer ${index % 2 === 0 ? 'hover:bg-gray-200' :  'hover:bg-gray-100'}`"
                                        style="width: 40px; height: 40px;"
                                        v-bind="props"
                                    >
                                        <i class="fa-solid fa-ellipsis-vertical text-lg"></i>
                                    </div>
                                </template>

                                <v-list>
                                    <v-list-item :class="`cursor-pointer hover:bg-blue-100`" @click="menuOptionClicked('update', user.id)">
                                        <template v-slot:prepend>
                                            <div :class="`mr-4`">
                                                <i :class="`fa-solid fa-pen-to-square`"></i>
                                            </div>
                                        </template>
                                        <div :class="`flex items-center gap-2`">
                                            <v-list-item-title>
                                                Update
                                            </v-list-item-title>
                                        </div>
                                    </v-list-item>

                                    <v-list-item :class="`cursor-pointer hover:bg-red-100`" @click="menuOptionClicked('remove', user.id)">
                                        <template v-slot:prepend>
                                            <div :class="`mr-4 text-red-600`">
                                                <i :class="`fa-solid fa-trash-can`"></i>
                                            </div>
                                        </template>
                                        <div :class="`flex items-center gap-2 text-red-600`">
                                            <v-list-item-title>
                                                Remove
                                            </v-list-item-title>
                                        </div>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center px-6 rounded-lg mt-4 text-gray-700" style="height: 60px; border: 1px rgb(238, 238, 238) solid;">
                    <div class="w-1/5" @click="previous">
                        <div class="flex items-center gap-2 cursor-pointer" v-if="page !== 1" @click="paginateTo(page - 1)">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Previous</span>
                        </div>
                    </div>

                    <div class="flex w-3/5 justify-center">
                        <div class="flex gap-2 items-center justify-center py-6">
                            <p class="px-4 py-2 rounded-lg cursor-pointer" v-if="page - 2 > 0" @click="paginateTo(page - 2)">{{ page - 2 }}</p>
                            <p class="px-4 py-2 rounded-lg cursor-pointer" v-if="page - 1 > 0" @click="paginateTo(page - 1)">{{ page - 1 }}</p>
                            <p class="bg-blue-500 px-4 py-2 text-white rounded-lg">{{ page }}</p>
                            <p class="px-4 py-2 rounded-lg cursor-pointer" v-if="lastPage >= page + 1" @click="paginateTo(page + 1)">{{ page + 1 }}</p>
                            <p class="px-4 py-2 rounded-lg cursor-pointer" v-if="lastPage >= page + 2" @click="paginateTo(page + 2)">{{ page + 2 }}</p>
                            <p class="px-4 py-2 rounded-lg cursor-pointer" v-if="lastPage === page + 3" @click="paginateTo(page + 3)">{{ page + 3 }}</p>
                            <p v-if="lastPage >= page + 4">...</p>
                            <p class="px-4 py-2 rounded-lg cursor-pointer" v-if="lastPage >= page + 4" @click="paginateTo(lastPage)">{{ lastPage }}</p>
                        </div>
                    </div>

                    <div class="w-1/5">
                        <div class="flex justify-end items-center gap-2 cursor-pointer"  v-if="lastPage >= page + 1" @click="paginateTo(page + 1)">
                            <span>Next</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <v-dialog v-model="dialogs.deleteUser" max-width="1100">
        <template v-slot:default="{ isActive }">
            <DeleteUserDialog @close-dialog="closeDeleteUserDialog" @user-deleted="userDeleted" :userId="selectedUserId" />
        </template>
    </v-dialog>
  
  <v-dialog v-model="dialogs.createUser" max-width="1100">
      <template v-slot:default="{ isActive }">
          <CreateUpdateUserDialog @close-dialog="closeCreateUserDialog" @user-created="userCreated" />
      </template>
  </v-dialog>
  
  <v-dialog v-model="dialogs.updateUser" max-width="1100">
      <template v-slot:default="{ isActive }">
          <CreateUpdateUserDialog @close-dialog="closeUpdateUserDialog" @user-updated="userUpdated" :userId="selectedUserId" />
      </template>
  </v-dialog>
</template>

<script>
import DeleteUserDialog from './DeleteUserDialog.vue';
import CreateUpdateUserDialog from './CreateUpdateUserDialog.vue';
import { useAuthStore } from '@/store/auth';
import { computed } from 'vue';
import axios from '@/axios';

export default {
    name: 'Dashboard',
    components: {
        DeleteUserDialog,
        CreateUpdateUserDialog,
    },
    setup() {
        const auth = useAuthStore();
        
        const isAdmin = computed({
            get: () => auth.isAdmin,
        });

        return { isAdmin };
    },
    watch: {
        'dialogs.deleteUser'(newVal, oldVal) {
            if (!newVal) {
                this.selectedUserId = null;

            }

        },
        'dialogs.updateUser'(newVal, oldVal) {
            if (!newVal) {
                this.selectedUserId = null;

            }

        },
    },
    data() {
        return {
            filters: [],
            users: [],
            userMenues: {},
            page: 1,
            lastPage: null,
            dialogs: {
                createUser: false,
                deleteUser: false,
                updateUser: false,
            },
            selectedUserId: null,
            alertBool: false,
            alertType: 'success',
            alertMessage: '',
        }
    },
    async mounted() {
        this.fetchData();
    },
    methods: {
        menuOptionClicked(key, userId) {
            if (key === 'remove') {
                this.dialogs.deleteUser = true;
                this.selectedUserId = userId;
            } else if (key === 'update') {
                this.selectedUserId = userId;
                this.dialogs.updateUser = true;
            }
        },

        // Fetch Calls
        async fetchData() {
            try {
                const response = await axios.get(`/api/users/index?page=${this.page}`, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                })

                this.users = response.data.users.data;
                this.users.forEach(user => {
                    this.userMenues[user.id] = {menuOpen: false};
                });

            } catch (error) {
                console.error('Fetch failed:', error)
                this.setAlert({type: 'error', message: 'An error occurred while fetching.'})
            }

        },

        // Dialogs
        closeCreateUserDialog() {
            this.dialogs.createUser = false;

        },
        closeDeleteUserDialog() {
            this.dialogs.deleteUser = false;

        },
        closeUpdateUserDialog() {
            this.dialogs.updateUser = false;

        },
        userCreated() {
            this.selectedQuoteId = null;
            this.dialogs.createUser = false;
            this.fetchData();
            this.setAlert({type: 'success', message: 'User Created'});

        },
        userDeleted() {
            this.selectedQuoteId = null;
            this.dialogs.deleteUser = false;
            this.fetchData();
            this.setAlert({type: 'error', message: 'User Deleted'});

        },
        userUpdated() {
            this.fetchData();
            this.selectedQuoteId = null;
            this.dialogs.updateUser = false;
            this.setAlert({type: 'success', message: 'User Updated'});

        },


        // Table Functions
        paginateTo(page) {
            this.data = [];
            this.page = page;
            this.fetchData();

        },
        setAlert(alertObj) {
            this.alertType = alertObj.type;
            this.alertMessage = alertObj.message;
            this.alertBool = true;
        }
    },
};
</script>

<style>
    .v-checkbox {
        transform: scale(0.8); /* shrink the whole checkbox */
    }
    .custom-placeholder .v-field__input {
        padding: 0 !important;
        min-height: 2.5rem;
    }
    .custom-placeholder .v-field__append-inner {
        padding: 0 !important;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .custom-placeholder input::placeholder {
        font-size: 14px; /* or whatever size you want */
        height: 2.5rem;
    }
    .group:hover > div {
        display: block;
    }

    :deep(.small-date-picker) {
        font-size: 12px;
        width: 250px;
    }
</style>