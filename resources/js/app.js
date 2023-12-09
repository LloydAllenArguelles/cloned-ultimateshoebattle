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
    const darkToggles = document.querySelectorAll('.darkMode-tog');
    const darkCustom = document.querySelectorAll('.darkMode-tog-c');
    const darkPage = document.querySelectorAll('.darkMode-tog-p');

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
        darkCustom.forEach(navElement => {
            if (isDarkMode) {
                navElement.classList.remove('rightbar-light');
                navElement.classList.add('rightbar-dark');
            } else {
                navElement.classList.remove('rightbar-dark');
                navElement.classList.add('rightbar-light');
            }
        });
        darkPage.forEach(navElement => {
            if (isDarkMode) {
                navElement.classList.remove('page-light');
                navElement.classList.add('page-dark');
            } else {
                navElement.classList.remove('page-dark');
                navElement.classList.add('page-light');
            }
        });

        const logoBlack = document.getElementById('logoBlack');
    const logoWhite = document.getElementById('logoWhite');
    const otherLogo1 = document.getElementById('other-logo-1');
    const otherLogo2 = document.getElementById('other-logo-2');

    if (isDarkMode) {
        logoBlack.style.display = 'none';
        logoWhite.style.display = 'block';
        otherLogo1.style.display = 'none';
        otherLogo2.style.display = 'block';
    } else {
        logoBlack.style.display = 'block';
        logoWhite.style.display = 'none';
        otherLogo1.style.display = 'block';
        otherLogo2.style.display = 'none';
    }


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