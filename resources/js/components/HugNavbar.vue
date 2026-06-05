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
        color: active ? '#e60f48' : '#2c4140',
        fontWeight: active ? '700' : '500',
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
        <div class="hug-navbar-inner">

            <!-- Brand -->
            <div class="hug-brand">
                <RouterLink to="/" class="brand-hug">HUG</RouterLink>
                <div class="brand-sep"></div>
                <span class="brand-sub">Don du sang</span>
            </div>

            <!-- Links -->
            <nav class="hug-nav-links">
                <RouterLink to="/label"   :style="linkStyle('/label')">Label CTS</RouterLink>
                <RouterLink to="/trophee" :style="linkStyle('/trophee')">Trophée de la générosité</RouterLink>
                <RouterLink :to="coinEntrepriseLink" :style="coinStyle">Coin entreprise</RouterLink>
                <RouterLink to="/quiz"    :style="linkStyle('/quiz')">Quiz d'éligibilité</RouterLink>
                <RouterLink to="/contact" :style="linkStyle('/contact')">Contact</RouterLink>
            </nav>

            <!-- CTA -->
            <button v-if="showLogout && auth.isLoggedIn" class="btn-logout" @click="handleLogout">
                Déconnexion
            </button>
            <RouterLink v-else :to="participerLink" class="btn-participer">
                Participer
            </RouterLink>

        </div>
    </header>
</template>

<style scoped>
.hug-navbar {
    background: white;
    border-bottom: 1px solid #f2f4f3;
    height: 76px;
    position: sticky;
    top: 0;
    z-index: 50;
    flex-shrink: 0;
}
.hug-navbar-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
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
    color: #2c4140;
    text-decoration: none;
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
    color: #e60f48;
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
.btn-participer {
    font-size: 1rem;
    font-weight: 600;
    color: #e60f48;
    border: 2px solid #e60f48;
    border-radius: 9999px;
    padding: 0.4rem 1.25rem;
    text-decoration: none;
    transition: opacity 0.15s;
    flex-shrink: 0;
    white-space: nowrap;
    background: white;
}
.btn-participer:hover { opacity: 0.8; }

.btn-logout {
    font-size: 1rem;
    font-weight: 600;
    color: #e60f48;
    border: 2px solid #e60f48;
    border-radius: 9999px;
    padding: 0.4rem 1.25rem;
    background: none;
    cursor: pointer;
    flex-shrink: 0;
    transition: opacity 0.15s;
    font-family: inherit;
}
.btn-logout:hover { opacity: 0.75; }

@media (max-width: 960px) {
    .hug-nav-links { display: none; }
}
</style>
