import { createRouter, createWebHashHistory } from "vue-router";
import Login from "../pages/Login.vue";
import Home from "../pages/Welcome.vue";
import Appointment from "../pages/CreateAppointment.vue";


const routes = [
    {
        path: '/',
        component: Home,
        name: 'Home'
    },
    {
        path: '/login',
        component: Login,
        name: 'Login'
    },
    {
        path: '/create',
        component: Appointment,
        name: 'Appointment'
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes
});
export default router;
