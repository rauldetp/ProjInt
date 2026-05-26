<template>
  <div class="collecte-details">
    <p>Date : {{ collecte.date_debut }}</p>
    <p>Lieu : {{ collecte.lieu }}</p>
    <p>Objectif : {{ collecte.nb_inscrits_estime }} dons</p>
    <p>{{ compteur }} collègues participent déjà</p>
  </div>
</template>

<script setup>
defineProps(['collecte', 'compteur'])
</script>
