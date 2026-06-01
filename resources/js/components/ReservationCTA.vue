<template>
  <div class="reservation-cta">

    <div class="compteur" v-if="collecte.nb_inscrits_estime > 0">
      <span class="dot"></span>
      <p>{{ collecte.nb_inscrits_estime }} collègues ont déjà réservé leur créneau</p>
    </div>

    <div class="infos-collecte" v-if="showInfos">
      <div class="info-item">
        <span class="label">Lieu</span>
        <span>{{ collecte.sur_site ? 'Sur site entreprise' : 'Centre de transfusion' }}</span>
      </div>
      <div class="info-item">
        <span class="label">Date</span>
        <span>{{ dateRange }}</span>
      </div>
    </div>

    <button class="btn-rdv" @click="handleRDV" :disabled="clicked">
      {{ clicked ? 'Créneau réservé !' : 'Prendre mon RDV' }}
    </button>

    <div class="confirmation" v-if="clicked">
      <p>Votre créneau a bien été pris en compte. À bientôt !</p>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  collecte: {
    type: Object,
    required: true
  },
  showInfos: {
    type: Boolean,
    default: false
  }
})

const clicked = ref(false)

const dateRange = computed(() => {
  if (!props.collecte?.date_debut) return 'Dates à définir'
  const start = new Date(props.collecte.date_debut).toLocaleDateString('fr-FR', {
    day: '2-digit', month: '2-digit', year: 'numeric'
  })
  const end = props.collecte.date_fin
    ? new Date(props.collecte.date_fin).toLocaleDateString('fr-FR', {
        day: '2-digit', month: '2-digit', year: 'numeric'
      })
    : start
  return start === end ? start : `${start} - ${end}`
})

const handleRDV = async () => {
  if (props.collecte?.lien_rdv_externe) {
    window.open(props.collecte.lien_rdv_externe, '_blank')
  }
  try {
    await fetch(`/api/collectes/${props.collecte.id}/nb_inscrits_estime`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' }
    })
  } catch (e) {
    console.error('Erreur incrément inscrit', e)
  }
  clicked.value = true
}
</script>

<style scoped>
.reservation-cta {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.compteur {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  background: #fff1f2;
  border: 1px solid #fecdd3;
  border-radius: 12px;
  padding: 0.85rem 1.25rem;
  color: #9f1239;
  font-size: 0.95rem;
}

.dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #e11d48;
  flex-shrink: 0;
}

.infos-collecte {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 1rem 1.25rem;
}

.info-item {
  display: flex;
  justify-content: space-between;
  font-size: 0.95rem;
  color: #334155;
}

.label {
  font-weight: 600;
  color: #64748b;
}

.btn-rdv {
  border: none;
  border-radius: 9999px;
  background: #e11d48;
  color: white;
  padding: 1rem 1.75rem;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
  align-self: flex-start;
  transition: background 0.2s;
}

.btn-rdv:disabled {
  background: #fda4af;
  cursor: default;
}

.btn-rdv:hover:not(:disabled) {
  background: #be123c;
}

.confirmation {
  background: #fff1f2;
  border: 1px solid #fecdd3;
  border-radius: 12px;
  padding: 1rem 1.25rem;
  color: #9f1239;
  font-size: 0.95rem;
}
</style>
