<?php
// Most specific title available for the content pane: the open sub-page if one is selected, else the open topic.
$pageTitle = null;
if (!empty($empg_id) && !empty($policy_list)) {
    foreach ($policy_list as $list) {
        if ($list['empg_id'] == $empg_id) {
            $pageTitle = $list['page_name'];
            break;
        }
    }
}
if (!$pageTitle && !empty($policy_name)) {
    $pageTitle = $policy_name[0]['document_name'];
}
if (!$pageTitle) {
    $pageTitle = 'Help';
}
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('my_training'); ?>">
                            Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Help
            </h4>
        </div>
    </div>
</div>
<style>
    .help-sidebar-card,
    .help-content-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .help-search-wrap {
        position: relative;
        margin-bottom: 1rem;
    }

    .help-search-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #98a6ad;
        pointer-events: none;
    }

    .help-search-wrap input {
        width: 100%;
        border: 1px solid #dee2e6;
        border-radius: .6rem;
        padding: .55rem .9rem .55rem 2.5rem;
        font-size: .875rem;
    }

    [data-bs-theme="dark"] .help-search-wrap input {
        border-color: #36404a;
        background: #232b36;
        color: #cedeef;
    }

    .help-topic-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .help-topic-item {
        display: block;
        margin-bottom: 0;
        border-left: 3px solid transparent;
        border-bottom: 1px solid #eef2f7;
    }

    [data-bs-theme="dark"] .help-topic-item {
        border-bottom-color: #36404a;
    }

    .help-topic-item.active {
        background: rgba(102, 88, 221, .08);
        border-left-color: #6658dd;
    }

    .help-topic-item button.help-topic-btn {
        all: unset;
        display: flex;
        align-items: center;
        gap: .7rem;
        width: 100%;
        padding: .7rem .9rem;
        cursor: pointer;
        font-size: .9rem;
        color: #495057;
        box-sizing: border-box;
    }

    [data-bs-theme="dark"] .help-topic-item button.help-topic-btn {
        color: #cedeef;
    }

    .help-topic-item.active button.help-topic-btn {
        color: #6658dd;
        font-weight: 700;
    }

    .help-subtopic-item button.help-subtopic-btn {
        all: unset;
        display: block;
        width: 100%;
        padding: .55rem .9rem .55rem 3.4rem;
        cursor: pointer;
        font-size: .825rem;
        color: #6c757d;
        box-sizing: border-box;
        border-bottom: 1px solid #eef2f7;
    }

    [data-bs-theme="dark"] .help-subtopic-item button.help-subtopic-btn {
        color: #98a6ad;
        border-bottom-color: #36404a;
    }

    .help-subtopic-item.active button.help-subtopic-btn {
        color: #6658dd;
        font-weight: 600;
    }

    .help-support-box {
        margin: 1rem;
        border-radius: 14px;
        background: rgba(102, 88, 221, .08);
        padding: 1rem;
    }

    [data-bs-theme="dark"] .help-support-box {
        background: rgba(146, 152, 245, .12);
    }

    .help-support-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        background: rgba(102, 88, 221, .15);
        color: #6658dd;
        flex-shrink: 0;
    }

    .help-content-header {
        display: flex;
        align-items: center;
        gap: .9rem;
        margin-bottom: 1.5rem;
    }

    .help-content-icon {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        background: rgba(102, 88, 221, .12);
        color: #6658dd;
    }

    .help-content-header h2 {
        font-weight: 700;
        margin-bottom: .35rem;
    }

    .help-title-underline {
        width: 46px;
        height: 3px;
        background: #6658dd;
        border-radius: 2px;
    }

    .help-content-body p {
        color: #495057;
        line-height: 1.7;
    }

    [data-bs-theme="dark"] .help-content-body p {
        color: #cedeef;
    }

    .help-feedback-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #eef2f7;
    }

    [data-bs-theme="dark"] .help-feedback-row {
        border-top-color: #36404a;
    }

    .help-feedback-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        background: rgba(255, 188, 0, .15);
        color: #ffbc00;
        flex-shrink: 0;
    }

    #videoElement {
        width: 100%;
        height: 400px;
        object-fit: contain;
    }
</style>
<div class="row">
    <div class="col-md-3">
        <div class="card help-sidebar-card">
            <div class="card-body p-0 pt-3 px-3">
                <div class="help-search-wrap">
                    <i class="mdi mdi-magnify"></i>
                    <input type="text" id="helpTopicSearch" placeholder="Search help topics...">
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="help-topic-list">
                    <?php
                    foreach ($policies as $pol) {
                        $isActiveTopic = ($emd_id == $pol['emd_id']);
                    ?>
                        <li class="help-topic-item <?= $isActiveTopic ? 'active' : '' ?>" data-help-search="<?= esc(strtolower($pol['document_name'])) ?>">
                            <form class="form-horizontal m-0" action="<?php echo base_url('help/view'); ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="emd_id" value="<?php echo $pol['emd_id']; ?>">
                                <button type="submit" class="help-topic-btn">
                                    <span><?php echo $pol['document_name']; ?></span>
                                </button>
                            </form>
                        </li>
                        <?php if ($isActiveTopic && !empty($policy_list)) {
                            foreach ($policy_list as $list) {
                                $isActivePage = ($empg_id == $list['empg_id']);
                        ?>
                                <li class="help-subtopic-item <?= $isActivePage ? 'active' : '' ?>" data-help-search="<?= esc(strtolower($list['page_name'])) ?>">
                                    <form class="form-horizontal m-0" action="<?php echo base_url('help/view'); ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="emd_id" value="<?php echo $emd_id; ?>">
                                        <input type="hidden" name="empg_id" value="<?php echo $list['empg_id']; ?>">
                                        <button type="submit" class="help-subtopic-btn"><?php echo $list['page_name']; ?></button>
                                    </form>
                                </li>
                        <?php
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
            <div class="help-support-box">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="help-support-icon"><i class="mdi mdi-headset"></i></div>
                    <div>
                        <p class="fw-bold mb-0">Need more help?</p>
                        <p class="text-muted mb-0 font-13">Can't find what you're looking for?</p>
                    </div>
                </div>
                <a href="<?php echo base_url('Support/Support_user'); ?>" class="btn btn-primary rounded-pill w-100">
                    Contact Support <i class="mdi mdi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card help-content-card">
            <div class="card-body">
                <div class="help-content-header">
                    <div class="help-content-icon"><i class="mdi mdi-file-document-outline"></i></div>
                    <div>
                        <h2 class="mb-0"><?php echo esc($pageTitle); ?></h2>
                        <div class="help-title-underline"></div>
                    </div>
                </div>
                <div class="help-content-body">
                    <?php
                    if (count($policy_pages) > 0) {
                        foreach ($policy_pages as $list) {
                            $type = $list['type'];
                            if ($type == 88) {
                                echo '<h3>';
                            }
                            if ($type == 89) {
                                echo '<h4>';
                            }
                            if ($type == 101) {
                                echo '<p>';
                            }
                            if ($type == 96) { ?>
                                <img id="previewImg-<?php echo $list['page_id'] ?>"
                                    class="help-preview-img"
                                    src="<?php echo base_url() ?>assets/assets/uploads/emanual_image/<?php echo $list['page_id'] ?>/<?php echo $list['content1'] ?>"
                                    style="max-width:100%;cursor:pointer;">
                            <?php } elseif ($type == 97 && !empty($list['content1'])) {
                                $videoUrl = base_url("assets/assets/uploads/emanual_video/" . $empg_id . "/" . $list['content1']);
                            ?>
                                <div class="video-container mb-3">
                                    <video id="videoElement" controls>
                                        <source src="<?= $videoUrl ?>" type="video/mp4">
                                    </video>
                                </div>
                            <?php } else {
                                echo $list['content1'];
                            }
                            if ($type == 101) {
                                echo '</p>';
                            }
                            if ($type == 89) {
                                echo '</h4>';
                            }
                            if ($type == 88) {
                                echo '</h3>';
                            }
                        }
                    }
                    ?>
                </div>

                <div id="imgPopup" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.8);z-index:9999;">
                    <span id="closePopup" style="position:absolute;top:20px;right:30px;color:#fff;font-size:35px;cursor:pointer;">&times;</span>
                    <img id="popupImg" src="" style="display:block;max-width:90%;max-height:90%;margin:30px auto;">
                </div>

                <div class="help-feedback-row">
                    <div class="d-flex align-items-center gap-2">
                        <div class="help-feedback-icon"><i class="mdi mdi-lightbulb-on-outline"></i></div>
                        <div>
                            <p class="fw-bold mb-0">Was this helpful?</p>
                            <p class="text-muted mb-0 font-13">Your feedback helps us improve our documentation.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary rounded-pill help-feedback-btn"><i class="mdi mdi-thumb-up-outline me-1"></i>Yes, helpful</button>
                        <button type="button" class="btn btn-outline-secondary rounded-pill help-feedback-btn"><i class="mdi mdi-thumb-down-outline me-1"></i>Not really</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.help-preview-img').forEach(function(img) {
            img.addEventListener('click', function() {
                document.getElementById('popupImg').src = img.src;
                document.getElementById('imgPopup').style.display = 'block';
            });
        });

        var closePopup = document.getElementById('closePopup');
        if (closePopup) {
            closePopup.addEventListener('click', function() {
                document.getElementById('imgPopup').style.display = 'none';
            });
        }

        var searchInput = document.getElementById('helpTopicSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                var term = this.value.trim().toLowerCase();
                document.querySelectorAll('[data-help-search]').forEach(function(item) {
                    var matches = term === '' || item.dataset.helpSearch.indexOf(term) !== -1;
                    item.style.display = matches ? '' : 'none';
                });
            });
        }

        document.querySelectorAll('.help-feedback-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.help-feedback-btn').forEach(function(b) {
                    b.disabled = true;
                });
                btn.classList.remove('btn-outline-primary', 'btn-outline-secondary');
                btn.classList.add('btn-primary');
            });
        });
    });
</script>
