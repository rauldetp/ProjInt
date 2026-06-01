<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

onMounted(() => {
  document.title = 'Trophée de la générosité — HUG'
})
</script>

<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between">
      <span class="font-bold text-lg text-teal-700">HUG — Trophée de la générosité</span>
      <button
        @click="router.push('/login')"
        class="text-sm text-gray-500 hover:text-gray-800 transition"
      >
        Espace admin →
      </button>
    </header>

    <main class="flex-1 flex flex-col items-center justify-center text-center px-6 py-16">
      <p class="text-sm uppercase tracking-widest text-rose-600 font-semibold mb-4">
        Collecte de don du sang
      </p>
      <h1 class="text-4xl font-bold text-gray-900 mb-6 max-w-xl leading-tight">
        Trophée de la générosité
      </h1>
      <p class="text-gray-500 max-w-md mb-10 text-lg leading-relaxed">
        Votre entreprise organise une collecte de sang en partenariat avec les HUG.
        Retrouvez la page de votre entreprise pour participer.
      </p>
      <div class="bg-white rounded-2xl shadow px-8 py-6 text-left max-w-sm w-full">
        <p class="text-sm font-semibold text-gray-600 mb-2">Accéder à la page de votre entreprise</p>
        <p class="text-gray-400 text-sm">
          Exemple : <span class="font-mono text-gray-600">/entreprise/pictet</span>
        </p>
      </div>
    </main>

    <footer class="text-center text-sm text-gray-400 py-6">
      © {{ new Date().getFullYear() }} HUG — Hôpitaux Universitaires de Genève
    </footer>
  </div>
</template>
