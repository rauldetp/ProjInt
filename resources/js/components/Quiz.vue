<template>
  <div class="quiz">
    <h1>Puis-je donner mon sang ?</h1>

    <div v-if="result">
      <p class="result-label">Résultat :</p>
      <p v-if="result === 'eligible'">Éligible</p>
      <p v-else-if="result === 'not_eligible'">Non éligible</p>
      <p v-else>Incertain</p>
      <button @click="restartQuiz">Recommencer</button>
    </div>

    <div v-else>
      <p>Question {{ currentQuestion + 1 }} / {{ questions.length }}</p>
      <p class="question">{{ questions[currentQuestion] }}</p>

      <div class="answers">
        <button type="button" @click="answer('yes')">Oui</button>
        <button type="button" @click="answer('no')">Non</button>
        <button
          v-if="currentQuestion === 8"
          type="button"
          @click="answer('prefer_not_to_answer')"
        >
          Je ne préfère pas répondre
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const currentQuestion = ref(0)
const answers = ref([])

const questions = [
  'Avez-vous entre 18 et 75 ans ?',
  'Vous sentez-vous en bonne santé aujourd\'hui ?',
  'Avez-vous eu un tatouage ou un piercing au cours des 4 derniers mois ?',
  'Avez-vous subi une opération ou un traitement médical récemment ?',
  'Avez-vous eu un virus, un refroidissement ou pris des médicaments au cours des 2 dernières semaines ?',
  'Avez-vous suffisamment mangé et bu aujourd\'hui ?',
  'Êtes-vous enceinte ou avez-vous accouché au cours de l\'année écoulée ?',
  'Avez-vous voyagé dans une région à risque au cours des 4 dernières semaines ?',
  'Avez-vous eu un nouveau partenaire sexuel au cours des 4 derniers mois ?',
  'Avez-vous reçu une transfusion sanguine ou une greffe au cours des 4 derniers mois ?'
]

const result = computed(() => {
  if (answers.value.length !== questions.length) {
    return null
  }

  const hasAnyRiskYes = answers.value.some(answer => answer === 'yes')
  const question9Uncertain = answers.value[8] === 'prefer_not_to_answer'

  if (answers.value.every(answer => answer === 'no')) {
    return 'eligible'
  }

  if (hasAnyRiskYes) {
    return 'not_eligible'
  }

  if (question9Uncertain) {
    return 'uncertain'
  }

  return 'uncertain'
})

const answer = value => {
  answers.value[currentQuestion.value] = value

  if (currentQuestion.value < questions.length - 1) {
    currentQuestion.value += 1
  }
}

const restartQuiz = () => {
  currentQuestion.value = 0
  answers.value = []
}
</script>
