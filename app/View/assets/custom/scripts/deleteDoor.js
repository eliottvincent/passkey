/**
 * Created by eliottvincent on 03/06/2017.
 */

window.addEventListener('load', initialiser);

function initialiser(e) {

	var dbtns = document.getElementsByClassName('btn-delete-d');
	if (dbtns !== null) {
		for (var i = 0; i < dbtns.length; i++) {
			dbtns[i].addEventListener('click', deleteDoor);
		}
	}
}
