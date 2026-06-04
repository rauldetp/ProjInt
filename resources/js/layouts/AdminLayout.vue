<template>
    <div class="layout">
        <!-- Top Nav (same as public site) -->
        <header class="top-nav">
            <div class="nav-inner">
                <div class="nav-brand">
                    <RouterLink
                        to="/"
                        class="brand-hug"
                        style="text-decoration: none"
                        >HUG</RouterLink
                    >
                    <div class="brand-sep"></div>
                    <span class="brand-sub">Don du sang</span>
                </div>
                <nav class="nav-links">
                    <RouterLink to="/label" style="color:#2c4140; text-decoration:none;" class="hover:opacity-70 transition">Label CTS</RouterLink>
                    <RouterLink to="/trophee" style="color:#2c4140; text-decoration:none;" class="hover:opacity-70 transition">Trophée de la générosité</RouterLink>
                    <RouterLink :to="coinEntrepriseLink" style="color:#2c4140; text-decoration:none;" class="hover:opacity-70 transition">Coin entreprise</RouterLink>
                    <RouterLink to="/contact" style="color:#2c4140; text-decoration:none;" class="hover:opacity-70 transition">Contact</RouterLink>
                </nav>
                <button class="btn-logout" @click="handleLogout">
                    Déconnexion
                </button>
            </div>
        </header>

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

        <!-- Footer -->
        <footer class="site-footer">
            <div class="footer-inner">
                <div class="footer-grid">
                    <div>
                        <span class="footer-hug">HUG</span>
                        <p class="footer-tagline">
                            Hôpitaux<br />Universitaires<br />Genève
                        </p>
                    </div>
                    <div>
                        <p class="footer-col-title">Pages</p>
                        <ul>
                            <li>
                                <RouterLink
                                    to="/label"
                                    class="hover:opacity-70 transition"
                                    style="
                                        font-size: 1rem;
                                        color: white;
                                        text-decoration: none;
                                    "
                                    >Label CTS</RouterLink
                                >
                            </li>
                            <li>
                                <a href="/#trophee">Trophée de la générosité</a>
                            </li>
                            <li><a href="/#temoignages">Témoignages</a></li>
                        </ul>
                    </div>
                    <div>
                        <p class="footer-col-title">Support</p>
                        <ul>
                            <li><a href="/#faq">FAQ</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <p class="footer-col-title">Mentions légales</p>
                        <ul>
                            <li>
                                <a href="#">Politique de confidentialité</a>
                            </li>
                            <li><a href="#">Conditions générales</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-copy">
                    <p>
                        © {{ new Date().getFullYear() }} Hôpitaux Universitaire
                        Genève. Tous droits réservés.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import { useCoinEntrepriseLink } from "../composables/useCoinEntrepriseLink";

const auth = useAuthStore();
const router = useRouter();
const { coinEntrepriseLink } = useCoinEntrepriseLink();

function handleLogout() {
    auth.logout();
    router.push("/login");
}
</script>

<style scoped>
.layout {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: #f2f4f3;
    font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
}

.top-nav {
    background: white;
    border-bottom: 1px solid #f2f4f3;
    height: 76px;
    position: sticky;
    top: 0;
    z-index: 50;
}

.admin-subnav {
    background: white;
    border-bottom: 1px solid #f2f4f3;
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
    font-size: 0.875rem;
    font-weight: 500;
    color: #497371;
    text-decoration: none;
    padding: 0.6rem 1.25rem;
    border-bottom: 2px solid transparent;
    transition: color 0.15s, border-color 0.15s;
}
.admin-subnav a:hover {
    color: #2c4140;
}
.admin-subnav a.subnav-active {
    color: #e60f48;
    font-weight: 600;
    border-bottom-color: #e60f48;
}

.nav-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
}

.nav-brand {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}
.brand-hug {
    font-weight: 800;
    font-size: 1.25rem;
    color: #2c4140;
}
.brand-sep {
    width: 1px;
    height: 20px;
    background: rgba(44, 65, 64, 0.3);
    margin: 0 0.5rem;
}
.brand-sub {
    font-size: 1rem;
    font-weight: 600;
    color: #e60f48;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 1.75rem;
}
.nav-links a {
    font-size: 1rem;
    font-weight: 500;
    color: #2c4140;
    text-decoration: none;
    transition: opacity 0.15s;
}
.nav-links a:hover {
    opacity: 0.6;
}
.nav-links a.active {
    color: #e60f48;
    font-weight: 600;
}

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
}
.btn-logout:hover {
    opacity: 0.75;
}

.main-content {
    flex: 1;
    max-width: 1280px;
    width: 100%;
    margin: 0 auto;
    padding: 2.5rem 2rem;
}

/* Footer */
.site-footer {
    background: #2c4140;
    padding: 3.5rem 0 2.5rem;
}
.footer-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}
.footer-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2.5rem;
    margin-bottom: 3rem;
}
.footer-hug {
    display: block;
    font-weight: 800;
    font-size: 1.5rem;
    color: white;
    margin-bottom: 0.25rem;
}
.footer-tagline {
    font-size: 0.75rem;
    color: #93cfa9;
    line-height: 1.6;
    margin: 0;
}
.footer-col-title {
    font-weight: 700;
    font-size: 1.5rem;
    color: white;
    margin: 0 0 1.25rem;
}
.site-footer ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
.site-footer ul li a {
    font-size: 1rem;
    color: white;
    text-decoration: none;
    transition: opacity 0.15s;
}
.site-footer ul li a:hover {
    opacity: 0.7;
}
.footer-copy {
    border-top: 1px solid rgba(242, 244, 243, 0.15);
    padding-top: 1.5rem;
    text-align: center;
}
.footer-copy p {
    font-size: 1rem;
    color: #f2f4f3;
    margin: 0;
}
</style>
