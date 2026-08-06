<?php

namespace Config\AssessmentSets;

use CodeIgniter\Config\AssessmentSets;

class Assessment_portuguese
{
    // Please use unique array id for each template
    // Page level settings
    public static $assessment_sets = array(
        "42" => "Avaliação",
        "33" => "Número total de questões:",
        "34" => "Pontuação mínima para aprovação: ",
        "35" => "Número total de tentativas: ",
        "41" => "Tempo concedido para concluir a avaliação: ",
        "43" => "Se não atingir a pontuação mínima e ainda tiver mais tentativas, refaça a avaliação.",
        "40" => "<i>Selecione a(s) resposta(s) correta(s) e clique em <strong>Enviar.</strong></i>",
        // "66" => "<i>Select the best answers, then click Submit</i>",
        "44" => "Iniciar",
        "48" => "<i>Clique em <strong>Iniciar</strong> para começar.</i>",
        "45" => "ENVIAR",
        "47" => "Ver resultado",
        "46" => "Tentar novamente",
        "36" => "Você concluiu a avaliação!",
        "37" => "Sua pontuação:",
        "38" => "Parabéns! Você passou na avaliação.",
        "39" => "Você não passou na avaliação. Clique em <strong>Tentar novamente</strong> para fazer uma nova tentativa.",
        "67" => "Você não passou na avaliação. Contate seu gerente para receber instruções.",
        "49" => "Ops! O tempo acabou!",
        "69" => "Perguntas",
        "70" => "de",
        "71" => "minutos",
        "73" => "<i>Clique em para Zoom.</i>"

    );
    // Course level settings
    public static $assessment_export_sets = array(
        "50" => "Menu",
        "51" => "PRÓXIMO",
        "52" => "ANTERIOR",
        "53" => "Menu",
        "54" => "Transcrição",
        "55" => "Retomar",
        "56" => "Tem certeza de que deseja continuar de onde parou?",
        "57" => "Sim",
        "58" => "Não",
        "64" => "po", // VttLanguage
        "65" => "Protuguese", // VttLabel
        "62" => 1, // free navigation (master)
        "63" => 0, // page level course completion 
        "74" => 1, //CertificateEnabled
        "72" => "selecione <b> próximo </b> para continuar.",
        '75' => "LearningAids"
    );
    public static $assessment_scqmcq_sets = array(
        "59" => "<i>Selecione a(s) resposta correta(s) e clique em <strong>Enviar.</strong></i>",
        "60" => "<i>Selecione a(s) resposta(s) correta(s) e clique em <strong>Enviar.</strong></i>",
        "61" => "ENVIAR",
        "68" => "Selecione uma resposta.",
    );
}
