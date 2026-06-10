<template>
    <div class="layout">
        <!-- Top Nav -->
        <AppNavbar :show-logout="true" />

        <!-- Admin sub-nav -->
        <div v-if="auth.isAdmin" class="admin-subnav">
            <div class="subnav-inner">
                <RouterLink to="/admin" exact-active-class="subnav-active">Vue globale</RouterLink>
                <RouterLink to="/admin/collectes" active-class="subnav-active">Collectes</RouterLink>
                <RouterLink to="/admin/coordinateurs" active-class="subnav-active">Entreprises</RouterLink>
                <RouterLink to="/admin/labels" active-class="subnav-active">Labels & Trophées</RouterLink>
            </div>
        </div>

        <!-- Content -->
        <main class="main-content">
            <RouterView />
        </main>

        <!-- Footer : général pour l'admin, cobrandé pour le coordinateur -->
        <Footer :slug="auth.isAdmin ? '' : auth.entrepriseSlug" />
    </div>
</template>

<script setup>
import { useAuthStore } from "../stores/auth";
import AppNavbar from "../components/AppNavbar.vue";
import Footer from "../components/Footer.vue";

const auth = useAuthStore();
</script>

<style scoped>
.layout {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: white;
    font-family: inherit;
}

.admin-subnav {
    background: white;
    border-bottom: 1px solid var(--light-grey);
    position: sticky;
    top: 76px;
    z-index: 49;
}
.subnav-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    gap: 0;
}
.admin-subnav a {
    color: var(--default-text);
    text-decoration: none;
    padding: 0.6rem 1.25rem;
    border-bottom: 2px solid transparent;
    transition: color 0.15s, border-color 0.15s;
}
.admin-subnav a:hover {
    color: var(--default-titles);
}
.admin-subnav a.subnav-active {
    color: var(--color-default-red);
    border-bottom-color: var(--color-default-red);
}

.main-content {
    flex: 1;
    max-width: 1280px;
    width: 100%;
    margin: 0 auto;
    padding: 2.5rem 2rem;
}
</style>
