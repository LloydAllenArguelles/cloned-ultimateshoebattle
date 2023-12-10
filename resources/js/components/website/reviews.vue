<template>
    <div class="container">
        <div class="col-md-12 mt-5 text-center">
            <h2 class="font-weight-bold orange"> Reviews </h2>
            <hr class="reviews-hr">
            <div class="mtb-05 orange"> ★ {{ twoDecimal(average_star) }} </div>
            <div class="sub-text"> Recent reviews: </div>
        </div>
        <div class="row pl-5 pr-5 pt-3 pb-5">
            <div class="col-md-4 mb-5" v-for="(review, index) in review_list" :key="index">
                <div class="p-3 shadow text-center pt-4 pb-4 review-block">
                    <h6 class="font-weight-bold"> {{ review.name }} </h6>
                    <hr class="reviews-hr">
                    <div class="mtb-05 orange">
                        <span v-for="(star, index) in review.rate" :key="index"> ★ </span>
                    </div>
                    <div class="sub-text"> {{ fixDateFormat(review.created_at) }} </div>
                    <div class="pt-3 pb-4 p-2"> {{ review.review }} </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-12 text-center">
                <div class="mb-2">
                    <img src="images/favicon.png" height="100" width="150" alt="icon">
                </div>
                <div class="mb-2"> Got a feedback for us? </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal"> Write a Review </button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header font-weight-bold">
                    Write a Review
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label> Name (optional) </label>
                            <input type="text" class="form-control" v-model="name" />
                        </div>
                        <div class="form-group">
                            <label> Rate </label>
                            <select class="form-select" v-model="rate" required>
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
                            <textarea class="form-control" v-model="your_review" rows="3" required/>
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
</template>
<script>
export default {
    props: ['auth'],
    data() {
        return {
            name: '',
            your_review: '',
            rate: '',
            review_list: [],
            average_star: 0,
        }
    },
    mounted() {
        this.getReviews()
    },
    methods: {
        getReviews() {
            axios.get('api/reviews/getReviews').then(response => {
                this.review_list = response.data.data
                this.average_star = response.data.average
            })
        },
        saveReview() {
            Swal.fire({
                title: 'Submit Review?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (!this.name) {
                        this.name = 'Anonymous User'
                    }

                    axios.post('api/reviews/store', {
                        name: this.name,
                        rate: this.rate,
                        review: this.your_review
                    }).then(response => {
                        if (response.status == 200) {
                            this.name = '';
                            this.rate = '';
                            this.your_review = '';

                            Swal.fire({
                                title: 'Your review is recorded',
                                text: 'Please refresh to see your review.',
                                icon: 'success',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false
                            })
                        }
                    })

                }
            });
        },
        fixDateFormat(date) {
            if (date) {
                return moment(date).format('LLL')
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
