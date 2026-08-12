<?php

namespace App\Models\SCORM;

use ZipArchive;
use CodeIgniter\Model;

class Scorm_page_model extends Model
{
    protected $table = 'page';
    protected $primaryKey = 'page_id';
    protected $allowedFields = ['page_id', 'page_name', 'type', 'page_number', 'status', 'createdby', 'createdon', 'last_updated_by', 'last_updated_on', 'fk_course_id', 'sub_page_main'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];


    public function getpageDetails($course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('page as p');
        $builder->select('p.*,ANY_VALUE(pk.folder),ANY_VALUE(u.name) as pkname,ANY_VALUE(pt.transcript),ANY_VALUE(u1.name) as ptname,ANY_VALUE(v.filename) as video,ANY_VALUE(u2.name) as vname,ANY_VALUE(vt.filename) as vtt,ANY_VALUE(u3.name) as vtname,ANY_VALUE(q.question),ANY_VALUE(u4.name) as qname');
        $builder->join('page_package as pk', 'pk.page_id = p.page_id and pk.status =1', 'left');
        $builder->join('page_transcript as pt', 'pt.page_id = p.page_id and pt.status =1', 'left');
        $builder->join('page_video_vtt as v', 'v.page_id = p.page_id and v.type = 1 and v.status =1', 'left');
        $builder->join('page_video_vtt as vt', 'vt.page_id = p.page_id and vt.type = 2 and vt.status =1', 'left');
        $builder->join('assessment_questions as q', 'q.page_id  = p.page_id and q.status =1', 'left');
        $builder->join('users as u', 'u.id_user = pk.createdby', 'left');
        $builder->join('users as u1', 'u1.id_user = pt.createdby', 'left');
        $builder->join('users as u2', 'u2.id_user = v.createdby', 'left');
        $builder->join('users as u3', 'u3.id_user = vt.createdby', 'left');
        $builder->join('users as u4', 'u4.id_user = q.createdby', 'left');
        $builder->groupby('p.page_id');
        $builder->where('p.fk_course_id', $course_id);
        $builder->where('p.status !=', '0');
        $builder->orderBy('page_number');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    // Lighter-weight version of getpageDetails() for callers that only need the plain page
    // row (id/number/name/type/status/sub_page_main) - e.g. the course builder left menu,
    // which never reads the joined transcript/video/vtt/question columns getpageDetails()
    // computes via 5 LEFT JOINs (each further joined to users) + a GROUP BY over every page
    // in the course. Keep using getpageDetails() wherever those joined columns are actually
    // read (e.g. storyboarding()).
    public function getpageDetailsLite($course_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.page_id, p.page_name, p.type, p.page_number, p.status, p.sub_page_main, p.fk_course_id');
        $builder->where('p.fk_course_id', $course_id);
        $builder->where('p.status !=', '0');
        $builder->orderBy('page_number');
        return $builder->get()->getResultArray();
    }

    public function getAssessmentquestion($course_id)
    {
        $builder = $this->db->table('assessment_questions as q');
        $builder->select('q.page_id,q.q_id as question_id');
        $builder->where('q.scourse_id', $course_id);
        $builder->where('q.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function addpagedetails($newdata)
    {
        $builder = $this->db->table('page');
        if (!$builder->insert($newdata)) {
            return false;
        }

        $data['page_id'] = $this->db->insertID();
        if (!empty($data['page_id'])) {
            $builder = $this->db->table('scorm_courses as sc');
            $builder->select('sc.*');
            $builder->where('sc.scourse_id', $newdata['fk_course_id']);
            $builder->where('sc.status', '1');
            $coursedata = $builder->get()->getResultArray();
            if ($newdata['type'] == 5 || $newdata['type'] == 6) {

                $postdata = [
                    'scourse_id' => $newdata['fk_course_id'],
                    'page_id' => $data['page_id'],
                    'quiz_type' => 'SCQ',
                    'question' => 'Question',
                    // Default feedback so a freshly-created SCQ/MCQ question isn't blank until
                    // someone fills in the Feedback & Explanations panel - correct/incorrect2/
                    // incorrect map to "Correct"/"Wrong - Attempt 1"/"Wrong - Attempt 2" there.
                    'correct' => 'Great job! That\'s the correct answer.',
                    'incorrect2' => 'Not quite. Take another look at the options and try again.',
                    'incorrect' => 'That\'s not the correct answer. The correct option is now highlighted for you to review.',
                    'status' => '1',
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                if (isset($postdata)) {
                    $builder = $this->db->table('assessment_questions');
                    $builder->insert($postdata);
                    $data['question_id'] = $this->db->insertID();

                    // Every SCQ/MCQ question needs at least one option, and at least one
                    // correct option, to be answerable - seed one with placeholder text marked
                    // correct rather than leaving the question unanswerable until the user adds
                    // one themselves. Mirrors the same "at least one correct" rule enforced in
                    // Assessment_training_model::updateoptioneditableformat() when the user
                    // later edits/deletes options.
                    $optiondata = [
                        'scourse_id' => $newdata['fk_course_id'],
                        'question_id' => $data['question_id'],
                        'values' => 'Option 1',
                        'truefalse' => 1,
                        'status' => 1,
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time(),
                    ];
                    $this->db->table('assessment_options')->insert($optiondata);
                }

                // if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $coursedata[0]['scourse_id'] . '/' . $coursedata[0]['createdon'] . '/shared/assets/content/english/pages/' . $data['page_id'])) {
                //     mkdir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $coursedata[0]['scourse_id'] . '/' . $coursedata[0]['createdon'] . '/shared/assets/content/english/pages/' . $data['page_id'], 0777, true);
                // }
                // if ($newdata['type'] == 4) {
                //     $choice = 'quiz_choice.zip';
                // }
                // if ($newdata['type'] == 5) {
                //     $choice = 'single_choice.zip';
                // }
                // if ($newdata['type'] == 6) {
                //     $choice = 'multi_choice.zip';
                // }
                // $zipFilePath = FCPATH . 'assets/assets/uploads/template_container/' . $choice;

                // $targetDir = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $coursedata[0]['scourse_id'] . '/' . $coursedata[0]['createdon'] . '/shared/assets/content/english/pages/' . $data['page_id'];

                // // Check if the zip file exists
                // if (file_exists($zipFilePath)) {
                //     // Open the zip file
                //     $zip = new ZipArchive();
                //     $zip->open($zipFilePath);

                //     // Extract the contents to the target directory
                //     $zip->extractTo($targetDir);

                //     // Close the zip file
                //     $zip->close();
                // } else {
                //     echo 'The zip file does not exist.';
                // }
            }
            return $data;
        }

        return false;
    }
    function getCoursedata($course_id)
    {
        $builder = $this->db->table('scorm_courses as sc');
        $builder->select('sc.*');
        $builder->where('sc.scourse_id', $course_id);
        $builder->where('sc.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function editpagedetails($newdata, $page_id)
    {
        $builder = $this->db->table('page as p');
        $builder->where('p.page_id', $page_id);
        if ($builder->update($newdata)) {
            return ['page_id' => $page_id];
        }

        return false;
    }
    function edituploadpagedetails($newdata, $page_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('page as p');
        $builder->where('p.page_id', $page_id);
        $builder->update($newdata);
        $data['page_id'] = $db->insertID();
        if (!empty($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "Error";
        }
        return $data;
    }

    // function getPagedetailsofcourse($scourse_id)
    // {
    //     $builder = $this->db->table('page as p');
    //     $builder->select('p.page_id as name ,p.page_name as title,p.page_name as header,IFNULL(pt.transcript, "") AS transcript');
    //     $builder->join('page_transcript as pt', 'pt.page_id = p.page_id and pt.language =1 and pt.status =1', 'left');
    //     $builder->join('scorm_courses as c', 'c.scourse_id = p.fk_course_id and c.status =1', 'left');
    //     $builder->where('p.fk_course_id', $scourse_id);
    //     // $builder->where('pt.language', 1);
    //     $builder->where('p.status !=', 0);
    //     $builder->orderBy('p.page_number');
    //     $data = $builder->get()->getResultArray();
    //     // echo '<pre>';
    //     // print_r( $data);
    //     // exit();
    //     return $data;
    // }
    function getPagedetailsofcourse($scourse_id)
    {
        $this->db->query("SET SESSION group_concat_max_len = 1000000");
        $builder = $this->db->table('page as p');
        $builder->select('p.page_id,c.language,p.page_name as title,p.page_name as header, GROUP_CONCAT(IFNULL(pt.audio, " ")  ORDER BY pt.page_sequense SEPARATOR "|") AS transcript,c.createdon,p.type,ANY_VALUE(v.filename) as filename,p.page_number,c.createdon,c.course_name,p.content,p.page_image,p.image_alt');
        $builder->join('page_content as pt', 'pt.page_id = p.page_id and pt.status =1', 'left');
        $builder->join('page_video_vtt as v', 'v.page_id = p.page_id and v.type=1 and v.status =1', 'left');
        $builder->join('scorm_courses as c', 'c.scourse_id = p.fk_course_id and c.status =1', 'left');
        $builder->where('p.fk_course_id', $scourse_id);
        // $builder->where('pt.language', 1);
        $builder->where('p.status !=', 0);
        $builder->groupBy('p.page_id');
        $builder->orderBy('p.page_number');
        $data = $builder->get()->getResultArray();
        // echo '<pre>';
        // print_r( $data);
        // // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getAllpagedetails($scourse_id)
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('p.page_id,p.type,v.filename,p.page_number,c.createdon');
        $builder->join('page as p', 'p.fk_course_id = c.scourse_id', 'left');
        $builder->join('page_video_vtt as v', 'v.page_id = p.page_id and v.type=1', 'left');
        $builder->where('c.scourse_id', $scourse_id);
        $builder->where('p.status !=', 0);
        $data = $builder->get()->getResultArray();
        // echo '<pre>';
        // print_r($data);
        // // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function gettranscriptpagedata($page_id)
    {
        $builder = $this->db->table('page_transcript as p');
        $builder->select('p.*');
        $builder->where('p.page_id', $page_id);
        $builder->where('p.status', 1);
        $data = $builder->get()->getResultArray();
        //  print_r( $data);
        // exit();
        return $data;
    }

    function getpagedata_number($page_number, $course_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.*,c.createdon,c.course_name');
        $builder->join('scorm_courses as c', 'c.scourse_id = p.fk_course_id and c.status !=0', 'left');
        $builder->where('p.page_number', $page_number);
        $builder->where('p.fk_course_id', $course_id);
        $builder->where('p.status !=', 0);

        $data = $builder->get()->getResultArray();
        //     echo $this->db->getLastQuery();
        // exit();
        return $data;
    }

    // Same shape as getpagedata_number() (adds c.createdon/c.course_name, which callers like
    // Editor::page_content() rely on) but keyed by the unique page_id instead of page_number -
    // page_number is not guaranteed unique within a course (two pages can share one, e.g. right
    // after a manual edit collides with an existing page), so looking it up by number alone can
    // resolve to the wrong page. Prefer this whenever a page_id is already known.
    function getpagedata_by_id($page_id, $course_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.*,c.createdon,c.course_name');
        $builder->join('scorm_courses as c', 'c.scourse_id = p.fk_course_id and c.status !=0', 'left');
        $builder->where('p.page_id', $page_id);
        $builder->where('p.fk_course_id', $course_id);
        $builder->where('p.status !=', 0);

        return $builder->get()->getResultArray();
    }

    function getpagedata($page_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.*,c.createdon,c.type as course_type');
        $builder->join('scorm_courses as c', 'c.scourse_id = p.fk_course_id and c.status =1', 'left');
        $builder->where('p.page_id', $page_id);
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        return $data;
    }
    function getpagecontent($page_number, $fk_course_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.page_id');
        $builder->where('p.fk_course_id', $fk_course_id);
        $builder->where('p.page_number', $page_number);
        $builder->where('p.status !=', 0);
        $pagedata = $builder->get()->getResultArray();
        if (!empty($pagedata)) {
            $builder = $this->db->table('page_content as p');
            $builder->select('p.*');
            $builder->where('p.page_id', $pagedata[0]['page_id']);
            $builder->where('p.status !=', 0);
            $builder->orderBy('page_sequense');
            $data = $builder->get()->getResultArray();
        }
        return $data;
    }

    // Same as getpagecontent() but skips its internal page_number -> page_id lookup - useful
    // when the caller already resolved a specific page_id (see getpagedata_by_id() above),
    // since re-deriving it from page_number would reintroduce the same ambiguity when two
    // pages share a number.
    function getpagecontentbyid($page_id)
    {
        $builder = $this->db->table('page_content as p');
        $builder->select('p.*');
        $builder->where('p.page_id', $page_id);
        $builder->where('p.status !=', 0);
        $builder->orderBy('page_sequense');
        return $builder->get()->getResultArray();
    }

    function getSubpagecontent($page_id, $fk_course_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.*');
        $builder->where('p.fk_course_id', $fk_course_id);
        $builder->where('p.sub_page_main', $page_id);
        $builder->where('p.status !=', 0);
        $builder->orderBy('p.page_number');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getNextSubpageNumber($parentPageNumber, $courseId)
    {
        $builder = $this->db->table('page');
        $builder->selectMax('page_number', 'last_page_number');
        $builder->where('fk_course_id', $courseId);
        $builder->where('sub_page_main', $parentPageNumber);
        $builder->where('status !=', 0);
        $row = $builder->get()->getRowArray();

        $lastPageNumber = $row['last_page_number'] ?? null;
        $nextPageNumber = $lastPageNumber === null
            ? (float) $parentPageNumber + 0.01
            : (float) $lastPageNumber + 0.01;
        $nextPageNumber = round($nextPageNumber, 2);

        // page_number has two decimal places, so a parent can hold suffixes .01 through .99.
        if ($nextPageNumber >= ((float) $parentPageNumber + 1)) {
            return null;
        }

        return number_format($nextPageNumber, 2, '.', '');
    }

    /**
     * Serializes page-number mutations for a course. Call only inside a transaction.
     */
    public function lockCourseForNumbering($courseId)
    {
        $table = $this->db->escapeIdentifiers($this->db->prefixTable('scorm_courses'));
        $sql = 'SELECT scourse_id FROM ' . $table . ' WHERE scourse_id = ?';
        if (in_array($this->db->getPlatform(), ['MySQLi', 'Postgre'], true)) {
            $sql .= ' FOR UPDATE';
        }

        $query = $this->db->query($sql, [$courseId]);
        return $query !== false && $query->getRowArray() !== null;
    }

    private function hasDuplicateMainPageNumbers($courseId)
    {
        return $this->db->table('page')
            ->select('page_number')
            ->where('fk_course_id', $courseId)
            ->groupStart()
            ->where('sub_page_main', 0)
            ->orWhere('sub_page_main', null)
            ->groupEnd()
            ->where('status !=', 0)
            ->groupBy('page_number')
            ->having('COUNT(*) > 1', null, false)
            ->limit(1)
            ->get()
            ->getRowArray() !== null;
    }

    /**
     * Creates a child using the persisted parent and the next available decimal suffix.
     */
    public function addSubpage($parentPageId, $courseId, array $newdata)
    {
        $this->db->transStart();

        if (!$this->lockCourseForNumbering($courseId)) {
            $this->db->transRollback();
            return false;
        }
        if ($this->hasDuplicateMainPageNumbers($courseId)) {
            $this->db->transRollback();
            return false;
        }

        $parentPage = $this->db->table('page')
            ->select('page_id, fk_course_id, page_number, sub_page_main, status')
            ->where('page_id', $parentPageId)
            ->get()
            ->getRowArray();

        if (empty($parentPage)
            || (string) $parentPage['fk_course_id'] !== (string) $courseId
            || (int) $parentPage['status'] === 0
            || (float) $parentPage['sub_page_main'] !== 0.0) {
            $this->db->transRollback();
            return false;
        }

        $pageNumber = $this->getNextSubpageNumber($parentPage['page_number'], $courseId);
        if ($pageNumber === null) {
            $this->db->transRollback();
            return false;
        }

        $newdata['fk_course_id'] = $courseId;
        $newdata['sub_page_main'] = $parentPage['page_number'];
        $newdata['page_number'] = $pageNumber;
        $result = $this->addpagedetails($newdata);

        if (!$result) {
            $this->db->transRollback();
            return false;
        }

        $committed = $this->db->transComplete();
        if (!$committed || !$this->db->transStatus()) {
            return false;
        }

        $result['page_number'] = $pageNumber;
        return $result;
    }

    function get_full_sb($course_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.*, c.course_name, c.language');
        $builder->join('scorm_courses as c', 'c.scourse_id = p.fk_course_id and c.status =1', 'left');
        $builder->where('p.fk_course_id', $course_id);
        $builder->where('p.status !=', '0');
        $builder->orderBy('p.page_number');
        $pages = $builder->get()->getResultArray();

        if (empty($pages)) {
            return [];
        }

        // A page can contain several storyboard rows. Fetch them separately instead of
        // grouping with ANY_VALUE(), which drops every audio/on-screen/notes value except
        // one arbitrary row.
        $contentBuilder = $this->db->table('page_content as pc');
        $contentBuilder->select('pc.page_id, pc.audio, pc.on_screen_text, pc.production_notes');
        $contentBuilder->join('page as p', 'p.page_id = pc.page_id', 'inner');
        $contentBuilder->where('p.fk_course_id', $course_id);
        $contentBuilder->where('p.status !=', 0);
        $contentBuilder->where('pc.status !=', 0);
        $contentBuilder->orderBy('pc.page_id');
        $contentBuilder->orderBy('pc.page_sequense');
        $contentRows = $contentBuilder->get()->getResultArray();

        $contentByPage = [];
        foreach ($contentRows as $row) {
            $contentByPage[$row['page_id']][] = $row;
        }

        $combineContent = static function (array $rows, string $field): string {
            $values = [];
            foreach ($rows as $row) {
                if (isset($row[$field]) && trim((string) $row[$field]) !== '') {
                    $values[] = $row[$field];
                }
            }
            return implode('<hr class="my-2">', $values);
        };

        foreach ($pages as &$page) {
            $rows = $contentByPage[$page['page_id']] ?? [];
            $page['audio'] = $combineContent($rows, 'audio');
            $page['on_screen_text'] = $combineContent($rows, 'on_screen_text');
            $page['production_notes'] = $combineContent($rows, 'production_notes');
        }
        unset($page);

        return $pages;
    }


    function get_prev_page($page_num, $fk_course_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.page_number as pre_page, p.page_name as page_name,p.fk_course_id');
        $builder->where('p.page_number', $page_num);
        $builder->where('p.fk_course_id', $fk_course_id);
        $builder->groupStart();
        $builder->where('p.sub_page_main', 0);
        $builder->orWhere('p.sub_page_main', null);
        $builder->groupEnd();
        $builder->where('p.status !=', 0);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }


    function get_nxt_page($page_num, $fk_course_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.page_id as page_id, p.page_name as page_name, p.page_number as page_number,p.fk_course_id');
        $builder->where('p.page_number', $page_num);
        $builder->where('p.fk_course_id', $fk_course_id);
        $builder->groupStart();
        $builder->where('p.sub_page_main', 0);
        $builder->orWhere('p.sub_page_main', null);
        $builder->groupEnd();
        $builder->where('p.status !=', 0);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function updatePagenumber($courseId, $position)
    {
        if (!is_array($position) || empty($position) || filter_var($courseId, FILTER_VALIDATE_INT) === false) {
            return false;
        }

        $pageIds = [];
        foreach ($position as $pageId) {
            $validatedPageId = filter_var($pageId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($validatedPageId === false || in_array($validatedPageId, $pageIds, true)) {
                return false;
            }
            $pageIds[] = $validatedPageId;
        }

        $this->db->transStart();
        if (!$this->lockCourseForNumbering($courseId)) {
            $this->db->transRollback();
            return false;
        }

        $mainPages = $this->db->table('page')
            ->select('page_id, page_number')
            ->where('fk_course_id', $courseId)
            ->groupStart()
            ->where('sub_page_main', 0)
            ->orWhere('sub_page_main', null)
            ->groupEnd()
            ->where('status !=', 0)
            ->orderBy('page_number')
            ->orderBy('page_id')
            ->get()
            ->getResultArray();
        $existingIds = array_map('intval', array_column($mainPages, 'page_id'));

        $submittedSet = $pageIds;
        $existingSet = $existingIds;
        sort($submittedSet);
        sort($existingSet);
        if ($submittedSet !== $existingSet) {
            $this->db->transRollback();
            return false;
        }

        $slotNumbers = array_map(
            static fn ($page) => number_format((float) $page['page_number'], 2, '.', ''),
            $mainPages
        );
        if (count(array_unique($slotNumbers)) !== count($slotNumbers)) {
            $this->db->transRollback();
            return false;
        }

        $pagesById = [];
        $childrenByParent = [];
        foreach ($mainPages as $page) {
            $pageId = (int) $page['page_id'];
            $pagesById[$pageId] = $page;
            $childrenByParent[$pageId] = [];
        }

        $children = $this->db->table('page')
            ->select('page_id, sub_page_main')
            ->where('fk_course_id', $courseId)
            ->where('sub_page_main !=', 0)
            ->where('status !=', 0)
            ->get()
            ->getResultArray();
        $parentIdsByNumber = [];
        foreach ($mainPages as $page) {
            $parentIdsByNumber[number_format((float) $page['page_number'], 2, '.', '')] = (int) $page['page_id'];
        }
        foreach ($children as $child) {
            $parentKey = number_format((float) $child['sub_page_main'], 2, '.', '');
            if (isset($parentIdsByNumber[$parentKey])) {
                $childrenByParent[$parentIdsByNumber[$parentKey]][] = (int) $child['page_id'];
            }
        }

        $result = true;
        foreach ($pageIds as $index => $pageId) {
            $oldNumber = (float) $pagesById[$pageId]['page_number'];
            $newNumber = (float) $slotNumbers[$index];
            $result = $this->db->table('page')
                ->where('page_id', $pageId)
                ->where('fk_course_id', $courseId)
                ->groupStart()
                ->where('sub_page_main', 0)
                ->orWhere('sub_page_main', null)
                ->groupEnd()
                ->where('status !=', 0)
                ->update(['page_number' => $newNumber]) && $result;

            if (!empty($childrenByParent[$pageId])) {
                $delta = $newNumber - $oldNumber;
                $childBuilder = $this->db->table('page');
                $childBuilder->whereIn('page_id', $childrenByParent[$pageId]);
                $childBuilder->set('page_number', 'page_number + (' . (float) $delta . ')', false);
                $childBuilder->set('sub_page_main', $newNumber);
                $result = $childBuilder->update() && $result;
            }
        }

        if (!$result) {
            $this->db->transRollback();
            return false;
        }

        return $this->db->transComplete() && $this->db->transStatus();
    }

    // Shifts top-level page slots and carries each affected page's children with it. Children
    // are selected by sub_page_main, not by their decimal page_number, so they never count as
    // independent slots in the main-page sequence.
    function shiftPageNumbersFrom($course_id, $fromNumber, $increment = 1, $excludePageIds = [])
    {
        $this->db->transStart();

        if (!$this->lockCourseForNumbering($course_id)) {
            $this->db->transRollback();
            return false;
        }
        if ($this->hasDuplicateMainPageNumbers($course_id)) {
            $this->db->transRollback();
            return false;
        }

        $lastMainPage = $this->db->table('page')
            ->selectMax('page_number', 'last_page_number')
            ->where('fk_course_id', $course_id)
            ->groupStart()
            ->where('sub_page_main', 0)
            ->orWhere('sub_page_main', null)
            ->groupEnd()
            ->where('status !=', 0)
            ->get()
            ->getRowArray();
        $lastPageNumber = (float) ($lastMainPage['last_page_number'] ?? 0);
        if ((float) $fromNumber < 1 || (float) $fromNumber > $lastPageNumber + 1) {
            $this->db->transRollback();
            return false;
        }

        $builder = $this->db->table('page');
        $builder->where('fk_course_id', $course_id);
        $builder->groupStart();
        $builder->where('sub_page_main', 0);
        $builder->orWhere('sub_page_main', null);
        $builder->groupEnd();
        $builder->where('page_number >=', $fromNumber);
        $builder->where('status !=', 0);
        if (!empty($excludePageIds)) {
            $builder->whereNotIn('page_id', $excludePageIds);
        }
        $builder->set('page_number', 'page_number + (' . (float) $increment . ')', false);
        $result = $builder->update();

        $subBuilder = $this->db->table('page');
        $subBuilder->where('fk_course_id', $course_id);
        $subBuilder->where('sub_page_main >=', $fromNumber);
        $subBuilder->where('sub_page_main !=', 0);
        $subBuilder->where('status !=', 0);
        if (!empty($excludePageIds)) {
            $subBuilder->whereNotIn('page_id', $excludePageIds);
        }
        $subBuilder->set('page_number', 'page_number + (' . (float) $increment . ')', false);
        $subBuilder->set('sub_page_main', 'sub_page_main + (' . (float) $increment . ')', false);
        $subResult = $subBuilder->update();

        $committed = $this->db->transComplete();
        return $result && $subResult && $committed && $this->db->transStatus();
    }

    // Handles moving an EXISTING page to a new number (editpage()'s "page number changed"
    // case), which is a fundamentally different shape from shiftPageNumbersFrom(): inserting
    // or deleting a page actually changes how many pages exist from that point on, so
    // "everything from here onward moves by one" is correct there. Moving a page just
    // reorders the existing set - the total stays the same - so only pages strictly between
    // the old and new slot should shift, by exactly one, to open/close the gap; anything
    // outside that range (e.g. page 5 when moving page 4 to slot 2) must stay put. Using
    // shiftPageNumbersFrom() here (an unbounded ">= new number" shift) was dragging those
    // untouched later pages along too.
    function movePageNumberRange($course_id, $oldNumber, $newNumber, $excludePageIds = [])
    {
        if ((float) $oldNumber === (float) $newNumber) {
            return true;
        }

        if ($newNumber < $oldNumber) {
            // Moving earlier: [newNumber, oldNumber) shifts forward one to make room.
            $lowOperator = '>=';
            $rangeLow = $newNumber;
            $highOperator = '<';
            $rangeHigh = $oldNumber;
            $increment = 1;
        } else {
            // Moving later: (oldNumber, newNumber] shifts back one to close the gap left behind.
            $lowOperator = '>';
            $rangeLow = $oldNumber;
            $highOperator = '<=';
            $rangeHigh = $newNumber;
            $increment = -1;
        }

        $this->db->transStart();

        if (!$this->lockCourseForNumbering($course_id)) {
            $this->db->transRollback();
            return false;
        }
        if ($this->hasDuplicateMainPageNumbers($course_id)) {
            $this->db->transRollback();
            return false;
        }

        $lastMainPage = $this->db->table('page')
            ->selectMax('page_number', 'last_page_number')
            ->where('fk_course_id', $course_id)
            ->groupStart()
            ->where('sub_page_main', 0)
            ->orWhere('sub_page_main', null)
            ->groupEnd()
            ->where('status !=', 0)
            ->get()
            ->getRowArray();
        $lastPageNumber = (float) ($lastMainPage['last_page_number'] ?? 0);
        if ((float) $newNumber < 1 || (float) $newNumber > $lastPageNumber) {
            $this->db->transRollback();
            return false;
        }

        // Capture the moving parent's children before displaced parents can acquire the old
        // parent number. Updating these rows later by id prevents accidental re-parenting.
        $movingSubpageIds = [];
        if (!empty($excludePageIds)) {
            $movingSubpages = $this->db->table('page')
                ->select('page_id')
                ->where('fk_course_id', $course_id)
                ->where('sub_page_main', $oldNumber)
                ->where('status !=', 0)
                ->get()
                ->getResultArray();
            $movingSubpageIds = array_column($movingSubpages, 'page_id');
        }

        $builder = $this->db->table('page');
        $builder->where('fk_course_id', $course_id);
        $builder->where('status !=', 0);
        $builder->groupStart();
        $builder->where('sub_page_main', 0);
        $builder->orWhere('sub_page_main', null);
        $builder->groupEnd();
        $builder->where('page_number ' . $lowOperator, $rangeLow);
        $builder->where('page_number ' . $highOperator, $rangeHigh);
        if (!empty($excludePageIds)) {
            $builder->whereNotIn('page_id', $excludePageIds);
        }
        $builder->set('page_number', 'page_number + (' . (float) $increment . ')', false);
        $result = $builder->update();

        $subBuilder = $this->db->table('page');
        $subBuilder->where('fk_course_id', $course_id);
        $subBuilder->where('status !=', 0);
        $subBuilder->where('sub_page_main !=', 0);
        $subBuilder->where('sub_page_main ' . $lowOperator, $rangeLow);
        $subBuilder->where('sub_page_main ' . $highOperator, $rangeHigh);
        if (!empty($excludePageIds)) {
            $subBuilder->whereNotIn('page_id', $excludePageIds);
        }
        if (!empty($movingSubpageIds)) {
            $subBuilder->whereNotIn('page_id', $movingSubpageIds);
        }
        $subBuilder->set('page_number', 'page_number + (' . (float) $increment . ')', false);
        $subBuilder->set('sub_page_main', 'sub_page_main + (' . (float) $increment . ')', false);
        $subResult = $subBuilder->update();

        $movingPageResult = true;
        if (!empty($excludePageIds)) {
            $movingPageBuilder = $this->db->table('page');
            $movingPageBuilder->where('fk_course_id', $course_id);
            $movingPageBuilder->groupStart();
            $movingPageBuilder->where('sub_page_main', 0);
            $movingPageBuilder->orWhere('sub_page_main', null);
            $movingPageBuilder->groupEnd();
            $movingPageBuilder->whereIn('page_id', $excludePageIds);
            $movingPageBuilder->set('page_number', (float) $newNumber);
            $movingPageResult = $movingPageBuilder->update();
        }

        $movingSubpageResult = true;
        if (!empty($movingSubpageIds)) {
            $delta = (float) $newNumber - (float) $oldNumber;
            $movingSubpageBuilder = $this->db->table('page');
            $movingSubpageBuilder->whereIn('page_id', $movingSubpageIds);
            $movingSubpageBuilder->set('page_number', 'page_number + (' . $delta . ')', false);
            $movingSubpageBuilder->set('sub_page_main', (float) $newNumber);
            $movingSubpageResult = $movingSubpageBuilder->update();
        }

        $committed = $this->db->transComplete();
        return $result
            && $subResult
            && $movingPageResult
            && $movingSubpageResult
            && $committed
            && $this->db->transStatus();
    }

    function softDeletePageHierarchy($pageId, $updatedBy, $updatedOn)
    {
        $course = $this->db->table('page')
            ->select('fk_course_id')
            ->where('page_id', $pageId)
            ->get()
            ->getRowArray();

        if (empty($course)) {
            return false;
        }

        $this->db->transStart();
        if (!$this->lockCourseForNumbering($course['fk_course_id'])) {
            $this->db->transRollback();
            return false;
        }
        $page = $this->db->table('page')
            ->select('page_id, fk_course_id, page_number, sub_page_main, status')
            ->where('page_id', $pageId)
            ->where('fk_course_id', $course['fk_course_id'])
            ->get()
            ->getRowArray();

        if (empty($page)) {
            $this->db->transRollback();
            return false;
        }
        if ((int) $page['status'] === 0) {
            return $this->db->transComplete() && $this->db->transStatus();
        }
        if ((float) $page['sub_page_main'] === 0.0
            && $this->hasDuplicateMainPageNumbers($course['fk_course_id'])) {
            $this->db->transRollback();
            return false;
        }

        $result = $this->softDeletePageHierarchyLocked($page, $updatedBy, $updatedOn);
        if (!$result) {
            $this->db->transRollback();
            return false;
        }

        return $this->db->transComplete() && $this->db->transStatus();
    }

    /**
     * Applies an Editor page update without accepting hierarchy fields from the request.
     */
    public function updatePageHierarchy($pageId, array $changes, $updatedBy, $updatedOn)
    {
        $course = $this->db->table('page')
            ->select('fk_course_id')
            ->where('page_id', $pageId)
            ->get()
            ->getRowArray();

        if (empty($course)) {
            return false;
        }

        $this->db->transStart();
        if (!$this->lockCourseForNumbering($course['fk_course_id'])) {
            $this->db->transRollback();
            return false;
        }
        $page = $this->db->table('page')
            ->select('page_id, fk_course_id, page_name, type, page_number, sub_page_main, status')
            ->where('page_id', $pageId)
            ->where('fk_course_id', $course['fk_course_id'])
            ->get()
            ->getRowArray();

        if (empty($page)) {
            $this->db->transRollback();
            return false;
        }

        $newStatus = array_key_exists('status', $changes)
            && $changes['status'] !== null
            && $changes['status'] !== ''
            ? (int) $changes['status']
            : (int) $page['status'];
        if ((int) $page['status'] === 0) {
            if ($newStatus === 0) {
                return $this->db->transComplete() && $this->db->transStatus();
            }

            $this->db->transRollback();
            return false;
        }

        $isSubpage = (float) $page['sub_page_main'] !== 0.0;
        $hasPageNumberChange = array_key_exists('page_number', $changes)
            && (!is_numeric($changes['page_number'])
                || (float) $changes['page_number'] !== (float) $page['page_number']);
        if (!$isSubpage
            && ($newStatus === 0 || $hasPageNumberChange)
            && $this->hasDuplicateMainPageNumbers($course['fk_course_id'])) {
            $this->db->transRollback();
            return false;
        }

        if ((int) $page['status'] !== 0 && $newStatus === 0) {
            $result = $this->softDeletePageHierarchyLocked($page, $updatedBy, $updatedOn);
        } else {
            $newPageNumber = $page['page_number'];
            if (!$isSubpage && array_key_exists('page_number', $changes)) {
                $requestedNumber = filter_var($changes['page_number'], FILTER_VALIDATE_FLOAT);
                if ($requestedNumber === false
                    || $requestedNumber < 1
                    || floor((float) $requestedNumber) !== (float) $requestedNumber) {
                    $this->db->transRollback();
                    return false;
                }

                $newPageNumber = (int) $requestedNumber;
                if ((float) $page['page_number'] !== (float) $newPageNumber
                    && !$this->movePageNumberRange(
                        $page['fk_course_id'],
                        $page['page_number'],
                        $newPageNumber,
                        [$page['page_id']]
                    )) {
                    $this->db->transRollback();
                    return false;
                }
            }

            $newdata = [
                'page_name' => $changes['page_name'] ?? $page['page_name'],
                'type' => $changes['type'] ?? $page['type'],
                'status' => $newStatus,
                'page_number' => $newPageNumber,
                'sub_page_main' => $page['sub_page_main'],
                'last_update_by' => $updatedBy,
                'last_update_on' => $updatedOn,
            ];
            $result = (bool) $this->editpagedetails($newdata, $pageId);
        }

        if (!$result) {
            $this->db->transRollback();
            return false;
        }

        return $this->db->transComplete() && $this->db->transStatus();
    }

    private function softDeletePageHierarchyLocked(array $page, $updatedBy, $updatedOn)
    {
        $result = true;

        if ((float) $page['sub_page_main'] === 0.0) {
            // A deleted parent cannot leave active children behind or attach them to the page
            // that moves into its former slot.
            $childrenDeleted = $this->db->table('page')
                ->where('fk_course_id', $page['fk_course_id'])
                ->where('sub_page_main', $page['page_number'])
                ->where('status !=', 0)
                ->update([
                    'status' => 0,
                    'last_update_by' => $updatedBy,
                    'last_update_on' => $updatedOn,
                ]);
            $result = $childrenDeleted && $result;

            // Start at the next main slot. The deleted parent and its children must not shift.
            $result = $this->shiftPageNumbersFrom(
                $page['fk_course_id'],
                (float) $page['page_number'] + 1,
                -1
            ) && $result;
        }

        $pageDeleted = $this->db->table('page')
            ->where('page_id', $page['page_id'])
            ->update([
                'status' => 0,
                'last_update_by' => $updatedBy,
                'last_update_on' => $updatedOn,
            ]);

        return $pageDeleted && $result;
    }
    function addtrancript($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('page_transcript');
        $builder->insert($newdata);
        $data['t_id'] = $db->insertID();
        if (isset($data['t_id'])) {
            return $data;
        }
    }
    function addNewFeedback($newfeedback)
    {
        // print_r($newfeedback);
        // exit();
        $db = \Config\Database::connect();
        $builder = $this->db->table('feedback');
        $builder->insert($newfeedback);
        $data['feedbackid'] = $db->insertID();
        if (isset($data['feedbackid'])) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "Error";
        }
        return $data;
        // $insertID = $db->insertID(); // Get the ID of the newly inserted reply

        // // Fetch the updated feedback and replies from the database
        // $data['feedbacks'] = $this->getFeedbackDetails($insertID);
        // $data['replies'] = $this->getAllFeedback_replies($insertID);

        // // Return the updated feedback section as HTML
        // return $data;
    }
    function getpagealldetails($course_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.*');
        $builder->where('p.fk_course_id', $course_id);
        $builder->where('p.status !=', 0);
        $builder->orderBy('p.page_number');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function addtestFeedback($pagesDetails, $scourse_id)
    {
        $data = [];
        foreach ($pagesDetails as $page) {
            $newfeedback = [
                'course_id' => $scourse_id,
                'pageid' => $page['page_id'],
                'feedback' => $page['page_name'],
                'stage' => 1,
                // 'videotime' => $_POST['videotime'],
                'type' => 1,
                'comment_type' => 1,
                'serverity' => 1,
                'comment_category' => 1,
                'status' => 1,
                'createdby' => session()->get('id_user'),
                'createdon' => '10231023',
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time()
            ];
            $db = \Config\Database::connect();
            $builder = $this->db->table('feedback');
            $builder->insert($newfeedback);
            $data['feedbackid'] = $db->insertID();
        }
        return $data;
    }
    function deletetestFeedback($course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('feedback');
        $builder->set('status', 0);
        $builder->where('createdon', '10231023');
        $builder->where('course_id', $course_id);
        $builder->update();
        return true;
    }
    function deleteCoursePages($course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('page');
        $builder->set('status', 0);
        $builder->where('fk_course_id', $course_id);
        $builder->update();
        return true;
    }
    function getFeedbackDetails($feedbackId)
    {
        $builder = $this->db->table('feedback as f');
        $builder->select('f.*, u.name as fname, u.name as updatedby,u.profile_foldername, u.profile_image, u.id_user');
        $builder->join('users as u', 'u.id_user = f.createdby', 'left');
        // $builder->join('profile as p1', 'p1.username = u.username', 'left');
        $builder->join('users as uu', 'uu.id_user = f.last_updated_by', 'left');
        $builder->where('f.feedbackid', $feedbackId);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getAllFeedback_replies($feedbackid)
    {
        $builder = $this->db->table('feedback_replies as r');
        $builder->select('r.*,r.feedbackid as replyfeedbackid,r.feedback as feedback_replies,u1.name as fname1,u1.profile_foldername, u1.profile_image, u1.id_user');
        $builder->join('users as u1', 'u1.id_user = r.createdby', 'left');
        // $builder->join('profile as p1', 'p1.username = u1.username', 'left');
        $builder->where('r.feedbackid', $feedbackid);
        $builder->where('r.status', 1);
        $builder->orderBy('r.feedbackid DESC');
        // $builder->groupBy('f.feedbackid ');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function addreplyfeedback($newfeedback)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('feedback_replies');
        $builder->insert($newfeedback);
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "Error";
        }
        return $data;
    }
    function addNewFeedbackReply($newfeedback)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('feedback_replies');
        $data = $builder->insert($newfeedback);
        return $data;
    }
    function add_content_to_page($newdata)
    {
        $builder = $this->db->table('page_content');
        $builder->insert($newdata);
        return 'true';
    }
    function delPageContent($newdata, $page_content_id)
    {
        $builder = $this->db->table('page_content');
        $builder->where('page_content_id ', $page_content_id);
        $builder->update($newdata);
        return 'true';
    }
    function updateFeedbackStatus($feedbackupadate, $feedbackid)
    {
        $builder = $this->db->table('feedback');
        $builder->where('feedbackid ', $feedbackid);
        $builder->update($feedbackupadate);
        return 'true';
    }

    function deleteFeedbackReply($feedbackupadate, $feedbackreplyid)
    {
        $builder = $this->db->table('feedback_replies');
        $builder->where('feedbackreplyid ', $feedbackreplyid);
        $builder->update($feedbackupadate);
        return 'true';
    }
    function deleteFeedbackData($delfeeddata, $feedbackid)
    {
        $builder = $this->db->table('feedback');
        $builder->where('feedbackid ', $feedbackid);
        $builder->update($delfeeddata);
        return 'true';
    }


    function delete_feedback($feedbackupadate, $feedbackid)
    {
        $builder = $this->db->table('feedback');
        $builder->where('feedbackid ', $feedbackid);
        $builder->update($feedbackupadate);
        $data = $builder->get()->getResultArray();
        // print_r();
        if (!empty($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "Error";
        }
        return $data;
    }
    function delete_reply($feedbackupadate, $feedbackreplyid)
    {
        $builder = $this->db->table('feedback_replies');
        $builder->where('feedbackreplyid ', $feedbackreplyid);
        $builder->update($feedbackupadate);
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "Error";
        }
        return $data;
    }

    function update_content_to_page($newdata, $page_content_id)
    {
        $builder = $this->db->table('page_content');
        $builder->where('page_content_id ', $page_content_id);
        $builder->update($newdata);
        return 'true';
    }
    function getpagetraanscript($page_id)
    {
        $builder = $this->db->table('page_transcript as pt');
        $builder->select('pt.*,u.name as createdby');
        $builder->join('users as u', 'u.id_user = pt.createdby', 'left');
        $builder->where('pt.page_id', $page_id);
        $builder->where('pt.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getpageArticulate($page_id)
    {
        $builder = $this->db->table('page_package as pt');
        $builder->select('pt.*,u.name as createdby');
        $builder->join('users as u', 'u.id_user = pt.createdby', 'left');
        $builder->where('pt.page_id', $page_id);
        $builder->where('pt.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getpageVideo($page_id, $type)
    {
        $builder = $this->db->table('page_video_vtt as pt');
        $builder->select('pt.*,u.name as createdby');
        $builder->join('users as u', 'u.id_user = pt.createdby', 'left');
        $builder->where('pt.page_id', $page_id);
        $builder->where('pt.type', $type);
        $builder->where('pt.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function transcriptdata($t_id)
    {
        $builder = $this->db->table('page_transcript as pt');
        $builder->select('pt.*');
        $builder->where('pt.t_id', $t_id);
        $builder->where('pt.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function edittrascriptpage($newdata, $t_id)
    {
        $builder = $this->db->table('page_transcript as pt');
        $builder->where('pt.t_id', $t_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function delsvideofiles($newdata, $page_id, $folder_name)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('page_video_vtt');
        $builder->where('page_id', $page_id);
        $builder->where('filename', trim($folder_name));
        $builder->update($newdata);

        $builder = $this->db->table('page');
        $builder->set('video_upload', '');
        $builder->where('page_id', $page_id);
        $builder->update();
        $data = $builder->get()->getResultArray($newdata);
        return $data;
    }
    function delsfiles($newdata, $page_id, $folder_name)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('page_package');
        $builder->where('page_id', $page_id);
        $builder->where('folder', trim($folder_name));
        $builder->update($newdata);
        $data = $builder->get()->getResultArray($newdata);
        return $data;
    }
    public function getAllFileOwner($page_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('page_package as x');
        $builder->select('x.folder,x.language');
        $builder->where('x.page_id', $page_id);
        $builder->where('x.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAllvideoFileOwner($page_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('page_video_vtt as x');
        $builder->select('x.filename,x.language,x.type');
        $builder->where('x.page_id', $page_id);
        $builder->where('x.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAllpdfFileOwner($scourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table(tableName: 'scorm_courses as x');
        $builder->select(select: 'x.pdf_filename,x.createdon,x.course_name,x.theme,x.mode,x.upload');
        $builder->where('x.scourse_id', $scourse_id);
        $builder->where('x.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function edituploadpdfdetails($newdata, $scourse_id)
    {
        $builder = $this->db->table('scorm_courses');
        $builder->where('scourse_id', $scourse_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray($newdata);
        return $data;
    }
    public function insertvideoFileuploaddata($scormFile)
    {
        $builder = $this->db->table('page_video_vtt as v');
        $builder->select('v.language,v.page_id');
        $builder->where('page_id', $scormFile['page_id']);
        $builder->where('language', $scormFile['language']);
        $builder->where('type', $scormFile['type']);
        $builder->where('status', 1);
        $postdata = $builder->get()->getResultArray();
        if (!empty($postdata)) {
            $builder = $this->db->table('page_video_vtt');
            $builder->where('status', 1);
            $builder->where('page_id', $scormFile['page_id']);
            $builder->where('language', $scormFile['language']);
            $builder->where('type', $scormFile['type']);
            $builder->update($scormFile);
        } else {
            $builder = $this->db->table('page_video_vtt');
            $builder->insert($scormFile);
        }
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function insertFileuploaddata($scormFile)
    {
        $builder = $this->db->table(tableName: 'page_package as x');
        $builder->select(select: 'x.*');
        $builder->where(key: 'x.page_id', value: $scormFile['page_id']);
        $builder->where(key: 'x.folder', value: $scormFile['folder']);
        $builder->where(key: 'x.language', value: $scormFile['language']);
        $builder->where(key: 'x.status', value: 1);
        $getpackagedata = $builder->get()->getResultArray();
        if ($getpackagedata) {
            $scormFile['last_update_by'] = session()->get('id_user');
            $scormFile['last_update_on'] = time();
            $builder = $this->db->table(tableName: 'page_package as p');
            $builder->where(key: 'p.page_id', value: $scormFile['page_id']);
            $builder->where(key: 'p.folder', value: $scormFile['folder']);
            $builder->where(key: 'p.language', value: $scormFile['language']);
            $builder->where(key: 'p.status', value: 1);
            return $builder->update(set: $scormFile);
        }

        $builder = $this->db->table('page_package');
        return $builder->insert($scormFile);
    }
    function getcoursestage($scourse_id)
    {
        $builder = $this->db->table('scorm_courses as s');
        $builder->select('s.mode');
        $builder->where('s.scourse_id', $scourse_id);
        $builder->where('s.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getExportFeedback($course_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('f.*,p.page_name,u.name');
        $builder->join('scorm_courses as c', 'c.scourse_id = p.fk_course_id and c.status != 0', 'left');
        $builder->join('feedback as f', 'f.pageid = p.page_id and f.status != 0', 'left');
        $builder->join('users as u', 'u.id_user = f.createdby and u.valid != 0', 'left');
        $builder->where('p.fk_course_id', $course_id);
        $builder->where('p.status!=', 0);
        $builder->orderBy('f.feedbackid');
        // $builder->groupBy('p.page_id');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getExportFeedback_replies($feedbackid)
    {
        $builder = $this->db->table('feedback_replies as fr');
        $builder->select('fr.feedback as feedback_reply,u.name,fr.createdon,fr.stage');
        $builder->join('users as u', 'u.id_user = fr.createdby and u.valid != 0', 'left');
        $builder->where('fr.feedbackid', $feedbackid);
        $builder->where('fr.status!=', 0);
        // $builder->groupBy('p.page_id');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    public function getNextRecord($q_id, $page_id)
    {
        $builder = $this->db->table('assessment_questions as q');
        $builder->select('q.*');
        $builder->where('q.page_id', $page_id);
        $builder->where('q.status !=', 0);
        $builder->where('q.q_id >', $q_id)
            ->orderBy('q.q_id', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getPreviousRecord($q_id, $page_id)
    {
        $builder = $this->db->table('assessment_questions as q');
        $builder->select('q.*');
        $builder->where('q.page_id', $page_id);
        $builder->where('q.status !=', 0);
        $builder->where('q.q_id <', $q_id)
            ->orderBy('q.q_id', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function updatefeedbackAttachment($newdata, $feedbackid)
    {
        $builder = $this->db->table('feedback');
        $builder->where('feedbackid', $feedbackid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "Error";
        }
        return $data;
    }
    function importpagedetails($sheetData, $scourse_id, $columnCount)
    {
        // print_r($sheetData[0][0]);
        // exit();
        if (trim($sheetData[0][0]) == 'Page Name') {
            $this->db->transStart();
            if (!$this->lockCourseForNumbering($scourse_id)
                || $this->hasDuplicateMainPageNumbers($scourse_id)) {
                $this->db->transRollback();
                return ['error' => 'The course page numbering must be repaired before importing pages.'];
            }

            $j = 0;
            foreach ($sheetData as $Row) {
                // print_r($scourse_id.'-'.$page_id);
                // exit();

                if (trim($Row[0]) == 'Page Name') {
                    continue;
                }
                $j = $j + 1;
                if (isset($Row[0])) {

                    $page_name = "";
                    if (isset($Row[0])) {
                        $page_name = trim($Row[0]);
                    }
                    $Main_page_number = "";
                    if (isset($Row[1])) {
                        $Main_page_number = trim($Row[1]);
                    }
                    $Main_sub_page_number = "";
                    if (isset($Row[2])) {
                        $Main_sub_page_number = trim($Row[2]);
                    }
                    $type = "";
                    if (isset($Row[3])) {
                        $type = trim($Row[3]);
                        if (strtoupper($type) == 'articulate') {
                            $type = '1';
                        } elseif (strtoupper($type) == 'video') {
                            $type = '2';
                        } elseif (strtoupper($type) == 'html') {
                            $type = '3';
                        } elseif (strtoupper($type) == 'quiz') {
                            $type = '4';
                        } elseif (strtoupper($type) == 'SCQ CYU') {
                            $type = '5';
                        } elseif (strtoupper($type) == 'MCQ CYU') {
                            $type = '6';
                        } elseif (strtoupper($type) == 'Video Sub Page') {
                            $type = '8';
                        } elseif (strtoupper($type) == 'Audio Version') {
                            $type = '9';
                        } elseif (strtoupper($type) == 'TEXT ONLY') {
                            $type = '10';
                        } elseif (strtoupper($type) == 'IMAGE + TEXT') {
                            $type = '11';
                        } elseif (strtoupper($type) == 'TEXT + IMAGE') {
                            $type = '12';
                        } else {
                            $type = '2';
                            // $type = '112';
                        }
                    }
                    $isMainPage = $Main_sub_page_number === ''
                        || (float) $Main_sub_page_number === 0.0;
                    if ($isMainPage) {
                        $validatedPageNumber = filter_var($Main_page_number, FILTER_VALIDATE_FLOAT);
                        if ($validatedPageNumber === false
                            || $validatedPageNumber < 1
                            || floor((float) $validatedPageNumber) !== (float) $validatedPageNumber
                            || !$this->shiftPageNumbersFrom($scourse_id, $validatedPageNumber, 1)) {
                            $this->db->transRollback();
                            return ['error' => 'Row ' . $j . ': invalid main page number. No records were imported.'];
                        }
                        $newPageNumber = (int) $validatedPageNumber;
                        $parentPageNumber = 0;
                    } else {
                        $parentPages = $this->db->table('page')
                            ->select('page_number')
                            ->where('fk_course_id', $scourse_id)
                            ->where('page_number', $Main_sub_page_number)
                            ->groupStart()
                            ->where('sub_page_main', 0)
                            ->orWhere('sub_page_main', null)
                            ->groupEnd()
                            ->where('status !=', 0)
                            ->get()
                            ->getResultArray();
                        if (count($parentPages) !== 1) {
                            $this->db->transRollback();
                            return ['error' => 'Row ' . $j . ': sub page parent was not found. No records were imported.'];
                        }

                        $parentPageNumber = $parentPages[0]['page_number'];
                        $newPageNumber = $this->getNextSubpageNumber($parentPageNumber, $scourse_id);
                        if ($newPageNumber === null) {
                            $this->db->transRollback();
                            return ['error' => 'Row ' . $j . ': the parent already has 99 sub pages. No records were imported.'];
                        }
                    }
                    $insertpagedata = [
                        'fk_course_id' => $scourse_id,
                        'page_name' => $page_name,
                        'sub_page_main' => $parentPageNumber,
                        'page_number' => $newPageNumber,
                        'type' => $type,
                        'status' => '1',
                        'createdby' => session()->get('id_user'),
                        'createdon' => time(),
                        'last_update_by' => session()->get('id_user'),
                        'last_update_on' => time()
                    ];
                    // print_r($insertquestiondata);
                    // exit();
                    $builder = $this->db->table('page');
                    if (!$builder->insert($insertpagedata)) {
                        $this->db->transRollback();
                        return ['error' => 'Row ' . $j . ': the page could not be imported. No records were imported.'];
                    }
                } else {
                    $this->db->transRollback();
                    return ['error' => 'Row ' . $j . ': required values are missing. No records were imported.'];
                }
            }
            if (!$this->db->transComplete() || !$this->db->transStatus()) {
                return ['error' => 'The import could not be completed. No records were imported.'];
            }

            return ['success' => 'Record imported successfully'];
        } else {
            $data['error'] = 'Data not found!, Your trying to import wrong Excelsheet';
            return $data;
        }
    }
    public function getLatestPageNumber($course_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.page_number');
        $builder->where('fk_course_id', $course_id);
        $builder->where('status !=', 0);
        $builder->orderBy('page_id', 'DESC'); // Get the last inserted page number
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            $page_number = $data['0']['page_number'];
        } else {
            $page_number = '1.00';
        }
        return $page_number;
    }
    public function question_attachment($course_id, $q_id)
    {
        $builder = $this->db->table('question_attachments as qa');
        $builder->select('qa.*');
        $builder->where('scourse_id', $course_id);
        $builder->where('q_id', $q_id);
        $builder->where('status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
