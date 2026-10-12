<script>
    function confirmLogout(event) {
        event.preventDefault();

        Swal.fire({
            title: "Keluar dari Sistem?",
            text: "Anda akan keluar dari akun saat ini.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Keluar",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.closest('form').submit();
            }
        });
    }

    function previewImage(inputSelector, previewSelector) {
        const input = document.querySelector(inputSelector);
        const preview = document.querySelector(previewSelector);

        if (!input || !preview) return;

        input.addEventListener('change', function() {
            const file = this.files[0];

            if (!file) {
                preview.src = "";
                preview.style.display = "none";
                return;
            }

            if (!file.type.startsWith("image/")) {
                alert("Please select an image file.");
                input.value = "";
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            };

            reader.readAsDataURL(file);
        });
    }

    // init
    document.addEventListener("DOMContentLoaded", function() {
        previewImage("#image-upload-one", "#image-preview-one");
        previewImage("#image-upload-two", "#image-preview-two");
        previewImage("#image-upload-three", "#image-preview-three");
        previewImage("#image-upload-four", "#image-preview-four");
    });


    $(function() {

        $('.delete-btn').on('click', function(e) {

            e.preventDefault();

            const form = $(this).closest('form');

            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {
                    form.submit();
                }

            });

        });

    });

     $(function() {

            $('.delete-btn-batch').on('click', function(e) {

                e.preventDefault();

                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Hapus Batch Akreditasi?',
                    text: 'Jika batch ini dihapus, seluruh data akreditasi program studi yang terkait pada batch ini juga akan ikut terhapus dan tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

        });

    $(function() {

        $('.complete-accreditation-form').on('submit', function(e) {

            e.preventDefault();

            const form = $(this);

            Swal.fire({
                title: form.data('title'),
                text: form.data('text'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6c757d',
                confirmButtonText: form.data('confirm-text'),
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {
                    form.off('submit').submit();
                }

            });

        });

    });
</script>
