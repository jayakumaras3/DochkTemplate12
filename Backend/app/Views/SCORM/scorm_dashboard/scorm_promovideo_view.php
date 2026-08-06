<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DoChek</title>

    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #000;
        }

        #videoContainer {
            width: 100vw;
            height: 100vh;
        }

        #videoElement {
            width: 100%;
            height: 100%;
            object-fit: contain; /* use cover if you want full stretch */
            background: black;
        }
    </style>
</head>

<body>

<div id="videoContainer">
    <video id="videoElement" controls controlsList="nodownload">
        <source src="<?= $videoUrl ?>" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

</body>
</html>