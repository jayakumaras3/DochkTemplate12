<!-- Manrope + Material Symbols Rounded are now loaded once, site-wide, in templates/header_view.php -->
<link href="<?= base_url() ?>assets/assets/marketplace/css/styles.css?v=<?= @filemtime(FCPATH . 'assets/assets/marketplace/css/styles.css') ?: time() ?>" rel="stylesheet" type="text/css" />
<?php $accessmenu = session()->get('accessmenu');
$arrayaccessmenu  = array_map('intval', explode(',', $accessmenu));
?>
<style>
    /* Course tile design matches my_training's dashboard cards (see
       SCORM/scorm_dashboard/scorm_dashboard_view.php and favorites_view.php, which use the
       same class names/markup) so the catalog and the rest of the app feel consistent. */
    .course-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07), 0 2px 6px rgba(0, 0, 0, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background: var(--bg-surface);
    }

    .course-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .course-card-link-form {
        margin: 0;
        height: 100%;
    }

    .course-card-link {
        display: flex;
        flex-direction: column;
        width: 100%;
        height: 100%;
        padding: 0;
        border: 0;
        background: none;
        cursor: pointer;
        text-align: left;
    }

    .course-thumb {
        position: relative;
        height: 150px;
        overflow: hidden;
        background-color: #eef1f6;
    }

    .course-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .course-card-body {
        padding: 0.9rem 1rem 1rem;
    }

    .course-card-title {
        font-size: 13px;
        font-weight: 600;
        color: #343a40;
        margin: 0 0 0.35rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.6em;
    }

    [data-bs-theme="dark"] .course-card-title {
        color: #f3f7f9;
    }

    .course-card-duration {
        font-size: 11px;
        color: #98a6ad;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* The category/language filter panel (.catalog-sidebar-scroll, styles.css) scrolls with
       overflow-y: auto but the scrollbar itself is visually distracting next to the tile list,
       so it's hidden here while keeping the panel scrollable. */
    .catalog-sidebar-scroll {
        scrollbar-width: none;
        /* Firefox */
        -ms-overflow-style: none;
        /* IE/Edge */
    }

    .catalog-sidebar-scroll::-webkit-scrollbar {
        display: none;
        /* Chrome/Safari/Edge (Chromium) */
    }

    /* .filter-section's border-top is suppressed via :first-child, but the hidden (desktop)
       #sidebarCloseBtn button is actually the first child of the sidebar, so that rule never
       matched and "Filter by category" always showed a divider above it. */
    #sidebarCloseBtn+.filter-section {
        border-top: 0;
    }

    /* styles.css only ships a light palette (hardcoded #fff cards, dark navy text, etc.) with no
       dark-mode handling at all, so this page stayed white-on-white when the app theme switched
       to dark. Re-point its own custom properties plus every hardcoded color it uses instead of
       var() so the catalog matches the rest of the app's dark mode. */
    [data-bs-theme="dark"] {
        --bg-page: #1a2129;
        --bg-surface: #232b36;
        --bg-sidebar: #1f2733;
        --bg-primary-soft: rgba(52, 121, 246, 0.16);
        --text-title: #f3f7f9;
        --text-body: #c3cddc;
        --text-muted: #8c98a5;
        --line: #36404a;
    }

    [data-bs-theme="dark"] .catalog-main-content {
        background: #1a2129;
    }

    [data-bs-theme="dark"] .top-toolbar-container {
        background: #232b36;
    }

    [data-bs-theme="dark"] .filter-item:hover,
    [data-bs-theme="dark"] .language-item:hover {
        background: rgba(255, 255, 255, 0.06);
    }

    [data-bs-theme="dark"] .filter-left span:last-child {
        color: #dee2e6;
    }

    [data-bs-theme="dark"] .filter-icon,
    [data-bs-theme="dark"] .lang-count,
    [data-bs-theme="dark"] .view-btn,
    [data-bs-theme="dark"] .search-symbol,
    [data-bs-theme="dark"] .search-field input::placeholder,
    [data-bs-theme="dark"] .scroll-loader,
    [data-bs-theme="dark"] .range-text,
    [data-bs-theme="dark"] .empty-state-card p {
        color: #8c98a5;
    }

    [data-bs-theme="dark"] .reset-filter-btn {
        background: #232b36;
        color: #cedeef;
        border-color: #36404a;
    }

    [data-bs-theme="dark"] .reset-filter-btn:hover {
        background: #2c3644;
    }

    [data-bs-theme="dark"] .view-toggle-group {
        background: #1a2129;
    }

    [data-bs-theme="dark"] .view-btn.active {
        background: rgba(52, 121, 246, 0.2);
        color: #7fb0ff;
    }

    [data-bs-theme="dark"] .search-field {
        background: #1a2129;
        border-color: #36404a;
    }

    /* The <input> itself keeps the browser's default white field background regardless of its
       parent's background (styles.css never sets one), so it stayed white even once .search-field
       above went dark. */
    .search-field input {
        background: transparent;
    }

    [data-bs-theme="dark"] .course-card {
        background: #232b36;
    }

    [data-bs-theme="dark"] .course-thumb {
        background-color: #1a2129;
    }

    [data-bs-theme="dark"] .tile-table-container {
        background: #232b36;
    }

    [data-bs-theme="dark"] .tile-table th {
        background: #1a2129;
        color: #dee2e6;
    }

    [data-bs-theme="dark"] .tile-table th,
    [data-bs-theme="dark"] .tile-table td {
        border-bottom-color: #36404a;
    }

    [data-bs-theme="dark"] .tile-table td {
        color: #c3cddc;
    }

    [data-bs-theme="dark"] .pagination-bar span {
        color: #cedeef;
    }

    [data-bs-theme="dark"] .tile-table tbody tr:hover {
        background: #2c3644;
    }

    [data-bs-theme="dark"] .tile-table .title-cell {
        color: #f3f7f9;
    }

    [data-bs-theme="dark"] .pagination-bar {
        background: #1a2129;
    }

    [data-bs-theme="dark"] .pagination-bar select,
    [data-bs-theme="dark"] .pagination-bar button {
        background: #232b36;
        color: #cedeef;
        border-color: #36404a;
    }

    [data-bs-theme="dark"] .empty-state-card h3 {
        color: #f3f7f9;
    }

    .catalog-type-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .catalog-type-label {
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: var(--primary);
        white-space: nowrap;
    }

    .catalog-type-card {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 14px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--bg-surface);
        text-decoration: none;
        transition: box-shadow var(--ease), transform var(--ease);
    }

    .catalog-type-card:hover {
        box-shadow: var(--shadow-sm);
        transform: translateY(-1px);
    }

    .catalog-type-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .catalog-type-text {
        display: flex;
        flex-direction: column;
        line-height: 1.25;
    }

    .catalog-type-title {
        font-size: .82rem;
        font-weight: 700;
        color: var(--text-title);
        white-space: nowrap;
    }

    .catalog-type-subtitle {
        font-size: .68rem;
        color: var(--text-muted);
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .catalog-type-card {
        background: #232b36;
        border-color: #36404a;
    }

    @media (max-width: 1200px) {
        .catalog-type-group {
            display: none;
        }
    }

    @media (max-width: 991px) {
        .top-toolbar-container {
            flex-wrap: wrap;
        }

        .search-toolbar {
            order: 2;
        }
    }

    /* .view-btn has no display:flex of its own (styles.css), so the grid/list icon inside
       falls back to inline layout and isn't centered in the 34x34 button box. */
    .view-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title"><?= lang('UI_Text.Marketplace') ?></h4>
        </div>
    </div>
</div>

<div class="page-shell">
    <div class="catalog-layout-container">
        <div class="catalog-flex-row">
            <aside id="catalogSidebar" class="catalog-sidebar" aria-label="<?= lang('UI_Text.Filter_Sidebar') ?>">
                    <button id="sidebarCloseBtn" class="sidebar-close-btn" aria-label="<?= lang('Buttons.Close_Filters') ?>">
                        <span class="material-symbols-rounded" aria-hidden="true">close</span>
                    </button>

                    <div class="catalog-sidebar-scroll">

                    <?php
                    // Category names come straight from scorm_meta_category.description (real DB text,
                    // not app-defined lang keys), so the known set gets mapped to translated labels here.
                    // Anything outside that set (custom client categories) falls back to the raw DB text.
                    $categoryLangKeys = [
                        'business skills' => 'UI_Text.Category_Business_Skills',
                        'compliance' => 'UI_Text.Category_Compliance',
                        'dei (diversity, equity, and inclusion)' => 'UI_Text.Category_DEI',
                        'technology' => 'UI_Text.Category_Technology',
                        'safety' => 'UI_Text.Category_Safety',
                        'wellness' => 'UI_Text.Category_Wellness',
                        'healthcare' => 'UI_Text.Category_Healthcare',
                    ];
                    $categoryLabel = function ($skillname) use ($categoryLangKeys) {
                        $key = $categoryLangKeys[strtolower(trim($skillname))] ?? null;
                        return $key ? lang($key) : trim($skillname);
                    };

                    // Icon per known category, chosen to match the category's meaning at a glance.
                    // Custom client categories outside this set fall back to a generic icon.
                    $categoryIcons = [
                        'business skills' => 'business_center',
                        'compliance' => 'policy',
                        'dei (diversity, equity, and inclusion)' => 'diversity_3',
                        'technology' => 'computer',
                        'safety' => 'shield',
                        'wellness' => 'self_improvement',
                        'healthcare' => 'medical_services',
                    ];
                    $categoryIcon = function ($skillname) use ($categoryIcons) {
                        return $categoryIcons[strtolower(trim($skillname))] ?? 'school';
                    };
                    ?>
                    <div class="filter-section">
                        <h2 class="filter-section-title"><?= lang('UI_Text.Filter_By_Category') ?></h2>
                        <nav class="filter-content" id="categoryList">
                            <button type="button" class="filter-item <?= $selectedCategory === '' ? 'active' : '' ?>" data-category="">
                                <span class="filter-left">
                                    <span class="material-symbols-rounded filter-icon">apps</span>
                                    <span><?= lang('UI_Text.All') ?></span>
                                </span>
                            </button>
                            <?php foreach ($categories as $cat): ?>
                                <button type="button" class="filter-item <?= ((string) $selectedCategory === (string) $cat['sc_mcid']) ? 'active' : '' ?>" data-category="<?= esc($cat['sc_mcid']) ?>">
                                    <span class="filter-left">
                                        <span class="material-symbols-rounded filter-icon"><?= $categoryIcon($cat['skillname']) ?></span>
                                        <span><?= esc($categoryLabel($cat['skillname'])) ?></span>
                                    </span>
                                </button>
                            <?php endforeach; ?>
                        </nav>
                    </div>

                    <?php
                    // Same situation as categories: these come from scorm_courses.language (real DB
                    // text), so the known course-authoring language list is mapped to translated labels,
                    // falling back to the raw DB value for anything outside that set.
                    $languageLangKeys = [
                        'arabic' => 'UI_Text.Language_Name_Arabic',
                        'bahasa' => 'UI_Text.Language_Name_Bahasa',
                        'bahasa indonesian' => 'UI_Text.Language_Name_Bahasa_Indonesian',
                        'bahasa malaysian' => 'UI_Text.Language_Name_Bahasa_Malaysian',
                        'czech' => 'UI_Text.Language_Name_Czech',
                        'dutch' => 'UI_Text.Language_Name_Dutch',
                        'english' => 'UI_Text.Language_Name_English',
                        'french' => 'UI_Text.Language_Name_French',
                        'german' => 'UI_Text.Language_Name_German',
                        'hindi' => 'UI_Text.Language_Name_Hindi',
                        'italian' => 'UI_Text.Language_Name_Italian',
                        'japanese' => 'UI_Text.Language_Name_Japanese',
                        'kazakh' => 'UI_Text.Language_Name_Kazakh',
                        'khmer' => 'UI_Text.Language_Name_Khmer',
                        'korean' => 'UI_Text.Language_Name_Korean',
                        'macedonian' => 'UI_Text.Language_Name_Macedonian',
                        'marathi' => 'UI_Text.Language_Name_Marathi',
                        'polish' => 'UI_Text.Language_Name_Polish',
                        'portuguese' => 'UI_Text.Language_Name_Portuguese',
                        'russian' => 'UI_Text.Language_Name_Russian',
                        'simplified chinese' => 'UI_Text.Language_Name_Simplified_Chinese',
                        'spanish' => 'UI_Text.Language_Name_Spanish',
                        'swedish' => 'UI_Text.Language_Name_Swedish',
                        'traditional chinese' => 'UI_Text.Language_Name_Traditional_Chinese',
                        'turkish' => 'UI_Text.Language_Name_Turkish',
                        'vietnamese' => 'UI_Text.Language_Name_Vietnamese',
                    ];
                    $languageLabel = function ($name) use ($languageLangKeys) {
                        $key = $languageLangKeys[strtolower(trim($name))] ?? null;
                        return $key ? lang($key) : trim($name);
                    };
                    ?>
                    <div class="filter-section">
                        <h2 class="filter-section-title"><?= lang('UI_Text.Filter_By_Languages') ?></h2>
                        <div class="filter-content" id="languageList">
                            <?php foreach ($languageCounts as $lang => $count):
                                $value = ($lang === 'All') ? 'All' : $lang;
                            ?>
                                <button type="button" class="language-item <?= ($selectedLanguage === $value) ? 'active' : '' ?>" data-language="<?= esc($value) ?>">
                                    <span><?= ($lang === 'All') ? lang('UI_Text.All') : esc($languageLabel($lang)) ?></span>
                                    <span class="lang-count">(<?= (int) $count ?>)</span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div id="resetFilterContainer" class="reset-filter-container <?= ($selectedCategory === '' && $selectedLanguage === 'All' && $searchText === '') ? 'hidden' : '' ?>">
                        <button id="resetFiltersBtn" class="reset-filter-btn" type="button">
                            <span class="icon icon-reset" aria-hidden="true"></span>
                            <span><?= lang('Buttons.Reset_Filters') ?></span>
                        </button>
                    </div>

                    </div>
                </aside>

                <div id="sidebarOverlay" class="sidebar-overlay"></div>

                <main class="catalog-main-content">
                    <div class="top-toolbar-container">
                        <button id="mobileMenuBtn" class="mobile-menu-btn" aria-label="<?= lang('Buttons.Toggle_Filters') ?>">
                            <span class="material-symbols-rounded" aria-hidden="true">menu</span>
                        </button>

                        <div class="catalog-type-group">
                            <span class="catalog-type-label"><?= lang('UI_Text.Catalog_Type') ?></span>
                            <a href="<?= base_url('ang/coursecatalog') ?>" target="_blank" rel="noopener" class="catalog-type-card">
                                <span class="catalog-type-icon bg-soft-primary text-primary">
                                    <i class="mdi mdi-bookshelf"></i>
                                </span>
                                <span class="catalog-type-text">
                                    <span class="catalog-type-title"><?= lang('UI_Text.Complete_Course_Library') ?></span>
                                    <span class="catalog-type-subtitle"><?= lang('UI_Text.Browse_Full_Course_List') ?></span>
                                </span>
                            </a>
                            <a href="<?= base_url('ang/sme-catalog') ?>" target="_blank" rel="noopener" class="catalog-type-card">
                                <span class="catalog-type-icon bg-soft-warning text-warning">
                                    <i class="mdi mdi-bank"></i>
                                </span>
                                <span class="catalog-type-text">
                                    <span class="catalog-type-title"><?= lang('UI_Text.SME_Learning_Pathways') ?></span>
                                    <span class="catalog-type-subtitle"><?= lang('UI_Text.Structured_Learning_By_Level') ?></span>
                                </span>
                            </a>
                        </div>

                        <section class="search-toolbar">
                            <div class="view-toggle-group" role="group" aria-label="<?= lang('UI_Text.View_Mode') ?>">
                                <button class="view-btn <?= $selectedView === 'grid' ? 'active' : '' ?>" data-view="grid" type="button" aria-label="<?= lang('UI_Text.Grid_View') ?>">
                                    <span class="material-symbols-rounded view-symbol" aria-hidden="true">grid_view</span>
                                </button>
                                <button class="view-btn <?= $selectedView === 'list' ? 'active' : '' ?>" data-view="list" type="button" aria-label="<?= lang('UI_Text.List_View') ?>">
                                    <span class="material-symbols-rounded view-symbol" aria-hidden="true">view_list</span>
                                </button>
                            </div>

                            <div class="search-field">
                                <span class="material-symbols-rounded search-symbol" aria-hidden="true">search</span>
                                <input id="searchInput" type="text" value="<?= esc($searchText) ?>" placeholder="<?= lang('UI_Text.Search_Courses') ?>" autocomplete="off" />
                                <button id="clearSearchBtn" class="clear-search <?= $searchText !== '' ? 'visible' : '' ?>" type="button" aria-label="<?= lang('Buttons.Clear_Search') ?>">✕</button>
                            </div>
                        </section>
                    </div>

                    <section class="content-stage">
                        <div id="gridView" class="course-grid <?= $selectedView === 'list' ? 'hidden' : '' ?>">
                            <?= view('marketplace/new/catalog_grid_cards', ['courses' => $courses]) ?>
                        </div>
                        <div id="scrollLoader" class="scroll-loader hidden"><?= lang('UI_Text.Loading_More_Courses') ?></div>

                        <div id="listView" class="tile-table-container <?= $selectedView === 'grid' ? 'hidden' : '' ?>">
                            <table class="tile-table" aria-label="<?= lang('UI_Text.Course_List_Table') ?>">
                                <thead>
                                    <tr>
                                        <th data-sort="id"><?= lang('UI_Text.Id') ?> <span class="sort-indicator"></span></th>
                                        <th data-sort="title"><?= lang('UI_Text.Course_Title') ?> <span class="sort-indicator"></span></th>
                                        <th data-sort="language"><?= lang('UI_Text.Language') ?> <span class="sort-indicator"></span></th>
                                        <th data-sort="duration"><?= lang('UI_Text.Duration') ?> <span class="sort-indicator"></span></th>
                                        <th><?= lang('UI_Text.Action') ?></th>
                                    </tr>
                                </thead>
                                <tbody id="listTableBody">
                                    <?php if ($selectedView === 'list') { ?>
                                        <?= view('marketplace/new/catalog_list_rows', ['courses' => $courses, 'startRank' => 1]) ?>
                                    <?php } ?>
                                </tbody>
                            </table>

                            <div id="paginationBar" class="pagination-bar">
                                <span><?= lang('UI_Text.Items_Per_Page') ?></span>
                                <select id="pageSizeSelect">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                </select>
                                <span id="rangeText" class="range-text"></span>
                                <button id="prevPageBtn" type="button" aria-label="<?= lang('UI_Text.Previous') ?>">‹</button>
                                <button id="nextPageBtn" type="button" aria-label="<?= lang('UI_Text.Next') ?>">›</button>
                            </div>
                        </div>

                        <div id="emptyState" class="empty-state hidden">
                            <div class="empty-state-card">
                                <div class="empty-icon">⌕</div>
                                <h3><?= lang('UI_Text.No_Results_Found') ?></h3>
                                <p><?= lang('UI_Text.Try_Adjusting_Search') ?></p>
                                <button id="emptyResetBtn" type="button"><?= lang('Buttons.Reset_Filters') ?></button>
                            </div>
                        </div>
                    </section>
                </main>
        </div>
    </div>
</div>

<script>
    const BASE_URL = "<?= base_url(); ?>";
    const CSRF_NAME = "<?= csrf_token() ?>";
    const CSRF_HASH = "<?= csrf_hash() ?>";
    const RANGE_OF_TEXT = <?= json_encode(lang('UI_Text.Of')) ?>;

    const state = {
        category: <?= json_encode((string) $selectedCategory) ?>,
        language: <?= json_encode((string) $selectedLanguage) ?>,
        search: <?= json_encode((string) $searchText) ?>,
        view: <?= json_encode((string) $selectedView) ?>,
        sortColumn: "",
        sortDirection: "asc",
        page: 0,
        pageSize: 10,
        totalCount: <?= (int) $totalCount ?>,
        loadedCount: <?= count($courses) ?>,
        listLoaded: <?= $selectedView === 'list' ? 'true' : 'false' ?>,
    };

    const els = {
        categoryList: document.getElementById("categoryList"),
        languageList: document.getElementById("languageList"),
        resetFilterContainer: document.getElementById("resetFilterContainer"),
        resetFiltersBtn: document.getElementById("resetFiltersBtn"),
        emptyResetBtn: document.getElementById("emptyResetBtn"),
        mobileMenuBtn: document.getElementById("mobileMenuBtn"),
        sidebarCloseBtn: document.getElementById("sidebarCloseBtn"),
        sidebar: document.getElementById("catalogSidebar"),
        sidebarOverlay: document.getElementById("sidebarOverlay"),
        gridView: document.getElementById("gridView"),
        listView: document.getElementById("listView"),
        listTableBody: document.getElementById("listTableBody"),
        emptyState: document.getElementById("emptyState"),
        pageSizeSelect: document.getElementById("pageSizeSelect"),
        prevPageBtn: document.getElementById("prevPageBtn"),
        nextPageBtn: document.getElementById("nextPageBtn"),
        rangeText: document.getElementById("rangeText"),
        searchInput: document.getElementById("searchInput"),
        clearSearchBtn: document.getElementById("clearSearchBtn"),
        scrollLoader: document.getElementById("scrollLoader"),
    };

    function debounce(fn, wait = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => fn(...args), wait);
        };
    }

    function hasActiveFilters() {
        return state.category !== "" || state.language !== "All" || state.search.trim() !== "";
    }

    function toggleScrollLoader(show) {
        if (!els.scrollLoader) return;
        els.scrollLoader.classList.toggle("hidden", !show);
    }

    function renderToolbarState() {
        els.clearSearchBtn.classList.toggle("visible", state.search.trim().length > 0);
        document.querySelectorAll(".view-btn").forEach((button) => {
            button.classList.toggle("active", button.dataset.view === state.view);
        });
        els.resetFilterContainer.classList.toggle("hidden", !hasActiveFilters());
        document.querySelectorAll(".filter-item").forEach((button) => {
            button.classList.toggle("active", button.dataset.category === state.category);
        });
        document.querySelectorAll(".language-item").forEach((button) => {
            button.classList.toggle("active", button.dataset.language === state.language);
        });
    }

    function updateLanguageCounts(counts) {
        document.querySelectorAll(".language-item").forEach((button) => {
            const key = button.dataset.language === "All" ? "All" : button.dataset.language;
            const countEl = button.querySelector(".lang-count");
            if (countEl && Object.prototype.hasOwnProperty.call(counts, key)) {
                countEl.textContent = `(${counts[key]})`;
            }
        });
    }

    function toggleViews() {
        const noResults = state.totalCount === 0;
        els.emptyState.classList.toggle("hidden", !noResults);

        if (noResults) {
            els.gridView.classList.add("hidden");
            els.listView.classList.add("hidden");
            toggleScrollLoader(false);
            return;
        }

        if (state.view === "grid") {
            els.gridView.classList.remove("hidden");
            els.listView.classList.add("hidden");
        } else {
            els.gridView.classList.add("hidden");
            els.listView.classList.remove("hidden");
            // The scroll loader is a grid-infinite-scroll concept; it has no place above the
            // table, but nothing hid it here, so it stayed visible after switching from grid.
            toggleScrollLoader(false);
        }
    }

    async function postFilter(extra = {}) {
        const body = new URLSearchParams({
            category: state.category,
            language: state.language,
            search: state.search,
            view: state.view,
            sort_column: state.sortColumn,
            sort_dir: state.sortDirection,
            page: state.page,
            page_size: state.pageSize,
            [CSRF_NAME]: CSRF_HASH,
            ...extra,
        });

        const response = await fetch(BASE_URL + "marketplace/catalog/filter", {
            method: "POST",
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            body,
        });

        return response.json();
    }

    async function refresh() {
        const data = await postFilter();
        state.totalCount = data.totalCount;
        updateLanguageCounts(data.languageCounts);
        renderToolbarState();
        toggleViews();

        if (state.totalCount === 0) {
            return;
        }

        if (state.view === "grid") {
            els.gridView.innerHTML = data.html;
            state.loadedCount = data.loadedCount;
            toggleScrollLoader(data.hasMore);
        } else {
            els.listTableBody.innerHTML = data.rows;
            els.rangeText.textContent = `${data.from} - ${data.to} ${RANGE_OF_TEXT} ${state.totalCount}`;
            els.prevPageBtn.disabled = state.page === 0;
            els.nextPageBtn.disabled = data.to >= state.totalCount;
            state.listLoaded = true;

            document.querySelectorAll(".tile-table th[data-sort]").forEach((header) => {
                header.classList.remove("sort-asc", "sort-desc");
                if (header.dataset.sort === state.sortColumn) {
                    header.classList.add(state.sortDirection === "asc" ? "sort-asc" : "sort-desc");
                }
            });
        }
    }

    function onCategorySelect(category) {
        state.category = category;
        state.language = "All";
        state.page = 0;
        refresh();
    }

    function onLanguageSelect(language) {
        state.language = language;
        state.page = 0;
        refresh();
    }

    function setViewMode(mode) {
        if (state.view === mode) return;
        state.view = mode;
        state.page = 0;
        refresh();
    }

    function toggleSort(col) {
        if (state.sortColumn === col) {
            state.sortDirection = state.sortDirection === "asc" ? "desc" : "asc";
        } else {
            state.sortColumn = col;
            state.sortDirection = "asc";
        }
        state.page = 0;
        refresh();
    }

    function resetFilters() {
        state.category = "";
        state.language = "All";
        state.search = "";
        state.sortColumn = "";
        state.sortDirection = "asc";
        state.page = 0;
        els.searchInput.value = "";
        refresh();
    }

    let loadMoreInFlight = false;

    async function loadMoreOnScroll() {
        if (loadMoreInFlight || state.view !== "grid" || state.totalCount <= state.loadedCount) return;

        loadMoreInFlight = true;
        toggleScrollLoader(true);
        try {
            const response = await fetch(BASE_URL + "marketplace/catalog/load_more?offset=" + state.loadedCount, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                },
            });
            const data = await response.json();

            els.gridView.insertAdjacentHTML("beforeend", data.html);
            state.loadedCount = data.loadedCount;
            toggleScrollLoader(data.hasMore);
        } finally {
            loadMoreInFlight = false;
        }
    }

    let scrollTicking = false;

    function onScroll() {
        if (scrollTicking) return;
        scrollTicking = true;
        window.requestAnimationFrame(() => {
            scrollTicking = false;
            if (state.view !== "grid") return;
            const scrollPosition = window.innerHeight + window.scrollY;
            const pageHeight = document.documentElement.scrollHeight || document.body.scrollHeight;
            if ((scrollPosition / pageHeight) * 100 >= 70) {
                loadMoreOnScroll();
            }
        });
    }

    function toggleSidebar(open) {
        els.sidebar.classList.toggle("sidebar-open", open);
        els.sidebarOverlay.classList.toggle("open", open);
        document.body.style.overflow = open ? "hidden" : "";
    }

    function attachEvents() {
        els.categoryList.addEventListener("click", (event) => {
            const button = event.target.closest(".filter-item");
            if (button) onCategorySelect(button.dataset.category);
        });

        els.languageList.addEventListener("click", (event) => {
            const button = event.target.closest(".language-item");
            if (button) onLanguageSelect(button.dataset.language);
        });

        const onSearch = debounce(() => {
            state.search = els.searchInput.value;
            state.page = 0;
            refresh();
        });
        els.searchInput.addEventListener("input", onSearch);

        els.clearSearchBtn.addEventListener("click", resetFilters);
        els.resetFiltersBtn.addEventListener("click", resetFilters);
        els.emptyResetBtn.addEventListener("click", resetFilters);

        document.querySelectorAll(".view-btn").forEach((button) => {
            button.addEventListener("click", () => setViewMode(button.dataset.view));
        });

        document.querySelectorAll(".tile-table th[data-sort]").forEach((header) => {
            header.addEventListener("click", () => toggleSort(header.dataset.sort));
        });

        els.pageSizeSelect.addEventListener("change", (event) => {
            state.pageSize = Number(event.target.value);
            state.page = 0;
            refresh();
        });

        els.prevPageBtn.addEventListener("click", () => {
            if (state.page > 0) {
                state.page -= 1;
                refresh();
            }
        });

        els.nextPageBtn.addEventListener("click", () => {
            const maxPage = Math.max(0, Math.ceil(state.totalCount / state.pageSize) - 1);
            if (state.page < maxPage) {
                state.page += 1;
                refresh();
            }
        });

        els.mobileMenuBtn.addEventListener("click", () => toggleSidebar(true));
        els.sidebarCloseBtn.addEventListener("click", () => toggleSidebar(false));
        els.sidebarOverlay.addEventListener("click", () => toggleSidebar(false));

        window.addEventListener("resize", () => {
            if (window.innerWidth > 767) toggleSidebar(false);
        });

        window.addEventListener("scroll", onScroll, {
            passive: true
        });
    }

    attachEvents();
    renderToolbarState();
    toggleViews();

    if (state.view === "list" && !state.listLoaded) {
        refresh();
    } else if (state.view === "list") {
        els.rangeText.textContent = state.totalCount ? `1 - ${Math.min(state.pageSize, state.totalCount)} ${RANGE_OF_TEXT} ${state.totalCount}` : `0 - 0 ${RANGE_OF_TEXT} 0`;
        els.prevPageBtn.disabled = true;
        els.nextPageBtn.disabled = state.totalCount <= state.pageSize;
    } else {
        toggleScrollLoader(state.totalCount > state.loadedCount);
    }
</script>