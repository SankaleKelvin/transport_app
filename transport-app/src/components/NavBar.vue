<template>
    <!-- <v-navigation-drawer v-model="drawer" color="blue" dark app class="red darken-4"> -->
    <v-navigation-drawer v-model="drawer" :color="computedColor" dark app class="red darken-4" v-if="shouldShowFooter">
      <v-layout column align-center>
        <v-flex class="my-2 mx-auto text-center">
          <v-avatar size="100">
            <!-- <v-img src="/img1.png"></v-img> -->
          </v-avatar>
          <p class="white--text subheading mt-1 text-center">Username</p>
        </v-flex>
      </v-layout>
      <v-list flat>
        <v-list-item v-for="link in links" :key="link.text" router :to="link.route" active-class="border">
          <v-list-item-content class="d-flex align-center">
            <v-icon>{{ link.icon }}</v-icon>
            <span class="ml-2">{{ link.text }}</span>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-app-bar color="blue" dark app v-if="shouldShowFooter">
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title class="text-uppercase">
        <span class="font-weight-light">E-Health</span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
  
      <v-menu offset-y>
        <template v-slot:activator="{ props }">
          <v-btn text v-bind="props">
            <v-icon left>mdi-chevron-down</v-icon>
            <span>Menu</span>
          </v-btn>
        </template>
        <v-list flat>
          <v-list-item v-for="link in links" :key="link.text" router :to="link.route" active-class="border">
            <v-list-item-title>{{ link.text }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
  
      <v-btn text @click="toggleAuthentication">
        <span>{{ authenticated ? 'Logout' : 'Login' }}</span>
        <v-icon right>{{ authenticated ? 'mdi-logout' : 'mdi-login' }}</v-icon>
      </v-btn>
    </v-app-bar>
  </template>

  <script>
//   import { useColorsStore } from '@/store/colors';

  export default {
    data: () => ({
      drawer: true,
      authenticated: false, // Initially, the user is not authenticated
      links: [
        { icon: 'mdi-folder', text: 'Deserts', route: '/deserts' },
        { icon: 'mdi-home', text: 'Home', route: '/' },
        { icon: 'mdi-account-card', text: 'Designation', route: '/designation' },
        { icon: 'mdi-progress-wrench', text: 'Statuses', route: '/statuses' },
        { icon: 'mdi-briefcase', text: 'Projects', route: '/projects' },
        { icon: 'mdi-clipboard-text', text: 'Assignments', route: '/assignments' },
        { icon: 'mdi-upload', text: 'imageUpload', route: '/upload' },
      ],
    }),
    methods: {
      toggleAuthentication() {
        // Toggle the authenticated state
        this.authenticated = !this.authenticated;
      },
    },

    computed: {
      computedColor(){
        // const navColor = useColorsStore();
        return "";
      },

        shouldShowFooter(){
            return this.$route.name !== 'Home';
        },
    },
  };
  </script>
  
  <style scoped>
  .border {
    border-left: 4px solid #0ba518;
  }
  </style>
  