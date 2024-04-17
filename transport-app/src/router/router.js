import { createRouter, createWebHistory } from "vue-router";
import Home from '../pages/TrucksPage.vue';
import Trucks from '../pages/TrucksPage.vue';


const routes = [
    {
        path: '/',
        component: Home,
        name: 'Home'
    },
    
    {
        path: '/trucks',
        component: Trucks,
        name: 'TrucksPage'
    },
    
];

const router = createRouter({history:createWebHistory(), routes});

export default router;