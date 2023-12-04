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

function setDarkMode(isDarkMode) {
    const elementDarkToggle = document.querySelectorAll('.navbar-bg, .nav-text');
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
        elementDarkToggle.forEach(element => {
            element.style.backgroundColor = 'rgb(47, 47, 47)';
        });
    } else {
        document.body.classList.remove('dark-mode');
        elementDarkToggle.forEach(element => {
            element.style.backgroundColor = 'gainsboro';
        });
    }
    // Store the dark mode preference in local storage
    localStorage.setItem('darkMode', isDarkMode);
}

// Function to toggle dark mode
function toggleDarkMode() {
    const darkToggle = document.getElementById('darkToggle');
    const isDarkMode = darkToggle.checked;
    setDarkMode(isDarkMode);
}

// Check for stored dark mode preference on page load
document.addEventListener('DOMContentLoaded', function() {
    const darkToggle = document.getElementById('darkToggle');
    darkToggle.addEventListener('change', toggleDarkMode);

    const storedDarkMode = localStorage.getItem('darkMode');
    if (storedDarkMode === 'true') {
        darkToggle.checked = true;
        setDarkMode(true);
    }
});