<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('User_login/client_users'); ?>">Users</a></li>

                </ol>
            </div>
            <h4 class="page-title">Upload Bulk Email</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-body">
                <form class="form-inline" enctype="multipart/form-data" action="<?php echo base_url('User_login/client_users/importemail'); ?>" method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="mb-3">
                        <div class="input-group file">
                            <input type="file" name="file" id="file" accept=".xlsx" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light" id="submitButton">
                            Upload Bulk Email
                        </button>
                    </div>
                    <?php if (isset($excelvalidation)) : ?>
                        <div class=col-12 col-sm-4>
                            <div class="alert alert-danger" role="alert">
                                <?= $excelvalidation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>


<div id="textToCopy" style="display: none;">Welcomedochek@123</div>
<br /><br /><br /><br /><br /><br /><br /><br />
<script>
    function displayText() {
        var textToCopy = document.getElementById("textToCopy").innerText;
        alert("Text to Copy: " + textToCopy);
    }

    function copyText() {
        var textToCopy = document.getElementById("textToCopy").innerText;

        // Create a textarea element, set its value, and append it to the document
        var textarea = document.createElement("textarea");
        textarea.value = textToCopy;
        document.body.appendChild(textarea);

        // Select the text inside the textarea
        textarea.select();

        // Copy the selected text to the clipboard
        document.execCommand("copy");

        // Remove the temporary textarea from the document
        document.body.removeChild(textarea);

        // Update the button text to indicate that the text has been copied
        var copyButton = document.getElementById("copyButton");
        copyButton.innerText = "Copied!";

        // Reset the button text after a short delay
        setTimeout(function() {
            copyButton.innerText = "Copy";
        }, 2000); // Adjust the delay as needed
    }
</script>