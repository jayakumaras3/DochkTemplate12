<?php

namespace Config\AssessmentSets;

use CodeIgniter\Config\AssessmentSets;

class Assessment_french
{
    // Please use unique array id for each template
    // Page level settings
    public static $assessment_sets = array(
        "42" => "Évaluation",
        "33" => "Nombre total de questions : ",
        "34" => "Note minimale de réussite : ",
        "35" => "Nombre total de tentatives : ",
        "41" => "Durée de l'évaluation : ",
        "43" => "Si vous n'obtenez pas la note minimale et qu'il vous reste des tentatives, veuillez retenter l'évaluation.",
        "40" => "<i>Sélectionnez les bonnes réponses, puis cliquez sur <strong>Soumettre.</strong></i>",
        //"66" => "<i>Sélectionnez les bonnes réponse, puis cliquez sur <strong>Soumettre.</strong></i>",
        "44" => "Commencer",
        "48" => "<i>Cliquez sur <strong>Commencer</strong>.</i>",
        "45" => "VALIDER",
        "47" => "Voir le résultat",
        "46" => "Retenter",
        "36" => "Vous avez terminé l'évaluation !",
        "37" => "Votre note :",
        "38" => "Félicitations ! Vous avez réussi l'évaluation.",
        "39" => "Vous n'avez pas réussi l'évaluation. Veuillez cliquer sur <strong>Réessayer</strong> pour réessayer.",
        "67" => "Vous n'avez pas réussi l'évaluation. <br>Veuillez contacter votre responsable pour déterminer les prochaines étapes.",
        "49" => "Oups ! Le temps est écoulé !",
        "69" => "Questions",
        "70" => "de",
        "71" => "minutes",
        "73" => "<i>Cliquez sur l'image pour l'agrandir.</i>"

    );
    // Course level settings
    public static $assessment_export_sets = array(
        "50" => "Menu",
        "51" => "SUIVANT",
        "52" => "PRÉC",
        "53" => "Menu",
        "54" => "Transcription",
        "55" => "Reprendre",
        "56" => "Êtes-vous sûr de vouloir continuer là où vous vous êtes arrêté ?",
        "57" => "Oui",
        "58" => "Non",
        "64" => "en", // VttLanguage
        "65" => "French", // VttLabel
        "62" => 1, // free navigation (master)
        "63" => 0, // page level course completion 
        "74" => 1, //CertificateEnabled
        "72" => "sélectionnez <b> suivant </b> pour continuer.",
        '75' => "Aides à l'apprentissage",
        '76' => "Quitter le cours",
    );
    public static $assessment_scqmcq_sets = array(
        "59" => "<i>Sélectionnez les bonnes réponse, puis cliquez sur <strong>Soumettre.</strong></i>",
        "60" => "<i>Sélectionnez les bonnes réponses, puis cliquez sur <strong>Soumettre.</strong></i>",
        "61" => "VALIDER",
        "68" => "Veuillez sélectionner une réponse.",
    );
}
