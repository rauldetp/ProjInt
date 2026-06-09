<template>
    <div class="layout">
        <!-- Top Nav -->
        <HugNavbar :show-logout="true" />

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
                                    style="color: white; text-decoration: none"
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
import { useAuthStore } from "../stores/auth";
import HugNavbar from "../components/HugNavbar.vue";

const auth = useAuthStore();
</script>

<style scoped>
.layout {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: var(--light-grey);
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
    font-size: 0.875rem;
    font-weight: 500;
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
    font-weight: 600;
    border-bottom-color: var(--color-default-red);
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
    background: var(--default-titles);
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
    color: var(--color-default-green);
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
    color: var(--light-grey);
    margin: 0;
}
</style>
