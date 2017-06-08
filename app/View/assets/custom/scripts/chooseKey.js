/**
 * Created by chloecorfmat on 08/06/2017.
 */

window.addEventListener("load", initialiser);

function initialiser(e) {

	document.getElementsByName("key_type").forEach(function(type) {
		type.addEventListener("change", function() {
			var selected = document.querySelector("input[name = 'key_type']:checked").value;
			var divSelect = document.querySelector(".js-select-locks");
			var select = document.querySelector("select");

			if (selected == 'simple') {
				divSelect.style.display = "block";
				if (select.hasAttribute("multiple")) {
					select.removeAttribute("multiple");
				}
			} else if (selected == 'partial') {
				divSelect.style.display = "block";
				if (!select.hasAttribute("multiple")) {
					select.setAttribute("multiple", "multiple");
				}
			} else {
				divSelect.style.display = "none";
			}
		});
	});
}
