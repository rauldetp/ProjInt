<template>
  <div v-if="loading" class="loading">Chargement...</div>
  <div v-else-if="error" class="error">{{ error }}</div>
  <div v-else class="entreprise-page" :style="{ '--brand-color': brandColor }">

    <header class="page-header">
      <div class="brand">
        <div class="hug-logo">HUG</div>
        <div class="divider"></div>
        <div class="company-logo">
          <img v-if="entreprise.logo" :src="entreprise.logo" :alt="entreprise.nom" />
          <span v-else>{{ entreprise.nom }}</span>
        </div>
      </div>
      <nav class="page-nav">
        <a href="#label">Label CTS</a>
        <a href="#trophee">Trophée de la générosité</a>
        <a href="#faq">FAQ</a>
        <a href="#contact">Contact</a>
      </nav>
    </header>

    <section class="hero" :style="heroStyle">
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <p class="eyebrow">Collecte de sang</p>
        <h1>{{ entreprise.nom }} x HUG</h1>
        <p class="hero-text">Participez à la collecte organisée dans vos locaux du {{ entreprise.ville || 'site partenaire' }}.</p>
        <div class="hero-actions">
          <button class="button secondary" @click="startQuiz">Faire le quiz</button>
        </div>
      </div>
    </section>

    <section class="info-panel">
      <div class="info-card">
        <span class="icon-dot red"></span>
        <p>{{ collecte?.sur_site ? 'Sur site entreprise' : 'Centre de transfusion' }}</p>
      </div>
      <div class="info-card">
        <span class="icon-dot red"></span>
        <p>{{ dateRange }}</p>
      </div>
    </section>

    <section class="stats-section">
      <div class="stats-card">
        <p class="stats-label">Déjà</p>
        <p class="stats-value">{{ collecte?.nb_inscrits_estime ?? 0 }}</p>
        <p class="stats-text">collègues ont déjà passé le test !</p>
        <button class="button primary" @click="startQuiz">Participer dès maintenant</button>
        <ReservationCTA v-if="collecte" :collecte="collecte" />
      </div>
    </section>

    <section v-if="showQuiz" class="quiz-section">
        <QuizResult
            v-if="quizResultat"
            :resultat="quizResultat"
            :collecte="collecte"
        />
        <Quiz v-else @result="handleQuizResult" />
    </section>

    <section class="steps-section">
      <h2>La démarche en trois étapes</h2>
      <div class="steps-grid">
        <div class="step-card">
          <div class="step-number">1</div>
          <h3>Accueil</h3>
          <p>Arrivez sur la collecte et rencontrez l'équipe HUG pour votre accueil.</p>
        </div>
        <div class="step-card">
          <div class="step-number">2</div>
          <h3>Questionnaire médical</h3>
          <p>Répondez au quiz de pré-qualification pour savoir si vous êtes éligible.</p>
        </div>
        <div class="step-card">
          <div class="step-number">3</div>
          <h3>Don</h3>
          <p>Donnez votre sang en toute sécurité dans un cadre médicalisé.</p>
        </div>
      </div>
    </section>

    <section class="engagement-section">
      <div class="engagement-content">
        <div>
          <p class="eyebrow">{{ entreprise.nom }} s'engage</p>
          <h2>Engagement collectif autour du don du sang</h2>
          <p>Rejoignez les équipes de {{ entreprise.nom }} et aidez à soutenir les patients grâce à un geste solidaire.</p>
          <ReservationCTA v-if="collecte" :collecte="collecte" />
        </div>
        <div class="engagement-visual"></div>
      </div>
    </section>

    <footer class="page-footer">
      <div class="footer-logo">HUG</div>
      <div class="footer-links">
        <a href="#label">Label CTS</a>
        <a href="#trophee">Trophée de la générosité</a>
        <a href="#temoignages">Témoignages</a>
        <a href="#faq">FAQ</a>
      </div>
    </footer>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import ReservationCTA from '../components/ReservationCTA.vue'
import Quiz from '../components/Quiz.vue'
import QuizResult from '../components/QuizResult.vue'

const quizResultat = ref(null)
const route = useRoute()
const entreprise = ref({})
const collecte = ref(null)
const loading = ref(true)
const error = ref(null)
const showQuiz = ref(false)

onMounted(async () => {
  try {
    const res = await fetch(`/api/entreprises/${route.params.slug}`)
    if (!res.ok) throw new Error('Entreprise introuvable')
    const data = await res.json()
    entreprise.value = data.entreprise
    collecte.value = data.collecte
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
})

const brandColor = computed(() => entreprise.value.couleur_primaire || '#0f766e')

const heroStyle = computed(() => {
  const hex = entreprise.value.couleur_primaire || '#0f766e'
  const r = parseInt(hex.slice(1, 3), 16)
  const g = parseInt(hex.slice(3, 5), 16)
  const b = parseInt(hex.slice(5, 7), 16)
  return {
    backgroundImage: `linear-gradient(180deg, rgba(${r},${g},${b},0.6), rgba(${r},${g},${b},0.6)), url('/images/hero-default.jpg')`
  }
})

const dateRange = computed(() => {
  if (!collecte.value?.date_debut) return 'Dates à définir'
  const start = new Date(collecte.value.date_debut).toLocaleDateString('fr-FR', {
    day: '2-digit', month: '2-digit', year: 'numeric'
  })
  const end = collecte.value.date_fin
    ? new Date(collecte.value.date_fin).toLocaleDateString('fr-FR', {
        day: '2-digit', month: '2-digit', year: 'numeric'
      })
    : start
  return start === end ? start : `${start} - ${end}`
})

const startQuiz = () => {
  showQuiz.value = true
}

async function handleQuizResult(resultat) {
  quizResultat.value = resultat
  if (!collecte.value?.id) return
  try {
    await fetch(`/api/collectes/${collecte.value.id}/quiz-result`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ resultat }),
    })
  } catch (e) {
    console.error('Erreur sauvegarde quiz', e)
  }
}
</script>

<style scoped>
.loading, .error {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  font-size: 1.25rem;
  color: #64748b;
}

.entreprise-page {
  font-family: Inter, ui-sans-serif, system-ui, sans-serif;
  color: #0f172a;
  background: #f8fafc;
}

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 1.5rem 2rem;
  background: white;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
  position: sticky;
  top: 0;
  z-index: 20;
}

.brand { display: flex; align-items: center; gap: 1rem; }

.hug-logo, .footer-logo {
  font-weight: 700;
  font-size: 1.1rem;
  color: var(--brand-color);
}

.company-logo { display: flex; align-items: center; min-width: 140px; min-height: 40px; }
.company-logo img { max-height: 40px; object-fit: contain; }
.company-logo span { font-weight: 700; font-size: 1rem; }
.divider { width: 1px; height: 40px; background: #e2e8f0; }

.page-nav { display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; }
.page-nav a, .footer-links a { color: #334155; text-decoration: none; font-size: 0.95rem; }

.button { border: none; border-radius: 9999px; padding: 0.85rem 1.5rem; font-weight: 600; cursor: pointer; }
.button.primary { background: var(--brand-color); color: white; }
.button.secondary { background: white; color: var(--brand-color); border: 1px solid var(--brand-color); }

.hero {
  min-height: 520px;
  background-size: cover;
  background-position: center;
  position: relative;
  display: flex;
  align-items: center;
  color: white;
}

.hero-overlay { position: absolute; inset: 0; background: rgba(15, 23, 42, 0.2); }

.hero-content {
  position: relative;
  max-width: 760px;
  padding: 3rem 2rem;
  z-index: 1;
}

.eyebrow {
  text-transform: uppercase;
  letter-spacing: 0.25em;
  font-size: 0.8rem;
  margin-bottom: 0.75rem;
  color: #d1fae5;
}

.hero h1 { font-size: clamp(2.5rem, 4vw, 4rem); line-height: 1.05; margin-bottom: 1rem; }
.hero-text { max-width: 500px; margin-bottom: 1.5rem; color: #e2e8f0; }
.hero-actions { display: flex; flex-wrap: wrap; gap: 1rem; }

.info-panel {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1rem;
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.info-card {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
  display: flex;
  align-items: center;
  gap: 1rem;
}

.icon-dot { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; }
.icon-dot.red { background: #ef4444; }

.stats-section {
  background: linear-gradient(180deg, rgba(15,118,110,0.12), rgba(255,255,255,0.9));
  padding: 3rem 2rem;
}

.stats-card {
  max-width: 760px;
  margin: 0 auto;
  background: white;
  border-radius: 1.5rem;
  padding: 2rem;
  box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.stats-label { text-transform: uppercase; letter-spacing: 0.18em; color: #64748b; margin: 0; }
.stats-value { font-size: 3rem; font-weight: 700; color: #0f172a; margin: 0; }
.stats-text { color: #64748b; margin: 0; }

.steps-section { padding: 3rem 2rem; max-width: 1200px; margin: 0 auto; }
.steps-section h2 { text-align: center; font-size: 2rem; margin-bottom: 2rem; }
.steps-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.5rem; }
.step-card { background: white; border-radius: 1.5rem; padding: 2rem; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08); }

.step-number {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 9999px;
  display: grid;
  place-items: center;
  margin-bottom: 1rem;
  color: white;
  background: var(--brand-color);
  font-weight: 700;
}

.step-card h3 { margin-bottom: 0.75rem; }

.engagement-section { padding: 3rem 2rem; background: white; }

.engagement-content {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 2rem;
  align-items: center;
}

.engagement-visual {
  min-height: 320px;
  border-radius: 1.5rem;
  background: linear-gradient(180deg, rgba(15,118,110,0.25), rgba(255,255,255,0.75));
}

.page-footer {
  padding: 2rem;
  background: #0f172a;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 2rem;
}

.footer-links { display: flex; gap: 1.5rem; flex-wrap: wrap; }
.page-footer a { color: #cbd5e1; text-decoration: none; }

@media (max-width: 980px) {
  .info-panel, .steps-grid, .engagement-content { grid-template-columns: 1fr; }
  .page-header { flex-direction: column; align-items: stretch; }
  .page-nav { justify-content: flex-start; }
}

@media (max-width: 680px) {
  .hero { min-height: 420px; }
  .hero-content { padding: 2rem 1.25rem; }
  .page-header, .stats-section, .steps-section, .engagement-section, .page-footer {
    padding-left: 1rem;
    padding-right: 1rem;
  }
}
</style>
