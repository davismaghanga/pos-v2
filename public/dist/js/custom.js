//setup ajax

var abs_route='http://localhost/payroll/public/';


$.ajaxSetup({

    headers:{
        'X-CSRF-Token':$("meta[name='csrf-token']").attr("content")
    }
});



// $.ajax({
//     type:'POST',
//     url:temp_url,
//     data:{'data':data},
//     success:function(data){
//         var haserror=jQuery.parseJSON(data);
//         console.log(data);
//         if(haserror.error=="1"){
//             var erors=haserror.errors;
//             $('#feedback').html(erors);
//             $('#feedback').slideDown();
//         }else{
//
//             switch(mode){
//                 case 1:
//                     $('#feedback').html("Allowance added, redirecting..");
//                     $('#feedback').slideDown();
//                     getUserAllowances();
//                     break;
//                 case 2:
//                     $('#feedback').html("Deduction added, redirecting..");
//                     $('#feedback').slideDown();
//                     getUserDeductions();
//                     break;
//                 case 3:
//                     $('#feedback').html("Relief added, redirecting..");
//                     $('#feedback').slideDown();
//                     getUserReliefs();
//                     break;
//             }
//             $('#newmodal').modal('toggle');
//         }
//     }
// });


