/**
 * Created by eliottvincent on 03/06/2017.
 */

window.addEventListener('load', initialiser);

function initialiser(e) {

	var bbtns = document.getElementsByClassName('btn-delete-b');
	if (bbtns !== null) {
		for (var i = 0; i < bbtns.length; i++) {
			bbtns[i].addEventListener('click', deleteBorrow);
		}
	}
}
