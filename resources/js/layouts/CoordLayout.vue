<script setup>
import { onMounted } from "vue";
import { useRoute } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import AppNavbar from "../components/AppNavbar.vue";
import Footer from "../components/Footer.vue";

const route = useRoute();
const cobrand = useCobrandStore();

onMounted(async () => {
    try {
        const res = await fetch(`/api/entreprises/${route.params.slug}`);
        if (res.ok) {
            const data = await res.json();
            if (data.entreprise) cobrand.set(data.entreprise);
        }
    } catch {
        // chargement silencieux du branding
    }
});
</script>

<template>
    <div class="co-layout">
        <AppNavbar />
        <main class="co-main">
            <RouterView />
        </main>
        <Footer :slug="route.params.slug" />
    </div>
</template>

<style scoped>
.co-layout {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: var(--light-grey);
    font-family: inherit;
}
.co-main {
    flex: 1;
    width: 100%;
    max-width: 1280px;
    margin: 0 auto;
    padding: 2.5rem 2rem;
}
</style>
