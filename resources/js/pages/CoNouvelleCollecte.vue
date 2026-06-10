<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useCobrandStore } from "../stores/cobrand";
import { useAuthStore } from "../stores/auth";
import AppNavbar from "../components/AppNavbar.vue";
import Footer from "../components/Footer.vue";

const route  = useRoute();
const router = useRouter();
const cobrand = useCobrandStore();
const auth   = useAuthStore();

const entreprise  = ref(null);
const collecte    = ref(null);
const loading     = ref(true);
const submitting  = ref(false);
const submitError = ref(null);
const editId      = computed(() => route.query.edit ? parseInt(route.query.edit) : null);
const isEdit      = computed(() => !!editId.value);

const brandColor = computed(() => cobrand.couleurPrimaire || "var(--color-default-red)");

/* ── Form state ─────────────────────────────────────────────── */
const form = ref({
    titre:         "",
    date_debut:    "",
    date_fin:      "",
    sur_site:      true,
    lieu:          "",
    horaires:      "",
    objectif_dons: "",
    nb_employes:   "",
});

const acceptPublication   = ref(false);
const acceptTrophee       = ref(false);
const showSmallCompanyInfo = ref(false);

/* ── Submit ─────────────────────────────────────────────────── */
async function submitForm() {
    submitError.value = null;
    submitting.value  = true;
    try {
        const payload = {
            titre:         form.value.titre          || null,
            date_debut:    form.value.date_debut,
            date_fin:      form.value.date_fin       || null,
            sur_site:      form.value.sur_site,
            lieu:          form.value.lieu           || null,
            horaires:      form.value.horaires       || null,
            objectif_dons: form.value.objectif_dons  ? parseInt(form.value.objectif_dons) : null,
        };

        const url    = isEdit.value
            ? `/api/coordinateur/collectes/${editId.value}`
            : "/api/coordinateur/collectes";
        const method = isEdit.value ? "PUT" : "POST";

        const res = await fetch(url, {
            method,
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

        const n = parseInt(form.value.nb_employes);
        if (!isEdit.value && !isNaN(n) && n < 500) {
            showSmallCompanyInfo.value = true;
        } else {
            router.push(`/entreprise/${route.params.slug}/espace`);
        }
    } catch (e) {
        submitError.value = e.message;
    } finally {
        submitting.value = false;
    }
}

/* ── Fetch entreprise + éventuelle collecte à modifier ────────── */
onMounted(async () => {
    try {
        const res = await fetch(`/api/entreprises/${route.params.slug}`);
        if (res.ok) {
            const data = await res.json();
            entreprise.value = data.entreprise;
            collecte.value   = data.collecte ?? null;
            if (data.entreprise) cobrand.set(data.entreprise);
        }
    } catch {}

    if (entreprise.value?.nb_employes) {
        form.value.nb_employes = String(entreprise.value.nb_employes);
    }

    // Mode édition : charger la collecte existante et pré-remplir le formulaire
    if (editId.value) {
        try {
            const res = await fetch(`/api/coordinateur/collectes/${editId.value}`, {
                headers: {
                    Authorization: `Bearer ${auth.token}`,
                    Accept: "application/json",
                },
            });
            if (res.ok) {
                const c = await res.json();
                form.value.titre         = c.titre         ?? "";
                form.value.date_debut    = c.date_debut    ? String(c.date_debut).split("T")[0] : "";
                form.value.date_fin      = c.date_fin      ? String(c.date_fin).split("T")[0]   : "";
                form.value.sur_site      = !!c.sur_site;
                form.value.lieu          = c.lieu          ?? "";
                form.value.horaires      = c.horaires      ?? "";
                form.value.objectif_dons = c.objectif_dons ?? "";
            }
        } catch {}
    }

    loading.value = false;
    document.title = isEdit.value
        ? `Modifier la collecte — ${cobrand.nom || "HUG"}`
        : `Nouvelle collecte — ${cobrand.nom || "HUG"}`;
});
</script>

<template>
    <div class="min-h-screen bg-white">

        <AppNavbar />

        <!-- Form content -->
        <section class="form-section">
            <div class="form-wrap">
                <h1 class="form-title text-black">{{ isEdit ? "Modifier la collecte" : "Créer une nouvelle collecte" }}</h1>

                <form @submit.prevent="submitForm" class="form">

                    <!-- Ligne 1: Nom entreprise + Titre -->
                    <div class="form-row">
                        <div class="field">
                            <label class="captions field-label">Nom de l'entreprise</label>
                            <div class="form-input field-readonly">{{ cobrand.nom || entreprise?.nom || "—" }}</div>
                        </div>
                        <div class="field">
                            <label for="titre" class="captions field-label">Titre de la collecte</label>
                            <input
                                id="titre"
                                v-model="form.titre"
                                type="text"
                                class="form-input"
                                placeholder="ex : Collecte de printemps"
                            />
                        </div>
                    </div>

                    <!-- Ligne 2: Dates -->
                    <div class="form-row">
                        <div class="field">
                            <label for="date_debut" class="captions field-label">Date de début</label>
                            <input id="date_debut" v-model="form.date_debut" type="date" required class="form-input" />
                        </div>
                        <div class="field">
                            <label for="date_fin" class="captions field-label">Date de fin</label>
                            <input id="date_fin" v-model="form.date_fin" type="date" class="form-input" />
                        </div>
                    </div>

                    <!-- Lieu de la campagne -->
                    <div class="field">
                        <p class="captions field-label">Lieu de la campagne</p>
                        <div class="radio-group">
                            <label class="radio-row">
                                <input type="radio" v-model="form.sur_site" :value="true" class="choice-input" />
                                En entreprise
                            </label>
                            <label class="radio-row">
                                <input type="radio" v-model="form.sur_site" :value="false" class="choice-input" />
                                Au Centre de transfusion sanguine
                            </label>
                        </div>
                    </div>

                    <!-- Adresse (si en entreprise) -->
                    <div v-if="form.sur_site" class="field">
                        <label for="lieu" class="captions field-label">Adresse de la collecte</label>
                        <input
                            id="lieu"
                            v-model="form.lieu"
                            type="text"
                            class="form-input"
                            placeholder="ex : Salle A, Rue de la Paix 1, 1211 Genève"
                        />
                    </div>

                    <!-- Horaires + Objectif -->
                    <div class="form-row">
                        <div class="field">
                            <label for="horaires" class="captions field-label">Horaires</label>
                            <input id="horaires" v-model="form.horaires" type="text" class="form-input" placeholder="ex : 09:00 – 17:00" />
                        </div>
                        <div class="field">
                            <label for="objectif" class="captions field-label">Objectif de dons</label>
                            <input id="objectif" v-model="form.objectif_dons" type="number" min="1" class="form-input" placeholder="ex : 40" />
                        </div>
                    </div>

                    <!-- Nombre d'employés -->
                    <div class="field">
                        <label for="nb_employes" class="captions field-label">
                            Nombre d'employés dans l'entreprise
                        </label>
                        <input id="nb_employes" v-model="form.nb_employes" type="number" min="1" class="form-input field-narrow" placeholder="ex : 250" />
                    </div>

                    <!-- Participation et confidentialité -->
                    <div class="field">
                        <p class="captions field-label">Participation et confidentialité</p>
                        <div class="check-group">
                            <label class="check-row">
                                <input type="checkbox" v-model="acceptPublication" class="choice-input" />
                                <span>J'accepte que ma collecte puisse être publiée en tant qu'exemple sur le site web.</span>
                            </label>
                            <label class="check-row">
                                <input type="checkbox" v-model="acceptTrophee" class="choice-input" />
                                <span>J'accepte de participer au
                                <RouterLink :to="`/entreprise/${route.params.slug}/trophee`" class="link-brand" :style="{ color: brandColor }">Trophée de la Générosité</RouterLink>.</span>
                            </label>
                        </div>
                    </div>

                    <!-- Error -->
                    <p v-if="submitError" class="form-error">{{ submitError }}</p>

                    <!-- Submit -->
                    <div>
                        <button
                            type="submit"
                            class="btn"
                            :disabled="submitting || !form.date_debut"
                            :style="{ background: brandColor, color: cobrand.textOnBrand, opacity: (submitting || !form.date_debut) ? 0.6 : 1 }"
                        >{{ submitting ? "Enregistrement…" : isEdit ? "Enregistrer les modifications" : "Envoyer la demande" }}</button>
                    </div>

                </form>
            </div>
        </section>

        <!-- ── Overlay : moins de 500 salariés ───────────────────── -->
        <Transition name="fade">
            <div v-if="showSmallCompanyInfo" class="overlay-backdrop" @click.self="router.push(`/entreprise/${route.params.slug}/espace`)">
                <div class="overlay-card">

                    <h2 class="overlay-title">VOTRE ENTREPRISE COMPTE<br />MOINS DE 500 SALARIÉS&nbsp;?</h2>

                    <p class="overlay-lead">Découvrez nos solutions pour le don</p>

                    <div class="overlay-solution">
                        <span class="overlay-bullet">•</span>
                        <strong>Aux HUG</strong>
                    </div>
                    <p class="overlay-text">
                        Vous avez constitué un groupe de donneurs&nbsp;? Parfait. Nos équipes vous accueillent sur le site fixe de prélèvement le plus proche de votre entreprise. Vous pouvez d'ores et déjà réserver des temps dédiés au don de sang sur notre site.
                    </p>

                    <div class="overlay-solution">
                        <span class="overlay-bullet">•</span>
                        <strong>Dans votre commune</strong>
                    </div>
                    <p class="overlay-text">
                        Il y a sûrement une collecte de don à proximité de votre entreprise. Réunissez tous les collaborateurs donneurs et rejoignez les donneurs de l'extérieur.
                    </p>

                    <a
                        href="https://www.hug.ch/don-du-sang"
                        target="_blank"
                        rel="noopener"
                        class="overlay-btn-reglementaire"
                    >DÉCOUVRIR LES ASPECTS RÉGLEMENTAIRES</a>

                    <button
                        class="overlay-btn-continue"
                        :style="{ background: brandColor }"
                        @click="router.push(`/entreprise/${route.params.slug}/espace`)"
                    >
                        Continuer vers mon espace
                        <span class="material-symbols-outlined" style="font-size: 18px">arrow_forward</span>
                    </button>

                </div>
            </div>
        </Transition>

        <!-- Footer simplifié -->
        <Footer compact />
    </div>
</template>

<style scoped>
/* ── Formulaire ────────────────────────────────────────────── */
.form-section {
    background: var(--light-grey);
    min-height: calc(100vh - 76px - 180px);
    padding: 3rem 0 5rem;
}
.form-wrap {
    max-width: 48rem;
    margin: 0 auto;
    padding: 0 2rem;
}
.form-title {
    margin: 0 0 2rem;
}
.form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}
@media (max-width: 768px) {
    .form-wrap {
        padding: 0 1rem;
    }
    .form-row {
        grid-template-columns: 1fr;
    }
}
.field {
    display: flex;
    flex-direction: column;
}
.field-label {
    color: var(--default-text);
    margin: 0 0 0.4rem;
}
.field-readonly {
    color: var(--default-text);
    cursor: default;
}
.field-narrow {
    max-width: 200px;
}
.radio-group,
.check-group {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
}
.radio-row,
.check-row {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    cursor: pointer;
    color: var(--default-titles);
}
.check-row {
    align-items: flex-start;
    line-height: 1.5;
}
.choice-input {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
    accent-color: var(--color-default-green-39);
}
.check-row .choice-input {
    width: 16px;
    height: 16px;
    margin-top: 2px;
}
.link-brand {
    text-decoration: underline;
}
.form-error {
    color: var(--color-default-red);
    margin: 0;
}

/* ── Overlay backdrop ──────────────────────────────────────── */
.overlay-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(44, 65, 64, 0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 200;
    padding: 1.5rem;
}
.overlay-card {
    background: white;
    border-radius: 16px;
    padding: 2.5rem 2.75rem;
    max-width: 560px;
    width: 100%;
    box-shadow: 0 20px 60px rgba(0,0,0,0.18);
}

/* ── Contenu ───────────────────────────────────────────────── */
.overlay-title {
    font-size: 1.9rem;
    font-weight: 900;
    color: #1d7fc4;
    line-height: 1.15;
    text-transform: uppercase;
    margin: 0 0 1.25rem;
    font-style: italic;
}
.overlay-lead {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--default-titles);
    margin: 0 0 0.6rem;
}
.overlay-solution {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 1rem 0 0.4rem;
}
.overlay-bullet {
    color: var(--color-default-red);
    font-size: 1.2rem;
    line-height: 1;
}
.overlay-solution strong {
    font-size: 0.95rem;
    color: var(--default-titles);
}
.overlay-text {
    font-size: 0.875rem;
    color: var(--default-titles);
    line-height: 1.65;
    margin: 0;
}

/* ── Boutons ───────────────────────────────────────────────── */
.overlay-btn-reglementaire {
    display: block;
    width: 100%;
    margin-top: 1.75rem;
    border: 2px solid var(--color-default-red);
    border-radius: 9999px;
    padding: 0.75rem 1.5rem;
    font-size: 0.8rem;
    font-weight: 800;
    color: var(--color-default-red);
    text-align: center;
    text-decoration: none;
    letter-spacing: 0.04em;
    transition: background 0.15s, color 0.15s;
    box-sizing: border-box;
}
.overlay-btn-reglementaire:hover {
    background: var(--color-default-red);
    color: white;
}
.overlay-btn-continue {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    width: 100%;
    margin-top: 0.75rem;
    border: none;
    border-radius: 9999px;
    padding: 0.75rem 1.5rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
    cursor: pointer;
    font-family: inherit;
    transition: opacity 0.15s;
}
.overlay-btn-continue:hover { opacity: 0.85; }

/* ── Transition fade ───────────────────────────────────────── */
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
