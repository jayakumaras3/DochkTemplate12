</div>

<script>
    // CKEditor is only needed on pages with a textarea.ckeditor field. This runs after the
    // page's own content (rendered before footer_view.php) has already been parsed, so any
    // such fields already exist in the DOM. document.write() keeps this genuinely
    // parser-blocking - same as the old unconditional <script src> in header_view.php - so
    // CKEDITOR is guaranteed to be defined before any later script on the page runs,
    // including ones that call CKEDITOR.replace(...) from a window.onload handler.
    if (document.querySelector('.ckeditor')) {
        document.write('<script src="<?php echo base_url(); ?>/public/assets/ckeditor/ckeditor.js"><' + '/script>');
    }
</script>

<?php if (session('theme_color') != 0): ?>
    <script>
        // Theme is decided server-side per page load (session('theme_color'), same as
        // <html data-bs-theme="...">), so swap CKEditor's iframe content stylesheet for the
        // dark variant here rather than trying to sync it live - there's no live toggle to sync.
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.config.contentsCss = [
                '<?php echo base_url(); ?>/public/assets/ckeditor/contents-dark.css'
            ];
        }
    </script>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function() {
            $(".alert").alert('close');
        }, 5000);
        $('#errormeg').hide();
        $('#errormeg').text('');
        var demoArray = {};
        // These tables only exist on specific pages; guard each one so pages without
        // it don't pay for an unnecessary DataTables init (or risk a plugin call on
        // an empty selection).
        if ($('#MultiCheckTable').length) {
            $('#MultiCheckTable').DataTable();
        }
        if ($('#historyOfCourseTable').length) {
            $('#historyOfCourseTable').DataTable();
        }
        if ($('#getreviewersTable').length) {
            $('#getreviewersTable').DataTable();
        }
        if ($('#searchdatatable').length) {
            $('#searchdatatable').DataTable();
        }
        if ($('#example').length) {
            $('#example').DataTable();
        }
        $('#searchdatatable').on('click', '.demoselected', function(e) {
            console.log('searchdatatable clicked');
            demo_id = $(this).attr('data-id');
            activate = $(this).attr('data-activate');
            if (activate === 'true') {
                $('#check' + demo_id).attr('data-activate', false);
                var unique_ids = $('#td' + demo_id).find('input[name="unique_ids"]').val();
                var product_name = $('#td' + demo_id).find('input[name="product_name"]').val();
                var description = $('#td' + demo_id).find('input[name="description"]').val();
                var pdf = $('#td' + demo_id).find('input[name="pdf"]').val();
                var video_two = $('#td' + demo_id).find('input[name="video_two"]').val();
                var video_one = $('#td' + demo_id).find('input[name="video_one"]').val();
                var course_link = $('#td' + demo_id).find('input[name="course_link"]').val();
                var cart_button = $('#td' + demo_id).find('input[name="cart_button"]').val();
                e.preventDefault(); // avoid to execute the actual submit of the form.
                var form = {
                    unique_ids: unique_ids,
                    product_name: product_name,
                    description: description,
                    pdf: pdf,
                    video_two: video_two,
                    video_one: video_one,
                    course_link: course_link,
                    cart_button: cart_button
                };
                var url = '<?php echo base_url('demos/ajaxsubmit') ?>';
                console.log(form);
                $.ajax({
                    type: "POST",
                    url: url,

                    data: form, //form.serialize(), // serializes the form's elements.
                    success: function(newdata) {
                        var obj = JSON.parse(newdata);
                        console.log(obj); // show response from the php script.
                        $('#cartbadge').attr('data-badge', obj.count);
                        // $('.badge1').data('badge', obj.count);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        //console.log('request failed');
                        //alert(xhr.responseText);
                        console.log('request failed ' + xhr.responseText);
                        if (xhr.status == 404) {
                            $('#errormeg').show();
                            $('#errormeg').text('404: Page not found. Please contact admin.');
                        }
                        if (xhr.status == 502) {
                            $('#errormeg').show();
                            $('#errormeg').text('502: Comment not saved. Please Refresh this page & save again.');
                        }
                        if (xhr.status == 504) {
                            $('#errormeg').show();
                            $('#errormeg').text('504: Comment not saved. Please Refresh this page & save again.');
                        }
                    }
                });
            } else {
                $('#check' + demo_id).attr('data-activate', true);
            }
            // console.log('demo_id  ' + demo_id + ' activate ' + activate);
        });

        $('#MultiCheckTable').on('click', '.checkclicked', function() {
            // console.log('someSwitchOptionPrimary 2 clicked');
            project_details_id = $(this).attr('data-id');
            activate = $(this).attr('data-activate');
            if (activate === 'true') {
                $('#check' + project_details_id).attr('data-activate', false);
                $('#check' + project_details_id).prop('checked', true);

            } else {
                $('#check' + project_details_id).attr('data-activate', true);
                $('#check' + project_details_id).prop('checked', false);
            }
            console.log('project_details_id ' + project_details_id + 'activate ' + activate);
            dataString = {
                project_details_id: project_details_id,
                activate: activate,
                emailnotifysubmit: true,
                updatedby: '<?= session()->get('username') ?>'
            };
            $.ajax({
                url: '<?php echo base_url('demos/ajaxsubmit') ?>',
                type: "POST",
                data: dataString,
                success: function(data) {
                    var obj = JSON.parse(data);
                    //  console.log(obj);
                    if (obj.noemail === 'true') {
                        alert("Add Email Address to selected user.");
                    }
                    if (obj.status === 'success') {
                        var msg = 'User Account ' + obj.status + ' Successfully';
                        _flashMessage('success', msg);

                    } else if (obj.status === 'error') {
                        var msg = 'User Account ' + obj.status + ' Successfully';
                    }
                    console.log(msg);
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            })

        });

    });

    function popupfeedback(course_id, pageid, packagetype) {
        var wid = screen.width;
        wid = wid - 15;
        var hig = screen.height;
        hig = hig - 100;
        params = 'width=' + wid;
        params += ', height=' + hig;
        params += ', top=0, left=0';

        if (packagetype == 21 || packagetype == 22) {
            if (pageid == 1) {
                if (reviewid == 14400) {
                    newwin = window.open('<?php echo base_url() ?>/review/index_nestle?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);
                } else {
                    newwin = window.open('<?php echo base_url() ?>/review?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);
                }
            } else {
                newwin = window.open('<?php echo base_url() ?>/review/page?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid, 'windowname4', params);
            }

        } else if (packagetype == 24) { // for GE Testing
            newwin = window.open('<?php echo base_url() ?>/review/unity_index?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);
        } else if (packagetype == 23) {
            newwin = window.open('<?php echo base_url() ?>/review/index_nestle?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);
        } else if (packagetype == 25) { // for GE Testing
            newwin = window.open('<?php echo base_url() ?>/review/fullscreen?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);
        } else if (packagetype == 26) { // for GE Testing
            newwin = window.open('<?php echo base_url() ?>/review/video?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);
        } else if (packagetype == 62) { // for VR Testing
            newwin = window.open('<?php echo base_url() ?>/review/vr_feedback?course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);
        }
        var win_timer = setInterval(function() {
            if (newwin.closed) {
                window.location.reload();
                clearInterval(win_timer);
            }
        }, 100);
        if (window.focus) {
            newwin.focus()
        }
    }

    function popup(reviewid, course_id, pageid, packagetype) {
        var wid = screen.width;
        wid = wid - 15;
        var hig = screen.height;
        hig = hig - 100;
        params = 'width=' + wid;
        params += ', height=' + hig;
        params += ', top=0, left=0';
        if (packagetype == 21 || packagetype == 22) {
            if (pageid == 1) {
                if (reviewid == 14400) {
                    newwin = window.open('<?php echo base_url() ?>/review/index_nestle?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);
                } else {
                    newwin = window.open('<?php echo base_url() ?>/review?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);

                }
            } else {
                newwin = window.open('<?php echo base_url() ?>/review/page?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid, 'windowname4', params);
            }

        } else if (packagetype == 24) { // for GE Testing
            newwin = window.open('<?php echo base_url() ?>/review/unity_index?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);
        } else if (packagetype == 23) {
            newwin = window.open('<?php echo base_url() ?>/review/index_nestle?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);
        } else if (packagetype == 25) { // for GE Testing
            newwin = window.open('<?php echo base_url() ?>/review/fullscreen?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);
        } else if (packagetype == 26) { // for GE Testing
            newwin = window.open('<?php echo base_url() ?>/review/video?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);
        } else if (packagetype == 62) { // for VR Testing
            newwin = window.open('<?php echo base_url() ?>/review/vr_feedback?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);
        }
        var win_timer = setInterval(function() {
            if (newwin.closed) {
                window.location.reload();
                clearInterval(win_timer);
            }
        }, 100);
        if (window.focus) {
            newwin.focus()
        }
    }

    function makeid(length) {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for (var i = 0; i < length; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        return text;
    }

    function popuphistory(reviewid, course_id, pageid, packagetype, prohistoryid, content, linkfrom) {
        var wid = screen.width;
        wid = wid - 15;
        var hig = screen.height;
        hig = hig - 100;
        params = 'width=' + wid;
        params += ', height=' + hig;
        params += ', top=0, left=0,location=no,toolbar=no,menubar=no';
        params += ',"_blank"';
        var rano = Math.random();
        if (packagetype == 21 || packagetype == 22) {
            if (pageid == 1) {
                newwin = window.open('<?php echo base_url() ?>/review/historyindex?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype + '&historyid=' + prohistoryid + '&linkfrom=' + linkfrom + '&rano=' + rano, '"' + makeid(5) + '"', params);
            } else {
                newwin = window.open('<?php echo base_url() ?>/review/historyindex?reviewid=' + reviewid + '&course_id=' + course_id + '&historyid=' + prohistoryid + '&pageid=' + pageid + '&linkfrom=' + linkfrom + '&rano=' + rano, '"' + makeid(5) + '"', params);
            }
        } else if (packagetype == 23) {
            newwin = window.open('<?php echo base_url() ?>/review/single_asset?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, '"' + makeid(5) + '"', params);
        } else if (packagetype == 25) {
            newwin = window.open('<?php echo base_url() ?>/review/fullscreen?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, '"' + makeid(5) + '"', params);
        } else if (packagetype == 26) { // for GE Testing
            newwin = window.open('<?php echo base_url() ?>/review/video?reviewid=' + reviewid + '&course_id=' + course_id + '&pageid=' + pageid + '&packagetype=' + packagetype, 'windowname4', params);
        }
        var win_timer = setInterval(function() {
            if (newwin.closed) {
                window.location.reload();
                clearInterval(win_timer);
            }
        }, 100);
        if (window.focus) {
            newwin.focus()
        }
    }

    function popupscormcourse(scourse_id, upload) {
        var wid = screen.width;
        wid = wid - 15;
        var hig = screen.height;
        hig = hig - 100;
        params = 'width=' + wid;
        params += ', height=' + hig;
        params += ', top=0, left=0,location=no,toolbar=no,menubar=no';
        params += ',"_blank"';

        var rano = Math.random();
        newwin = window.open('<?php echo base_url() ?>/SCORM/scorm_launch?course_id=' + scourse_id + '&foldername=' + upload, 'windowname4', params);

        var win_timer = setInterval(function() {
            if (newwin.closed) {
                window.location.reload();
                clearInterval(win_timer);
            }
        }, 100);
        if (window.focus) {
            newwin.focus()
        }
    }

    function OpenNewWindowmiddlepop(scourse_id, $poptype) {
        var screenWidth = screen.width;
        var screenHeight = screen.height;
        var windowWidth = 800;
        var windowHeight = 450;
        var left = Math.floor((windowWidth) / 2);
        var top = Math.floor((windowHeight) / 2);
        var params = 'width=' + windowWidth;
        params += ', height=' + windowHeight;
        params += ', left=' + left;
        params += ', top=' + top;
        params += ', fullscreen=no';
        params += ', directories=no';
        params += ', location=no';
        params += ', menubar=no';
        params += ', resizable=no';
        params += ', scrollbars=no';
        params += ', status=no';
        params += ', toolbar=no';

        <?php $value = []; ?>
        <?php
        $course_id = "<script>document.write(scourse_id)</script>";
        ?>
        <?php session()->set($course_id); ?>
        var rano = Math.random();
        if ($poptype == 1) {
            newwin = window.open('<?php echo base_url() ?>/SCORM/scorm_dashboard/launchpromo_video', 'Video', params);
        } else if ($poptype == 2) {
            newwin = window.open('<?php echo base_url() ?>/SCORM/scorm_dashboard/launchDescriptionPopup', 'Description', params);
        } else if ($poptype == 3) {
            newwin = window.open('<?php echo base_url() ?>/SCORM/scorm_dashboard/downloadPdf', 'Description', params);
        } else if ($poptype == 4) {
            params = 'width=' + screen.width;
            params += ', height=' + screen.height;
            params += ', top=0, left=0'
            params += ', fullscreen=yes';
            params += ', directories=no';
            params += ', location=no';
            params += ', menubar=no';
            params += ', resizable=no';
            params += ', scrollbars=no';
            params += ', status=no';
            params += ', toolbar=no';
            newwin = window.open('<?php echo base_url() ?>/SCORM/scorm_dashboard/launchyoutube_video?course_id=' + scourse_id, 'Description', params);
        }
        if (window.focus) {
            newwin.focus()
        }
    }

    function OpenNewWindowPdfpop(scourse_id, $poptype) {
        var screenWidth = screen.width;
        var screenHeight = screen.height;
        var windowWidth = Math.floor(screenWidth * 0.9); // Adjust the multiplication factor to control the window width
        var windowHeight = Math.floor(screenHeight * 0.9); // Adjust the multiplication factor to control the window height
        var left = Math.floor((windowWidth) / 2);
        var top = Math.floor((windowHeight) / 2);
        var params = 'width=' + windowWidth;
        params += ', height=' + windowHeight;
        params += ', left=' + left;
        params += ', top=' + top;
        params += ', fullscreen=no';
        params += ', directories=no';
        params += ', location=no';
        params += ', menubar=no';
        params += ', resizable=no';
        params += ', scrollbars=no';
        params += ', status=no';
        params += ', toolbar=no';
        var rano = Math.random();
        if ($poptype == 1) {
            newwin = window.open('<?php echo base_url() ?>/SCORM/scorm_dashboard/launchpromo_video?course_id=' + scourse_id, 'Video', params);
        } else if ($poptype == 2) {
            newwin = window.open('<?php echo base_url() ?>/SCORM/scorm_dashboard/launchDescriptionPopup?course_id=' + scourse_id, 'Description', params);
        } else if ($poptype == 3) {
            newwin = window.open('<?php echo base_url() ?>/SCORM/scorm_dashboard/downloadPdf?course_id=' + scourse_id, 'Description', params);
        }

        if (window.focus) {
            newwin.focus()
        }
    }
</script>

<!-- Footer Start -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div>
                    &COPY; <?php echo date('Y'); ?> DOCHEK | <a href="https://www.touchstonelc.com" target="_blank">Touchstone Product</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-none d-md-flex gap-4 align-item-center justify-content-md-end footer-links">
                    <a target="_blank" href="https://dochek.com/ang/privacy">Privacy</a> |
                    <a target="_blank" href="https://dochek.com/ang/terms">Terms</a>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end Footer -->
</div>
<script>
    let elm = document.getElementById('submitForm');
    if (JSON.stringify(elm) !== "null") {
        document.getElementById('submitForm').addEventListener('submit', function() {
            var button = document.getElementById('submitButton');
            button.disabled = true;
            button.innerHTML = 'Submitting...';
        });
    }
</script>
<script>
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
</script>
<script>
    // Disable right-click
    document.addEventListener('contextmenu', e => e.preventDefault());

    // Disable key shortcuts
    document.onkeydown = function(e) {
        if (e.keyCode == 123) return false; // F12
        // if (e.ctrlKey && e.shiftKey && e.keyCode == 73) return false; // Ctrl+Shift+I
        if (e.ctrlKey && e.keyCode == 85) return false; // Ctrl+U
        if (e.ctrlKey && e.shiftKey && e.keyCode == 67) return false; // Ctrl+Shift+C
    };


    // Function to hide the message box
    function hideMessageBox() {
        const messageBox = document.getElementById('alertdiv');
        if (messageBox) {
            messageBox.style.display = 'none'; // Hides the div completely
        }
    }

    // Function to start the progress bar animation and the countdown
    function startCountdown(durationInSeconds) {
        const progressBar = document.getElementById('progressBar');

        // Set the initial width to 100%
        if (progressBar) {
            progressBar.style.width = '1%';

            // Trigger the CSS transition to zero width over the specified duration
            // The transition property in CSS handles the animation smoothly.
            progressBar.style.width = '100%';
        }
        // Set a timeout to call the hide function after the duration has passed
        setTimeout(hideMessageBox, durationInSeconds * 1000);
    }

    // Start the countdown when the page loads (or when an event occurs)
    // Here, we set it to 5 seconds
    window.onload = function() {
        startCountdown(5);
    };
    // Global session-expiry guard: any jQuery AJAX call (jQuery sends
    // X-Requested-With automatically, which is what SessionVersionFilter checks) that
    // comes back 401 means the session died mid-page - individual `error:` handlers
    // mostly only check for 403 (CSRF token expiry), so without this a dead session
    // just shows a generic "Request failed" alert and leaves the user stuck on the page.
    $(document).ajaxComplete(function(event, xhr) {
        if (xhr.status === 401) {
            window.location.href = '<?php echo base_url('ang/login'); ?>?reason=session-expired';
        }
    });
</script>


<script src="<?php echo base_url(); ?>/public/creative/assets/js/vendor.min.js"></script>

<script src="<?php echo base_url(); ?>/public/creative/assets/js/app.min.js"></script>



<script src="<?php echo base_url(); ?>/public/creative/assets/libs/flatpickr/flatpickr.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/apexcharts/apexcharts.min.js"></script>

<script src="<?php echo base_url(); ?>/public/creative/assets/libs/selectize/js/standalone/selectize.min.js"></script>
<!-- Dashboar 1 init js
<script src="<?php echo base_url(); ?>/public/creative/assets/js/pages/dashboard-1.init.js"></script>-->


<!-- third party js -->
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/select2/js/select2.min.js"></script>




<script src="<?php echo base_url(); ?>/public/creative/assets/libs/nestable2/jquery.nestable.min.js"></script>

<script src="<?php echo base_url(); ?>/public/creative/assets/js/pages/nestable.init.js"></script>

<script src="<?php echo base_url(); ?>/public/creative/assets/libs/mohithg-switchery/switchery.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/multiselect/js/jquery.multi-select.js"></script>


<script src="<?php echo base_url(); ?>/public/creative/assets/js/pages/datatables.init.js"></script>


<script src="<?php echo base_url(); ?>/public/creative/assets/libs/dropzone/min/dropzone.min.js"></script>
<script src="<?php echo base_url(); ?>/public/creative/assets/libs/dropify/js/dropify.min.js"></script>

<script src="<?php echo base_url(); ?>/public/creative/assets/js/pages/form-fileuploads.init.js"></script>


<script src="<?php echo base_url(); ?>public/creative/assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
<!-- <script src="<?php echo base_url(); ?>public/creative/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script> -->
<!-- <script src="<?php echo base_url(); ?>public/creative/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script> -->

<!-- Plugins js-->
<script src="<?php echo base_url(); ?>public/creative/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>

<script src="<?php echo base_url(); ?>public/creative/assets/libs/clockpicker/bootstrap-clockpicker.min.js"></script>
<script src="<?php echo base_url(); ?>public/creative/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(); ?>public/creative/assets/js/pages/form-advanced.init.js"></script>

</body>

</html>