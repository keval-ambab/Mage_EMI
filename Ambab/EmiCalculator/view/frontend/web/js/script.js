require(
[
    'jquery',
    'Magento_Ui/js/modal/modal'
],
function($,modal) 
{
    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        title: 'EMI STATUS',
        buttons: [{
            text: $.mage.__('Close'),
            class: '',
            click: function () {
                this.closeModal();
            }
        }]
    };
    var popup = modal(options, $('#popup-modal'));
    $("#lowest_emi_amount").on('click',function()
    {
        $("#popup-modal").modal("openModal");
    });
}
);

require(['jquery','accordion'], function ($) {
$("#element").accordion();
});
