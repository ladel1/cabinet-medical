function weekend(date) {
    return (date.getDay() === 0);
}
jQuery(document).ready(function($){

    $("#ava").on("click","table .abled",function(){
        $("table td").removeClass("selected");
        $(this).addClass("selected");
    });

    $("#selector").flatpickr({                
        dateFormat: "Y-m-d",
        minDate: "today",
        locale:'fr',
        disable: [weekend],
    });  
    
    $("#selector").change(function(){
        var date = $(this).val();
        var medecin = $("#rendez_vous_medecin").val();
        $.get("/rendez-vous/get/"+date+"/"+medecin,(data)=>{
            $("#ava").html(data);
        });
    })
});