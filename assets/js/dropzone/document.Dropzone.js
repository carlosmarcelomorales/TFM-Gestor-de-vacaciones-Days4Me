window.Dropzone = require('dropzone');
Dropzone.autoDiscover = false;
window.onload = function () {
    new Dropzone("#dropzone_form", {
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 100,
        minFiles: 1,
        maxFiles: 100,
        addRemoveLinks: true,
        removedfile: function (file) {
            var _ref;
            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
        },
        acceptedFiles: '.png,.jpg,.pdf',
        init: function () {
            let myDropzone = this;

            $("#addDocument").click(function (e) {
                e.preventDefault();
                e.stopPropagation();

                if ($('#create_request_typesRequest').val() === '') {
                    alert('You should choose a type of request!!!')
                    return false;
                }

                if ($('#create_request_requestPeriodStart').val() === '') {
                    alert('You should choose a start date!!!')
                    return false;
                }

                if ($('#create_request_requestPeriodEnd').val() === '') {
                    alert('You should choose a end date!!!')
                    return false;
                }

                if ($('#create_request_requestPeriodStart').val() > $('#create_request_requestPeriodEnd').val()) {
                    alert('Error: The start date is greater than end date!!!')
                    return false;

                }

                if ($('#descriptionDocument').val() === '') {
                    alert('You should write a description!!!')
                    return false;
                }


                if (myDropzone.files.length) {
                    myDropzone.processQueue(); // upload files and submit the form

                    myDropzone.on('completemultiple', function () {
                        location.reload();
                    });

                } else {
                    $('#dropzone_form').submit(); // submit the form
                }
            });

        }
    });
}


