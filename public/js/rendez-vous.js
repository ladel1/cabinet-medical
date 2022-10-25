function weekend(date){
    return (date.getDay()===0)
}

jQuery(document).ready(function($){
    $("#rendez_vous_semaine").flatpickr({
        locale:"fr",
        minDate: "today",
        disable:[weekend]
    });


    $("#rendez_vous_semaine").change(function(){
        var date = $(this).val() ;
        var medecin = $("#rendez_vous_medecin").val();
        $.get("/rendez-vous/api/disponibilite/"+date+"/"+medecin,function(response){
            $("#table-dispo").html(response);
        });
    });

    $("#table-dispo").on("click",".abled", function(){
        $(".abled").removeClass("selected");
        $(this).addClass("selected");
        $("#rendez_vous_dateDebut").val($(this).attr("data"));
    })

});