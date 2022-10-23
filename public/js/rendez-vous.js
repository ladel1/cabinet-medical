       //$("#selector").flatpickr().l10n.weekdays.longhand = ['Dimanche', 'Lun', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
       $("#selector").flatpickr().l10n.weekdays.shorthand = ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'];
       $("#selector").flatpickr().l10n.months.longhand =['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']
       console.log($("#selector").flatpickr());
       function weekend(date) {
           return (date.getDay() === 0);
       }
       jQuery(document).ready(function($){
           
           $("#selector").flatpickr({                
               dateFormat: "Y-m-d",
               minDate: "today",
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