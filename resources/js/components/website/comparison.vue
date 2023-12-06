<template>
    <div class="col-md-2 pr-0 rightbar-light darkMode-tog-c">
        <div class="text-center">
            <h2 class="mt-5 mb-4" style="color: white">Brands</h2>
            <ul class="mb-5 pl-0 pr-0">
                <center>
                    <a v-for="(brand, index) in shoe_brands" :key="index" @click="searchShoeBrand(brand.name)" class="brand-anchor">
                        <li class="brand-list"> {{ brand.text }} </li>
                    </a>
                </center>
            </ul>
        </div>
    </div>
    <div class="col-md-10 darkMode-tog">
        <div class="card mt-2 mr-2 page-light darkMode-tog-p">
            <div class="card-header p-4">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8 d-flex align-items-center">
                            <h3 class="mb-0 mr-3 font-weight-bold text-roboto text-c"> Pick your fighter </h3>
                            <button v-if="count_num >= 3" @click="seeMore()" id="compare-shoe-btn" data-bs-toggle="modal" data-bs-target="#compareShoe" type="button" class="btn btn-danger"> FIGHT! </button>
                        </div>
                        <div class="col-md-3">
                            <input type="text" v-model="search" class="form-control" />
                            <span v-if="search_error" style="font-style: italic; color: red" class="font-weight-bold"> Please input keywords first </span>
                        </div>
                        <div class="col-md-1">
                            <button @click="searchSpecific()" type="button" class="btn btn-primary"> Search </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3 mb-3 container-fluid" id="go-here-comparison">
            <div class="row">
                <div class="col-md-4" v-for="(num, index) in count_loop" :key="index">
                    <div v-if="num+1 == 2" class="p-2 mr-2 shoe-comparison-list"> {{ firstLetterUp(shoe_1.shoeName) }}<span style="float:right; cursor: pointer" @click="removeShoe(num+1)"> X </span></div>
                    <div v-else-if="num+1 == 3" class="p-2 mr-2 shoe-comparison-list"> {{ firstLetterUp(shoe_2.shoeName) }} <span style="float:right; cursor: pointer" @click="removeShoe(num+1)"> X </span></div>
                    <div v-else-if="num+1 == 4" class="p-2 shoe-comparison-list"> {{ firstLetterUp(shoe_3.shoeName) }} <span style="float:right; cursor: pointer" @click="removeShoe(num+1)"> X </span></div>
                </div>
            </div>
        </div>
        <div class="card mt-2 mr-2 page-light darkMode-tog-p">
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
                                <p class="card-text" v-if="count_num != 4 && (shoe.shoeName != shoe_1.shoeName && shoe.shoeName != shoe_2.shoeName && shoe.shoeName != shoe_3.shoeName)">
                                    <a @click="pickMe(shoe)" class="pseudo-link" style="text-decoration: none"> Pick Me! (Shoe # {{ count_num }}) </a>
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
    <!-- Comparison Modal -->
    <div class="modal fade" id="compareShoe" tabindex="-1" aria-labelledby="compareShoeLabel" aria-hidden="true" style="padding-left: 0px !important">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-roboto font-weight-bold" id="compareShoeLabel"> Compare & Contrast </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container" v-if="count_num == 4">
                        <div class="row">
                            <div class="col-md-4 p-3">
                                <img :src="shoe_1.thumbnail" class="card-img-top p-3" alt="shoes">
                                <h4 class="font-weight-bold text-center"> {{ firstLetterUp(shoe_1.shoeName) }} </h4>
                            </div>
                            <div class="col-md-4">
                                <img :src="shoe_2.thumbnail" class="card-img-top p-3" alt="shoes">
                                <h4 class="font-weight-bold text-center"> {{ firstLetterUp(shoe_2.shoeName) }} </h4>
                            </div>
                            <div class="col-md-4">
                                <img :src="shoe_3.thumbnail" class="card-img-top p-3" alt="shoes">
                                <h4 class="font-weight-bold text-center"> {{ firstLetterUp(shoe_3.shoeName) }} </h4>
                            </div>
                        </div>
                        <h3 class="font-weight-bold mt-5"> Summary </h3>
                        <hr>
                        <!-- Brand -->
                        <div class="row ml-5">
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40">Brand</td>
                                        <td class="font-weight-bold pb-2"> {{ firstLetterUp(shoe_1.brand) }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40">Brand</td>
                                        <td class="font-weight-bold pb-2"> {{ firstLetterUp(shoe_2.brand) }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40">Brand</td>
                                        <td class="font-weight-bold pb-2"> {{ firstLetterUp(shoe_3.brand) }} </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Colorway -->
                        <div class="row ml-5">
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Colorway </td>
                                        <td class="font-weight-bold pb-2"> {{ shoe_1.colorway }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Colorway </td>
                                        <td class="font-weight-bold pb-2"> {{ shoe_2.colorway }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Colorway </td>
                                        <td class="font-weight-bold pb-2"> {{ shoe_3.colorway }} </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Release Date -->
                        <div class="row ml-5">
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Release Date </td>
                                        <td class="font-weight-bold pb-2"> {{ fixDateFormat(shoe_1.releaseDate) }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Release Date </td>
                                        <td class="font-weight-bold pb-2"> {{ fixDateFormat(shoe_2.releaseDate) }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Release Date </td>
                                        <td class="font-weight-bold pb-2"> {{ fixDateFormat(shoe_3.releaseDate) }} </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Retail Price -->
                        <div class="row ml-5">
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Retail Price </td>
                                        <td class="font-weight-bold pb-2"> ₱ {{ usdToPhp(shoe_1.retailPrice) }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Retail Price </td>
                                        <td class="font-weight-bold pb-2"> ₱ {{ usdToPhp(shoe_2.retailPrice) }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Retail Price </td>
                                        <td class="font-weight-bold pb-2"> ₱ {{ usdToPhp(shoe_3.retailPrice) }} </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Lowest Resell Price -->
                        <div class="row ml-5">
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Lowest <br>Resell Price </td>
                                        <td class="font-weight-bold pb-2"> ₱ {{ usdToPhp(shoe_1.lowestResellPrice) }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Lowest <br>Resell Price </td>
                                        <td class="font-weight-bold pb-2"> ₱ {{ usdToPhp(shoe_2.lowestResellPrice) }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Lowest <br>Resell Price </td>
                                        <td class="font-weight-bold pb-2"> ₱ {{ usdToPhp(shoe_3.lowestResellPrice) }} </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Link -->
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="mt-4"> 
                                    <a v-if="shoe_1.length != 0" :href="shoe_1.resellLinks.stockX" target="_blank"> {{ shoe_1.resellLinks.stockX }} </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mt-4"> 
                                    <a v-if="shoe_2.length != 0" :href="shoe_2.resellLinks.stockX" target="_blank"> {{ shoe_2.resellLinks.stockX }} </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mt-4"> 
                                    <a v-if="shoe_3.length != 0" :href="shoe_3.resellLinks.stockX" target="_blank"> {{ shoe_3.resellLinks.stockX }} </a>
                                </div>
                            </div>
                        </div>
                        <!-- Reviews -->
                        <h3 class="font-weight-bold mt-5"> User Review(s) </h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <span style="font-style: italic; color: gray"> Based on {{ shoe_1_review.num_reviews }} review(s) </span> <span class="ml-1 orange ml-1" style="font-weight: 600"> ★ {{ shoe_1_review.average != null ? twoDecimal(shoe_1_review.average) : 0 }} </span>
                                <div v-if="shoe_1_review.num_reviews != 0" class="row mt-4">
                                    <div class="col-md-12 mb-4" v-for="(review, index) in shoe_1_review.reviews" :key="index">
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
                                </div> 
                                <div v-else>
                                    <h4 class="text-roboto font-weight-bold mb-4 pt-5" style="color: gray; text-align: center"> No Review Found </h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span style="font-style: italic; color: gray"> Based on {{ shoe_2_review.num_reviews }} review(s) </span> <span class="ml-1 orange ml-1" style="font-weight: 600"> ★ {{ shoe_2_review.average != null ? twoDecimal(shoe_2_review.average) : 0 }} </span>
                                <div v-if="shoe_2_review.num_reviews != 0" class="row mt-4">
                                    <div class="col-md-12 mb-4" v-for="(review, index) in shoe_2_review.reviews" :key="index">
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
                                </div> 
                                <div v-else>
                                    <h4 class="text-roboto font-weight-bold mb-4 pt-5" style="color: gray; text-align: center"> No Review Found </h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span style="font-style: italic; color: gray"> Based on {{ shoe_3_review.num_reviews }} review(s) </span> <span class="ml-1 orange ml-1" style="font-weight: 600"> ★ {{ shoe_3_review.average != null ? twoDecimal(shoe_3_review.average) : 0 }} </span>
                                <div v-if="shoe_3_review.num_reviews != 0" class="row mt-4">
                                    <div class="col-md-12 mb-4" v-for="(review, index) in shoe_3_review.reviews" :key="index">
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
                                </div> 
                                <div v-else>
                                    <h4 class="text-roboto font-weight-bold mb-4 pt-5" style="color: gray; text-align: center"> No Review Found </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 2 comparison -->
                    <div class="container" v-else-if="count_num == 3">
                        <div class="row">
                            <div class="col-md-6 p-5">
                                <img :src="shoe_1.thumbnail" class="card-img-top p-3" alt="shoes">
                                <h4 class="font-weight-bold text-center"> {{ firstLetterUp(shoe_1.shoeName) }} </h4>
                            </div>
                            <div class="col-md-6 p-5">
                                <img :src="shoe_2.thumbnail" class="card-img-top p-3" alt="shoes">
                                <h4 class="font-weight-bold text-center"> {{ firstLetterUp(shoe_2.shoeName) }} </h4>
                            </div>
                        </div>
                        <h3 class="font-weight-bold mt-5"> Summary </h3>
                        <hr>
                        <!-- Brand -->
                        <div class="row ml-5">
                            <div class="col-md-6">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40">Brand</td>
                                        <td class="font-weight-bold pb-2"> {{ firstLetterUp(shoe_1.brand) }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40">Brand</td>
                                        <td class="font-weight-bold pb-2"> {{ firstLetterUp(shoe_2.brand) }} </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Colorway -->
                        <div class="row ml-5">
                            <div class="col-md-6">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Colorway </td>
                                        <td class="font-weight-bold pb-2"> {{ shoe_1.colorway }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Colorway </td>
                                        <td class="font-weight-bold pb-2"> {{ shoe_2.colorway }} </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Release Date -->
                        <div class="row ml-5">
                            <div class="col-md-6">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Release Date </td>
                                        <td class="font-weight-bold pb-2"> {{ fixDateFormat(shoe_1.releaseDate) }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Release Date </td>
                                        <td class="font-weight-bold pb-2"> {{ fixDateFormat(shoe_2.releaseDate) }} </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Retail Price -->
                        <div class="row ml-5">
                            <div class="col-md-6">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Retail Price </td>
                                        <td class="font-weight-bold pb-2"> $ {{ usdToPhp(shoe_1.retailPrice) }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Retail Price </td>
                                        <td class="font-weight-bold pb-2"> $ {{ usdToPhp(shoe_2.retailPrice) }} </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Lowest Resell Price -->
                        <div class="row ml-5">
                            <div class="col-md-6">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Lowest <br>Resell Price </td>
                                        <td class="font-weight-bold pb-2"> ₱ {{ usdToPhp(shoe_1.lowestResellPrice) }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="w-100">
                                    <tr>
                                        <td class="pb-2 w-40"> Lowest <br>Resell Price </td>
                                        <td class="font-weight-bold pb-2"> ₱ {{ usdToPhp(shoe_2.lowestResellPrice) }} </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Link -->
                        <div class="row text-center">
                            <div class="col-md-6">
                                <div class="mt-4"> 
                                    <a v-if="shoe_1.length != 0" :href="shoe_1.resellLinks.stockX" target="_blank"> {{ shoe_1.resellLinks.stockX }} </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-4"> 
                                    <a v-if="shoe_2.length != 0" :href="shoe_2.resellLinks.stockX" target="_blank"> {{ shoe_2.resellLinks.stockX }} </a>
                                </div>
                            </div>
                        </div>
                        <!-- Reviews -->
                        <h3 class="font-weight-bold mt-5"> User Review(s) </h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <span style="font-style: italic; color: gray"> Based on {{ shoe_1_review.num_reviews }} review(s) </span> <span class="ml-1 orange ml-1" style="font-weight: 600"> ★ {{ shoe_1_review.average != null ? twoDecimal(shoe_1_review.average) : 0 }} </span>
                                <div v-if="shoe_1_review.num_reviews != 0" class="row mt-4">
                                    <div class="col-md-6 mb-4" v-for="(review, index) in shoe_1_review.reviews" :key="index">
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
                                </div> 
                                <div v-else>
                                    <h4 class="text-roboto font-weight-bold mb-4 pt-5" style="color: gray; text-align: center"> No Review Found </h4>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span style="font-style: italic; color: gray"> Based on {{ shoe_2_review.num_reviews }} review(s) </span> <span class="ml-1 orange ml-1" style="font-weight: 600"> ★ {{ shoe_2_review.average != null ? twoDecimal(shoe_2_review.average) : 0 }} </span>
                                <div v-if="shoe_2_review.num_reviews != 0" class="row mt-4">
                                    <div class="col-md-6 mb-4" v-for="(review, index) in shoe_2_review.reviews" :key="index">
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
                                </div>
                                <div v-else>
                                    <h4 class="text-roboto font-weight-bold mb-4 pt-5" style="color: gray; text-align: center"> No Review Found </h4>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
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
            shoe_1 : [],
            shoe_2 : [],
            shoe_3 : [],
            shoe_1_review: [],
            shoe_2_review: [],
            shoe_3_review: [],
            count_num: 1,
            count_loop: 0,
            pages: 0,
            previous_page: -1,
            active_page: 0,
            next_page: 2,
            search: '',
            search_error: false,
            // As of May 24, you can change this.
            conv_rate: 55.75,
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
                    text: 'Adidas'
                },
                {
                    name: "ASICS",
                    text: "ASICS"
                },
                {
                    name: "Balenciaga",
                    text: "Balenciaga"
                },
                {
                    name: "Birkenstock",
                    text: "Birkenstock"
                },
                {
                    name: "Brooks",
                    text: "Brooks"
                },
                {
                    name: "Burberry",
                    text: "Burberry"
                },
                {
                    name: "Christian Louboutin",
                    text: "Christian Louboutin"
                },
                {
                    name: "Clarks",
                    text: "Clarks"
                },
                {
                    name: "Converse",
                    text: "Converse"
                },
                {
                    name: "Crocs",
                    text: "Crocs"
                },
                {
                    name: "Dr. Martens",
                    text: "Dr. Martens"
                },
                {
                    name: "Fila",
                    text: "Fila"
                },
                {
                    name: "Gucci",
                    text: "Gucci"
                },
                {
                    name: "Hoka One One",
                    text: "Hoka One One"
                },
                {
                    name: "Jimmy Choo",
                    text: "Jimmy Choo"
                },
                {
                    name: "Jordan",
                    text: "Jordan"
                },
                {
                    name: "Keen",
                    text: "Keen"
                },
                {
                    name: "K-Swiss",
                    text: "K-Swiss"
                },
                {
                    name: "Louis Vuitton",
                    text: "Louis Vuitton"
                },
                {
                    name: "Merrell",
                    text: "Merrell"
                },
                {
                    name: "Mizuno",
                    text: "Mizuno"
                },
                {
                    name: "New Balance",
                    text: "New Balance"
                },
                {
                    name: "Nike",
                    text: "Nike"
                },
                {
                    name: "Prada",
                    text: "Prada"
                },
                {
                    name: "Puma",
                    text: "Puma"
                },
                {
                    name: "Reebok",
                    text: "Reebok"
                },
                {
                    name: "Salomon",
                    text: "Salomon"
                },
                {
                    name: "Superga",
                    text: "Superga"
                },
                {
                    name: "Timberland",
                    text: "Timberland"
                },
                {
                    name: "Under Armour",
                    text: "Under Armour"
                },
                {
                    name: "Vans",
                    text: "Vans"
                },
                {
                    name: "Versace",
                    text: "Versace"
                }
            ]
        }
    },
    watch: {
        count_num() {
            if (this.count_num == 4) {
                document.getElementById("compare-shoe-btn").click();
            }

            this.count_loop = this.count_num - 1
        },
    },
    mounted() {
        this.searchShoeBrand('All');
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
        rateMeShoe(shoe) {
            this.individual_shoe = []
            this.individual_shoe = shoe
        },
        seeMore() {
            let shoes = []
            shoes.push(this.shoe_1)
            shoes.push(this.shoe_2)

            if (this.shoe_3.length != 0) {
                shoes.push(this.shoe_3)
            }

            axios.post('api/reviews/getShoeReviewsComparison', {
                shoes: shoes
            }).then(response => {
                let data_return = response.data.data

                for (let i = 0; i < data_return.length; i++) {
                    if (data_return[i]['id'] == this.shoe_1.id) {
                        this.shoe_1_review = data_return[i]
                    } else if (data_return[i]['id'] == this.shoe_2.id) {
                        this.shoe_2_review = data_return[i]
                    } else {
                        this.shoe_3_review = data_return[i]
                    }
                }
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
        removeShoe(shoe) {
            if (shoe == 2) {
                // Remove first shoe
                this.shoe_1 = this.shoe_2
                this.shoe_2 = this.shoe_3
                this.shoe_3 = []
            } else if (shoe == 3) {
                // Remove second shoe
                this.shoe_2 = this.shoe_3
                this.shoe_3 = []
            } else if (shoe == 4) {
                // Remove third shoe
                this.shoe_3 = []
            }
            this.count_num -= 1
        },
        pickMe(shoe) {
            if (this.count_num == 1) {
                this.shoe_1 = shoe
                this.count_num += 1
            } else if (this.count_num == 2) {
                this.shoe_2 = shoe
                this.count_num += 1
            } else if (this.count_num == 3) {
                this.shoe_3 = shoe
                this.count_num += 1
            } else {
                //ERROR
                console.log('error')
            }
        },
        async searchSpecific(page = 0, from_pages = false, count = 20,) {
            let search = this.search
            let brand = this.brand_name
            this.active_page = page
            if (brand == 'All') {
                brand = 'Sneakers shoes'
            }

            // Scroll to top
            var access = document.getElementById("go-here-comparison");
            access.scrollIntoView({behavior: 'smooth'}, true);

            if (search || page || (page == 0 && from_pages == true)) {
                await axios.post('https://xw7sbct9v6-1.algolianet.com/1/indexes/products/query?x-algolia-agent=Algolia%20for%20vanilla%20JavaScript%203.32.1&x-algolia-application-id=XW7SBCT9V6&x-algolia-api-key=6b5e76b49705eb9f51a06d3c82f7acee', {
                    "params" : "query=${" + brand + " " + search + "}&facets=*&filters=&hitsPerPage=" + count + "&page=" + this.active_page + ""
                }).then(response => {
                    var json = response.data
                    var products = [];

                    this.pages = json.nbPages;
                    // this.pages = 5;

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
                                last_72: json.hits[i].sales_last_72,
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
        async searchShoeBrand(brand, count = 20) {
            this.brand_name = brand
            this.search = ''
            this.active_page = 0
            if (brand == 'All') {
                brand = 'Sneakers Shoes'
            }

            await axios.post('https://xw7sbct9v6-1.algolianet.com/1/indexes/products/query?x-algolia-agent=Algolia%20for%20vanilla%20JavaScript%203.32.1&x-algolia-application-id=XW7SBCT9V6&x-algolia-api-key=6b5e76b49705eb9f51a06d3c82f7acee', {
                "params" : "query=${" + brand + "}&facets=*&filters=&hitsPerPage=" + count + "&page=" + this.active_page + ""
            }).then(response => {
                var json = response.data
                var products = [];
                // var numOfShoes = json.hits.length;

                this.pages = json.nbPages;
                // this.pages = 5

                for (var i = 0; i < json.hits.length; i++) {
                    if (json.hits[i].product_category == 'sneakers') {
                        // if (!json.hits[i].style_id || (json.hits[i].style_id).indexOf(' ') >= 0) {
                        //     numOfShoes--;
                        //     continue;
                        // }
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
                            sold: json.hits[i].deadstock_sold,
                            last_72: json.hits[i].sales_last_72,
                            urlKey: json.hits[i].url,
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
        formatNum(num) {
            if (num) {
                return num.toLocaleString('en-US')
            }
        },
        usdToPhp(num) {
            if (num) {
                var multiply_num = num * this.conv_rate

                return multiply_num.toLocaleString("en-US", {minimumFractionDigits: 2})
            }
        },
        startFrom (arr, idx) {
            return arr.slice(idx)
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