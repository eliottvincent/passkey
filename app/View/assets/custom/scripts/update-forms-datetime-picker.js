/**
 * Created by eliottvincent on 05/06/2017.
 */

var ComponentsDateTimePickers = function () {

	var handleDatePickers = function () {

		if (jQuery().datepicker) {
			$('.date-picker').datepicker({
				rtl: App.isRTL(),
				orientation: "left",
				format: "yyyy-mm-dd",
				autoclose: true
			});
		}
	}

	return {
		//main function to initiate the module
		init: function () {
			handleDatePickers();
		}
	};

}();

if (App.isAngularJsApp() === false) {
	jQuery(document).ready(function() {
		ComponentsDateTimePickers.init();
	});
}
