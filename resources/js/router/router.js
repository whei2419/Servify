import { createRouter, createWebHashHistory } from "vue-router";
import Login from "../pages/Login.vue";
import Home from "../pages/Welcome.vue";
import Appointment from "../pages/CreateAppointment.vue";
import Register from "../pages/Register.vue";
import Scanner from "../pages/Scanner.vue";
import Quelist from "../pages/Quelist.vue";


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
        path: '/register',
        component: Register,
        name: 'Register'
    },
    {
        path: '/create',
        component: Appointment,
        name: 'Appointment'
    },
    {
        path: '/scan',
        component: Scanner,
        name: 'Scanner'
    },
    {
        path: '/queue',
        component: Quelist,
        name: 'Quelist'
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes
});
export default router;
