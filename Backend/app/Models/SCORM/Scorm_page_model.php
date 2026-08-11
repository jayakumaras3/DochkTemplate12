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
        $db = \Config\Database::connect();
        $builder = $this->db->table('page');
        $builder->insert($newdata);
        $data['page_id'] = $db->insertID();
        if (isset($data['page_id'])) {
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
                    $data['question_id'] = $db->insertID();

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
        $db = \Config\Database::connect();
        $builder = $this->db->table('page as p');
        $builder->where('p.page_id', $page_id);
        $builder->update($newdata);
        $data['page_id'] = $db->insertID();
        if (isset($data['page_id'])) {
            return $data;
        }
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
        $data = $builder->get()->getResultArray();
        return $data;
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
        $builder->where('p.sub_page_main', 0);
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
        $builder->where('p.sub_page_main', 0);
        $builder->where('p.status !=', 0);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function updatePagenumber($position)
    {
        $i = 2;
        foreach ($position as $k => $page_id) {
            $builder = $this->db->table('page as p');
            $builder->set('p.page_number', $i);
            $builder->where('p.page_id', $page_id);
            $builder->update();
            $i++;
        }
    }

    // Makes room for a page being inserted/moved to $fromNumber: every other page in the
    // course whose page_number is already at or past that slot moves up by $increment, in a
    // single atomic UPDATE (the WHERE clause is evaluated once against the pre-update values,
    // so shifting can't cause pages to collide with each other mid-update). A sub-page's
    // number is its parent's number plus a small fraction (e.g. 2.10 under page 2), so it
    // naturally shifts together with its parent whenever both are >= $fromNumber - no separate
    // handling needed for the common "insert a new page" case. $excludePageIds lets a caller
    // (e.g. editing a page's own number) keep specific pages out of the shift.
    function shiftPageNumbersFrom($course_id, $fromNumber, $increment = 1, $excludePageIds = [])
    {
        $builder = $this->db->table('page');
        $builder->where('fk_course_id', $course_id);
        $builder->where('page_number >=', $fromNumber);
        $builder->where('status !=', 0);
        if (!empty($excludePageIds)) {
            $builder->whereNotIn('page_id', $excludePageIds);
        }
        $builder->set('page_number', 'page_number + (' . (float) $increment . ')', false);
        $result = $builder->update();

        // A sub-page points at its parent via sub_page_main = parent's page_number (not the
        // parent's page_id, per this schema) - so any parent that just shifted needs its
        // children's sub_page_main shifted the same amount, or they'd silently detach from it.
        $subBuilder = $this->db->table('page');
        $subBuilder->where('fk_course_id', $course_id);
        $subBuilder->where('sub_page_main >=', $fromNumber);
        $subBuilder->where('sub_page_main !=', 0);
        $subBuilder->where('status !=', 0);
        if (!empty($excludePageIds)) {
            $subBuilder->whereNotIn('page_id', $excludePageIds);
        }
        $subBuilder->set('sub_page_main', 'sub_page_main + (' . (float) $increment . ')', false);
        $subBuilder->update();

        return $result;
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

        $builder = $this->db->table('page');
        $builder->where('fk_course_id', $course_id);
        $builder->where('status !=', 0);
        $builder->where('page_number ' . $lowOperator, $rangeLow);
        $builder->where('page_number ' . $highOperator, $rangeHigh);
        if (!empty($excludePageIds)) {
            $builder->whereNotIn('page_id', $excludePageIds);
        }
        $builder->set('page_number', 'page_number + (' . (float) $increment . ')', false);
        $result = $builder->update();

        // Same reasoning as shiftPageNumbersFrom(): a page that shifts within this range needs
        // its own children's sub_page_main (which stores the parent's page_number) shifted too.
        $subBuilder = $this->db->table('page');
        $subBuilder->where('fk_course_id', $course_id);
        $subBuilder->where('status !=', 0);
        $subBuilder->where('sub_page_main !=', 0);
        $subBuilder->where('sub_page_main ' . $lowOperator, $rangeLow);
        $subBuilder->where('sub_page_main ' . $highOperator, $rangeHigh);
        if (!empty($excludePageIds)) {
            $subBuilder->whereNotIn('page_id', $excludePageIds);
        }
        $subBuilder->set('sub_page_main', 'sub_page_main + (' . (float) $increment . ')', false);
        $subBuilder->update();

        return $result;
    }

    // When a specific page's own number is edited (not the bulk "make room" shift above), its
    // existing sub-pages need to be repointed at the new number and moved by the same delta -
    // shiftPageNumbersFrom() alone won't do this since the parent isn't moving by a uniform
    // "everything past this point" amount, it's jumping straight to $newParentNumber.
    function relinkSubpages($course_id, $oldParentNumber, $newParentNumber)
    {
        if ((float) $oldParentNumber === (float) $newParentNumber) {
            return true;
        }
        $delta = $newParentNumber - $oldParentNumber;
        $builder = $this->db->table('page');
        $builder->where('fk_course_id', $course_id);
        $builder->where('sub_page_main', $oldParentNumber);
        $builder->where('status !=', 0);
        $builder->set('sub_page_main', $newParentNumber);
        $builder->set('page_number', 'page_number + (' . (float) $delta . ')', false);
        return $builder->update();
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
                    $builder = $this->db->table('page as p');
                    $builder->select('p.page_number');
                    $builder->where('fk_course_id', $scourse_id);
                    $builder->where('status !=', 0);
                    $builder->orderBy('page_id', 'DESC'); // Get the last inserted page number
                    $data = $builder->get()->getResultArray();
                    if (!empty($data)) {
                        $page_number = $data['0']['page_number'];
                    } else {
                        $page_number = '';
                    }
                    $lastPage = $page_number;
                    $lastPage = $this->getLatestPageNumber($scourse_id);
                    // print_r($lastPage);
                    // exit();
                    if ($Main_sub_page_number == '' || $Main_sub_page_number == '0' || $Main_sub_page_number == NULL) {
                        $newPageNumber = $Main_page_number;
                    } else {
                        $newPageNumber = ($lastPage) ? number_format($lastPage + 0.01, 2) : $Main_page_number;
                    }
                    $insertpagedata = [
                        'fk_course_id' => $scourse_id,
                        'page_name' => $page_name,
                        'sub_page_main' => isset($Main_sub_page_number) ? $Main_sub_page_number : 0,
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
                    $db = \Config\Database::connect();
                    $builder = $this->db->table('page');
                    $builder->insert($insertpagedata);
                    $insert_id = $db->insertID();
                    if (isset($insert_id)) {
                        $q_id = $insert_id;
                    }
                } else {
                    $insertrecordCount = $j - 1;
                    $data['error'] = "Row " . $j . " : don't have properly value, further row excution has been stopped <br/>
                    only" . $insertrecordCount . " : Record imported successfully";
                    return $data;
                }
            }
            $data['success'] = 'Record imported successfully';
            return $data;
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
