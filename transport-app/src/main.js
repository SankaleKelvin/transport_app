import { createApp } from 'vue';
import App from './App.vue';
import router from './router/router';

// Vuetify
import 'vuetify/styles';
import 'vuetify/dist/vuetify.min.css';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
// import { VDataTable } from 'vuetify/lib/labs/components.mjs';

//icons
import { mdi, aliases } from 'vuetify/iconsets/mdi';
import '@mdi/js'; 
import '@mdi/font/css/materialdesignicons.css'; 
import 'material-design-icons-iconfont/dist/material-design-icons.css';

//pinia
import { pinia } from './stores/pinia';

//colors
import { useColorsStore} from './stores/colors';

const vuetify = createVuetify({
  components,
  directives,
  icons: {
    defaultSet: 'mdi',
    iconfont: 'mdi',
    aliases,
    sets: {
      mdi,
    },
  },
});

// const colorsStore = useColorsStore();
const app = createApp(App);

  app.use(pinia);
  app.config.globalProperties.$colorStore = useColorsStore.footerColor;
//   app.component('VDataTable', VDataTable)
  app.use(router)
  .use(vuetify)
  .mount('#app');

