require(
    [
        'Magento_Ui/js/lib/validation/validator',
        'jquery',
        'mage/translate'
], function(validator, $){
    var pattern = '/^[a-zA-Z ]*$/';

    validator.addRule(
        'custom-alpha-validate',
        function (value) {                          
            if(value.match(pattern)){   //Here you will get your field's value                      
                console.log("worked");
                return true;
            }else {
                console.log("not worked");
                return false;

            }
        }
        ,$.mage.__('Enter Valued Not Supported')
    );
    
});


