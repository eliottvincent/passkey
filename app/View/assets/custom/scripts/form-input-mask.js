/**
 * Created by eliottvincent on 01/06/2017.
 */
var FormInputMask = function () {

	var handleInputMasks = function () {


		$("#form_control_1_mask").inputmask({
			"mask": "9",
			"repeat": 10,
			"greedy": false
		}); // ~ mask "9" or mask "99" or ... mask "9999999999"
	};

	return {
		//main function to initiate the module
		init: function () {
			handleInputMasks();
		}
	};

}();

if (App.isAngularJsApp() === false) {
	jQuery(document).ready(function() {
		FormInputMask.init(); // init metronic core componets
	});
}
