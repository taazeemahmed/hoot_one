import './bootstrap';
import { createApp } from 'vue';
import AdminDashboard from './components/AdminDashboard.vue';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

const app = createApp({});
app.component('admin-dashboard', AdminDashboard);
import MarketingDashboard from './components/MarketingDashboard.vue';
app.component('marketing-dashboard', MarketingDashboard);
import MarketingLeadCreate from './components/MarketingLeadCreate.vue';
app.component('marketing-lead-create', MarketingLeadCreate);
import MarketingLeadList from './components/MarketingLeadList.vue';
app.component('marketing-lead-list', MarketingLeadList);
import RepresentativeDashboard from './components/RepresentativeDashboard.vue';
app.component('representative-dashboard', RepresentativeDashboard);

const vueRoot = document.getElementById('vue-app');
if (vueRoot) {
    app.mount(vueRoot);
}
