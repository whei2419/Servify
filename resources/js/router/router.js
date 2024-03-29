import { createRouter, createWebHistory  } from "vue-router";
import Login from "../pages/Login.vue";
import Home from "../pages/Welcome.vue";
import Appointment from "../pages/CreateAppointment.vue";
import Register from "../pages/Register.vue";
import Scanner from "../pages/Scanner.vue";
import Quelist from "../pages/Quelist.vue";
import Details from "../pages/AppointmentDetails.vue";
import Dashboard from "../pages/Dashboard.vue";
import Requirements from "../pages/Requirements.vue"


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
        path: '/dashboard',
        component: Dashboard,
        name: 'Dashboard'
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
    {
        path: '/details',
        component: Details,
        name: 'Details'
    },
    {
        path: '/requirements',
        component: Requirements,
        name: 'Requirements'
    },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.VITE_BASE_URL),
    routes
});
export default router;
