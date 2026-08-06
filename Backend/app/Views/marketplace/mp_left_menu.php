<!-- <span class="moving-container">
    <span class="moving-text">
        Payment Gateway: The application is integrated with Stripe to process secure and reliable online payments.
    </span>
</span> -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
    href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
    rel="stylesheet" />
<link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:FILL@0..1&opsz@20..24&wght@300..700&GRAD@-25..200"
    rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/assets/marketplace/css/styles.css" rel="stylesheet" type="text/css" />


<div class="page-shell">
    <div class="catalog-layout-container">
        <section class="catalog-card">
            <div class="catalog-flex-row">
                <aside id="catalogSidebar" class="catalog-sidebar" aria-label="<?php echo lang('UI_Text.Filter_Sidebar'); ?>">
                    <button id="sidebarCloseBtn" class="sidebar-close-btn" aria-label="<?php echo lang('Buttons.Close_Filters'); ?>">
                        <span class="material-symbols-rounded" aria-hidden="true">close</span>
                    </button>

                    <div class="filter-section">
                        <h2 class="filter-section-title"><?php echo lang('UI_Text.Filter_By_Category'); ?></h2>
                        <nav class="filter-content" id="categoryList"></nav>
                    </div>

                    <div class="filter-section">
                        <h2 class="filter-section-title"><?php echo lang('UI_Text.Filter_By_Languages'); ?></h2>
                        <div class="filter-content" id="languageList"></div>
                    </div>

                    <div id="resetFilterContainer" class="reset-filter-container hidden">
                        <button id="resetFiltersBtn" class="reset-filter-btn" type="button">
                            <span class="icon icon-reset" aria-hidden="true"></span>
                            <span><?php echo lang('Buttons.Reset_Filters'); ?></span>
                        </button>
                    </div>
                </aside>

                <div id="sidebarOverlay" class="sidebar-overlay"></div>

                <main class="catalog-main-content">
                    <div class="top-toolbar-container">
                        <button id="mobileMenuBtn" class="mobile-menu-btn" aria-label="<?php echo lang('Buttons.Toggle_Filters'); ?>">
                            <span class="material-symbols-rounded" aria-hidden="true">menu</span>
                        </button>

                        <section class="search-toolbar">
                            <div class="view-toggle-group" role="group" aria-label="<?php echo lang('UI_Text.View_Mode'); ?>">
                                <button class="view-btn active" data-view="grid" type="button" aria-label="<?php echo lang('UI_Text.Grid_View'); ?>">
                                    <span class="material-symbols-rounded view-symbol" aria-hidden="true">grid_view</span>
                                </button>
                                <button class="view-btn" data-view="list" type="button" aria-label="<?php echo lang('UI_Text.List_View'); ?>">
                                    <span class="material-symbols-rounded view-symbol" aria-hidden="true">view_list</span>
                                </button>
                            </div>

                            <div class="search-field">
                                <span class="material-symbols-rounded search-symbol" aria-hidden="true">search</span>
                                <input id="searchInput" type="text" placeholder="<?php echo lang('UI_Text.Search_Courses'); ?>" autocomplete="off" />
                                <button id="clearSearchBtn" class="clear-search" type="button" aria-label="<?php echo lang('Buttons.Clear_Search'); ?>">✕</button>
                            </div>
                        </section>
                    </div>

                    <section class="content-stage">
                        <div id="gridView" class="course-grid"></div>
                        <div id="scrollLoader" class="scroll-loader hidden"><?php echo lang('UI_Text.Loading_More_Courses'); ?></div>

                        <div id="listView" class="tile-table-container hidden">
                            <table class="tile-table" aria-label="<?php echo lang('UI_Text.Course_List_Table'); ?>">
                                <thead>
                                    <tr>
                                        <th data-sort="id"><?php echo lang('UI_Text.Id'); ?> <span class="sort-indicator"></span></th>
                                        <th data-sort="title"><?php echo lang('UI_Text.Course_Title'); ?> <span class="sort-indicator"></span></th>
                                        <th data-sort="language"><?php echo lang('UI_Text.Language'); ?> <span class="sort-indicator"></span></th>
                                        <th data-sort="category"><?php echo lang('UI_Text.Categories'); ?> <span class="sort-indicator"></span></th>
                                        <th data-sort="duration"><?php echo lang('UI_Text.Duration'); ?> <span class="sort-indicator"></span></th>
                                    </tr>
                                </thead>
                                <tbody id="listTableBody"></tbody>
                            </table>

                            <div id="paginationBar" class="pagination-bar">
                                <span><?php echo lang('UI_Text.Items_Per_Page'); ?></span>
                                <select id="pageSizeSelect">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                </select>
                                <span id="rangeText" class="range-text"></span>
                                <button id="prevPageBtn" type="button" aria-label="<?php echo lang('Buttons.Previous_Page'); ?>">‹</button>
                                <button id="nextPageBtn" type="button" aria-label="<?php echo lang('Buttons.Next_Page'); ?>">›</button>
                            </div>
                        </div>

                        <div id="emptyState" class="empty-state hidden">
                            <div class="empty-state-card">
                                <div class="empty-icon">⌕</div>
                                <h3><?php echo lang('UI_Text.No_Results_Found'); ?></h3>
                                <p><?php echo lang('UI_Text.Try_Adjusting_Search'); ?></p>
                                <button id="emptyResetBtn" type="button"><?php echo lang('Buttons.Reset_Filters'); ?></button>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </section>
    </div>
</div>
<script>
    const BASE_URL = "<?= base_url(); ?>";
    const CSRF_NAME = "<?= csrf_token() ?>";
    const CSRF_HASH = "<?= csrf_hash() ?>";
</script>

<script src="<?php echo base_url(); ?>assets/assets/marketplace/js/script.js"></script>


        