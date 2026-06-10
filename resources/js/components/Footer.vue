<script setup>
defineProps({
    // Slug entreprise : si fourni, la colonne « Pages » pointe vers
    // /entreprise/:slug/... ; sinon liens publics.
    slug: { type: String, default: "" },
    // Version réduite : uniquement branding + copyright (pages app/formulaire).
    compact: { type: Boolean, default: false },
});
</script>

<template>
    <footer id="contact" style="background: var(--default-titles)">
        <div class="max-w-7xl mx-auto py-30">
            <div v-if="compact" class="flex items-center justify-between">
                <div>
                    <div class="hug-brand">
                        <RouterLink to="/" class="brand-hug"
                            ><img
                                :src="'/images/LOG HUG_H_NEGATIF fond transparent.png'"
                                alt="Logo HUG"
                        /></RouterLink>
                        <div class="brand-sep"></div>
                        <span class="brand-sub">Don du sang</span>
                    </div>
                    <div
                        class="captions"
                        style="color: var(--color-default-green)"
                    >
                        Hôpitaux Universitaires Genève
                    </div>
                </div>
                <p style="color: rgba(242, 244, 243, 0.4)">
                    © {{ new Date().getFullYear() }} Hôpitaux Universitaires
                    Genève
                </p>
            </div>

            <!-- ── Version complète : 4 colonnes ── -->
            <template v-else>
                <div class="grid grid-cols-4 gap-10 mb-12">
                    <!-- Branding -->
                    <div class="hug-brand">
                        <RouterLink to="/" class="brand-hug"
                            ><img
                                :src="'/images/LOG HUG_H_NEGATIF fond transparent.png'"
                                alt="Logo HUG"
                        /></RouterLink>
                    </div>

                    <!-- Pages -->
                    <div>
                        <h3 class="mb-4 text-white">Pages</h3>
                        <ul class="space-y-3">
                            <template v-if="slug">
                                <li>
                                    <RouterLink
                                        :to="`/entreprise/${slug}`"
                                        class="text-white hover:opacity-70 transition"
                                        style="text-decoration: none"
                                        >Accueil collecte</RouterLink
                                    >
                                </li>
                                <li>
                                    <RouterLink
                                        :to="`/entreprise/${slug}/label`"
                                        class="text-white hover:opacity-70 transition"
                                        style="text-decoration: none"
                                        >Label CTS</RouterLink
                                    >
                                </li>
                                <li>
                                    <RouterLink
                                        :to="`/entreprise/${slug}/trophee`"
                                        class="text-white hover:opacity-70 transition"
                                        style="text-decoration: none"
                                        >Trophée de la générosité</RouterLink
                                    >
                                </li>
                            </template>
                            <template v-else>
                                <li>
                                    <RouterLink
                                        to="/label"
                                        class="text-white hover:opacity-70 transition"
                                        style="text-decoration: none"
                                        >Label CTS</RouterLink
                                    >
                                </li>
                                <li>
                                    <RouterLink
                                        to="/trophee"
                                        class="text-white hover:opacity-70 transition"
                                        style="text-decoration: none"
                                        >Trophée de la générosité</RouterLink
                                    >
                                </li>
                                <li>
                                    <RouterLink
                                        :to="`/quiz`"
                                        class="text-white hover:opacity-70 transition"
                                        style="text-decoration: none"
                                        >Quiz d'eligibilité</RouterLink
                                    >
                                </li>
                            </template>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div>
                        <h3 class="mb-4 text-white">Support</h3>
                        <ul class="space-y-3">
                            <li>
                                <RouterLink
                                    :to="`/login`"
                                    class="text-white hover:opacity-70 transition"
                                    style="text-decoration: none"
                                    >Espace entreprise</RouterLink
                                >
                            </li>
                            <li>
                                <RouterLink
                                    :to="slug ? `/entreprise/${slug}/faq` : '/faq'"
                                    class="text-white hover:opacity-70 transition"
                                    style="text-decoration: none"
                                    >FAQ</RouterLink
                                >
                            </li>
                            <li>
                                <RouterLink
                                    :to="slug ? `/entreprise/${slug}/contact` : '/contact'"
                                    class="text-white hover:opacity-70 transition"
                                    style="text-decoration: none"
                                    >Contact</RouterLink
                                >
                            </li>
                        </ul>
                    </div>

                    <!-- Mentions légales -->
                    <div>
                        <h3 class="mb-4 text-white">Mentions légales</h3>
                        <ul class="space-y-3">
                            <li>
                                <RouterLink
                                    to="/politique-confidentialite"
                                    class="text-white hover:opacity-70 transition"
                                    style="text-decoration: none"
                                    >Politique de confidentialité</RouterLink
                                >
                            </li>
                            <li>
                                <RouterLink
                                    to="/conditions-generales"
                                    class="text-white hover:opacity-70 transition"
                                    style="text-decoration: none"
                                    >Conditions générales</RouterLink
                                >
                            </li>
                        </ul>
                    </div>
                </div>

                <div
                    class="border-t pt-6"
                    style="border-color: rgba(242, 244, 243, 0.15)"
                >
                    <p style="color: rgba(242, 244, 243, 0.5)">
                        © {{ new Date().getFullYear() }} Hôpitaux Universitaires
                        Genève. Tous droits réservés.
                    </p>
                </div>
            </template>
        </div>
    </footer>
</template>

<style scoped>
.brand-hug {
    text-decoration: none;
}
.brand-hug img {
    max-height: 4rem;
    width: auto;
}
.brand-sep {
    width: 1px;
    height: 20px;
    background: rgba(255, 255, 255, 0.3);
    margin: 0 0.25rem;
}
.brand-sub {
    font-size: 1rem;
    font-weight: 600;
    color: white;
}
</style>
