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
    <link href='http://fonts.googleapis.com/css?family=Leckerli+One' rel='stylesheet' type='text/css'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js">
    </script>
    <!---- TQEdit start---------->
    <script>
        var LaunchFileV1;

        function LoadScormManifestfile(path) {

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    myFunction(this);
                }
            };
            //console.log("990000000");
            var path = "<?php echo $SCORMpath . '/imsmanifest.xml' ?>";
            //console.log("dfdfd");
            //console.log(path);

            xhttp.open("GET", path, true);
            xhttp.send();
        }

        function myFunction(xml) {
            var i;
            var xmlDoc = xml.responseXML;
            var x = xmlDoc.getElementsByTagName("resource");
            ////console.log(xmlDoc)
            ////console.log("Launch File::",x[0].getAttribute('href'));
            LaunchFileV1 = x[0].getAttribute('href');

            const myTimeout = setTimeout(myGreeting, 1000);

            function myGreeting() {
                clearTimeout(myTimeout);
                Utils.launchSCO();
            }

        }
    </script>

    <!---- TQEdit End---------->
    <script type="text/javascript">
        launchSCO: function() {
            // Reset the SimpleAPI
            hasTerminated = false;
            hasInitialized = false;
            API.terminated = false;
            API.initialized = false;
            initTimeout = 0;
            timeoutErrorDisplayed = false;

            var launchFileAltVal = $('launchFileAlt').value;

            var cookieNameAltVal = $('cookieNameAlt').value;


            if (LaunchFileV1.length > 0) {
                //TQ Edit
                //launchFile = launchFileAltVal;
                var scormpath = "<?php echo $SCORMpath ?>";
                launchFile = scormpath + '/' + LaunchFileV1;
                //	//console.log("sasdas" + launchFile);
                if (launchFileAltVal.indexOf(":") == 1) {
                    launchFile = launchFile;
                    //	//console.log(launchFile);
                }
            }
            scoWin = this.openWindow(launchFile + embedParam, "SCOwindow", w, h, opts);

        }
        openWindow: function(winURL, winName, winW, winH, winOpts) {
            winOptions = winOpts + ",width=" + winW + ",height=" + winH;
            newWin = window.open(winURL, winName, winOptions);
            newWin.moveTo(0, 0);
            newWin.focus();
            return newWin;
            var tempUrlString = winURL;
            ////console.log(winURL);
            // Create a div element to hold the content
            var contentDiv = document.createElement('div');
            contentDiv.style.width = '100%';
            contentDiv.style.height = '100%';
            contentDiv.style.overflow = 'auto';



            // Create an iframe element to load the content
            var iframe = document.createElement('iframe');
            iframe.style.border = 'none';
            iframe.style.width = '100%';
            iframe.style.height = '100%';
            iframe.src = tempUrlString;
            contentDiv.appendChild(iframe);



            // Create the popup window and add the content div to it
            var winOpts = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes';
            var newWin = window.open('', winName, winOpts);
            newWin.document.body.style.margin = '0';
            newWin.document.body.appendChild(contentDiv);



            // Set the initial size of the popup window
            var screenWidth = screen.availWidth;
            var screenHeight = screen.availHeight;
            newWin.resizeTo(screenWidth, screenHeight);
            newWin.moveTo(0, 0);



            // Update the size of the popup window and content div when the window is resized
            window.addEventListener('resize', function() {
                var newWidth = window.innerWidth;
                var newHeight = window.innerHeight;
                newWin.resizeTo(newWidth, newHeight);
                contentDiv.style.width = newWidth + 'px';
                contentDiv.style.height = newHeight + 'px';
            });



            return newWin;


        };
    </script>

<body>

    <!-- page content -->
    <!-- 	<div class="col-md-12">
		<?php if ($type == 1) { ?>
			<a href="<?= base_url('demo_dashboard') ?>"><button class="btn btn-sm btn-success">Back</button></a>
		<?php } elseif ($type == 2) { ?>
			<a href="<?= base_url('scorm_dashboard') ?>"><button class="btn btn-sm btn-success">Back</button></a>
		<?php } elseif ($type == 3) { ?>
			<a href="<?= base_url('my_training') ?>"><button class="btn btn-sm btn-success">Back</button></a>
		<?php } else { ?>
			<a href="javascript:void(0);"><button class="btn btn-sm btn-success">Back</button></a>
		<?php } ?>
	</div>
 -->


    <div id="container">
        <div style="display:none">
            <fieldset>
                <legend>Event Log <b>( <a href="javascript:void(0);" onclick="Utils.toggleDisplay('debug'); return false;">Toggle</a> | <a href="javascript:void(0);" onclick="Utils.dumpAPI(); return false;">Dump Current API Object</a> )</b></legend>
                <div id="debug"></div>
            </fieldset>
            <fieldset>
                <legend>Options <b>( <a href="javascript:void(0);" onclick="Utils.toggleDisplay('optionSet'); return false;">Toggle</a> )</b></legend>
                <div id="optionSet">
                    <div class="tblRowInstructions">Enter the name of the storage to use for this session (or to clear using the option above):</div>
                    <div class="tblRow">
                        <div class="tblRowHeader">Storage Name:</div><input type="text" id="cookieNameAlt" size="50"> <a href="javascript:void(0);" onclick="Utils.genNewSessionName(); return false;" class="actionLink">Generate New Name</a>
                    </div>
                    <div class="tblRowInstructions">Enter the full/relative path/filename for the SCO's launch file. The default is currently &quot;<script>
                            document.write(launchFile);
                        </script>&quot;. Populated automatically if IMS manifest is present:</div>
                    <div class="tblRow">
                        <div class="tblRowHeader">Launch File:</div><input type="text" id="launchFileAlt" size="30">
                    </div>
                    <div class="tblRowInstructions">Override the default window options (Default values can be changed in the source):</div>
                    <div class="tblRow">
                        <div class="tblRowHeader">Width:</div><input type="text" id="winW" size="10">
                    </div>
                    <div class="tblRow">
                        <div class="tblRowHeader">Height:</div><input type="text" id="winH" size="10">
                    </div>
                    <div class="tblRow">
                        <div class="tblRowHeader">Features:</div>
                        <div class="inlineOption"><label for="wToolbarOption">Toolbar</label> <input type="checkbox" id="wToolbarOption" onclick="Utils.toggleWindowOption('wToolbar',this);"></div>
                        <div class="inlineOption"><label for="wTitlebarOption">Titlebar</label> <input type="checkbox" id="wTitlebarOption" onclick="Utils.toggleWindowOption('wTitlebar',this);"></div>
                        <div class="inlineOption"><label for="wLocationOption">Location</label> <input type="checkbox" id="wLocationOption" onclick="Utils.toggleWindowOption('wLocation',this);"></div>
                        <div class="inlineOption"><label for="wStatusOption">Statusbar</label> <input type="checkbox" id="wStatusOption" onclick="Utils.toggleWindowOption('wStatus',this);"></div>
                        <div class="inlineOption"><label for="wScrollbarsOption">Scrollbars</label> <input type="checkbox" id="wScrollbarsOption" onclick="Utils.toggleWindowOption('wScrollbars',this);"></div>
                        <div class="inlineOption"><label for="wMenubarOption">Menubar</label> <input type="checkbox" id="wMenubarOption" onclick="Utils.toggleWindowOption('wMenubar',this);"></div>
                        <div class="inlineOption"><label for="wResizableOption">Resizable</label> <input type="checkbox" id="wResizableOption" onclick="Utils.toggleWindowOption('wResizable',this);"></div>

                        <div class="optionGroup">
                            <a href="#" onclick="Utils.enableAllWindowOptions();return false;">Select All</a> | <a href="#" onclick="Utils.disableAllWindowOptions();return false;">Deselect All</a>
                        </div>
                    </div>

                    <div class="tblRowInstructions">SimpleAPI behavioral settings:</div>
                    <div class="tblRow"><input type="checkbox" id="closeOnFinishOption" onclick="Utils.toggleCloseOnFinishOption(this.checked);"> <label for="closeOnFinishOption">Close SCO on LMSFinish</label></div>
                    <div style="display:none;" class="tblRow"><input type="checkbox" id="toggleEmbeddedOption" onclick="Utils.toggleEmbeddedParam(this.checked);"> <label for="toggleEmbeddedOption">Launch with custom search string appended to launch file: </label> <input type="text" id="searchString" size="50"></div>
                    <div style="display:none;" class="tblRow"><input type="checkbox" id="toggleCustomKeyValueOption" onclick="Utils.toggleCustomKeyValueOption(this.checked);"> <label for="toggleCustomKeyValueOption">Launch with custom key/value pair injected into API object: </label><input type="text" id="customApiKey" size="30"> = <input type="text" id="customApiValue" size="30"></div>
                    &nbsp;
                </div>
            </fieldset>
        </div>
    </div>
    <iframe style="display: block;     
    background: #000;
    border: none;         
    height: 100vh;       
    width: 100vw;" id="contentloader"></iframe>
</body>

</html>