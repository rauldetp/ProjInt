import Home from './pages/Home.vue';
import Login from './pages/Login.vue';
import Entreprise from './pages/Entreprise.vue';

export default [
    { path: '/', component: Home },
    { path: '/login', component: Login },
    { path: '/entreprise/:slug', component: Entreprise },
];
