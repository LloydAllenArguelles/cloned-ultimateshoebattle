<template>
    <div class="col-md-12" style="height: 90vh; padding-top: 50px;">
        <h1 class="text-center font-weight-bold"> Shoe Brand Sales Statistics </h1>
        <h5 class="text-center" style="font-style: italic"> Leading footwear sales for 2022 </h5>
        <div id="chart">
            <apexchart type="bar" height="450" :options="chartOptions" :series="series"></apexchart>
            <h5> Source: Company Filings </h5>
        </div>
    </div>
</template>

<style scoped>
#chart {
    max-width: 650px;
    margin: 35px auto;
}
</style>

<script>
import VueApexCharts from 'vue3-apexcharts'

export default {
    props: ['auth'],
    components: {
        apexchart: VueApexCharts,
    },
    data() {
        return {
            sales: {
                nike: 29.1,
                adidas: 13.1,
                skechers: 7.4,
                puma: 4.5,
                asics: 3.0,
                converse: 2.1,
                under_armour: 1.4
            },
            series: [{
                data: [29.1, 13.1, 7.4, 4.5, 3.0, 2.1, 1.4]
            }],
            chartOptions: {
                theme: {
                    mode: 'light',
                    palette: 'palette7',
                    monochrome: {
                        enabled: true,
                        color: '#F46036',
                        shadeTo: 'light',
                        shadeIntensity: 0.65
                    },
                },
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: [
                        'Nike', 'Adidas', 'Skechers', 'Puma', 'Asics', 'Converse', 'Under Armour'
                    ],
                }
            },
        }
    },
    mounted() {
        // this.showChart();
    },
    methods: {
        // showChart() {
        //     var chart = new ApexCharts(document.querySelector("#chart"), options);

        //     chart.render();
        // }
    }
}
</script>


