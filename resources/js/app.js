import './bootstrap';
import { createApp, getCurrentInstance } from 'vue';
import Swal from 'sweetalert2'
import moment from 'moment'
import VueApexCharts from 'vue3-apexcharts'

const app = createApp({});
window.Swal = Swal;
window.moment = moment;

app.use(VueApexCharts)

//Website
import Index from "./components/website/index.vue";
import Home from "./components/website/home.vue";
import Landing from "./components/website/landing.vue";
import Comparison from "./components/website/comparison.vue";
import InDemand from "./components/website/in-demand.vue";
import MostSearched from "./components/website/most-searched.vue";
import Reviews from "./components/website/reviews.vue";
import Stats from "./components/website/stats.vue";

app.component("index", Index);
app.component("home", Home);
app.component("landing", Landing);
app.component("comparison", Comparison);
app.component("inDemand", InDemand);
app.component("mostSearched", MostSearched);
app.component("reviews", Reviews);
app.component("stats", Stats);

app.mount('#app');

const darkToggle = document.getElementById('darkToggle');

darkToggle.addEventListener('change',function(){
    console.log('Toggle changed:', this.checked);
    if (this.checked){
        console.log('Dark mode enabled');
    } else {
        console.log('Dark mode disabled');
    }
});