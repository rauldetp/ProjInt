<template>
  <div class="quiz-result">
    <div class="layout">
      <div class="mascotte-column">
        <div class="mascotte-circle">
          <img :src="courage" alt="Mascotte Courage" />
        </div>
      </div>

      <div class="content-column">
        <div class="header-block">
          <h1>{{ title }}</h1>
          <p class="subtitle">{{ subtitle }}</p>
        </div>

        <div v-if="showRaisons" class="reasons-block">
          <h2>Pourquoi certains points ne sont pas éligibles ?</h2>
          <ul>
            <li>Tatouage ou piercing récent</li>
            <li>Opération ou traitement médical récent</li>
            <li>Virus ou médicaments dans les 2 dernières semaines</li>
            <li>Voyage en région à risque</li>
            <li>Nouveau partenaire sexuel</li>
            <li>Transfusion ou greffe récente</li>
          </ul>
          <button class="button secondary" type="button" @click="showRaisons = false">
            Retour
          </button>
        </div>

        <div v-else>
          <div v-if="props.resultat === 'eligible'" class="advice-card">
            <p>La validation finale sera effectuée sur place par l'équipe médicale.</p>
          </div>

          <ReservationCTA
            v-if="props.resultat === 'eligible'"
            :collecte="collecte"
            :showInfos="true"
          />

          <button
            v-else-if="props.resultat === 'non-eligible'"
            class="button"
            type="button"
            @click="showRaisons = true"
          >
            Découvrir pourquoi
          </button>

          <button
            v-else
            class="button"
            type="button"
            @click="showRaisons = false"
          >
            J'ai bien compris
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import courage from '../assets/courage.png'
import ReservationCTA from './ReservationCTA.vue'

const props = defineProps({
  resultat: {
    type: String,
    required: true,
    validator: value => ['eligible', 'non-eligible', 'incertain'].includes(value),
  },
  collecte: {
    type: Object,
    required: false,
    default: () => ({}),
  },
})

const showRaisons = ref(false)

const title = computed(() => {
  if (props.resultat === 'eligible') return 'Bravo ! Vous êtes la star du don.'
  if (props.resultat === 'non-eligible') return 'Certains points ne sont pas éligibles.'
  return 'Courage vous informe !'
})

const subtitle = computed(() => {
  if (props.resultat === 'eligible') return 'Sur la base de vos réponses, vous remplissez les principales conditions de don.'
  if (props.resultat === 'non-eligible') return 'Malheureusement, sur la base de vos réponses, certains points ne remplissent pas les conditions de don adéquates.'
  return 'Nous ne pouvons pas déterminer votre éligibilité complète pour le moment. Voici quelques conseils à suivre avant de vous rendre à la collecte.'
})
</script>

<style scoped>
.quiz-result {
  background: white;
  border-radius: 24px;
  box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
  overflow: hidden;
}

.layout {
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 0;
}

.mascotte-column {
  background: #f8fafc;
  padding: 3rem 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.mascotte-circle {
  width: 220px;
  height: 220px;
  border-radius: 50%;
  background: #f1f5f9;
  display: flex;
  align-items: center;
  justify-content: center;
}

.mascotte-circle img {
  max-width: 140px;
  max-height: 140px;
}

.content-column {
  padding: 3rem 2.5rem;
}

.header-block h1 {
  margin: 0 0 1rem;
  font-size: 2.25rem;
  line-height: 1.05;
  color: #0f172a;
}

.subtitle {
  margin: 0 0 2rem;
  color: #475569;
  font-size: 1rem;
  line-height: 1.75;
}

.advice-card {
  background: #dcfce7;
  border: 1px solid #86efac;
  border-radius: 18px;
  padding: 1.25rem 1.5rem;
  color: #166534;
  margin-bottom: 1.75rem;
}

.reasons-block h2 {
  margin: 0 0 1.25rem;
  font-size: 1.5rem;
  color: #0f172a;
}

.reasons-block ul {
  list-style: disc;
  margin: 0 0 2rem 1.25rem;
  padding: 0;
  color: #475569;
}

.reasons-block li {
  margin-bottom: 0.85rem;
}

.button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.95rem 1.5rem;
  border: none;
  border-radius: 9999px;
  background: #16a34a;
  color: white;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.button.secondary {
  background: #e5e7eb;
  color: #0f172a;
}

.button:hover {
  background: #15803d;
}

.button.secondary:hover {
  background: #d1d5db;
}

@media (max-width: 900px) {
  .layout {
    grid-template-columns: 1fr;
  }

  .mascotte-column,
  .content-column {
    padding: 2rem;
  }
}

@media (max-width: 640px) {
  .content-column {
    padding: 1.5rem;
  }

  .mascotte-circle {
    width: 180px;
    height: 180px;
  }

  .header-block h1 {
    font-size: 1.75rem;
  }
}
</style>
