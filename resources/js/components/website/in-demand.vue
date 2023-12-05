<template>
    <div class="col-md-2 pr-0 sidebar rightbar-light darkMode-tog-c" id="sidebar-scroll">
        <div class="text-center">
            <h2 class="mt-5 mb-4" style="color: white">Shoe Brands</h2>
            <ul class="mb-5 pl-0 pr-0">
                <center>
                    <a v-for="(brand, index) in shoe_brands" :key="index" @click="mostPopular(brand.name)" class="brand-anchor">
                        <li class="brand-list"> {{ brand.text }} </li>
                    </a>
                </center>
            </ul>
        </div>
    </div>
    <div class="col-md-10">
        <div class="card mt-2 mr-2 page-light darkMode-tog-p">
            <div class="card-header p-4">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 v-if="brand_name" class="mb-0 font-weight-bold text-roboto text-c"> Most Popular - {{ brand_name }} </h3>
                        </div>
                    </div>
                    <div class="row text-c">
                        <span> As of <span class="font-weight-bold">{{ date_today }}</span></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-2 mr-2 page-light darkMode-tog-p">
            <div class="card-body p-3 pt-2">
                <div class="row">
                    <div class="col-md-3 mt-4" v-for="(shoe, index) in shoe_list" :key="index">
                        <div class="card" v-if="index <= 19">
                            <div> 
                                <img v-if="shoe.brand == 'Superga' || shoe.brand == 'Birkenstock' || shoe.brand == 'ASICS'" :src="brandLogo(shoe.brand)" class="brand-logo-width mr-3 mt-3" />
                                <img v-else-if="shoe.brand == 'Louis Vuitton'" :src="brandLogo(shoe.brand)" class="brand-logo-lv mr-3 mt-3" />
                                <img v-else-if="shoe.brand == 'Nike' || shoe.brand == 'Versace'" :src="brandLogo(shoe.brand)" class="brand-logo-nike mr-3 mt-3" />
                                <img v-else-if="shoe.brand == 'Timberland'" :src="brandLogo(shoe.brand)" class="brand-logo-timberland mr-3 mt-3" />
                                <img v-else :src="brandLogo(shoe.brand)" class="brand-logo mr-3 mt-3" />
                            </div>
                            <img :src="shoe['thumbnail']" class="card-img-top p-3" alt="shoes">
                            <div class="card-body" style="margin: 2px solid black">
                                <h5 class="card-title text-roboto font-weight-bold">{{ firstLetterUp(shoe.shoeName) }} </h5>
                                <p class="card-text font-weight-bold"> ₱ {{ usdToPhp(shoe.retailPrice) }} </p>
                                <p class="card-text"> Colorway: {{ shoe.colorway }} </p>
                                <hr>
                                <div class="mtb-05 orange">
                                    <span v-if="shoe.average_rating"> ★ {{ twoDecimal(shoe.average_rating) }}</span> <span v-else> No Review </span>
                                    <span  class="gray margin-sold"> | </span> <span class="gray"> {{ shoe.sold }} sold </span>
                                    <span style="float: right"> <a @click="rateMeShoe(shoe)" class="shoe-review-link" data-bs-toggle="modal" data-bs-target="#rateShoe"> Rate me </a> </span>
                                </div>
                                <p class="card-text">
                                    <a @click="seeMore(shoe)" class="pseudo-link" data-bs-toggle="modal" data-bs-target="#checkShoePopular"> See More </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Rate Shoe Modal -->
    <div class="modal fade" id="rateShoe" tabindex="-1" aria-labelledby="rateShoeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-roboto font-weight-bold" id="rateShoeLabel"> Write a review for {{ firstLetterUp(individual_shoe.shoeName) }} </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <img :src="individual_shoe.thumbnail" class="card-img-top p-3" alt="shoes">
                            </div>
                        </div>
                        <h4 class="text-roboto font-weight-bold mb-4"> Product Details </h4>
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
    <!-- Show Modal -->
    <div class="modal fade" id="checkShoePopular" tabindex="-1" aria-labelledby="checkShoePopularLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-roboto font-weight-bold" id="checkShoePopularLabel"> {{ firstLetterUp(individual_shoe.shoeName) }} - {{ individual_shoe.sold }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <img :src="individual_shoe.thumbnail" class="card-img-top p-3" alt="shoes">
                            </div>
                        </div>
                        <h4 class="text-roboto font-weight-bold mb-4"> Product Details </h4>
                        <div class="row">
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
                                <div class="mt-4"> 
                                    <a v-if="individual_shoe.length != 0" :href="individual_shoe.resellLinks.stockX" target="_blank"> {{ individual_shoe.resellLinks.stockX }} </a>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <h6 class="font-weight-bold"> Product Description (if there's any) </h6>
                                <p> {{ this.changeNewLine(individual_shoe.description) }} </p>
                            </div>
                        </div>
                        <div v-if="shoe_reviews.length != 0">
                            <h4 class="text-roboto font-weight-bold mb-0 pt-5"> User Reviews <span class="ml-1 orange"> ★ {{ twoDecimal(average_shoe_ratings) }} </span> </h4>
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
            brand_name: '',
            shoe_list: [],
            individual_shoe: [],
            all_pages: 0,
            page: 0,
            num_of_shoes: 0,
            // As of May 24, you can change this.
            conv_rate: 55.75,
            date_today: '',
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
                    text: 'All',
                    sold_average: 20000
                },
                {
                    name: 'Adidas',
                    text: 'Adidas',
                    sold_average: 20000
                },
                {
                    name: "ASICS",
                    text: "ASICS",
                    sold_average: 300
                },
                {
                    name: "Balenciaga",
                    text: "Balenciaga",
                    sold_average: 100
                },
                {
                    name: "Birkenstock",
                    text: "Birkenstock",
                    sold_average: 100
                },
                {
                    name: "Brooks",
                    text: "Brooks",
                    sold_average: 1
                },
                {
                    name: "Burberry",
                    text: "Burberry",
                    sold_average: 50
                },
                {
                    name: "Christian Louboutin",
                    text: "Christian Louboutin",
                    sold_average: 10
                },
                {
                    name: "Clarks",
                    text: "Clarks",
                    sold_average: 1
                },
                {
                    name: "Converse",
                    text: "Converse",
                    sold_average: 1000
                },
                {
                    name: "Crocs",
                    text: "Crocs",
                    sold_average: 1000
                },
                {
                    name: "Dr. Martens",
                    text: "Dr. Martens",
                    sold_average: 40
                },
                {
                    name: "Fila",
                    text: "Fila",
                    sold_average: 20
                },
                {
                    name: "Gucci",
                    text: "Gucci",
                    sold_average: 50
                },
                {
                    name: "Hoka One One",
                    text: "Hoka One One",
                    sold_average: 200
                },
                {
                    name: "Jimmy Choo",
                    text: "Jimmy Choo",
                    sold_average: 0
                },
                {
                    name: "Jordan",
                    text: "Jordan",
                    sold_average: 20000
                },
                {
                    name: "Keen",
                    text: "Keen",
                    sold_average: 0
                },
                {
                    name: "K-Swiss",
                    text: "K-Swiss",
                    sold_average: 1
                },
                {
                    name: "Louis Vuitton",
                    text: "Louis Vuitton",
                    sold_average: 50
                },
                {
                    name: "Merrell",
                    text: "Merrell",
                    sold_average: 5
                },
                {
                    name: "Mizuno",
                    text: "Mizuno",
                    sold_average: 10
                },
                {
                    name: "New Balance",
                    text: "New Balance",
                    sold_average: 4000
                },
                {
                    name: "Nike",
                    text: "Nike",
                    sold_average: 20000
                },
                {
                    name: "Prada",
                    text: "Prada",
                    sold_average: 10
                },
                {
                    name: "Puma",
                    text: "Puma",
                    sold_average: 1000
                },
                {
                    name: "Reebok",
                    text: "Reebok",
                    sold_average: 300
                },
                {
                    name: "Salomon",
                    text: "Salomon",
                    sold_average: 50
                },
                {
                    name: "Superga",
                    text: "Superga",
                    sold_average: 0
                },
                {
                    name: "Timberland",
                    text: "Timberland",
                    sold_average: 100
                },
                {
                    name: "Under Armour",
                    text: "Under Armour",
                    sold_average: 200
                },
                {
                    name: "Vans",
                    text: "Vans",
                    sold_average: 200
                },
                {
                    name: "Versace",
                    text: "Versace",
                    sold_average: 20
                }
            ]
        }
    },
    watch: {
        shoe_list() {
            var sidebar = document.getElementById("sidebar-scroll");
            var pageHeight = document.body.scrollHeight

            if (pageHeight <= 789) {
                sidebar.classList.add('min-height');
            } else {
                sidebar.classList.remove('min-height');
                var newHeight = parseInt(pageHeight) + 1500
                sidebar.style.maxHeight = newHeight + 'px';
            }
        }
    },
    mounted() {
        this.mostPopular('All');
        this.getDate();
    },
    methods: {
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
                    let assignment = shoe_data.find(e => e.id == shoe.id)

                    if (assignment.average) {
                        shoe.average_rating = assignment.average
                    } else {
                        shoe.average_rating = 0
                    }
                })
                
            })
        },
        brandLogo(brand) {
            let is_present = this.shoe_brands.find(e => e.name == this.firstLetterUp(brand))

            if (is_present) {
                return "images/logo/" + brand + ".png"
            } else {
                return "images/logo/Unknown.png"
            }
        },
        async mostPopular(brand) {
            this.brand_name = brand
            var current_brand = ''

            // Get shoe brand deadstock average value
            this.shoe_brands.forEach((brand) => {
                if (brand.name == this.brand_name) {
                    current_brand = brand
                }
            })

            if (brand == 'All') {
                brand = 'sneakers shoes'
            }
            this.shoe_list = []

            await axios.post('https://xw7sbct9v6-1.algolianet.com/1/indexes/products/query?x-algolia-agent=Algolia%20for%20vanilla%20JavaScript%203.32.1&x-algolia-application-id=XW7SBCT9V6&x-algolia-api-key=6b5e76b49705eb9f51a06d3c82f7acee', {
                "params" : "query=${" + brand + "}&facets=*&filters=&hitsPerPage=20"
            }).then(async response => {
                var products = [];
                this.num_of_shoes = 0;

                if (response.data.nbPages <= 10) {
                    this.all_pages = response.data.nbPages;
                } else {
                    this.all_pages = 10
                }

                for (var numOfPages = 0; numOfPages < this.all_pages; numOfPages++) {
                    // products = []
                    // New Axios Request
                    await axios.post('https://xw7sbct9v6-1.algolianet.com/1/indexes/products/query?x-algolia-agent=Algolia%20for%20vanilla%20JavaScript%203.32.1&x-algolia-application-id=XW7SBCT9V6&x-algolia-api-key=6b5e76b49705eb9f51a06d3c82f7acee', {
                        "params" : "query=${" + brand + "}&facets=*&filters=&hitsPerPage=100&page=" + numOfPages + "",
                    }).then(response => {
                        var json = response.data

                        for (var i = 0; i < json.hits.length; i++) {
                            if (json.hits[i].product_category == 'sneakers'  && json.hits[i].deadstock_sold >= current_brand.sold_average && (json.hits[i].brand.toLowerCase() == this.brand_name.toLowerCase() || this.brand_name == 'All')) {
                                var shoe = {
                                    shoeName: json.hits[i].name,
                                    brand: json.hits[i].brand,
                                    // silhoutte: json.hits[i].make,
                                    // styleID: json.hits[i].style_id,
                                    colorway: json.hits[i].colorway,
                                    retailPrice: json.hits[i].searchable_traits['Retail Price'],
                                    thumbnail: json.hits[i].media.imageUrl,
                                    releaseDate: json.hits[i].release_date,
                                    description: json.hits[i].description,
                                    // urlKey: json.hits[i].url,
                                    resellLinks: {
                                        stockX: 'https://stockx.com/' + json.hits[i].url
                                    },
                                    sold: json.hits[i].deadstock_sold,
                                    id: json.hits[i].id
                                };
                                if(json.hits[i].lowest_ask){
                                    shoe.lowestResellPrice = json.hits[i].lowest_ask;
                                }

                                this.shoe_list.push(shoe)
                            }
                            if (this.shoe_list.length == 20) {
                                break
                            }
                        }
                    });

                    if (this.shoe_list.length == 20) {
                        break
                    }
                }
            });
            this.getReviewPerShoe()
            this.fixShoeList();
        },
        fixShoeList() {
            var arr = [];
            var list = this.shoe_list
            list.sort((a, b) => b.sold - a.sold)
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
        changeNewLine(desc) {
            if (desc) {
                var newDesc = ''
                newDesc = desc.replaceAll(/<br>/g, '\n')

                return newDesc
            }
        },
        firstLetterUp(word) {
            if (word) {
                let str = '';
                str = word.charAt(0).toUpperCase() + word.slice(1)

                return str
            }
        },
        fixDateFormat(date) {
            if (date) {
                var newDate = date + ' 00:00:00'


                return moment(newDate).format('LL')
            }
        },
        usdToPhp(num) {
            if (num) {
                var multiply_num = num * this.conv_rate

                return multiply_num.toLocaleString("en-US", {minimumFractionDigits: 2})
            }
        },
        getDate() {
            this.date_today = moment().format('LL hh:mm A');
        },
        twoDecimal(num) {
            if (num) {
                return parseFloat(num).toFixed(2)
            }
        },
        fixDate(date) {
            if (date) {
                return moment(date).format('LLL')
            }
        }
    }
}
</script>