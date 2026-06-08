<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import { useCoinEntrepriseLink } from '../composables/useCoinEntrepriseLink';

const props = defineProps({
    showLogout: { type: Boolean, default: false },
});

const route  = useRoute();
const router = useRouter();
const auth   = useAuthStore();
const { coinEntrepriseLink } = useCoinEntrepriseLink();

const participerLink = computed(() => {
    if (auth.isAdmin) return '/entreprises';
    if (auth.isCoordinateur && auth.entrepriseSlug) return `/entreprise/${auth.entrepriseSlug}`;
    return '/login';
});

function isActive(path) {
    return route.path === path;
}

function linkStyle(path) {
    const active = isActive(path);
    return {
        color: active ? 'var(--color-default-red)' : 'var(--default-titles)',
        fontWeight: '700',
        textDecoration: 'none',
    };
}

const coinStyle = computed(() => linkStyle(coinEntrepriseLink.value));

function handleLogout() {
    auth.logout();
    router.push('/login');
}
</script>

<template>
    <header class="hug-navbar">
        <div class="hug-navbar-inner max-w-7xl px-8">
            <!-- Brand -->
            <div class="hug-brand">
                <RouterLink to="/" class="brand-hug"><img :src="'/images/logo_hug_h_quadri.png'" alt="Logo HUG" /></RouterLink>
                <div class="brand-sep"></div>
                <span class="brand-sub">Don du sang</span>
            </div>

            <!-- Links -->
            <nav class="hug-nav-links">
                <RouterLink to="/label"   :style="linkStyle('/label')">Label CTS</RouterLink>
                <RouterLink to="/trophee" :style="linkStyle('/trophee')">Trophée de la générosité</RouterLink>
                <RouterLink :to="coinEntrepriseLink" :style="coinStyle">Espace entreprise</RouterLink>
                <RouterLink to="/quiz"    :style="linkStyle('/quiz')">Quiz d'éligibilité</RouterLink>
                <RouterLink to="/contact" :style="linkStyle('/contact')">Contact</RouterLink>
            </nav>

            <!-- CTA -->
            <button v-if="showLogout && auth.isLoggedIn" class="btn btn-outlined-red" @click="handleLogout">
                Déconnexion
            </button>
        </div>
    </header>
</template>

<style scoped>
.hug-navbar {
    background: white;
    border-bottom: 1px solid var(--light-grey);
    height: 76px;
    position: sticky;
    top: 0;
    z-index: 50;
    flex-shrink: 0;
}
.hug-navbar-inner {
    margin: 0 auto;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
}

/* Brand */
.hug-brand {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}
.brand-hug {
    font-weight: 800;
    font-size: 1.25rem;
    color: var(--default-titles);
    text-decoration: none;
}
.brand-hug img {
    max-height: 2.75rem;
    width: auto;
}
.brand-sep {
    width: 1px;
    height: 20px;
    background: rgba(44, 65, 64, 0.3);
    margin: 0 0.25rem;
}
.brand-sub {
    font-size: 1rem;
    font-weight: 600;
    color: var(--color-default-red);
}

/* Nav links */
.hug-nav-links {
    display: flex;
    align-items: center;
    gap: 1.75rem;
    margin-left: auto;
}
.hug-nav-links a {
    font-size: 1rem;
    white-space: nowrap;
    transition: opacity 0.15s;
}
.hug-nav-links a:hover {
    opacity: 0.65;
}

/* CTA */

@media (max-width: 960px) {
    .hug-nav-links { display: none; }
}
</style>
