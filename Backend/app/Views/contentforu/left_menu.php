<style>
    .thumbnail_db img {
        height: 100%;
        width: 100%;
    }

    .thumbnail_db img {
        object-fit: contain;
    }

    /* Add these styles for stars */
    .star {
        font-size: 24px;
        color: #ccc;
        cursor: pointer;
    }

    .star.selected,
    .star.hover,
    .star.half-selected {
        color: #ffcc00;
    }

    .star-rating {
        /* font-size: 24px; */
        color: gold;
        /* Adjust the color as needed */
    }

    .rating-container {
        display: inline-flex;
        /* Use inline-flex for better control */
        align-items: center;
        /* Align items vertically in the flex container */
    }

    #ratingContainer {
        /* Adjust the styles as needed */
        font-size: 18px;
        /* Example font size */
        margin-left: 0;
        /* Adjust as needed for spacing between rating and count user */
        line-height: 1;
        /* Reset the line-height to default for precise vertical alignment */
    }

    .cardtile {
        padding: 10px;
        background-color: rgba(255, 255, 255, 1);
        margin-right: 1px;
        margin-left: 1px;
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1), 0 6px 20px 0 rgba(0, 0, 0, 0.02);
    }

    .cardshaddow {
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1), 0 6px 20px 0 rgba(0, 0, 0, 0.02);
    }
</style>
<script>
    function displayStars(rating, containerId) {
        const container = document.getElementById(containerId);
        console.log("Rating for " + containerId + ": " + rating); // Debug log for rating
        container.innerHTML = ''; // Clear previous content

        if (rating === null || rating === 0) {
            // Display 5 outlined stars if the rating is null or zero
            for (let i = 0; i < 5; i++) {
                const outlinedStar = document.createElement('span');
                outlinedStar.innerHTML = '<i class="mdi mdi-star-outline text-warning"></i>';
                container.appendChild(outlinedStar);
            }
        } else {
            const fullStars = Math.floor(rating);
            for (let i = 0; i < fullStars; i++) {
                const star = document.createElement('span');
                star.innerHTML = '<i class="mdi mdi-star text-warning"></i>'; // Full star character
                container.appendChild(star);
            }

            const decimalPart = rating - fullStars;
            if (decimalPart > 0) {
                const halfStar = document.createElement('span');
                halfStar.innerHTML = '<i class="mdi mdi-star-half text-warning"></i>'; // Half-filled star character
                container.appendChild(halfStar);
            }

            const emptyStars = 5 - Math.ceil(rating); // Calculate empty stars
            for (let i = 0; i < emptyStars; i++) {
                const outlinedStar = document.createElement('span');
                outlinedStar.innerHTML = '<i class="mdi mdi-star-outline text-warning"></i>'; // Outlined star character
                container.appendChild(outlinedStar);
            }
        }
    }
</script>

<div class="row">
    <div class="col-xl-3 col-lg-6 order-lg-1 order-xl-1">

        <div class="card">
            <div class="card-body">

                <div class="list-group list-group-flush mt-2 font-15">
                    <a href="<?php echo base_url('Contentforu/Dashboard/by_category/43') ?>" class="list-group-item list-group-item-action border-0"> <span class="mdi mdi-circle text-info me-2"></span>Business Skills</a>
                    <a href="<?php echo base_url('Contentforu/Dashboard/by_category/102') ?>" class="list-group-item list-group-item-action border-0"> <span class="mdi mdi-circle text-primary me-2"></span>Compliance</a>
                    <a href="<?php echo base_url('Contentforu/Dashboard/by_category/45') ?>" class="list-group-item list-group-item-action border-0"> <span class="mdi mdi-circle text-success me-2"></span>DEI</a>
                    <a href="<?php echo base_url('Contentforu/Dashboard/by_category/101') ?>" class="list-group-item list-group-item-action border-0"> <span class="mdi mdi-circle text-danger me-2"></span>Technology</a>
                    <a href="<?php echo base_url('Contentforu/Dashboard/by_category/104') ?>" class="list-group-item list-group-item-action border-0"> <span class="mdi mdi-circle text-warning me-2"></span>Safety</a>
                    <a href="<?php echo base_url('Contentforu/Dashboard/by_category/56') ?>" class="list-group-item list-group-item-action border-0"> <span class="mdi mdi-circle text-blue me-2"></span>Wellness</a>
                    <a href="<?php echo base_url('Contentforu/Dashboard/by_category/65') ?>" class="list-group-item list-group-item-action border-0"> <span class="mdi mdi-circle text-pink me-2"></span>Healthcare</a>
                </div>

                <h6 class="font-13 text-muted ps-3 my-3 text-uppercase">Course Bundles</h6>

                <div class="px-2">
                    <div class="d-flex align-items-start mb-2">
                        <div class="w-100 ps-2">
                            <span class="badge bg-pink mt-1 float-end">102</span>
                            <h5 class="mt-1 mb-0 font-family-primary fw-semibold"><a href="<?php echo base_url('Contentforu/Dashboard/contactAdmin') ?>" class="text-reset">Manager Essentials</a></h5>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-2">

                        <div class="w-100 ps-2">
                            <span class="badge bg-pink mt-1 float-end">35</span>
                            <h5 class="mt-1 mb-0 font-family-primary fw-semibold"><a href="<?php echo base_url('Contentforu/Dashboard/contactAdmin') ?>" class="text-reset">HR Essentials</a></h5>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-2">

                        <div class="w-100 ps-2">
                            <span class="badge bg-pink mt-1 float-end">22</span>
                            <h5 class="mt-1 mb-0 font-family-primary fw-semibold"><a href="<?php echo base_url('Contentforu/Dashboard/contactAdmin') ?>" class="text-reset">Finance Essentials</a></h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- end col -->

    