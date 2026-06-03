<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import { useAuthStore } from "../stores/auth";

const route  = useRoute();
const router = useRouter();
const cobrand = useCobrandStore();
const auth   = useAuthStore();

const entreprise = ref(null);
const loading    = ref(true);
const submitting = ref(false);
const submitError = ref(null);

const brandColor = computed(() => cobrand.couleurPrimaire || "#e60f48");

/* ── Form state ─────────────────────────────────────────────── */
const form = ref({
    titre:         "",
    date_debut:    "",
    date_fin:      "",
    sur_site:      true,
    lieu:          "",
    horaires:      "",
    objectif_dons: "",
});

const acceptPublication = ref(false);
const acceptTrophee     = ref(false);

/* ── Submit ─────────────────────────────────────────────────── */
async function submitForm() {
    submitError.value = null;
    submitting.value  = true;
    try {
        const payload = {
            titre:        form.value.titre         || null,
            date_debut:   form.value.date_debut,
            date_fin:     form.value.date_fin      || null,
            sur_site:     form.value.sur_site,
            lieu:         form.value.lieu          || null,
            horaires:     form.value.horaires      || null,
            objectif_dons: form.value.objectif_dons ? parseInt(form.value.objectif_dons) : null,
        };

        const res = await fetch("/api/coordinateur/collectes", {
            method: "POST",
            headers: {
                Authorization: `Bearer ${auth.token}`,
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify(payload),
        });

        if (!res.ok) {
            const data = await res.json();
            throw new Error(data.message || "Erreur lors de la soumission.");
        }

        // Redirect to espace entreprise after success
        router.push(`/entreprise/${route.params.slug}/espace`);
    } catch (e) {
        submitError.value = e.message;
    } finally {
        submitting.value = false;
    }
}

/* ── Fetch entreprise pour cobrand ───────────────────────────── */
onMounted(async () => {
    try {
        const res = await fetch(`/api/entreprises/${route.params.slug}`);
        if (res.ok) {
            const data = await res.json();
            entreprise.value = data.entreprise;
            if (data.entreprise) cobrand.set(data.entreprise);
        }
    } catch {}
    finally { loading.value = false; }
    document.title = `Nouvelle collecte — ${cobrand.nom || "HUG"}`;
});
</script>

<template>
    <div class="min-h-screen bg-white" style="font-family: 'Instrument Sans', sans-serif">

        <!-- Navbar co-brandée -->
        <header class="bg-white sticky top-0 z-50" style="height: 76px; border-bottom: 1px solid #f2f4f3">
            <div class="max-w-7xl mx-auto px-8 h-full flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <RouterLink to="/" style="font-weight: 800; font-size: 20px; color: #2c4140; text-decoration: none">HUG</RouterLink>
                    <span style="color: rgba(44,65,64,0.3); font-size: 18px; margin: 0 4px">|</span>
                    <span style="font-size: 15px; font-weight: 600; color: #497371">Don du sang</span>
                    <span style="color: rgba(44,65,64,0.3); font-size: 18px; margin: 0 4px">×</span>
                    <span style="font-size: 15px; font-weight: 700" :style="{ color: brandColor }">
                        <img v-if="cobrand.logo" :src="cobrand.logo" :alt="cobrand.nom" style="max-height: 28px; object-fit: contain" />
                        <span v-else>{{ cobrand.nom || entreprise?.nom }}</span>
                    </span>
                </div>
                <nav class="hidden md:flex items-center gap-7 text-base font-medium">
                    <RouterLink :to="`/entreprise/${route.params.slug}`" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Accueil</RouterLink>
                    <RouterLink :to="`/entreprise/${route.params.slug}/label`" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Label CTS</RouterLink>
                    <RouterLink :to="`/entreprise/${route.params.slug}/trophee`" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Trophée de la Générosité</RouterLink>
                    <RouterLink :to="`/entreprise/${route.params.slug}/espace`" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Espace entreprise</RouterLink>
                    <RouterLink to="/contact" style="color: #2c4140; text-decoration: none" class="hover:opacity-60 transition">Contact</RouterLink>
                </nav>
                <RouterLink
                    :to="`/entreprise/${route.params.slug}/nouvelle-collecte`"
                    class="border-2 rounded-full px-5 py-2 text-sm font-semibold"
                    style="text-decoration: none; white-space: nowrap"
                    :style="{ color: brandColor, borderColor: brandColor }"
                >Participer à la collecte</RouterLink>
            </div>
        </header>

        <!-- Form content -->
        <section style="background: #f2f4f3; min-height: calc(100vh - 76px - 180px); padding: 3rem 0 5rem">
            <div class="max-w-3xl mx-auto px-8">
                <h1 class="font-bold mb-8" style="font-size: 32px; color: #2c4140">Créer une nouvelle collecte</h1>

                <form @submit.prevent="submitForm" style="display: flex; flex-direction: column; gap: 1.5rem">

                    <!-- Ligne 1: Nom entreprise + Titre -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem">
                        <div>
                            <label style="font-size: 12px; font-weight: 600; color: #497371; display: block; margin-bottom: 0.4rem">Nom de l'entreprise</label>
                            <div style="background: #e8edec; border-radius: 8px; padding: 0.75rem 1rem; font-size: 14px; color: #497371; border: 1px solid #dce5e4">
                                {{ cobrand.nom || entreprise?.nom || "—" }}
                            </div>
                        </div>
                        <div>
                            <label for="titre" style="font-size: 12px; font-weight: 600; color: #497371; display: block; margin-bottom: 0.4rem">Titre de la collecte</label>
                            <input
                                id="titre"
                                v-model="form.titre"
                                type="text"
                                placeholder="ex : Collecte de printemps"
                                style="width: 100%; border-radius: 8px; padding: 0.75rem 1rem; font-size: 14px; color: #2c4140; border: 1px solid #dce5e4; background: white; outline: none; box-sizing: border-box"
                            />
                        </div>
                    </div>

                    <!-- Ligne 2: Dates -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem">
                        <div>
                            <label for="date_debut" style="font-size: 12px; font-weight: 600; color: #497371; display: block; margin-bottom: 0.4rem">Date de début</label>
                            <input
                                id="date_debut"
                                v-model="form.date_debut"
                                type="date"
                                required
                                style="width: 100%; border-radius: 8px; padding: 0.75rem 1rem; font-size: 14px; color: #2c4140; border: 1px solid #dce5e4; background: white; outline: none; box-sizing: border-box"
                            />
                        </div>
                        <div>
                            <label for="date_fin" style="font-size: 12px; font-weight: 600; color: #497371; display: block; margin-bottom: 0.4rem">Date de fin</label>
                            <input
                                id="date_fin"
                                v-model="form.date_fin"
                                type="date"
                                style="width: 100%; border-radius: 8px; padding: 0.75rem 1rem; font-size: 14px; color: #2c4140; border: 1px solid #dce5e4; background: white; outline: none; box-sizing: border-box"
                            />
                        </div>
                    </div>

                    <!-- Lieu de la campagne -->
                    <div>
                        <p style="font-size: 12px; font-weight: 600; color: #497371; margin: 0 0 0.75rem">Lieu de la campagne</p>
                        <div style="display: flex; flex-direction: column; gap: 0.6rem">
                            <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer; font-size: 14px; color: #2c4140">
                                <input
                                    type="radio"
                                    v-model="form.sur_site"
                                    :value="true"
                                    style="width: 18px; height: 18px; accent-color: v-bind(brandColor)"
                                />
                                En entreprise
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer; font-size: 14px; color: #2c4140">
                                <input
                                    type="radio"
                                    v-model="form.sur_site"
                                    :value="false"
                                    style="width: 18px; height: 18px; accent-color: v-bind(brandColor)"
                                />
                                Au Centre de transfusion sanguine
                            </label>
                        </div>
                    </div>

                    <!-- Adresse (si en entreprise) -->
                    <div v-if="form.sur_site">
                        <label for="lieu" style="font-size: 12px; font-weight: 600; color: #497371; display: block; margin-bottom: 0.4rem">Adresse de la collecte</label>
                        <input
                            id="lieu"
                            v-model="form.lieu"
                            type="text"
                            placeholder="ex : Salle A, Rue de la Paix 1, 1211 Genève"
                            style="width: 100%; border-radius: 8px; padding: 0.75rem 1rem; font-size: 14px; color: #2c4140; border: 1px solid #dce5e4; background: white; outline: none; box-sizing: border-box"
                        />
                    </div>

                    <!-- Horaires + Objectif -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem">
                        <div>
                            <label for="horaires" style="font-size: 12px; font-weight: 600; color: #497371; display: block; margin-bottom: 0.4rem">Horaires</label>
                            <input
                                id="horaires"
                                v-model="form.horaires"
                                type="text"
                                placeholder="ex : 09:00 – 17:00"
                                style="width: 100%; border-radius: 8px; padding: 0.75rem 1rem; font-size: 14px; color: #2c4140; border: 1px solid #dce5e4; background: white; outline: none; box-sizing: border-box"
                            />
                        </div>
                        <div>
                            <label for="objectif" style="font-size: 12px; font-weight: 600; color: #497371; display: block; margin-bottom: 0.4rem">Objectif de dons</label>
                            <input
                                id="objectif"
                                v-model="form.objectif_dons"
                                type="number"
                                min="1"
                                placeholder="ex : 40"
                                style="width: 100%; border-radius: 8px; padding: 0.75rem 1rem; font-size: 14px; color: #2c4140; border: 1px solid #dce5e4; background: white; outline: none; box-sizing: border-box"
                            />
                        </div>
                    </div>

                    <!-- Participation et confidentialité -->
                    <div>
                        <p style="font-size: 12px; font-weight: 600; color: #497371; margin: 0 0 0.75rem">Participation et confidentialité</p>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem">
                            <label style="display: flex; align-items: flex-start; gap: 0.6rem; cursor: pointer; font-size: 14px; color: #2c4140; line-height: 1.5">
                                <input type="checkbox" v-model="acceptPublication" style="width: 16px; height: 16px; margin-top: 2px; flex-shrink: 0" />
                                J'accepte que ma collecte puisse être publiée en tant qu'exemple sur le site web.
                            </label>
                            <label style="display: flex; align-items: flex-start; gap: 0.6rem; cursor: pointer; font-size: 14px; color: #2c4140; line-height: 1.5">
                                <input type="checkbox" v-model="acceptTrophee" style="width: 16px; height: 16px; margin-top: 2px; flex-shrink: 0" />
                                J'accepte de participer au
                                <RouterLink :to="`/entreprise/${route.params.slug}/trophee`" :style="{ color: brandColor }" style="text-decoration: underline">Trophée de la Générosité</RouterLink>.
                            </label>
                        </div>
                    </div>

                    <!-- Error -->
                    <p v-if="submitError" style="font-size: 14px; color: #e60f48; margin: 0">{{ submitError }}</p>

                    <!-- Submit -->
                    <div>
                        <button
                            type="submit"
                            :disabled="submitting || !form.date_debut"
                            style="border-radius: 9999px; padding: 0.75rem 2rem; font-size: 15px; font-weight: 600; color: white; border: none; cursor: pointer; transition: opacity 0.15s"
                            :style="{ background: brandColor, opacity: (submitting || !form.date_debut) ? 0.6 : 1 }"
                        >{{ submitting ? "Envoi en cours…" : "Envoyer la demande" }}</button>
                    </div>

                </form>
            </div>
        </section>

        <!-- Footer simplifié -->
        <footer style="background: #2c4140; padding: 2.5rem 0 2rem">
            <div class="max-w-6xl mx-auto px-8 flex items-center justify-between">
                <div>
                    <div class="font-extrabold text-xl text-white">HUG</div>
                    <div style="font-size: 12px; color: #93cfa9">Hôpitaux Universitaires Genève</div>
                </div>
                <p style="font-size: 13px; color: rgba(242,244,243,0.4)">
                    © {{ new Date().getFullYear() }} Hôpitaux Universitaires Genève
                </p>
            </div>
        </footer>
    </div>
</template>
