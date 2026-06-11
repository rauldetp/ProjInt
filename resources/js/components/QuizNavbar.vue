<script setup>
import { computed } from "vue";
import { useRoute } from "vue-router";

defineProps({
    onBack: { type: Function, required: true },
    cobrand: { type: Object, default: null },
});

const route = useRoute();
// Accueil : cobrandé si on est dans l'espace d'une entreprise, général sinon.
const homeLink = computed(() =>
    route.params.slug ? `/entreprise/${route.params.slug}` : "/",
);
</script>

<template>
    <header class="quiz-navbar">
        <div class="quiz-navbar-inner">
            <div class="quiz-nav-left">
                <button class="quiz-nav-back" @click="onBack" aria-label="Retour">
                    <span class="material-symbols-outlined">arrow_back</span>
                </button>
                <div class="quiz-nav-avatar">
                    <img :src="'/images/courage/Mascotte_default.png'" alt="Courage" class="quiz-nav-avatar-img" />
                </div>
                <span class="quiz-nav-title">Test don du sang</span>
            </div>

            <!-- Logos : HUG + Don du sang ou HUG × Entreprise -->
            <RouterLink
                :to="homeLink"
                class="quiz-nav-logos"
                aria-label="Retour à l'accueil"
            >
                <img :src="'/images/logo_hug_h_quadri.png'" alt="Logo HUG" class="quiz-nav-logo" />
                <template v-if="cobrand">
                    <span class="quiz-nav-sep">×</span>
                    <img v-if="cobrand.logo" :src="cobrand.logo" :alt="cobrand.nom" class="quiz-nav-logo" />
                    <span v-else class="quiz-nav-cobrand-name" :style="{ color: cobrand.couleurPrimaire }">{{ cobrand.nom }}</span>
                </template>
                <template v-else>
                    <div class="brand-sep"></div>
                    <span class="brand-sub">Don du sang</span>
                </template>
            </RouterLink>
        </div>
    </header>
</template>

<style scoped>
.quiz-navbar {
    background: white;
    border-bottom: 1px solid var(--light-grey);
    height: 76px;
    position: sticky;
    top: 0;
    z-index: 50;
    flex-shrink: 0;
}
.quiz-navbar-inner {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 2rem;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.quiz-nav-left {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.quiz-nav-back {
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    color: var(--default-titles);
    padding: 0.25rem;
    transition: opacity 0.15s;
}
.quiz-nav-back:hover { opacity: 0.6; }
.quiz-nav-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--light-grey);
    flex-shrink: 0;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.quiz-nav-avatar-img {
    width: 100%;
    height: 126%;
    object-fit: cover;
    object-position: top center;
    transform: translateY(8px);
}
.quiz-nav-title {
    color: var(--color-default-red);
    font-weight: 700;
}
.quiz-nav-logos {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    flex-shrink: 0;
    text-decoration: none;
}
.quiz-nav-logo {
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
.quiz-nav-sep {
    color: rgba(44, 65, 64, 0.3);
    font-size: 1.1rem;
}
.quiz-nav-cobrand-name {
    font-weight: 700;
    font-size: 1rem;
}

/* Mobile : on cache les logos à droite (cf. maquettes) */
@media (max-width: 768px) {
    .quiz-navbar-inner {
        padding: 0 1rem;
    }
    .quiz-nav-logos {
        display: none;
    }
}
</style>
