<template>
    <div class="col-md-12 dashboard" style="background-image: url('images/bg8.jpg'); height: 100vh">
        <a class="ml-2" @click="toMain()">
            <img src="images/favicon.png" class="mt-2" alt="" width="200" height="150" style="cursor: pointer">
        </a>
        <div class="col-md-6 mt-5" style=" color: white; padding-left: 50px">
            <h1 class="text-sriracha" style="font-size: 6em; text-shadow: 2px 2px #112211"> SHOE <span style="color: #8F8F8F">BATTLE</span> </h1>
            <div class="mb-4 mt-4 font-weight-bold" style="font-size: 1.5em; font-style: italic; text-shadow: 2px 2px #112211">
                Which shoe will reign supreme?<br>
                Who will be crowned champion?<br>
                It's time to lace up and compete.<br>
                Measure up against the best
            </div>
            <button @click="toMain()" class="text-sriracha font-weight-bold fight-btn"> FIGHT! </button>
        </div>
    </div>
</template>
<script>
export default {
    props: ['auth'],
    data() {
        return {
        }
    },
    methods: {
        toMain() {
            window.location.href = '/main'
        }
    }
}
</script>


