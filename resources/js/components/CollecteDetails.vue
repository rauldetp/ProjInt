<template>
  <div class="collecte-details">
    <p>Date : {{ collecte.date_debut }}</p>
    <p>Date fin : {{ collecte.date_fin }}</p>
    <p>Lieu : {{ collecte.lieu }}</p>
    <p>Objectif : {{ collecte.nb_inscrits_estime }} dons</p>
    <p>{{ compteur }} collègues participent déjà</p>
  </div>
</template>

<script setup>
defineProps(['collecte', 'compteur'])
</script>
