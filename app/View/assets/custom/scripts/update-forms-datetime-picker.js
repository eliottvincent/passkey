/**
 * Created by eliottvincent on 05/06/2017.
 */

var ComponentsDateTimePickers = function () {


	var handleDatetimePicker = function () {

		if (!jQuery().datetimepicker) {
			return;
		}

		$(".form_datetime").datetimepicker({
			autoclose: true,
			isRTL: App.isRTL(),
			format: "dd MM yyyy - hh:ii",
			pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
		});

	};


	return {
		//main function to initiate the module
		init: function () {
			handleDatetimePicker();
		}
	};

}();

if (App.isAngularJsApp() === false) {
	jQuery(document).ready(function() {
		ComponentsDateTimePickers.init();
	});
}
