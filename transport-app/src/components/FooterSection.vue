<template>
    <v-footer dark padless app inset v-if="shouldShowFooter">
        <v-card class="flex w-100" flat tile :color="computedColor" :elevation="10">

            <v-card-text class="py-2 white--text text-center">
               <strong>transport-app</strong> {{ new Date().getFullYear() }} 
            </v-card-text>
        </v-card>
    </v-footer>
</template>

<script>
import { useColorsStore } from '@/stores/colors';

export default {
    computed: {
        computedColor(){
            const colorStore = useColorsStore();
            return colorStore.footerColor;
        },

        shouldShowFooter(){
            return this.$route.name !== 'Home';
        },
    },
};
</script>