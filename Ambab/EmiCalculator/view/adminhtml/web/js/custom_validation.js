require([
  "Magento_Ui/js/lib/validation/validator",
  "jquery",
  "mage/translate",
], function (validator, $) {
  var pattern = "/^[a-zA-Z ]*$/";
  var alpha = /^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/


  validator.addRule(
    "validate-alpha-space",
    function (v) {
      return Validation.get("IsEmpty").test(v) || alpha.test(v);
    },
    $.mage.__("Please use letters only (a-z or A-Z) in this field.")
  );
});
