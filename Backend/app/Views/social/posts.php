<style>
    .post-media-grid {
        display: grid;
        grid-gap: 6px;
    }

    .post-media-grid .media-item img,
    .post-media-grid .media-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    /* Layout like Facebook */
    .post-media-grid .media-item {
        overflow: hidden;
    }

    /* One media full width */
    .post-media-grid:has(.media-item:nth-child(1):last-child) {
        grid-template-columns: 1fr;
    }

    /* Two media side by side */
    .post-media-grid:has(.media-item:nth-child(2):last-child) {
        grid-template-columns: 1fr 1fr;
    }

    /* Three or more -> 2 on top, rest below */
    .post-media-grid:has(.media-item:nth-child(3)) {
        grid-template-columns: 1fr 1fr;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <?php if ($course_id != 0) { ?>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('my_training/read_more'); ?>">
                                Course Detail
                            </a>
                        </li>
                    </ol>
                </div>
            <?php } ?>
            <h4 class="page-title">Discussion</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-bordered" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#newpost" data-bs-toggle="tab" aria-expanded="false" class="nav-link active px-3 py-2"
                            aria-selected="true" role="tab">
                            <i class="mdi mdi-pencil-box-multiple font-18 d-md-none d-block"></i>
                            <span class="d-none d-md-block">Create Post</span>
                        </a>
                    </li>

                </ul> <!-- end nav-->
                <div class="tab-content pt-0">
                    <div class="tab-pane show active p-3" id="newpost" role="tabpanel">
                        <!-- comment box -->
                        <div class="border rounded">
                            <!-- <form class="comment-area-box" action="<?php echo base_url('social/post/add_post') ?>"
                                method="POST"><?= csrf_field() ?>

                                <textarea rows="4" name="new_post" class="form-control border-0 resize-none"
                                    placeholder="Write something...."></textarea>
                                <div class="p-2 bg-light d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="#" class="btn btn-sm px-2 font-16 btn-light"><i class="mdi mdi-image-outline"></i></a>
                                            <a href="#" class="btn btn-sm px-2 font-16 btn-light"><i class="mdi mdi-attachment"></i></a>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-success"><i
                                            class="mdi mdi-send-outline me-1"></i>Post</button>
                                </div>
                            </form> -->
                            <form class="comment-area-box" action="<?php echo base_url('social/post/add_post') ?>"
                                method="POST" enctype="multipart/form-data"><?= csrf_field() ?>

                                <textarea rows="4" name="new_post" class="form-control border-0 resize-none"
                                    placeholder="Write something...."></textarea>

                                <div class="p-2 bg-light d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <!-- Trigger for image upload -->
                                        <label class="btn btn-sm px-2 font-16 btn-light m-0" for="imageUpload">
                                            <i class="mdi mdi-image-outline"></i>
                                        </label>
                                        <input type="file" name="image_files[]" id="imageUpload" multiple
                                            accept="image/*" style="display: none;">

                                        <!-- Trigger for video upload -->
                                        <label class="btn btn-sm px-2 font-16 btn-light m-0" for="videoUpload">
                                            <i class="mdi mdi-video-outline"></i>
                                        </label>
                                        <input type="file" name="video_files[]" id="videoUpload" multiple
                                            accept="video/*" style="display: none;">
                                    </div>
                                    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="mdi mdi-send-outline me-1"></i>Post
                                    </button>
                                </div>
                            </form>


                        </div> <!-- end .border-->
                        <!-- end comment box -->
                    </div> <!-- end preview-->
                </div> <!-- end tab-content-->
            </div>
        </div>

        <style>
            .btn-card {
                display: block;
                /* make it behave like a div */
                width: 100%;
                /* full column width */
                border: none;
                /* remove black border */
                background: none;
                /* no background */
                padding: 0;
                /* remove button padding */
                margin: 0;
                text-align: left;
                cursor: pointer;
            }

            .btn-card:focus {
                outline: none;
                /* remove blue highlight */
                box-shadow: none;
                /* remove Bootstrap shadow */
            }
        </style>
        <div class="row">
            <div class="col-md-6 col-xl-6">
                <form action="<?php echo base_url('social/Post/posts_list'); ?>" method="POST" class="m-0">
                    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                    <button type="submit" class="btn-card">
                        <div class="widget-rounded-circle card m-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div
                                            class="avatar-lg rounded-circle bg-soft-primary border-primary border d-flex align-items-center justify-content-center">
                                            <i class="mdi mdi-signal-variant font-22 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-end">
                                            <h3 class="text-dark mt-1">
                                                <span data-plugin="counterup"><?php echo $my_all_post; ?></span>
                                            </h3>
                                            <p class="text-muted mb-1 text-truncate">Total Posts</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                    </button>
                </form>
            </div> <!-- end col-->
        </div>
    </div>
    <div class="col-md-6">
        <?php foreach ($active_posts as $post) { ?>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <img class="me-2 avatar-sm rounded-circle" src="<?php
                        echo (isset($post['profile_image']) && $post['profile_foldername']) ?
                            base_url('assets/assets/uploads/profile/' . $post['id_user'] . "/" . $post['profile_foldername'] . "/" . $post['profile_image']) :
                            base_url('public/aristo_assets/images/User_2_1.svg');
                        ?>" alt="Generic placeholder image">
                        <div class="w-100">
                            <?php if ($post['created_by'] == $user_id): ?>
                                <div class="dropdown float-end text-muted">
                                    <a href="#" class="dropdown-toggle text-muted font-18" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <button type="button" class="dropdown-item"
                                            onclick="toggleEditPostForm(<?php echo $post['social_id']; ?>)">Edit</button>


                                        <form action="<?php echo base_url('social/post/delete_post') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="post_id" value="<?php echo $post['social_id'] ?>">
                                            <button type="submit" class="dropdown-item">Delete</button>
                                        </form>

                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="edit-post-form bg-light p-3 rounded mt-2"
                                id="edit-post-<?php echo $post['social_id']; ?>" style="display: none;">
                                <form action="<?php echo base_url('social/post/edit_post') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="post_id" value="<?php echo $post['social_id']; ?>">
                                    <textarea name="post_data"
                                        class="form-control mb-2"><?php echo $post['post_data']; ?></textarea>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            onclick="toggleEditPostForm(<?php echo $post['social_id']; ?>)">Cancel</button>
                                    </div>
                                </form>
                            </div>
                            <h5 class="m-0"><a class="text-reset">
                                    <?php echo $post['posted_by'] . ' ' . $post['posted_by_last_name']; ?></a></h5>
                            <p class="text-muted"><small><?php echo $post['time_ago']; ?></small></p>
                        </div>
                    </div>
                    <div class="font-16 text-left fst-italic text-dark">

                        <?php echo nl2br(htmlspecialchars($post['post_data'])); ?>

                        <?php
                        // Collect media
                        $mediaItems = [];

                        if (!empty($post['post_image'])) {
                            $images = explode(',', $post['post_image']);
                            foreach ($images as $img) {
                                $img = trim($img);
                                if (!empty($img)) {
                                    $mediaItems[] = [
                                        'type' => 'image',
                                        'path' => base_url('assets/assets/uploads/post_images/' . $img)
                                    ];
                                }
                            }
                        }

                        if (!empty($post['post_video'])) {
                            $videos = explode(',', $post['post_video']);
                            foreach ($videos as $vid) {
                                $vid = trim($vid);
                                if (!empty($vid)) {
                                    $mediaItems[] = [
                                        'type' => 'video',
                                        'path' => base_url('assets/assets/uploads/post_videos/' . $vid)
                                    ];
                                }
                            }
                        }
                        ?>

                        <?php if (!empty($mediaItems)) { ?>
                            <div class="post-media-grid">
                                <?php foreach ($mediaItems as $media) { ?>
                                    <div class="media-item">
                                        <?php if ($media['type'] === 'image') { ?>
                                            <img src="<?php echo $media['path']; ?>" alt="post image" />
                                        <?php } else { ?>
                                            <video controls>
                                                <source src="<?php echo $media['path']; ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="mt-3">
                        <?php if ($post['melikes'] > 0) { ?>
                            <span class="text-muted ps-0"><i class="mdi mdi-heart text-danger"></i>
                                <?php echo $post['likes']; ?> Likes</span>
                        <?php } else { ?>
                            <form class="form-horizontal" action="<?php echo base_url('social/post/like_post') ?>"
                                method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="post_id" value="<?php echo $post['social_id'] ?>">
                                <button type="submit" class="btn btn-sm btn-link text-muted ps-0"><i
                                        class="mdi mdi-heart text-danger"></i> <?php echo $post['likes']; ?> Likes</button>
                            </form>
                        <?php } ?>

                    </div>
                    <!-- Replies -->
                    <?php if (isset($replies[$post['social_id']])): ?>
                        <?php
                        $totalReplies = count($replies[$post['social_id']]);
                        $visibleCount = 2;
                        $i = 0;
                        ?>
                        <div class="post-user-comment-box mt-2">
                            <a href="javascript:void(0);" class="text-muted font-13 d-inline-block mt-2">
                                <i class="mdi mdi-reply"></i> Reply
                            </a>

                            <?php foreach ($replies[$post['social_id']] as $reply): ?>
                                <div class="d-flex align-items-start mt-3 reply-item-<?php echo $post['social_id']; ?> <?php if ($i >= $visibleCount)
                                        echo 'd-none'; ?>">
                                    <!-- Reply Profile Picture -->
                                    <img class="me-2 avatar-sm rounded-circle" src="<?php echo (isset($reply['profile_image']) && $reply['profile_foldername']) ?
                                        base_url('assets/assets/uploads/profile/' . $reply['id_user'] . "/" . $reply['profile_foldername'] . "/" . $reply['profile_image']) :
                                        base_url('public/aristo_assets/images/User_2_1.svg'); ?>" alt="User image">

                                    <div class="w-100">
                                        <?php if ($reply['last_updated_by'] == $user_id): ?>
                                            <div class="dropdown float-end text-muted">
                                                <a href="#" class="dropdown-toggle text-muted font-18" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="mdi mdi-dots-horizontal"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <button type="button" class="dropdown-item"
                                                        onclick="toggleEditReplyForm(<?php echo $reply['social_reply_id']; ?>)">Edit</button>


                                                    <form action="<?php echo base_url('social/post/delete_reply') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="social_reply_id"
                                                            value="<?php echo $reply['social_reply_id'] ?>">
                                                        <button type="submit" class="dropdown-item">Delete</button>
                                                    </form>

                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="edit-reply-form bg-light p-3 rounded mt-2"
                                            id="edit-reply-<?php echo $reply['social_reply_id']; ?>" style="display: none;">
                                            <form action="<?php echo base_url('social/post/edit_reply') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="social_reply_id"
                                                    value="<?php echo $reply['social_reply_id']; ?>">
                                                <textarea name="reply_content"
                                                    class="form-control mb-2"><?php echo $reply['reply_content']; ?></textarea>
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        onclick="toggleEditReplyForm(<?php echo $reply['social_reply_id']; ?>)">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                        <h6 class="mt-0 mb-1">
                                            <a  class="text-reset">
                                                <?php echo $reply['replied_by'] . ' ' . $reply['replied_by_last_name']; ?>
                                            </a>
                                            <small class="text-muted ms-2"><?php echo $reply['time_ago']; ?></small>
                                        </h6>
                                        <p class="mb-2"><?php echo $reply['reply_content']; ?></p>
                                    </div>
                                </div>
                                <?php $i++; ?>
                            <?php endforeach; ?>

                            <?php if ($totalReplies > $visibleCount): ?>
                                <div class="mt-2 view-more-wrapper-<?php echo $post['social_id']; ?>">
                                    <a href="javascript:void(0);" class="text-primary font-13 read-more-replies"
                                        data-post-id="<?php echo $post['social_id']; ?>">
                                        View more replies (<?php echo $totalReplies - $visibleCount; ?>)
                                    </a>
                                </div>
                            <?php endif; ?>

                            <!-- Reply Form -->
                            <div class="d-flex align-items-start mt-2">
                                <div class="w-100">
                                    <form action="<?php echo base_url('social/post/reply_post') ?>" method="POST"
                                        class="form-horizontal">
                                        <div class="row g-2">
                                            <div class="col">
                                                <input type="text" name="reply_comment"
                                                    class="form-control border-0 form-control-sm" placeholder="Write a comment">
                                            </div>
                                            <div class="col-auto">
                                                <input type="hidden" name="post_id" value="<?php echo $post['social_id'] ?>">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fe-send"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>





                </div>
            </div>


        <?php } ?>

    </div>
</div>


<script>
    function toggleEditPostForm(postId) {
        var form = document.getElementById('edit-post-' + postId);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    function toggleEditReplyForm(replyId) {
        var form = document.getElementById('edit-reply-' + replyId);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".read-more-replies").forEach(function (btn) {
            btn.addEventListener("click", function () {
                let postId = this.getAttribute("data-post-id");
                let replies = document.querySelectorAll(".reply-item-" + postId);
                let isExpanded = this.getAttribute("data-expanded") === "true";

                if (isExpanded) {
                    // Hide all but first 2 replies
                    replies.forEach(function (reply, index) {
                        if (index >= 2) reply.classList.add("d-none");
                    });
                    this.textContent = "View more replies (" + (replies.length - 2) + ")";
                    this.setAttribute("data-expanded", "false");
                } else {
                    // Show all replies
                    replies.forEach(function (reply) {
                        reply.classList.remove("d-none");
                    });
                    this.textContent = "Hide replies";
                    this.setAttribute("data-expanded", "true");
                }
            });
        });
    });

</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const textarea = document.querySelector('textarea[name="new_post"]');
        textarea.addEventListener("keydown", function (e) {
            // Stop Enter from submitting the form
            if (e.key === "Enter") {
                e.stopPropagation();
            }
        });
    });
</script>