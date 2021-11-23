define([
    "jquery",
    "jquery/ui"
], function($){
    "use strict";
 
    function main(config, element) {
        var $element = $(element);
        var AjaxUrl = config.AjaxUrl;
        var emiamount = config.emiamount;
        var duration = config.duration;
        var rateOfInterest = config.rateOfInterest;
        var interestPM = config.interestPM;
        var totalAmount = config.totalAmount;
         


        $(document).ready(function(){
            setTimeout(function(){
                $.ajax({
                    context: '#ajaxresponse',
                    url: AjaxUrl,
                    type: "POST",
                    data:[
                        {emiamount:emiamount},
                        {duration:duration},
                        {rateOfInterest:rateOfInterest},
                        {interestPM:interestPM},
                        {totalAmount:totalAmount}
                        ] ,
                }).done(function (data) {
                    $('#ajaxresponse').html(data.output);
                    return true;
                });
            },2000);
        });
 
 
    };
    return main;
});