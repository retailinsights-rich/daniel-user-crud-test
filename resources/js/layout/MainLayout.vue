<template>
    <v-app>
        <div class="flex w-screen bg-gray-900">
            <div style="width: 250px;" class="text-white flex flex-col justify-between items-center px-6 py-10 h-screen">
                <div class="flex flex-col gap-8 w-full items-center">
                    <div class="text-2xl text-white bg-indigo-500 p-4 rounded-full mb-6">
                        <span>DG</span>
                    </div>
                    
                    <div class="flex flex-col gap-4 w-full">
                        <div v-for="navRoute in navRoutes" :class="`w-full flex justify-between items-center rounded py-2 px-4 cursor-pointer ${isSelected(navRoute.path) ? 'bg-blue-500' : ''}`"
                            @click="navigateTo(navRoute.path)"
                        >
                            <div class="flex items-center">
                                <div style="width: 28px;">
                                    <i :class="`fa-solid ${navRoute.icon}`"></i>
                                </div>
                                <span text>{{ navRoute.name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <v-spacer />

                <div class="bg-gray-400 w-full my-5" style="height: 1px;"></div>

                <div class="flex flex-col justify-end w-full" v-if="user">
                    <div class="flex justify-between gap-2 items-center w-full">
                        <div>
                            <p class="text-white font-semibold text-sm mb-2">{{ user.name }}</p>
                            <p class="text-gray-300 text-xs">{{ user.email }}</p>
                        </div>

                        <div class="cursor-pointer px-2 py-1 cursor-pointer hover:bg-red-700 rounded transition duration-150" @click="logout">
                            <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-l-2xl overflow-hidden bg-gray-100" style="width: calc(100vw - 250px);">
                <router-view />
            </div>
        </div>
    </v-app>
</template>

<script>
    import { useAuthStore } from '@/store/auth';
    import router from '@/router';
    import { computed } from 'vue';

    export default {
        name: 'MainLayout',
        setup() {
            const auth = useAuthStore();
            
            const logout = auth.logout;
            const user = computed({
                get: () => auth.user,
            });

            return { logout, user };
        },
        computed: {
            route() {
                return this.$route;
            },
        },
        data() {
            return {
                navRoutes: [],
            }
        },
        async mounted() {
            this.navRoutes.push(
                {path: '/dashboard', name: 'Dashboard', icon: 'fa-solid fa-home'},
                {path: '/ttt', name: 'Tic Tac Toe', icon: 'fa-solid fa-gamepad'}
            );
        },
        methods: {
            navigateTo(path) {
                router.push(path)
            },
            isSelected(path) {
                return this.route?.path === path;
            },
        },
    };
</script>
