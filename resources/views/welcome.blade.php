<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css"
        integrity="sha512-1k7mWiTNoyx2XtmI96o+hdjP8nn0f3Z2N4oF/9ZZRgijyV4omsKOXEnqL1gKQNPy2MTSP9rIEWGcH/CInulptA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<style>
</style>

<body>
    <div class="container mt-5">
        <h1>Dropzone</h1>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modal">
            Add Data
        </button>

        <!-- Modal -->
        <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Data Fill</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" onsubmit="return false;" enctype="multipart/form-data" id="myform">
                            <input type="hidden" name="allimage" id="allimage" value="">
                            <input type="hidden" name="folder" id="folder" value="">
                            <input type="hidden" name="hid" id="hid" value="">

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Firstname:</label>
                                <input type="text" class="form-control" id="firstname" name="firstname"
                                    value="">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Lastname:</label>
                                <input type="text" class="form-control" id="lastname" name="lastname"
                                    value="">
                            </div>
                            <div class="mt-2 mb-2 lol">
                                <label class="form-check-label">Gender:</label> <input type="radio"
                                    class="form-check-input" id="male" name="gender" value="male"> Male
                                <input type="radio" class="form-check-input" id="female" name="gender"
                                    value="female"> Female
                            </div>
                            <div class="mt-2 mb-2">
                                <label for="">Status:</label>
                                <select class="form-select" id="status" name="status"
                                    aria-label="Default select example">
                                    <option selected disabled>Select..</option>
                                    <option value="0">Active</option>
                                    <option value="1">Inactive</option>
                                </select>
                            </div>
                            <div class="mt-2 mb-2">
                                <label for="">Language:</label>
                                <select class="form-select" id="language" name="language"
                                    aria-label="Default select example">
                                    <option selected disabled>Select..</option>
                                    <option value="English">English</option>
                                    <option value="French">French</option>
                                    <option value="Italian">Italian</option>
                                    <option value="Russain">Russain</option>
                                </select>
                            </div>

                            <div id="dropzone" class="dropzone" style="border-radius: 20px;">
                                <div class="dz-message" data-dz-message>
                                    <span>Drag and drop files here or click to upload.</span>
                                </div>
                            </div>

                            <div id="img" class="img row">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <table class="table table-striped table-hover" id="table">
            <thead>
                <tr>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Gender</th>
                    {{-- <th>status</th> --}}
                    <th>Language</th>
                    {{-- <th>Image</th> --}}
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $("#myform").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '/add',
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: new FormData(this),
                    success: function(data) {
                        $('#modal').modal('hide');
                        $('#myform').trigger("reset");
                        $('#table').DataTable().ajax.reload();
                        $("#folder").val("");
                        $("#hid").val("");
                        $("#allimage").val("");
                    }
                });
            });

            $("#modal").on("hidden.bs.modal", function() {
                $('#myform').trigger("reset");
                $("#img").html("");
                Dropzone.forElement("#dropzone").removeAllFiles();
            });

            $('#table').DataTable({
                destroy: true,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                ajax: {
                    url: '/list'
                },
                columns: [{
                        data: "firstname"
                    },
                    {
                        data: "lastname"
                    },
                    {
                        data: "gender"
                    },
                    {
                        data: "language"
                    },
                    // {data: "image" ,
                    // "orderable": false},
                    {
                        data: "action",
                        "orderable": false
                    }
                ]
            });

            $(document).on("click", "#edit_id", function() {
                var id = $(this).data("id");
                $.ajax({
                    type: 'get',
                    url: '/edit',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id
                    },
                    success: function(response) {
                        // console.log(response);   
                        $("#modal").modal("show");
                        $("#hid").val(response[0].id);
                        $("#firstname").val(response[0].firstname);
                        $("#lastname").val(response[0].lastname);
                        $('input[type="radio"]').filter('[value=' + response[0].gender + ']')
                            .prop("checked", true);
                        $("#status").val(response[0].status);
                        $("#language").val(response[0].language);
                        $("#img").html(response[1]);
                        $("#allimage").val(response[2]);

                    }
                });
            });

            $(document).on("click", "#delete_id", function() {
                var id = $(this).data("id");
                $.ajax({
                    type: "post",
                    url: "/deleteimage",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#table').DataTable().ajax.reload();
                    }
                });
            });

            $(document).on("click", ".delete", function() {
                var deleteButton = $(this); 
                var img_id = $(this).data("id");
                var id = $(this).attr("id");

                $.ajax({
                    type: "post",
                    url: "/deleteimg",
                    data: {
                        id: id,
                        img_id: img_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        var allImages = $("#allimage").val().split(","); 
                        var index = allImages.indexOf(response); 

                        if (index !== -1) { 
                            allImages.splice(index, 1); 
                            $("#allimage").val(allImages.join(",")); 
                            if (allImages.length === 0) {
                                $("#allimage").val(''); 
                            }
                        } else {
                            console.log("Image not found in allimage array");
                        }
                        
                        var imageUrl = "image/" + id + "/" + response;
                        $('#img img[src="' + imageUrl + '"]').remove();
                        $('#modal img').each(function() {
                            var modalImageUrl = $(this).attr('src');
                            if (modalImageUrl.endsWith(response)) {
                                $(this).remove();
                                return false;
                            }
                        });
                        deleteButton.remove();
                    }
                });
            });

    });


        Dropzone.autoDiscover = false;
        const myDropzone = new Dropzone("#dropzone", {
            paramName: "file",
            url: "/upload",
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            maxFileSize: 10,
            addRemoveLinks: true,
            uploadMultiple: true,
            parallelUploads: 10,
            maxFiles: 10,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        myDropzone.on("removedfile", function(file, response) {
            var imageName = file.name;
            // var folderName = Date.now() + "_temp";
            var folderName = $("#folder").val();
            $.ajax({
                type: "post",
                url: "/deleteupload",
                data:{
                image: imageName,
                folder: folderName
                },
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                
                    var allImages = $("#allimage").val().split(","); 
                    var index = allImages.indexOf(imageName);
                    if (index !== -1) {
                        allImages.splice(index, 1);
                        $("#allimage").val(allImages.join(","));
                    }
                    
                },
                error: function(xhr, status, error) {
                }
            });
        });

        myDropzone.on("addedfile", function(file) {
            console.log("File added: " + file.name);
        });

        myDropzone.on("successmultiple", function(file, responseText) {
            // console.log(responseText);
            var folderName = responseText[0];
            $("#folder").val(folderName);
            var existingImageNames = $("#allimage").val() ? $("#allimage").val().split(',') : [];
            for (var i = 1; i < responseText.length; i++) {
                existingImageNames.push(responseText[i]);
            }
            $("#allimage").val(existingImageNames.join(','));
        });

        myDropzone.on("error", function(file, errorMessage) {
            console.log("Error uploading file: " + file.name);
            console.log("Error message: ", errorMessage);
        });

        myDropzone.on("sending", function(file, xhr, formData) {
            var hiddenId = $("#hid").val();
            formData.append("hidden_id", hiddenId);
        });

    </script>
</body>

</html>
