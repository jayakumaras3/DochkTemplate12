<body>
    <div style="margin-bottom: 20px;">
        <img src="<?php echo $logo; ?>" alt="Header Image" class="header-img" height="40px" />
    </div>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            font-size: 18pt;
            background-color: #325f8bff;
            border: 5px solid #325f8bff;
            color: white;
            margin-bottom: 15px;
        }

        h4 {
            font-size: 22pt;
            font-weight: bold;
            margin-bottom: 20px;
            margin-top: 20px;
            padding-bottom: 10px;
            padding-top: 10px;
        }

        h6 {
            font-size: 14pt;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 2px;
        }

        .audio-box {
            font-size: 12pt;
            background-color: #ffffff;
            margin-bottom: 15px;
            color: #000000;
        }

        .blue-line {
            border: none;
            height: 2px;
            background-color: #325f8bff;
            margin: 10px 0 20px 0;
        }
    </style>

    <?php if ($full_sb[0]['language'] == 'French') {
        $Audiotrancript = 'Transcription audio';
    } elseif ($full_sb[0]['language'] == 'Spanish') {
        $Audiotrancript = ' Transcripción del audio';
    } elseif ($full_sb[0]['language'] == 'Russian') {
        $Audiotrancript = 'Audio Transcript';
    } elseif ($full_sb[0]['language'] == 'Portuguese') {
        $Audiotrancript = 'Audio Transcript';
    } elseif ($full_sb[0]['language'] == 'Bahasa') {
        $Audiotrancript = 'Audio Transcript';
    } elseif ($full_sb[0]['language'] == 'Arabic') {
        $Audiotrancript = 'Audio Transcript';
    } elseif ($full_sb[0]['language'] == 'German') {
        $Audiotrancript = 'Audio-Transkript';
    } elseif ($full_sb[0]['language'] == 'Italian') {
        $Audiotrancript = 'Trascrizione audio';
    } else {
        $Audiotrancript = 'Audio Transcript';
    } ?>
    <h2><?php echo $Audiotrancript ?></h2>
    <h4><?php echo htmlspecialchars($full_sb[0]['course_name']) ?></h4>

    <?php foreach ($full_sb as $sb) {
        if (!empty($sb['audio'])) { ?>
            <h6><?php echo $sb['page_name'] ?></h6>
            <div class="audio-box"><?php echo $sb['audio'] ?></div>
            <!-- <hr class="blue-line" /> -->
        <?php }
    } ?>
</body>