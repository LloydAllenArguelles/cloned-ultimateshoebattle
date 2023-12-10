<template>
    <div class="col-md-2 pr-0 sidebar rightbar-light darkMode-tog-c" id="sidebar-scroll">
        <div class="text-center">
            <h2 class="mt-5 mb-4 text-c font-weight-bold">Brands</h2>
            <ul class="mb-5 pl-0 pr-0">
                <center>
                    <a v-for="(brand, index) in shoe_brands" :key="index" @click="searchShoeBrand(brand.name)" class="brand-anchor">
                        <li class="brand-list"> {{ brand.text }} </li>
                    </a>
                </center>
            </ul>
        </div>
    </div>
    <div class="col-md-10" id="go-here">
        <div class="card mt-2 mr-2 page-light darkMode-tog-p">
            <div class="card-header p-4 text-c">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 v-if="brand_name" class="mb-0 font-weight-bold text-roboto"> {{ brand_name }}</h3>
                            <h3 v-else class="mb-0 font-weight-bold text-roboto"> All Brands </h3>
                        </div>
                        <div class="col-md-3">
                            <input type="text" v-model="search" class="form-control" />
                            <span v-if="search_error" style="font-style: italic; color: red" class="font-weight-bold"> Please input keywords first </span>
                        </div>
                        <div class="col-md-1">
                            <button @click="searchSpecific()" type="button" class="btn btn-primary"> Search </button>
                        </div>
                    </div>
                    <div class="row" v-if="brand_name != 'All'">
                        <span> Official Website: <a class="font-weight-bold" :href="selected_brand.website" target="_blank">{{ selected_brand.website }}</a></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="pl-4 pr-4 pt-3 pb-2 text-c">
            <input class="form-check-input" type="checkbox" value="show" v-model="show_price_range">
            <label class="form-check-label ml-3 mb-2" for="flexCheckDefault">
                Price Range
            </label>
            <div class="range-slider d-none" id="range-slider">
                <input type="range" min="0" max="999999999" step="1000" v-model="slider_min" />
                <span class="font-weight-bold" style="font-size: 1.5em;"> ₱ </span>
                <!-- Min -->
                <input class="px-3 py-1" type="number" style="width: 15%" min="0" max="999999999" step="1000" v-model="slider_min" />
                <span class="ml-3 mr-3"> TO </span>
                <span class="font-weight-bold" style="font-size: 1.5em"> ₱ </span>
                <!-- Max -->
                <input type="range" min="0" max="999999999" step="1000"  v-model="slider_max" />
                <input class="px-3 py-1" id="slider-max" style="width: 15%" type="number" min="0" max="999999999" step="1000" v-model="slider_max" />
                <button @click="searchSpecific(0, false, true)" type="button" class="btn btn-warning ml-4"> Search </button>
            </div>
        </div>
        <div class="page-light card mt-2 mr-2 darkMode-tog-p">
            <div class="card-body p-3 pt-2">
                <div class="row">
                    <div class="col-md-3 mt-4" v-for="(shoe, index) in shoe_list" :key="index">
                        <div class="card">
                            <div> 
                                <img v-if="shoe.brand == 'Superga' || shoe.brand == 'Birkenstock' || shoe.brand == 'ASICS'" :src="brandLogo(shoe.brand)" class="brand-logo-width mr-3 mt-3" />
                                <img v-else-if="shoe.brand == 'Louis Vuitton'" :src="brandLogo(shoe.brand)" class="brand-logo-lv mr-3 mt-3" />
                                <img v-else-if="shoe.brand == 'Nike' || shoe.brand == 'Versace'" :src="brandLogo(shoe.brand)" class="brand-logo-nike mr-3 mt-3" />
                                <img v-else-if="shoe.brand == 'Timberland'" :src="brandLogo(shoe.brand)" class="brand-logo-timberland mr-3 mt-3" />
                                <img v-else :src="brandLogo(shoe.brand)" class="brand-logo mr-3 mt-3" />
                            </div>
                            <img :src="shoe['thumbnail']" class="card-img-top p-3" alt="shoes">
                            <div class="card-body" style="margin: 2px solid black">
                                <h5 class="card-title text-roboto font-weight-bold">{{ firstLetterUp(shoe.shoeName) }}</h5>
                                <p class="card-text font-weight-bold"> ₱ {{ usdToPhp(shoe.retailPrice) }} </p>
                                <p class="card-text"> {{ this.changeAndCut(shoe.description) }} </p>
                                <hr>
                                <div class="mtb-05 orange">
                                    <span v-if="shoe.average_rating"> ★ {{ twoDecimal(shoe.average_rating) }}</span> <span v-else> No Review </span>
                                    <span  class="gray margin-sold"> | </span> <span class="gray"> {{ shoe.sold }} sold </span>
                                    <span style="float: right"> <a @click="rateMeShoe(shoe)" class="shoe-review-link" data-bs-toggle="modal" data-bs-target="#rateShoe"> Rate me </a> </span>
                                </div>
                                <p class="card-text">
                                    <a @click="seeMore(shoe)" class="pseudo-link" data-bs-toggle="modal" data-bs-target="#checkShoe"> See More </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <nav aria-label="Page navigation pagination">
                        <ul class="pagination justify-content-end">
                            <li v-if="active_page != 0" class="page-item">
                                <a class="page-link" aria-label="First" @click="searchSpecific(0, true)">
                                    <span aria-hidden="true"> First </span>
                                </a>
                            </li>
                            <li v-if="active_page == 0" class="page-item disabled">
                                <a class="page-link" aria-label="Previous">
                                    <span aria-hidden="true"> Previous </span>
                                </a>
                            </li>
                            <li v-else class="page-item">
                                <a class="page-link" aria-label="Previous" @click="searchSpecific(this.active_page - 1)">
                                    <span aria-hidden="true"> Previous </span>
                                </a>
                            </li>
                            <li v-for="(page, index) in pages" :key="index">
                                <a v-if="active_page + 1 == page" class="page-link active" @click="searchSpecific(page-1)"> {{ page }} </a>
                                <a v-else-if="previous_page == page" class="page-link" @click="searchSpecific(page-1)"> {{ previous_page }} </a>
                                <a v-else-if="next_page == page" class="page-link" @click="searchSpecific(page-1)"> {{ next_page }} </a>
                                <a v-else> ⁃ </a>
                            </li>
                            <li v-if="active_page + 1 == pages" class="page-item disabled">
                                <a class="page-link" aria-label="Next">
                                    <span aria-hidden="true"> Next </span>
                                </a>
                            </li>
                            <li v-else class="page-item">
                                <a class="page-link" aria-label="Next" @click="searchSpecific(this.active_page + 1)">
                                    <span aria-hidden="true"> Next </span>
                                </a>
                            </li>
                            <li v-if="active_page + 1 != pages" class="page-item">
                                <a class="page-link" aria-label="Last" @click="searchSpecific(this.pages-1)">
                                    <span aria-hidden="true"> Last </span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Rate Shoe Modal -->
    <div class="modal fade" id="rateShoe" tabindex="-1" aria-labelledby="rateShoeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="darkMode-tog-p page-light modal-content text-c">
                <div class="modal-header">
                    <h5 class="modal-title text-roboto font-weight-bold" id="rateShoeLabel"> Write a review for {{ firstLetterUp(individual_shoe.shoeName) }} </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 img-border">
                                <img :src="individual_shoe.thumbnail" class="card-img-top p-3" alt="shoes">
                            </div>
                        </div>
                        <h4 class="text-roboto font-weight-bold mb-4 mt-4"> Product Details </h4>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2">Brand</td>
                                        <td class="font-weight-bold pb-2"> {{ firstLetterUp(individual_shoe.brand) }} </td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"> Colorway </td>
                                        <td class="font-weight-bold pb-2"> {{ individual_shoe.colorway }} </td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"> Release Date </td>
                                        <td class="font-weight-bold pb-2"> {{ fixDateFormat(individual_shoe.releaseDate) }} </td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"> Retail Price </td>
                                        <td class="font-weight-bold pb-2"> ₱ {{ usdToPhp(individual_shoe.retailPrice) }} </td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"> Lowest <br>Resell Price </td>
                                        <td class="font-weight-bold pb-2"> ₱ {{ usdToPhp(individual_shoe.lowestResellPrice) }} </td>
                                    </tr>
                                </table>
                                <div class="mt-4"> 
                                    <a v-if="individual_shoe.length != 0" :href="individual_shoe.resellLinks.stockX" target="_blank"> {{ individual_shoe.resellLinks.stockX }} </a>
                                </div>
                            </div>
                        </div> 
                        <hr>
                        <h4 class="text-roboto font-weight-bold mb-4"> Rate this Shoe </h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Name (optional) </label>
                                    <input type="text" class="form-control" v-model="rate.name" />
                                </div>
                                <div class="form-group">
                                    <label> Rate </label>
                                    <select class="form-select" v-model="rate.star_rate" required>
                                        <option value="" disabled selected></option>
                                        <option value="1"> ★ </option>
                                        <option value="2"> ★★ </option>
                                        <option value="3"> ★★★ </option>
                                        <option value="4"> ★★★★ </option>
                                        <option value="5"> ★★★★★ </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label> Your Review </label>
                                    <textarea class="form-control" v-model="rate.your_review" rows="3" required/>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="saveReview()">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Check Shoe Modal -->
    <div class="modal fade" id="checkShoe" tabindex="-1" aria-labelledby="checkShoeLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="darkMode-tog-p page-light modal-content">
                <div class="modal-header text-c">
                    <h5 class="modal-title text-roboto font-weight-bold" id="checkShoeLabel"> {{ firstLetterUp(individual_shoe.shoeName) }} </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 offset-md-3 img-border">
                                <img :src="individual_shoe.thumbnail" class="card-img-top p-3" alt="shoes">
                            </div>
                        </div>
                        <h4 class="text-roboto font-weight-bold mb-4 text-c"> Product Details </h4>
                        <div class="row text-c">
                            <div class="col-md-5">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2">Brand</td>
                                        <td class="font-weight-bold pb-2"> {{ firstLetterUp(individual_shoe.brand) }} </td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"> Colorway </td>
                                        <td class="font-weight-bold pb-2"> {{ individual_shoe.colorway }} </td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"> Release Date </td>
                                        <td class="font-weight-bold pb-2"> {{ fixDateFormat(individual_shoe.releaseDate) }} </td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"> Retail Price </td>
                                        <td class="font-weight-bold pb-2"> ₱ {{ usdToPhp(individual_shoe.retailPrice) }} </td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2"> Lowest <br>Resell Price </td>
                                        <td class="font-weight-bold pb-2"> ₱ {{ usdToPhp(individual_shoe.lowestResellPrice) }} </td>
                                    </tr>
                                </table>
                                <div class="mt-4 font-weight-bold"> 
                                    <a v-if="individual_shoe.length != 0" :href="individual_shoe.resellLinks.stockX" target="_blank"> {{ individual_shoe.resellLinks.stockX }} </a>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <h6 class="font-weight-bold"> Product Description (if there's any) </h6>
                                <p> {{ this.changeNewLine(individual_shoe.description) }} </p>
                            </div>
                        </div> 
                        <div v-if="shoe_reviews.length != 0" style="">
                            <h4 class="text-roboto font-weight-bold mb-0 pt-5 text-c"> User Reviews <span class="ml-1 orange"> ★ {{ twoDecimal(average_shoe_ratings) }} </span> </h4>
                            <span style="font-style: italic; color: gray"> Based on {{ num_reviews }} review(s) </span>
                            <div class="row mt-4">
                                <div class="col-md-4 mb-4" v-for="(review, index) in shoe_reviews" :key="index">
                                    <div class="p-3 shadow text-center pt-4 pb-4 review-block">
                                        <h6 class="font-weight-bold"> {{ review.name }} </h6>
                                        <hr class="reviews-hr">
                                        <div class="mtb-05 orange">
                                            <span v-for="(star, index) in review.rate" :key="index"> ★ </span>
                                        </div>
                                        <div class="sub-text"> {{ fixDate(review.created_at) }} </div>
                                        <div class="pt-3 pb-4 p-2"> {{ review.review }} </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <h4 class="text-roboto font-weight-bold mb-4 pt-5" style="color: gray"> No Review Found </h4>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['auth'],
    data() {
        return {
            brand_name: 'All',
            shoe_list: [],
            individual_shoe: [],
            pages: 0,
            previous_page: -1,
            active_page: 0,
            next_page: 2,
            search: '',
            search_error: false,
            min_price: 0,
            max_price: 999999999,
            show_price_range: false,
            // As of May 24, you can change this.
            conv_rate: 55.75,
            selected_brand: [],
            shoe_reviews: [],
            num_reviews: 0,
            average_shoe_ratings: 0,
            rate: {
                name: '',
                star_rate: 0,
                your_review: '',
            },
            shoe_brands: [
                {
                    name: 'All',
                    text: 'All'
                },
                {
                    name: 'Adidas',
                    text: 'Adidas',
                    website: 'https://www.adidas.com/'
                },
                {
                    name: "ASICS",
                    text: "ASICS",
                    website: 'https://www.asics.com/'
                },
                {
                    name: "Balenciaga",
                    text: "Balenciaga",
                    website: 'https://www.balenciaga.com/'
                },
                {
                    name: "Birkenstock",
                    text: "Birkenstock",
                    website: 'https://www.birkenstock.com/'
                },
                {
                    name: "Brooks",
                    text: "Brooks",
                    website: 'https://www.brooksrunning.com/'
                },
                {
                    name: "Burberry",
                    text: "Burberry",
                    website: 'https://www.burberry.com/'
                },
                {
                    name: "Christian Louboutin",
                    text: "Christian Louboutin",
                    website: 'https://us.christianlouboutin.com/'
                },
                {
                    name: "Clarks",
                    text: "Clarks",
                    website: 'https://www.clarksusa.com/'
                },
                {
                    name: "Converse",
                    text: "Converse",
                    website: 'https://www.converse.com/'
                },
                {
                    name: "Crocs",
                    text: "Crocs",
                    website: 'https://www.crocs.com/'
                },
                {
                    name: "Dr. Martens",
                    text: "Dr. Martens",
                    website: 'https://www.drmartens.com/'
                },
                {
                    name: "Fila",
                    text: "Fila",
                    website: 'https://www.fila.com/'
                },
                {
                    name: "Gucci",
                    text: "Gucci",
                    website: 'https://www.gucci.com/'
                },
                {
                    name: "Hoka One One",
                    text: "Hoka One One",
                    website: 'https://www.hokaoneone.com/'
                },
                {
                    name: "Jimmy Choo",
                    text: "Jimmy Choo",
                    website: 'https://www.jimmychoo.com/'
                },
                {
                    name: "Jordan",
                    text: "Jordan",
                    website: 'https://www.nike.com/jordan/'
                },
                {
                    name: "Keen",
                    text: "Keen",
                    website: 'https://www.keenfootwear.com/'
                },
                {
                    name: "K-Swiss",
                    text: "K-Swiss",
                    website: 'https://www.kswiss.com/'
                },
                {
                    name: "Louis Vuitton",
                    text: "Louis Vuitton",
                    website: 'https://www.louisvuitton.com/'
                },
                {
                    name: "Merrell",
                    text: "Merrell",
                    website: 'https://www.merrell.com/'
                },
                {
                    name: "Mizuno",
                    text: "Mizuno",
                    website: 'https://www.mizunousa.com/'
                },
                {
                    name: "New Balance",
                    text: "New Balance",
                    website: 'https://www.newbalance.com/'
                },
                {
                    name: "Nike",
                    text: "Nike",
                    website: 'https://www.nike.com/'
                },
                {
                    name: "Prada",
                    text: "Prada",
                    website: 'https://www.prada.com/'
                },
                {
                    name: "Puma",
                    text: "Puma",
                    website: 'https://www.puma.com/'
                },
                {
                    name: "Reebok",
                    text: "Reebok",
                    website: 'https://www.reebok.com/'
                },
                {
                    name: "Salomon",
                    text: "Salomon",
                    website: 'https://www.salomon.com/'
                },
                {
                    name: "Superga",
                    text: "Superga",
                    website: 'https://www.superga-usa.com/'
                },
                {
                    name: "Timberland",
                    text: "Timberland",
                    website: 'https://www.timberland.com/'
                },
                {
                    name: "Under Armour",
                    text: "Under Armour",
                    website: 'https://www.underarmour.com/'
                },
                {
                    name: "Vans",
                    text: "Vans",
                    website: 'https://www.vans.com/'
                },
                {
                    name: "Versace",
                    text: "Versace",
                    website: 'https://www.versace.com/'
                }
            ]
        }
    },
    watch: {
        max_price(val) {
            if (val > 999999999) {
                this.max_price = 999999999
            }
        },
        min_price(val) {
            if (val > 999999999) {
                this.min_price = 999999999
            }
        },
        show_price_range(val) {
            var range = document.getElementById("range-slider");

            if (val) {
                range.classList.remove("d-none");
            } else {
                range.classList.add("d-none");
            }
        },
        active_page() {
            this.next_page = this.active_page + 2
            this.previous_page = this.active_page
        },
        shoe_list() {
            var sidebar = document.getElementById("sidebar-scroll");
            var pageHeight = document.body.scrollHeight

            if (pageHeight <= 789) {
                sidebar.classList.add('min-height');
            } else {
                sidebar.classList.remove('min-height');
                var newHeight = parseInt(pageHeight) + 400
                sidebar.style.maxHeight = newHeight + 'px';
            }
        }
    },
    computed: {
        slider_min: {
            get: function() {
                var val = parseInt(this.min_price);
                return val;
            },
            set: function(val) {
                val = parseInt(val);
                if (val > this.max_price) {
                this.max_price = val;
                }
                this.min_price = val;
            }
        },
        slider_max: {
            get: function() {
                var val = parseInt(this.max_price);
                return val;
            },
            set: function(val) {
                val = parseInt(val);
                if (val < this.min_price) {
                this.min_price = val;
                }
                this.max_price = val;
            }
        }
    },
    mounted() {
        this.searchShoeBrand('All');
    },
    methods: {
        brandLogo(brand) {
            let is_present = this.shoe_brands.find(e => e.name == this.firstLetterUp(brand))

            if (is_present) {
                return "images/logo/" + brand + ".png"
            } else {
                return "images/logo/Unknown.png"
            }
        },
        getReviewPerShoe() {
            let shoe_ids = []

            this.shoe_list.forEach(shoe => {
                shoe_ids.push(shoe.id)
            })

            axios.post('api/reviews/getAverageRating', {
                ids: shoe_ids,
            }).then(response => {
                let shoe_data = response.data.data

                this.shoe_list.forEach(shoe => {
                    // Find match id of shoe to response.data.data
                    // if match, get the average
                    let assignment = shoe_data.find(e => e.id == shoe.id)

                    if (assignment.average) {
                        shoe.average_rating = assignment.average
                    } else {
                        shoe.average_rating = 0
                    }
                })
                
            })
        },
        saveReview() {
            Swal.fire({
                title: 'Submit Shoe Review?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (!this.rate.name) {
                        this.rate.name = 'Anonymous User'
                    }

                    axios.post('api/reviews/storeShoeReview', {
                        name: this.rate.name,
                        rate: this.rate.star_rate,
                        review: this.rate.your_review,
                        shoe_id: this.individual_shoe.id,
                        shoe_name: this.individual_shoe.shoeName,
                        shoe_brand: this.individual_shoe.brand,
                    }).then(response => {
                        if (response.status == 200) {
                            this.rate.name = '';
                            this.rate.star_rate = '';
                            this.rate.your_review = '';

                            Swal.fire({
                                title: 'Your review is recorded',
                                text: 'Please refresh to see your review.',
                                icon: 'success',
                            })
                        }
                    })

                }
            });
        },
        async searchSpecific(page = 0, from_pages = false, from_range = false, count = 12,) {
            let search = this.search
            let brand = this.brand_name
            let min_price = (this.min_price / this.conv_rate).toFixed(2)
            let max_price = (this.max_price / this.conv_rate).toFixed(2)
            this.active_page = page
            
            if (brand == 'All') {
                brand = 'Sneakers shoes'
            }

            if (from_range) {
                count = 30
            }

            // Scroll to top
            var access = document.getElementById("go-here");
            access.scrollIntoView({behavior: 'smooth'}, true);

            if (search || page || (page == 0 && from_pages == true) || (page == 0 && from_range == true)) {
                await axios.post('https://xw7sbct9v6-1.algolianet.com/1/indexes/products/query?x-algolia-agent=Algolia%20for%20vanilla%20JavaScript%203.32.1&x-algolia-application-id=XW7SBCT9V6&x-algolia-api-key=6b5e76b49705eb9f51a06d3c82f7acee', {
                    "params" : "query=${" + brand + " " + search + "}&facets=*&filters=&hitsPerPage=" + count + "&page=" + this.active_page + ""
                }).then(response => {
                    var json = response.data
                    var products = [];

                    this.pages = json.nbPages;
                    // this.pages = 5;

                    for (var i = 0; i < json.hits.length; i++) {
                        if ((json.hits[i].product_category == 'sneakers') && (json.hits[i].searchable_traits['Retail Price'] >= min_price && json.hits[i].searchable_traits['Retail Price'] <= max_price)  &&
                        (json.hits[i].brand.toLowerCase() == this.brand_name.toLowerCase() || this.brand_name == 'All')) {
                            var shoe = {
                                shoeName: json.hits[i].name,
                                brand: json.hits[i].brand,
                                silhoutte: json.hits[i].make,
                                styleID: json.hits[i].style_id,
                                colorway: json.hits[i].colorway,
                                retailPrice: json.hits[i].searchable_traits['Retail Price'],
                                thumbnail: json.hits[i].media.imageUrl,
                                releaseDate: json.hits[i].release_date,
                                description: json.hits[i].description,
                                urlKey: json.hits[i].url,
                                sold: json.hits[i].deadstock_sold,
                                id: json.hits[i].id,
                                resellLinks: {
                                    stockX: 'https://stockx.com/' + json.hits[i].url
                                }
                            };
                            if(json.hits[i].lowest_ask){
                                shoe.lowestResellPrice = json.hits[i].lowest_ask;
                            }
                            products.push(shoe)
                        }
                    }
                    this.shoe_list = products
                });
                this.search_error = false
            } else {
                this.search_error = true
            }
            this.getReviewPerShoe()
        },
        async searchShoeBrand(brand, count = 12) {
            this.brand_name = brand
            this.search = ''
            this.active_page = 0
            if (brand == 'All') {
                brand = 'Sneakers Shoes'
            }

            this.getBrand(brand)

            var access = document.getElementById("go-here");
            access.scrollIntoView({behavior: 'smooth'}, true);

            await axios.post('https://xw7sbct9v6-1.algolianet.com/1/indexes/products/query?x-algolia-agent=Algolia%20for%20vanilla%20JavaScript%203.32.1&x-algolia-application-id=XW7SBCT9V6&x-algolia-api-key=6b5e76b49705eb9f51a06d3c82f7acee', {
                "params" : "query=${" + brand + "}&facets=*&filters=&hitsPerPage=" + count + "&page=" + this.active_page + ""
            }).then(response => {
                var json = response.data
                var products = [];
                // var numOfShoes = json.hits.length;

                this.pages = json.nbPages;
                // this.pages = 5

                for (var i = 0; i < json.hits.length; i++) {
                    if (json.hits[i].product_category == 'sneakers' && (json.hits[i].brand.toLowerCase() == this.brand_name.toLowerCase() || this.brand_name == 'All')) {
                        var shoe = {
                            shoeName: json.hits[i].name,
                            brand: json.hits[i].brand,
                            silhoutte: json.hits[i].make,
                            styleID: json.hits[i].style_id,
                            colorway: json.hits[i].colorway,
                            retailPrice: json.hits[i].searchable_traits['Retail Price'],
                            thumbnail: json.hits[i].media.imageUrl,
                            releaseDate: json.hits[i].release_date,
                            description: json.hits[i].description,
                            urlKey: json.hits[i].url,
                            sold: json.hits[i].deadstock_sold,
                            id: json.hits[i].id,
                            resellLinks: {
                                stockX: 'https://stockx.com/' + json.hits[i].url
                            }
                        };
                        if(json.hits[i].lowest_ask){
                            shoe.lowestResellPrice = json.hits[i].lowest_ask;
                        }
                        products.push(shoe)
                    }
                }
                this.shoe_list = products
            });
            this.getReviewPerShoe()
        },
        getBrand(brand) {
            if (brand) {
                this.shoe_brands.forEach(b => {
                    if (b.name == brand) {
                        this.selected_brand = b
                    }
                })
            }
        },
        rateMeShoe(shoe) {
            this.individual_shoe = []
            this.individual_shoe = shoe
        },
        seeMore(shoe) {
            this.individual_shoe = []
            this.individual_shoe = shoe
            axios.post('api/reviews/getShoeReviews', {
                id: this.individual_shoe.id,
                name: this.individual_shoe.shoeName,
                brand: this.individual_shoe.brand
            }).then(response => {
                this.shoe_reviews = response.data.data
                this.num_reviews = response.data.num_reviews
                this.average_shoe_ratings = response.data.average
            })
        },
        changeAndCut(desc) {
            if (desc) {
                var newDesc = ''
                newDesc = desc.slice(0, desc.indexOf('.')).replaceAll(/<br>/g, '\n')

                return newDesc
            }
        },
        changeNewLine(desc) {
            if (desc) {
                var newDesc = ''
                newDesc = desc.replaceAll(/<br>/g, '\n')

                return newDesc
            }
        },
        fixDateFormat(date) {
            if (date) {
                var newDate = date + ' 00:00:00'


                return moment(newDate).format('LL')
            }
        },
        fixDate(date) {
            if (date) {
                return moment(date).format('LLL')
            }
        },
        replaceDash(desc) {
            var newDesc = ''
            newDesc = desc.replaceAll(/-/g, ' ')

            return newDesc
        },
        firstLetterUp(word) {
            if (word) {
                let str = '';
                str = word.charAt(0).toUpperCase() + word.slice(1)

                return str
            }
        },
        usdToPhp(num) {
            if (num) {
                var multiply_num = num * this.conv_rate

                return multiply_num.toLocaleString("en-US", {minimumFractionDigits: 2})
            }
        },
        twoDecimal(num) {
            if (num) {
                return parseFloat(num).toFixed(2)
            }
        }
    }
}
</script>
