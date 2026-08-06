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
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('social/Post'); ?>">
                            Discussion
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">All Post</h4>
        </div>
    </div>
</div>

<div class="col-md-12">
    <?php foreach ($active_posts as $post) { ?>
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <img class="me-2 avatar-sm rounded-circle" src="<?php
                    echo (isset($post['profile_image']) && isset($post['profile_foldername'])) ?
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
                        <h5 class="m-0"><a href="contacts-profile.html" class="text-reset">
                                <?php echo $post['posted_by'] . ' ' . $post['posted_by_last_name']; ?></a></h5>
                        <p class="text-muted"><small><?php echo $post['time_ago']; ?></small></p>
                    </div>
                </div>
                <div class="font-16 text-center fst-italic text-dark">
                    <?php echo $post['post_data']; ?>

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
                        <form class="form-horizontal" action="<?php echo base_url('social/post/like_post') ?>" method="POST"><?= csrf_field() ?>
                            <input type="hidden" name="post_id" value="<?php echo $post['social_id'] ?>">
                            <button type="submit" class="btn btn-sm btn-link text-muted ps-0"><i
                                    class="mdi mdi-heart text-danger"></i> <?php echo $post['likes']; ?> Likes</button>
                        </form>
                    <?php } ?>

                </div>
                <!-- Replies -->
                <!-- <?php if (isset($replies[$post['social_id']])): ?>
                    <?php
                    $totalReplies = count($replies[$post['social_id']]);
                    $visibleCount = 2;
                    $i = 0;
                    ?>
                    <div class="post-user-comment-box mt-2">
                        <a href="javascript: void(0);" class="text-muted font-13 d-inline-block mt-2"><i
                                class="mdi mdi-reply"></i> Reply</a>

                        <?php foreach ($replies[$post['social_id']] as $reply): ?>

                            <div class="d-flex align-items-start mt-3 reply-item-<?php echo $post['social_id']; ?>" <?php if ($i >= $visibleCount): ?>style="display: none;" <?php endif; ?>>
                                <img class="me-2 avatar-sm rounded-circle" src="<?php
                                echo (isset($reply['profile_image']) && $reply['profile_foldername']) ?
                                    base_url('assets/assets/uploads/profile/' . $reply['id_user'] . "/" . $reply['profile_foldername'] . "/" . $reply['profile_image']) :
                                    base_url('public/aristo_assets/images/User_2_1.svg');
                                ?>" alt="User image">

                                 <div class="w-100">
                                    <?php if ($reply['last_updated_by'] == $user_id): ?>
                                        <div class="dropdown float-end text-muted">
                                            <a href="#" class="dropdown-toggle text-muted font-18" data-bs-toggle="dropdown">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <button type="button" class="dropdown-item"
                                                    onclick="toggleEditReplyForm(<?php echo $reply['social_reply_id']; ?>)">Edit</button>

                                                <form action="<?php echo base_url('social/post/delete_reply') ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="social_reply_id"
                                                        value="<?php echo $reply['social_reply_id']; ?>">
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
                                        <a href="contacts-profile.html" class="text-reset">
                                            <?php echo $reply['replied_by'] . ' ' . $reply['replied_by_last_name']; ?>
                                        </a>
                                        <small class="text-muted ms-2"><?php echo $reply['time_ago']; ?></small>
                                    </h6>

                                     <p class="mb-2"><?php echo $reply['reply_content']; ?></p>
                                </div>
                            </div>

                            <?php $i++; ?>
                        <?php endforeach; ?>
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
                <?php endif; ?> -->


            </div>
        </div>

    <?php } ?>

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