<!DOCTYPE html>
<html>

<head>
    <meta http-equi="Content-Type" content="text/html;charset=utf-8" />
    <title>Launch</title>
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo base_url(); ?>/public/css/build/css/custom.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Leckerli+One' rel='stylesheet' type='text/css'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js">
    </script>
    <!---- TQEdit start---------->
    <script>
        var LaunchFileV1;
        const result = "<?php echo $result; ?>";
        console.log(result);
        function LoadScormManifestfile(path) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    myFunction(this);
                }
            };
            xhttp.open("GET", path, true);
            xhttp.send();
        }

        function myFunction(xml) {
            var xmlDoc = xml.responseXML;
            var x = xmlDoc.getElementsByTagName("resource");
            LaunchFileV1 = x[0].getAttribute('href');
          //  console.log(LaunchFileV1);
           // code = <?php //echo $code; ?>;
          //  cid = <?php //echo $cid; ?>;
          //  console.log(code, cid);


            if (LaunchFileV1 && LaunchFileV1.length > 0) {
                var scormpath = "<?php echo $SCORMpath ?>";
                var launchFile = scormpath + '/' + LaunchFileV1;
                console.log("Launch file: " + launchFile);
                loadVideo(launchFile);
            }
        }

        function scormGetValues() {
            let result_out = result.replace(/---/g, " ");
            //alert(result_out);
            return result_out;
        }

        function loadVideo(videoURL) {
         
            var iframe = document.createElement('iframe');
            iframe.style.border = 'none';
            iframe.style.width = '100%';
            iframe.style.height = '100%';
            iframe.src = videoURL;

            var contentDiv = document.getElementById('contentloader');
            contentDiv.innerHTML = '';
            contentDiv.appendChild(iframe);

            // Adjust dimensions based on screen size
            var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            var screenHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
            iframe.style.width = screenWidth + 'px';
            iframe.style.height = screenHeight + 'px';
        }

        window.onload = function() {
            var path = "<?php echo $SCORMpath . '/tincan.xml' ?>";
            LoadScormManifestfile(path);
        };
    </script>
    <div id="contentloader"></div>