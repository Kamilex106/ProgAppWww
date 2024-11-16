$(document).ready(function() {
    $("#search").keyup(function() {
        var search_term = $(this).val();
        if (search_term.length > 2) {
            $.ajax({
                url: "./utils/search.php",
                type: "GET",
                data: { search: search_term },
                success: function(response) {
                    $("#search-results").html(response);
                }
            });
        } else {
            $("#search-results").empty();
        }
    });
});