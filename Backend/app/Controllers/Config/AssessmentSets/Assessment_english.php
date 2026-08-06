<?php

namespace Config\AssessmentSets;

use CodeIgniter\Config\AssessmentSets;

class Assessment_english
{
    // Please use unique array id for each template
    // Page level settings
    public static $assessment_sets = array(
        "42" => "Assessment",
        "33" => "Total number of questions: ",
        "34" => "Minimum passing score: ",
        "35" => "Total number of attempts: ",
        "41" => "Time allowed to complete the assessment: ",
        "43" => "If you fail to achieve the minimum score and you have attempts remaining, please re-attempt the assessment.",
        "40" => "<i>Select the correct answer(s), and then click <strong>Submit.</strong></i>",
        // "66" => "<i>Select the best answers, then click Submit</i>",
        "44" => "Start",
        "48" => "<i>Click <strong>Start</strong> to begin.</i>",
        "45" => "Submit",
        "47" => "View Result",
        "46" => "Retry",
        "36" => "You have completed the assessment!",
        "37" => "Your score:",
        "38" => "Congratulations! You passed the assessment.",
        "39" => "You did not pass the assessment. Please click <strong>Retry</strong> to try again.",
        "67" => "You did not pass the assessment. <br>Please contact your manager for next steps.",
        "49" => "Oops! Time's up!",
        "69" => "Questions",
        "70" => "of",
        "71" => "minutes",
        "73" =>"<i>Click the image to enlarge it.</i>"

    );
    // Course level settings
    public static $assessment_export_sets = array(
        "50" => "Menu",
        "51" => "Next",
        "52" => "Prev",
        "53" => "Menu",
        "54" => "Transcript",
        "55" => "Resume",
        "56" => "Are you sure you want to continue where you left?",
        "57" => "Yes",
        "58" => "No",
        "64" => "en", // VttLanguage
        "65" => "English", // VttLabel
        "62" => 1, // free navigation (master)
        "63" => 0, // page level course completion 
        "74" => 1, //CertificateEnabled
        "72" =>"Select <b>Next</b> to Continue.",
         '75' => "LearningAids"
    );
    public static $assessment_scqmcq_sets = array(
        "59" => "<i>Select the best answer, then click Submit</i>",
        "60" => "<i>Select the best answers, then click Submit</i>",
        "61" => "Submit",
        "68" => "Please select an answer.",
    );

    public static $certificate_sets = array(
        "75" => "<i>Select the best answer, then click Submit</i>",
        "76" => "<i>Select the best answers, then click Submit</i>",
        "77" => "Submit",
        "78" => "Please select an answer.",
        "78" => "Please select an answer.",
    );
}
