$(document).ready(function () {
    function checkCpm() {
        $.ajax({
            url: "/chart/latestData",
            method: "GET",
            success: function (response) {
                console.log("Response:", response); // Tambahkan log untuk memeriksa respons

                if (response.alert) {
                    $("#alert-container").html(`
                            <div class="alert alert-warning solid alert-right-icon alert-dismissible fade show">
                                <span><i class="mdi mdi-alert"></i></span>
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                                    <span><i class="mdi mdi-close"></i></span>
                                </button>
                                <strong>Peringatan!</strong> Tingkat radiasi tinggi di sekitar.
                            </div>
                        `);
                }
            },
        });
    }

    setInterval(checkCpm, 500);
});
