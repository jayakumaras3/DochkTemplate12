<div class="col-xl-9 col-lg-8">
    <div class="row">
        <div id="loadMoreBlock"></div>
    </div>
    <div class="load-more" lastID="" style="display: none;">
        <?php echo lang('UI_Text.Loading_Courses'); ?>
    </div>
    <div class="col-12 text-center" style="display: none;" id="noMoreCourses">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-3"><?php echo lang('UI_Text.No_Courses_Available'); ?></h4>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<script> 
    var page = 0;
    var triggerScrollLoader = true;
    var isLoading = false;
    var no_more_course = 0;
    $(document).ready(function() {
        isLoading = true;
        $('.load-more').show();
        get_more_courses(page);
    });


    $(window).scroll(function() {
        if (no_more_course < 1) {
            if (triggerScrollLoader && !isLoading) {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 50) {
                    isLoading = true;
                    $('.load-more').show();
                    page = parseInt(page) + 9;
                    get_more_courses(page);
                }
            }
        }
    });


    var get_more_courses = function(page) {

        $.ajax({
            url: "<?php echo base_url() ?>marketplace/dashboard/load_more/" + page,
            type: "GET",
            dataType: "html",
        }).done(function(data) {
            $('.load-more').hide();
            isLoading = false;
            if (data.length == 0) {
                triggerScrollLoader = false;
                return;
            }
            $('#loadMoreBlock').append(data).show('slow');
        }).fail(function(jqXHR, ajaxOptions, thrownError) {
            console.log('Nothing to display');
        });

        var new_page = page + 1;
        $('#load_more').data('val', new_page);
    };
</script>