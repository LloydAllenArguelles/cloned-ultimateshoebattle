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

document.addEventListener('DOMContentLoaded', function() {
    const darkToggle = document.getElementById('darkMode-button');
    const darkToggles = document.querySelectorAll('.darkMode-toggle');

    function setDarkMode(isDarkMode) {
        darkToggles.forEach(navElement => {
            if (isDarkMode) {
                navElement.classList.remove('bg-light', 'navbar-light');
                navElement.classList.add('bg-dark', 'navbar-dark');
            } else {
                navElement.classList.remove('bg-dark', 'navbar-dark');
                navElement.classList.add('bg-light', 'navbar-light');
            }
        });

        // Store the dark mode preference in localStorage
        localStorage.setItem('darkMode', isDarkMode.toString());
    }

    // Retrieve the stored dark mode preference from localStorage
    const storedDarkMode = localStorage.getItem('darkMode');
    if (storedDarkMode === 'true') {
        darkToggle.checked = true;
        setDarkMode(true);
    }

    darkToggle.addEventListener('change', function() {
        setDarkMode(darkToggle.checked);
    });
});