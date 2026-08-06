<link rel="stylesheet" href="<?php echo base_url('public/aristo_assets/css/review.css'); ?>">

</head>
<!-- Sidebar -->
<div class="w3-sidebar w3-bar-block w3-card w3-animate-left" style="display:none" id="leftMenu">
  <button onclick="closeLeftMenu()" class="w3-bar-item w3-button w3-large">Close &times;</button>

    <!-- Sidebar Logo -->
    <img src="<?php echo base_url('assets/assets/uploads/Aristo_Theme/images/logo_.png'); ?>" alt="logo" height="60px">
    <!-- Tabs for Menu and Transcript (Horizontal Layout) -->
    <div class="w3-bar" style="display: flex; justify-content: space-between;">
        <button class="w3-bar-item w3-button tablink" onclick="toggleTab(event, 'Menu')" id="defaultOpen">Menu</button>
        <button class="w3-bar-item w3-button tablink" onclick="toggleTab(event, 'Transcript')">Transcript</button>
        <hr />
    </div>

    <!-- Menu Tab Content -->
    <div id="Menu" class="tabcontent" style="display:block;">
        <?php foreach ($pagedetails as $page) {
            if ($page['sub_page_main'] == 0) { ?>
                <a href="<?php echo base_url('SCORM/Course_builder/review_course/videolaunch/' . $course_id . '/' . $page['page_id']); ?>" class="w3-bar-item w3-button" style="text-decoration: none;"><?php echo $page['page_name']; ?></a>
        <?php }
        } ?>
    </div>

    <!-- Transcript Tab Content -->
    <div id="Transcript" class="tabcontent" style="display:none;">
        <?php if (isset($transcript)) {
            foreach ($transcript as $script) { ?>
                <div class="transcript-item">
                    <?php echo $script['audio']; ?>
                </div>
        <?php }
        } ?>
    </div>

</div>

<!-- JavaScript for Tab Toggling -->
<script>
    // Function to toggle between "Menu" and "Transcript" tabs
    function toggleTab(evt, tabName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablink" and remove the "active" class
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" w3-blue", "");
        }

        // Show the current tab and add an "active" class to the button that opened it
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " w3-grey";
    }

    // Set default tab to open (Menu tab)
    document.getElementById("defaultOpen").click();
</script>


<script>
    function openLeftMenu() {
        document.getElementById("leftMenu").style.display = "block";
    }

    function closeLeftMenu() {
        document.getElementById("leftMenu").style.display = "none";
    }
   
</script>