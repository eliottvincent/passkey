/**
 * Created by eliottvincent on 03/06/2017.
 */

window.addEventListener('load', initialiser);

function initialiser(e) {

	var bbtns = document.getElementsByClassName('btn-delete-b');
	if (bbtns !== null) {
		for (var i = 0; i < bbtns.length; i++) {
			bbtns[i].addEventListener('click', deleteBorrowing);
		}
	}


}

function deleteBorrowing() {
	var id = this.getAttribute('value');
	swal({
		title: 'Êtes-vous sûr de vouloir supprimer cet emprunt ?',
		text: 'Cette action est irréversible',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Supprimer',
		showLoaderOnConfirm: true,
		preConfirm: function () {
			return new Promise(function (resolve, reject) {
				$.ajax({
					url: "/?action=deleteBorrowingAjax",
					type: "POST",
					data: {
						value: encodeURIComponent(id),
					},
					dataType: "json",
					success: function (data) {
						if( data.status === 'error' ) {
							swal("Erreur !", "Merci de réessayer", "error");
						} else {
							swal("Fait !", "L'emprunt a bien été supprimé", "success");
							var tr = document.querySelector('#' + id);
							document.querySelector('tbody').removeChild(tr);
						}
					},
					error: function (xhr, ajaxOptions, thrownError) {
						swal("Erreur !", "Merci de réessayer", "error");
					}
				});
			})
		},
		allowOutsideClick: false
	});
}
