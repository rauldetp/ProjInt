<template>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-logo">HUG Admin</div>

            <nav class="sidebar-nav">
                <template v-if="auth.isAdmin">
                    <RouterLink to="/admin" exact-active-class="active">
                        Dashboard
                    </RouterLink>
                    <RouterLink to="/admin/collectes" active-class="active">
                        Collectes
                    </RouterLink>
                    <RouterLink to="/admin/coordinateurs" active-class="active">
                        Coordinateurs
                    </RouterLink>
                    <RouterLink to="/admin/labels" active-class="active">
                        Labels CTS
                    </RouterLink>
                    <RouterLink to="/admin/trophees" active-class="active">
                        Trophées
                    </RouterLink>
                </template>

                <template v-if="auth.isCoordinateur">
                    <RouterLink to="/coordinateur" exact-active-class="active">
                        Mon tableau de bord
                    </RouterLink>
                </template>
            </nav>

            <div class="sidebar-footer">
                <p class="sidebar-user">{{ auth.user?.name }}</p>
                <p class="sidebar-role">
                    {{ auth.isAdmin ? "Administrateur" : "Coordinateur" }}
                </p>
                <button @click="handleLogout">Se déconnecter</button>
            </div>
        </aside>

        <main class="admin-main">
            <RouterView />
        </main>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const auth = useAuthStore();
const router = useRouter();

function handleLogout() {
    auth.logout();
    router.push("/login");
}
</script>

<style scoped>
.admin-layout {
    display: grid;
    grid-template-columns: 240px 1fr;
    min-height: 100vh;
    font-family: Inter, ui-sans-serif, system-ui, sans-serif;
}

.sidebar {
    background: #0f172a;
    color: white;
    display: flex;
    flex-direction: column;
    padding: 1.5rem 1rem;
    gap: 2rem;
    position: sticky;
    top: 0;
    height: 100vh;
}

.sidebar-logo {
    font-weight: 700;
    font-size: 1.1rem;
    color: #38bdf8;
    padding: 0 0.5rem;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    flex: 1;
}

.sidebar-nav a {
    color: #94a3b8;
    text-decoration: none;
    padding: 0.65rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.95rem;
    transition:
        background 0.15s,
        color 0.15s;
}

.sidebar-nav a:hover {
    background: #1e293b;
    color: white;
}

.sidebar-nav a.active {
    background: #1e3a5f;
    color: #38bdf8;
    font-weight: 600;
}

.sidebar-footer {
    border-top: 1px solid #1e293b;
    padding-top: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.sidebar-user {
    font-size: 0.875rem;
    color: #e2e8f0;
    margin: 0;
    font-weight: 500;
}

.sidebar-role {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.sidebar-footer button {
    background: none;
    border: 1px solid #334155;
    color: #94a3b8;
    border-radius: 0.5rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    cursor: pointer;
    text-align: left;
    margin-top: 0.25rem;
    transition:
        border-color 0.15s,
        color 0.15s;
}

.sidebar-footer button:hover {
    border-color: #ef4444;
    color: #ef4444;
}

.admin-main {
    background: #f8fafc;
    padding: 2rem;
    overflow-y: auto;
}
</style>
