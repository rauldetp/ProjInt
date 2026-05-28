import Home from './pages/Home.vue';

export default [
    { path: '/', component: Home },
    { path: '/login', component: Login },
    { path: '/entreprise/:slug', component: Entreprise },
];
