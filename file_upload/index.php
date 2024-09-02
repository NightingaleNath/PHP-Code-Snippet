<?php require 'functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Upload File</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords" content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="#">

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="files\bower_components\bootstrap\css\bootstrap.min.css">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="files\assets\icon\icofont\css\icofont.css">

    <!-- jpro forms css -->
    <link rel="stylesheet" type="text/css" href="files\assets\pages\j-pro\css\j-pro-modern.css">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="files\assets\css\style.css">

    <script src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css"></script>

</head>

<body class="fix-menu">

    <?php include("loader.php") ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="j-wrapper j-wrapper-640">
                    <form class="j-pro" id="j-pro" enctype="multipart/form-data" novalidate="">
                        <div class="j-content">
                            <div class="j-unit">
                                <div class="j-input j-append-big-btn">
                                    <label class="j-icon-left" for="file_input">
                                        <i class="icofont icofont-download"></i>
                                    </label>
                                    <div class="j-file-button">
                                        Browse
                                        <input type="file" name="file_name" accept=".jpg,.jpeg,.png,.doc,.docx,.pdf,.txt" onchange="document.getElementById('file_input').value = this.value;">
                                    </div>
                                    <input type="text" id="file_input" readonly="" placeholder="no file selected">
                                    <span class="j-hint">Only: jpg / png / doc / pdf / txt, not greater 1Mb</span>
                                </div>
                            </div>
                            <!-- start response from server -->
                            <div class="j-response"></div>
                            <!-- end response from server -->
                        </div>
                        <div class="j-footer">
                            <button id="upload_file" type="submit" class="btn btn-primary">Send</button>
                            <button type="reset" class="btn btn-default m-r-20">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <!-- File grid card start -->
            <div class="card">
                <div class="card-header">
                    <h5>File List</h5>
                </div>
                <div class="card-block">
                    <div class="row">
                        <?php if (!empty($files)) : ?>
                            <?php foreach ($files as $file) : ?>
                                <div class="col-lg-4 col-sm-6">
                                    <div class="thumbnail">
                                        <div class="thumb">
                                            <?php if (strpos($file['file_type'], 'image/') === 0) : ?>
                                                <!-- Display image files -->
                                                <a href="<?= htmlspecialchars($file['file_path']); ?>" data-lightbox="file-gallery" data-title="<?= htmlspecialchars($file['file_name']); ?>">
                                                    <img src="<?= htmlspecialchars($file['file_path']); ?>" alt="<?= htmlspecialchars($file['file_name']); ?>" class="img-fluid img-thumbnail">
                                                </a>
                                            <?php else : ?>
                                                <!-- Display document files -->
                                                <a href="<?= htmlspecialchars($file['file_path']); ?>" target="_blank">
                                                    <i class="icofont icofont-file-text"></i> <?= htmlspecialchars($file['file_name']); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="col-12">
                                <p class="text-muted text-center">No files available.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- File grid card end -->
        </div>
    </div>


    <!-- Required Jquery -->
    <script type="text/javascript" src="files\bower_components\jquery\js\jquery.min.js"></script>
    <script type="text/javascript" src="files\bower_components\jquery-ui\js\jquery-ui.min.js"></script>

    <!-- j-pro js -->
    <script type="text/javascript" src="files\assets\pages\j-pro\js\jquery.ui.min.js"></script>
    <script type="text/javascript" src="files\assets\pages\j-pro\js\jquery.maskedinput.min.js"></script>
    <script type="text/javascript" src="files\assets\pages\j-pro\js\jquery.j-pro.js"></script>

    <!-- Custom js -->
    <script type="text/javascript" src="files\assets\pages\j-pro\js\custom\suggestion-form.js"></script>

    <script type="text/javascript" src="files\assets\js\script.js"></script>

    <script type="text/javascript">
        $('#upload_file').click(function(event) {
            event.preventDefault();

            (async () => {
                var formData = new FormData();
                formData.append('action', 'file-upload');

                var fileInput = $('input[type="file"][name="file_name"]')[0];

                if (fileInput.files.length > 0) {
                    formData.append('file_input', fileInput.files[0]);
                } else {
                    Swal.fire({
                        title: 'No File Selected',
                        text: 'Please choose a file before submitting.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    console.log('No file selected.');
                    return;
                }

                console.log('File selected. Form submission started.');

                // Log form data
                console.log('Form data prepared:');
                for (let pair of formData.entries()) {
                    console.log(`${pair[0]}: ${pair[1]}`);
                }

                $.ajax({
                    url: 'upload.php',
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log('success function called');
                        response = JSON.parse(response);
                        console.log('RESPONSE HERE: ' + response.status)
                        console.log(`RESPONSE HERE: ${response.message}`);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                html: response.message,
                                confirmButtonColor: '#01a9ac',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.message,
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('AJAX request failed:', textStatus, errorThrown);

                        Swal.fire({
                            title: 'Error!',
                            text: 'An unexpected error occurred.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            })()
        });
    </script>

</body>

</html>