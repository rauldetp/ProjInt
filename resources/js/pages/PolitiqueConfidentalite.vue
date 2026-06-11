<script setup>
import { onMounted } from "vue";
import AppNavbar from "../components/AppNavbar.vue";
import Footer from "../components/Footer.vue";

onMounted(() => {
    document.title = "Politique de confidentialité — HUG";
});
</script>

<template>
    <div class="min-h-screen bg-white">
        <AppNavbar />

        <section class="max-w-3xl mx-auto px-4 md:px-8 py-16">
            <h1 class="mb-4">Politique de confidentialité</h1>
            <p class="mb-8">Dernière mise à jour : Mai 2026</p>

            <p class="mb-8">
                Les Hôpitaux Universitaires de Genève (HUG), à travers le Centre
                de Transfusion Sanguine (CTS), accordent une importance
                particulière à la protection de vos données personnelles.
            </p>
            <p class="mb-8">
                Cette politique de confidentialité explique quelles données sont
                collectées, pourquoi elles sont utilisées et quels sont vos
                droits lorsque vous utilisez la plateforme CTS, participez à une
                collecte de sang ou effectuez un test d'éligibilité en ligne.
            </p>

            <h2 class="mb-4">1. Responsable du traitement</h2>
            <p class="mb-8">Le responsable du traitement des données est :</p>
            <p class="mb-8">
                Centre de Transfusion Sanguine (CTS)<br />
                Hôpitaux Universitaires de Genève (HUG)<br />
                Rue Gabrielle-Perret-Gentil 4<br />
                1205 Genève<br />
                Suisse
            </p>
            <p class="mb-8">Pour toute question concernant la protection des données :</p>
            <p class="mb-8">📧 cts@hug.ch<br />📞 +41 22 372 XX XX</p>

            <h2 class="mb-4">2. Données collectées</h2>
            <p class="mb-8">
                Selon votre utilisation de la plateforme, nous pouvons collecter
                les informations suivantes :
            </p>
            <p class="mb-8">Informations de contact</p>
            <ul class="mb-8">
                <li>Nom</li>
                <li>Prénom</li>
                <li>Adresse e-mail</li>
                <li>Numéro de téléphone</li>
                <li>Entreprise ou organisation</li>
            </ul>
            <p class="mb-8">Informations liées aux rendez-vous</p>
            <ul class="mb-8">
                <li>Date et heure du rendez-vous</li>
                <li>Lieu de collecte sélectionné</li>
                <li>Historique des rendez-vous</li>
            </ul>
            <p class="mb-8">Informations liées au test d'éligibilité</p>
            <ul class="mb-8">
                <li>Réponses fournies dans le questionnaire d'éligibilité</li>
                <li>Résultat indicatif du test</li>
            </ul>

            <h2 class="mb-4">3. Finalités du traitement</h2>
            <p class="mb-8">Vos données sont utilisées uniquement pour :</p>
            <ul class="mb-8">
                <li>Répondre à vos demandes de contact</li>
                <li>Organiser et gérer les collectes de sang</li>
                <li>Permettre la prise de rendez-vous</li>
                <li>Évaluer votre éligibilité au don de sang</li>
                <li>Améliorer nos services</li>
                <li>Respecter nos obligations légales et réglementaires</li>
            </ul>
            <p class="mb-8">
                Les données ne sont jamais utilisées à des fins commerciales ou
                publicitaires.
            </p>

            <h2 class="mb-4">4. Partage des données</h2>
            <p class="mb-8">
                Les données collectées sont traitées exclusivement par les HUG
                et les personnes autorisées à intervenir dans l'organisation des
                collectes de sang.
            </p>
            <p class="mb-8">
                Dans le cadre des collectes organisées avec des entreprises
                partenaires, ces dernières n'ont pas accès aux réponses du
                questionnaire d'éligibilité ni aux informations médicales
                éventuelles des participants.
            </p>
            <p class="mb-8">
                Les HUG ne vendent ni ne louent vos données personnelles à des
                tiers.
            </p>

            <h2 class="mb-4">5. Conservation des données</h2>
            <p class="mb-8">
                Les données sont conservées uniquement pendant la durée
                nécessaire à la réalisation des objectifs décrits dans cette
                politique et conformément aux exigences légales applicables.
            </p>
            <p class="mb-8">
                Les durées de conservation peuvent varier selon la nature des
                données concernées.
            </p>

            <h2 class="mb-4">6. Sécurité des données</h2>
            <p class="mb-8">
                Les HUG mettent en œuvre des mesures techniques et
                organisationnelles appropriées afin de protéger les données
                contre :
            </p>
            <ul class="mb-8">
                <li>L'accès non autorisé</li>
                <li>La perte accidentelle</li>
                <li>La modification non autorisée</li>
                <li>La divulgation illicite</li>
            </ul>

            <h2 class="mb-4">7. Vos droits</h2>
            <p class="mb-8">
                Conformément à la législation suisse applicable en matière de
                protection des données, vous disposez notamment des droits
                suivants :
            </p>
            <ul class="mb-8">
                <li>Droit d'accès à vos données</li>
                <li>Droit de rectification</li>
                <li>Droit à l'effacement lorsque cela est possible</li>
                <li>Droit à la limitation du traitement</li>
                <li>Droit d'opposition dans certains cas</li>
            </ul>
            <p class="mb-8">
                Toute demande peut être adressée au Centre de Transfusion
                Sanguine via les coordonnées indiquées ci-dessus.
            </p>

            <h2 class="mb-4">8. Cookies</h2>
            <p class="mb-8">
                La plateforme peut utiliser des cookies techniques nécessaires à
                son bon fonctionnement.
            </p>
            <p class="mb-8">Ces cookies permettent notamment :</p>
            <ul class="mb-8">
                <li>De maintenir votre session active</li>
                <li>D'améliorer la navigation</li>
                <li>D'assurer la sécurité du site</li>
            </ul>
            <p class="mb-8">Aucun cookie publicitaire n'est utilisé.</p>

            <h2 class="mb-4">9. Liens externes</h2>
            <p class="mb-8">
                Le site peut contenir des liens vers des sites tiers. Les HUG ne
                sont pas responsables des pratiques de confidentialité
                appliquées sur ces sites externes.
            </p>

            <h2 class="mb-4">10. Modifications de la politique</h2>
            <p class="mb-8">
                Cette politique de confidentialité peut être mise à jour afin de
                refléter les évolutions légales, réglementaires ou techniques.
            </p>
            <p class="mb-8">
                La date de la dernière mise à jour figure en haut de cette page.
            </p>

            <h2 class="mb-4">Contact</h2>
            <p class="mb-8">
                Pour toute question concernant cette politique de
                confidentialité ou le traitement de vos données personnelles :
            </p>
            <p class="mb-8">
                Centre de Transfusion Sanguine (CTS)<br />
                Hôpitaux Universitaires de Genève (HUG)<br />
                📧 cts@hug.ch<br />
                📞 +41 22 372 XX XX
            </p>
        </section>

        <Footer />
    </div>
</template>
