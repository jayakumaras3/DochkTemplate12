<?php

namespace App\Controllers\Marketplace;

use App\Controllers\BaseController;
use App\Models\Marketplace\M_Dashboard_model;
use App\Models\Settings\Settings_model;

#[\AllowDynamicProperties]
class Catalog extends BaseController
{
    private $initialLoadCount = 8;
    private $scrollLoadCount = 4;

    public function __construct()
    {
        $this->is_session_available();
        $this->M_Dashboard_model = new M_Dashboard_model();
        $this->settings_model = new Settings_model();
    }

    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }
    }

    // Reads current category/language/search/view state from session, applying defaults on first visit.
    private function getFilterState()
    {
        return [
            'category' => session()->get('catalog_category') ?? '',
            'language' => session()->get('catalog_language') ?? 'All',
            'search'   => session()->get('catalog_search') ?? '',
            'view'     => session()->get('catalog_view') ?? 'grid',
        ];
    }

    private function saveFilterState($category, $language, $search, $view)
    {
        session()->set('catalog_category', $category);
        session()->set('catalog_language', $language);
        session()->set('catalog_search', $search);
        session()->set('catalog_view', $view);
    }

    // Rebuilds the sidebar language counts (category-scoped only, matching how the UI recalculates
    // counts on category change but not on language/search changes).
    private function buildLanguageCounts($client_id, $category)
    {
        $rows = $this->M_Dashboard_model->getCatalogLanguageCounts($client_id, $category);
        $counts = ['All' => 0];
        foreach ($rows as $row) {
            $lang = trim((string) $row['language']);
            if ($lang === '') {
                continue;
            }
            $counts[$lang] = (int) $row['langcount'];
            $counts['All'] += (int) $row['langcount'];
        }
        return $counts;
    }

    public function marketplace_view()
    {
        if ($response = $this->requireRole(['5', '44', '3'])) {
            return $response;
        }

        $client_id = session()->get('client');
        $marketplaceaccess = $this->settings_model->marketplaceaccess($client_id);
        if (empty($marketplaceaccess)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $state = $this->getFilterState();

        $data = [];
        $data['header_title'] = lang('UI_Text.Marketplace_Catalog');
        $data['header_link']  = 'marketplace/catalog/marketplace_view';
        $data['categories']   = $this->M_Dashboard_model->getSkills();
        $data['languageCounts'] = $this->buildLanguageCounts($client_id, $state['category']);
        $data['selectedCategory'] = $state['category'];
        $data['selectedLanguage'] = $state['language'];
        $data['selectedView']     = $state['view'];
        $data['searchText']       = $state['search'];
        $data['initialLoadCount'] = $this->initialLoadCount;

        $data['totalCount'] = $this->M_Dashboard_model->getCatalogCourseCount($client_id, $state['language'], $state['category'], $state['search']);

        if ($state['view'] === 'list') {
            $data['courses'] = $this->M_Dashboard_model->getCatalogCourses($client_id, $state['language'], $state['category'], $state['search'], '', 'asc', 10, 0);
            $data['pageSize'] = 10;
            $data['currentPage'] = 0;
        } else {
            $data['courses'] = $this->M_Dashboard_model->getCatalogCourses($client_id, $state['language'], $state['category'], $state['search'], '', 'asc', $this->initialLoadCount, 0);
        }

        echo view('templates/header_view', $data);
        echo view('marketplace/new/catalog_view', $data);
        echo view('templates/footer_view', $data);
    }

    // AJAX endpoint backing category/language/search/view/sort/pagination changes.
    // Returns the freshly-filtered batch as rendered HTML plus the counts needed to refresh the sidebar/toolbar.
    public function filter()
    {
        if ($response = $this->requireRole(['5', '44', '3'])) {
            return $response;
        }

        $client_id = session()->get('client');

        $category = trim((string) $this->request->getPost('category'));
        $language = trim((string) $this->request->getPost('language')) ?: 'All';
        $search   = trim((string) $this->request->getPost('search'));
        $view     = $this->request->getPost('view') === 'list' ? 'list' : 'grid';
        $sortColumn = (string) $this->request->getPost('sort_column');
        $sortDir    = (string) $this->request->getPost('sort_dir') ?: 'asc';
        $page       = max(0, (int) $this->request->getPost('page'));
        $pageSize   = (int) $this->request->getPost('page_size') ?: 10;

        $this->saveFilterState($category, $language, $search, $view);

        $totalCount = $this->M_Dashboard_model->getCatalogCourseCount($client_id, $language, $category, $search);
        $languageCounts = $this->buildLanguageCounts($client_id, $category);

        $response = [
            'view' => $view,
            'totalCount' => $totalCount,
            'languageCounts' => $languageCounts,
        ];

        if ($view === 'list') {
            $courses = $this->M_Dashboard_model->getCatalogCourses($client_id, $language, $category, $search, $sortColumn, $sortDir, $pageSize, $page * $pageSize);
            $response['rows'] = view('marketplace/new/catalog_list_rows', ['courses' => $courses, 'startRank' => $page * $pageSize + 1]);
            $response['from'] = $totalCount ? $page * $pageSize + 1 : 0;
            $response['to']   = min(($page + 1) * $pageSize, $totalCount);
        } else {
            $courses = $this->M_Dashboard_model->getCatalogCourses($client_id, $language, $category, $search, '', 'asc', $this->initialLoadCount, 0);
            $response['html'] = view('marketplace/new/catalog_grid_cards', ['courses' => $courses]);
            $response['hasMore'] = $totalCount > count($courses);
            $response['loadedCount'] = count($courses);
        }

        return $this->response->setJSON($response);
    }

    // AJAX endpoint for grid infinite scroll: appends the next batch using the filters already stored in session.
    public function load_more()
    {
        if ($response = $this->requireRole(['5', '44', '3'])) {
            return $response;
        }

        $client_id = session()->get('client');
        $state = $this->getFilterState();
        $offset = max(0, (int) $this->request->getGet('offset'));

        $courses = $this->M_Dashboard_model->getCatalogCourses($client_id, $state['language'], $state['category'], $state['search'], '', 'asc', $this->scrollLoadCount, $offset);
        $totalCount = $this->M_Dashboard_model->getCatalogCourseCount($client_id, $state['language'], $state['category'], $state['search']);

        return $this->response->setJSON([
            'html' => view('marketplace/new/catalog_grid_cards', ['courses' => $courses]),
            'hasMore' => $totalCount > ($offset + count($courses)),
            'loadedCount' => $offset + count($courses),
        ]);
    }
}
