<template>
  <v-app>
      <NavBar />
      <v-content>
        <v-container fluid>
          <router-view></router-view>
        </v-container>
      </v-content>
      <FooterSection/>
  </v-app>  
  </template>
  
  <script>
    import { defineComponent } from 'vue';
    import NavBar from './components/NavBar.vue';
    import FooterSection from './components/FooterSection.vue';
    import { useRoute, useRouter } from 'vue-router';
  
    export default defineComponent({
      components: {
        NavBar,
        FooterSection,
      },
  
      setup(){
        const route = useRoute();
        const router = useRouter();
  
        router.beforeEach((to, from, next) => {
          const authToken = localStorage.getItem("authToken");
          if(!authToken && to.path !== '/'){
            next('/');
          } else {
            next();
          }
        });
        return {
          route,
          router,
        }
      }
    });
  </script>
  
  
  