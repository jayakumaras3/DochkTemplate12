<?php

namespace App\Controllers\Open;

use App\Controllers\BaseController;
use App\Models\Others\QuestionBankModel;

class Question_bank extends BaseController
{

    public function index()
    {
        $model = new QuestionBankModel();

        $question = $model->orderBy('qb_id', 'ASC')->first();

        if (!$question) {
            return view('others/question_bank/complete');
        }

        $next = $model->where('qb_id >', $question['qb_id'])->orderBy('qb_id', 'ASC')->first();
        $prev = $model->where('qb_id <', $question['qb_id'])->orderBy('qb_id', 'DESC')->first();

        $data = [
            'question' => $question,
            'next_id' => $next ? $next['qb_id'] : null,
            'prev_id' => $prev ? $prev['qb_id'] : null,
        ];
        echo view('templates/header_view');
        echo view('others/question_bank/question_bank_view', $data);
        echo view('templates/footer_view');
    }
    public function tab_questions_post()
    {
        $model = new QuestionBankModel();

        $tabs = $model->select('tabid')->groupBy('tabid')->orderBy('tabid', 'ASC')->findAll();

        if (isset($_POST['tabid'])) {
            $tabid = $_POST['tabid'];
            $_SESSION['tabid'] = $tabid;
        } elseif (isset($_SESSION['tabid'])) {
            $tabid = $_SESSION['tabid'];
        } else {
            $tabid = 1;
        }

        if (!$tabid && !empty($tabs)) {
            $tabid = $tabs[0]['tabid'];
        }
        $questions = [];
        if ($tabid) {
            $questions = $model->where('tabid', $tabid)->orderBy('qno', 'ASC')->findAll();
        }

        echo view('templates/header_view');
        echo view('others/question_bank/tab_questions', [
            'tabs' => $tabs,
            'questions' => $questions,
            'active_tab' => $tabid
        ]);
        echo view('templates/footer_view');
    }

    // public function edit_question()
    // {
    //     $data = [];
    //     $model = new QuestionBankModel();

    //     // Determine which question ID to load
    //     if (isset($_POST['qb_id'])) {
    //         $data['qb_id'] = $_POST['qb_id'];
    //         $_SESSION['qb_id'] = $data['qb_id'];
    //     } elseif (isset($_GET['qb_id'])) {
    //         $data['qb_id'] = $_GET['qb_id'];
    //     } elseif (isset($_SESSION['qb_id'])) {
    //         $data['qb_id'] = $_SESSION['qb_id'];
    //     }

    //     // Get current question or first question
    //     $question = $data['qb_id']
    //         ? $model->find($data['qb_id'])
    //         : $model->orderBy('qb_id', 'ASC')->first();

    //     if (!$question) {
    //         return view('others/question_bank/complete');
    //     }

    //     // Next and previous
    //     $next = $model->where('qb_id >', $question['qb_id'])->orderBy('qb_id', 'ASC')->first();
    //     $prev = $model->where('qb_id <', $question['qb_id'])->orderBy('qb_id', 'DESC')->first();

    //     // Reset query builder
    //     $model->resetQuery();

    //     // Get total per tab
    //     $tabCounts = $model
    //         ->select('tabid, COUNT(*) as total')
    //         ->groupBy('tabid')
    //         ->findAll();

    //     // Get total for current tab
    //     $currentTabTotal = $model
    //         ->where('tabid', $question['tabid'])
    //         ->countAllResults();

    //     // Reset again (important — countAllResults clears builder)
    //     $model->resetQuery();

    //     // Get position (question number in this tab)
    //     $currentQuestionNumber = $model
    //         ->where('tabid', $question['tabid'])
    //         ->where('qb_id <=', $question['qb_id'])
    //         ->countAllResults();

    //     // Ensure defaults (prevents "undefined" if queries fail)
    //     $currentTabTotal = $currentTabTotal ?? 0;
    //     $currentQuestionNumber = $currentQuestionNumber ?? 0;

    //     // Prepare data for the view
    //     $data = [
    //         'question' => $question,
    //         'next_id' => $next ? $next['qb_id'] : null,
    //         'prev_id' => $prev ? $prev['qb_id'] : null,
    //         'tabCounts' => $tabCounts,
    //         'currentTabTotal' => $currentTabTotal,
    //         'currentQuestionNumber' => $currentQuestionNumber,
    //     ];

    //     echo view('templates/header_view');
    //     echo view('others/question_bank/question_bank_view', $data);
    //     echo view('templates/footer_view');
    // }

    public function edit_question()
    {
        $session = session();
        $model   = new QuestionBankModel();
        if (!$session->has('qb_questions')) {

            $questions = $model
                ->select('qb_id, tabid, question, new_question, answer_a, answer_b, answer_c, answer_d,
                          correct_answer, remarks, reference, acs_code, status')
                ->orderBy('tabid', 'ASC')
                ->orderBy('qb_id', 'ASC')
                ->findAll();

            if (empty($questions)) {
                echo view('templates/header_view');
                return view('others/question_bank/complete');
                echo view('templates/footer_view');
            }
            $indexed = [];
            foreach ($questions as $q) {
                $indexed[$q['qb_id']] = $q;
            }

            $session->set([
                'qb_questions' => $indexed,
                'qb_order'     => array_keys($indexed),
            ]);
        }

        $questions = $session->get('qb_questions'); 
        $order     = $session->get('qb_order');

        $qb_id = $this->request->getPost('qb_id')
            ?? $this->request->getGet('qb_id')
            ?? $session->get('qb_id')
            ?? $order[0];

        if (!isset($questions[$qb_id])) {
           // return view('others/question_bank/complete');
        }

        $session->set('qb_id', $qb_id);
       //  return redirect()->to(base_url('Open/Question_bank/next'));
        $question = $questions[$qb_id];
        $index   = array_search($qb_id, $order);
        $prev_id = $order[$index - 1] ?? null;
        $next_id = $order[$index + 1] ?? null;

        if (!$session->has('qb_tab_counts')) {
            $tabCounts = [];
            foreach ($questions as $q) {
                $tabCounts[$q['tabid']] = ($tabCounts[$q['tabid']] ?? 0) + 1;
            }
            $session->set('qb_tab_counts', $tabCounts);
        }

        $tabCounts = $session->get('qb_tab_counts');

        $currentTab = $question['tabid'];
        $currentTabTotal = $tabCounts[$currentTab] ?? 0;

        $currentQuestionNumber = 0;
        foreach ($questions as $q) {
            if ($q['tabid'] == $currentTab && $q['qb_id'] <= $qb_id) {
                $currentQuestionNumber++;
            }
        }

        
        echo view('templates/header_view');
        return view('others/question_bank/question_bank_view', [
            'question'              => $question,
            'prev_id'               => $prev_id,
            'next_id'               => $next_id,
            'tabCounts'             => $tabCounts,
            'currentTabTotal'       => $currentTabTotal,
            'currentQuestionNumber' => $currentQuestionNumber
        ]);
        echo view('templates/footer_view');
    }

    // public function update_status()
    // {
    //     $model = new QuestionBankModel();
    //     $qb_id = $this->request->getPost('qb_id');
    //     $newStatus = $this->request->getPost('update_status');

    //     if ($qb_id && $newStatus !== null) {
    //         $model->update($qb_id, ['status' => $newStatus]);
    //         session()->set('qb_id', $qb_id);
    //         return redirect()->to(base_url('Open/Question_bank/edit_question'));
    //     }

    //     return redirect()->to(base_url('Open/Question_bank'));
    // }
    public function update_status()
    {
        $model = new QuestionBankModel();

        $qb_id     = $this->request->getPost('qb_id');
        $newStatus = $this->request->getPost('update_status');

        if ($qb_id && $newStatus !== null) {

            $model->update($qb_id, [
                'status' => $newStatus
            ]);

            session()->remove([
                'qb_questions',
                'qb_order',
                'qb_tab_counts'
            ]);
            session()->set('qb_id', $qb_id);

            return redirect()->to(base_url('Open/Question_bank/edit_question'));
        }

        return redirect()->to(base_url('Open/Question_bank'));
    }


    // function next()
    // {
    //     $model = new QuestionBankModel();

    //     $qb_id = $this->request->getPost('qb_id');
    //     $action = $this->request->getPost('action');
    //     $next_id = $this->request->getPost('next_id');
    //     $prev_id = $this->request->getPost('prev_id');

    //     // --- Step 1: Handle save action ---
    //     if ($action === 'save') {
    //         $updateData = [
    //             'question' => $this->request->getPost('question'),
    //             'new_question' => $this->request->getPost('new_question'),
    //             'answer_a' => $this->request->getPost('answer_a'),
    //             'answer_b' => $this->request->getPost('answer_b'),
    //             'answer_c' => $this->request->getPost('answer_c'),
    //             'answer_d' => $this->request->getPost('answer_d'),
    //             'remarks' => $this->request->getPost('remarks'),
    //             'reference' => $this->request->getPost('reference'),
    //             'acs_code' => $this->request->getPost('acs_code'),
    //         ];
    //         $model->update($qb_id, $updateData);
    //         session()->setFlashdata('success', lang('Messages.Success_0008'));
    //     }

    //     // --- Step 2: Determine which question to show ---
    //     if ($action === 'next' && $next_id) {
    //         $current = $model->find($next_id);
    //     } elseif ($action === 'prev' && $prev_id) {
    //         $current = $model->find($prev_id);
    //     } else {
    //         $current = $model->find($qb_id);
    //     }

    //     if (!$current) {
    //         return view('others/question_bank/complete');
    //     }

    //     $next = $model->where('qb_id >', $current['qb_id'])
    //         ->orderBy('qb_id', 'ASC')
    //         ->first();

    //     $prev = $model->where('qb_id <', $current['qb_id'])
    //         ->orderBy('qb_id', 'DESC')
    //         ->first();

    //     $model->resetQuery(); // reset builder before next query set

    //     $tabCounts = $model->select('tabid, COUNT(*) as total')
    //         ->groupBy('tabid')
    //         ->findAll();

    //     // Total questions in current tab
    //     $currentTabTotal = $model
    //         ->where('tabid', $current['tabid'])
    //         ->countAllResults();

    //     $model->resetQuery();

    //     // Current question number in this tab
    //     $currentQuestionNumber = $model
    //         ->where('tabid', $current['tabid'])
    //         ->where('qb_id <=', $current['qb_id'])
    //         ->countAllResults();

    //     $data = [
    //         'question' => $current,
    //         'next_id' => $next ? $next['qb_id'] : null,
    //         'prev_id' => $prev ? $prev['qb_id'] : null,
    //         'tabCounts' => $tabCounts,
    //         'currentTabTotal' => $currentTabTotal,
    //         'currentQuestionNumber' => $currentQuestionNumber,
    //     ];


    //     echo view('templates/header_view');
    //     echo view('others/question_bank/question_bank_view', $data);
    //     echo view('templates/footer_view');
    // }
    public function next()
    {
        $session = session();
        $model   = new QuestionBankModel();

        $questions = $session->get('qb_questions');
        $order     = $session->get('qb_order');
        $tabCounts = $session->get('qb_tab_counts');

        if (!$questions || !$order) {
            return redirect()->to(base_url('Open/Question_bank/edit_question'));
        }

        $qb_id  = $this->request->getPost('qb_id');
        $action = $this->request->getPost('action');

        if ($action === 'save' && isset($questions[$qb_id])) {

            $updateData = [
                'question'     => $this->request->getPost('question'),
                'new_question' => $this->request->getPost('new_question'),
                'answer_a'     => $this->request->getPost('answer_a'),
                'answer_b'     => $this->request->getPost('answer_b'),
                'answer_c'     => $this->request->getPost('answer_c'),
                'answer_d'     => $this->request->getPost('answer_d'),
                'remarks'      => $this->request->getPost('remarks'),
                'reference'    => $this->request->getPost('reference'),
                'acs_code'     => $this->request->getPost('acs_code'),
            ];

            $model->update($qb_id, $updateData);

            foreach ($updateData as $key => $value) {
                $questions[$qb_id][$key] = $value;
            }

            $session->set('qb_questions', $questions);
            $session->setFlashdata('success', lang('Messages.Success_0008'));
        }

        $index = array_search($qb_id, $order);

        if ($action === 'next' && isset($order[$index + 1])) {
            $qb_id = $order[$index + 1];
        } elseif ($action === 'prev' && isset($order[$index - 1])) {
            $qb_id = $order[$index - 1];
        }

        if (!isset($questions[$qb_id])) {
            echo view('templates/header_view');
            echo view('others/question_bank/complete');
            echo view('templates/footer_view');
            return;
        }

        $session->set('qb_id', $qb_id);
        $question = $questions[$qb_id];

        $currentTab = $question['tabid'];
        $currentTabTotal = $tabCounts[$currentTab] ?? 0;

        $currentQuestionNumber = 0;
        foreach ($questions as $q) {
            if ($q['tabid'] == $currentTab && $q['qb_id'] <= $qb_id) {
                $currentQuestionNumber++;
            }
        }
        $index = array_search($qb_id, $order);

        $data = [
            'question'              => $question,
            'prev_id'               => $order[$index - 1] ?? null,
            'next_id'               => $order[$index + 1] ?? null,
            'tabCounts'             => $tabCounts,
            'currentTabTotal'       => $currentTabTotal,
            'currentQuestionNumber' => $currentQuestionNumber,
        ];

        echo view('templates/header_view');
        echo view('others/question_bank/question_bank_view', $data);
        echo view('templates/footer_view');
    }


    public function export_excel($tabid)
    {
        $model = new QuestionBankModel();
        $questions = $model->where('tabid', $tabid)->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($tabid);


        $sheet->setCellValue('A1', 'ACS Code')
            ->setCellValue('B1', 'Question')
            ->setCellValue('C1', 'New Question')
            ->setCellValue('D1', 'Option A')
            ->setCellValue('E1', 'Option B')
            ->setCellValue('F1', 'Option C')
            ->setCellValue('G1', 'Option D')
            ->setCellValue('H1', 'Correct Answer (Excel Cell)')
            ->setCellValue('I1', 'Reference');

        $headerStyle = $sheet->getStyle('A1:I1');
        $headerStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF000000');
        $headerStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->freezePane('A2');

        $row = 2;
        foreach ($questions as $q) {
            $correctAnswer = strtoupper($q['correct_answer']);
            $correctCell = '';

            switch ($correctAnswer) {
                case 'A':
                    $correctCell = 'D' . $row;
                    break;
                case 'B':
                    $correctCell = 'E' . $row;
                    break;
                case 'C':
                    $correctCell = 'F' . $row;
                    break;
                case 'D':
                    $correctCell = 'G' . $row;
                    break;
                default:
                    $correctCell = '-';
            }

            // Write data
            $sheet->setCellValue('A' . $row, $q['acs_code'] ?? '')
                ->setCellValue('B' . $row, $q['question'])
                ->setCellValue('C' . $row, $q['new_question'])
                ->setCellValue('D' . $row, $q['answer_a'])
                ->setCellValue('E' . $row, $q['answer_b'])
                ->setCellValue('F' . $row, $q['answer_c'])
                ->setCellValue('G' . $row, $q['answer_d'])
                ->setCellValue('H' . $row, $correctCell)
                ->setCellValue('I' . $row, $q['reference'] ?? '');

            if (in_array($correctAnswer, ['A', 'B', 'C', 'D'])) {
                $colLetter = chr(ord('D') + (ord($correctAnswer) - ord('A')));
                $sheet->getStyle($colLetter . $row)
                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF28A745');
            }

            $row++;
        }

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $filename = 'Question_Bank_' . $tabid . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    public function export_excel_all()
    {
        $model = new QuestionBankModel();

        $tabids = $model->distinct()->select('tabid')->findAll();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        foreach ($tabids as $t) {
            $tabid = $t['tabid'];

            $questions = $model->where('tabid', $tabid)->findAll();

            $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $tabid);
            $spreadsheet->addSheet($sheet);

            $sheet->setCellValue('A1', 'ACS Code')
                ->setCellValue('B1', 'Question')
                ->setCellValue('C1', 'New Question')
                ->setCellValue('D1', 'Option A')
                ->setCellValue('E1', 'Option B')
                ->setCellValue('F1', 'Option C')
                ->setCellValue('G1', 'Option D')
                ->setCellValue('H1', 'Correct Answer (Excel Cell)')
                ->setCellValue('I1', 'Reference');

            $headerStyle = $sheet->getStyle('A1:I1');
            $headerStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
            $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF000000');
            $headerStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $sheet->freezePane('A2');

            $row = 2;
            foreach ($questions as $q) {
                $correctAnswer = strtoupper($q['correct_answer']);
                $correctCell = match ($correctAnswer) {
                    'A' => 'D' . $row,
                    'B' => 'E' . $row,
                    'C' => 'F' . $row,
                    'D' => 'G' . $row,
                    default => '-',
                };

                $sheet->setCellValue('A' . $row, $q['acs_code'] ?? '')
                    ->setCellValue('B' . $row, $q['question'])
                    ->setCellValue('C' . $row, $q['new_question'])
                    ->setCellValue('D' . $row, $q['answer_a'])
                    ->setCellValue('E' . $row, $q['answer_b'])
                    ->setCellValue('F' . $row, $q['answer_c'])
                    ->setCellValue('G' . $row, $q['answer_d'])
                    ->setCellValue('H' . $row, $correctCell)
                    ->setCellValue('I' . $row, $q['reference'] ?? '');

                // Highlight correct cell in green
                if (in_array($correctAnswer, ['A', 'B', 'C', 'D'])) {
                    $colLetter = chr(ord('D') + (ord($correctAnswer) - ord('A')));
                    $sheet->getStyle($colLetter . $row)
                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FF28A745');
                }

                $row++;
            }

            // Auto-size all columns
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'Question_Bank_All_Tabs.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
