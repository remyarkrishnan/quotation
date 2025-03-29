$(document).on("click", '.tabs li', function (e) {
    var tabId = $(this).data("tab");
    $(".tabs li").removeClass("active");
    $(this).addClass("active");
    $(".profile-tab-content").removeClass("active");
    $("#" + tabId).addClass("active");
});

$(document).on("click",".add_lead_req", function(){
    let lastRowNo = $("table.lead_req_tbl tr:last").data("tr-no");
    $.ajax({
        url: "Lead_manager/get_extra_lead_req_tr", 
        type: "GET",
        data: { lastRowNo: lastRowNo },
        success: function(response){
            $("table.lead_req_tbl tbody").append(response);
            
        },
        error: function(xhr, status, error){
            console.log("Error:", error);
        }
    });
});

$(document).on("click", ".del_lead_req", function () {
    
    $(".lead_req_tbl input[type='checkbox']:checked").each(function () {
        let checkboxId = $(this).attr("id"); // Example: check_1, check_2
        let rowId = checkboxId.replace("check", "tr"); // Convert check_1 -> tr_1
        let confirmation = confirm("Are you sure you want to delete this row?");
        if (confirmation) {
            $("#" + rowId).remove(); // Remove the corresponding row
        }
    });
});

$(document).on("click", ".upload-icon", function(){
    $(this).siblings(".file-input").click();
});

// When an image is selected, show it in the corresponding preview
$(document).on("change", ".file-input", function(event){
    var file = event.target.files[0];
    var reader = new FileReader();
    var previewImg = $(this).siblings(".image-preview").find("img");
    $(this).siblings(".image-preview").show();
    if (file) {
        reader.onload = function(e) {
            previewImg.attr("src", e.target.result);
        };
        reader.readAsDataURL(file);
    }
});