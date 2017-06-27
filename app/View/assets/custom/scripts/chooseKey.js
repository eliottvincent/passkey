/**
 * Created by chloecorfmat on 08/06/2017.
 */

window.addEventListener("load", initialiser);

function initialiser(e) {

	document.getElementsByName("key_type").forEach(function(type) {
		type.addEventListener("change", function() {

			var selected = document.querySelector("input[name = 'key_type']:checked").value;
			var select = document.querySelector("select");

			var multipleLockSelectDiv = document.querySelector("#multipleLockSelectDiv");
			var simpleLockSelectDiv = document.querySelector("#simpleLockSelectDiv");
			var multipleLockSelect = document.querySelector("#multipleLockSelect");
			var simpleLockSelect = document.querySelector("#simpleLockSelect");

			if (selected === 'simple') {
				multipleLockSelect.disabled = true;
				multipleLockSelectDiv.style.display = "none";

				simpleLockSelect.disabled = false;
				simpleLockSelectDiv.style.display = "block";
			}
			else if (selected === 'partial') {
				simpleLockSelect.disabled = true;
				simpleLockSelectDiv.style.display = "none";

				multipleLockSelect.disabled = false;
				multipleLockSelectDiv.style.display = "block";
			}
			else {
				multipleLockSelectDiv.style.display = "none";
				simpleLockSelectDiv.style.display = "none";
			}
		});
	});
}
