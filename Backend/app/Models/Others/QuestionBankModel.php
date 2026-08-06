<?php namespace App\Models\others;

use CodeIgniter\Model;

class QuestionBankModel extends Model
{
    protected $table = 'question_bank';
    protected $primaryKey = 'qb_id';

    protected $allowedFields = [
        'qno', 'tabid', 'question', 'answer_a', 'answer_b',
        'answer_c', 'answer_d', 'correct_answer', 'correct_answer_excel',
        'reference', 'subject', 'new_question', 'acs_code', 'remarks', 'status'
    ];
}
