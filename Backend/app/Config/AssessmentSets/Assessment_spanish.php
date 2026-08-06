<?php

namespace Config\AssessmentSets;

use CodeIgniter\Config\AssessmentSets;

class Assessment_spanish
{
    // Please use unique array id for each template
    // Page level settings
    public static $assessment_sets = array(
        "42" => "Evaluación",
        "33" => "Número total de preguntas: ",
        "34" => "Puntuación mínima para aprobar: ",
        "35" => "Número total de intentos: ",
        "41" => "Tiempo disponible para completar la evaluación: ",
        "43" => "Si no consigue la puntuación mínima, y le quedan algunos intentos, vuelva a realizar la evaluación.",
        "40" => "<i>Seleccione las respuesta(s) correctas y haga clic en <b>Enviar</b>.</i>",
        //"66" => "Seleccione las respuesta correctas y haga clic en <strong>Submitir</strong>.",
        "44" => "Inicio",
        "48" => "<i>Haga clic en <strong>Iniciar</strong> para comenzar.</i>",
        "45" => "ENVIAR",
        "47" => "Ver resultado",
        "46" => "Reintentar",
        "36" => "Ha completado la evaluación.",
        "37" => "Su puntuación:",
        "38" => "¡ Felicidades! Ha aprobado la evaluación.",
        "39" => "No ha aprobado la evaluación. Haga clic <strong>reintentar</strong> para intentar de nuevo.",
        "67" => "No ha aprobado la evaluación. <br>Póngase en contacto con su jefe para conocer los pasos a seguir.",
        "49" => "¡Uy! ¡Se acabó el tiempo!",
        "69" => "Preguntas",
        "70" => "de",
        "71" => "minutos",
        "73" => "<i>Haga clic en la imagen para ampliarla.</i>"

    );
    // Course level settings
    public static $assessment_export_sets = array(
        "50" => "Menú",
        "51" => "SIG",
        "52" => "ANT",
        "53" => "Menú",
        "54" => "Transcripciones",
        "55" => "Continuar",
        "56" => "¿ Seguro que desea continuar donde lo dejó?",
        "57" => "Sí",
        "58" => "No",
        "64" => "en", // VttLanguage
        "65" => "Spanish", // VttLabel
        "62" => 1, // free navigation (master)
        "63" => 0, // page level course completion 
        "74" => 1, //CertificateEnabled
        "72" => "seleccione <b> siguiente </b> para continuar.",
        '75' => "Ayudas para el aprendizaje",
        '76' => "Salir del curso",
    );
    public static $assessment_scqmcq_sets = array(
        "59" => "Seleccione las respuesta correctas y haga clic en <strong>Submitir</strong>.",
        "60" => "Seleccione las respuestas correctas y haga clic en <strong>Submitir</strong>.",
        "61" => "ENVIAR",
        "68" => "Por favor seleccione en respuesta."

    );
}
