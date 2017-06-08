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

function deleteDoor() {
	var id = this.getAttribute('value');
	console.log(id);
	swal({
		title: 'Êtes-vous sûr de vouloir supprimer cette porte ?',
		text: 'Cette action est irréversible',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Supprimer',
		showLoaderOnConfirm: true,
		preConfirm: function () {
			return new Promise(function (resolve, reject) {
				$.ajax({
					url: "/?action=deleteDoorAjax",
					type: "POST",
					data: {
						value: encodeURIComponent(id),
					},
					dataType: "json",
					success: function (data) {
						if( data.status === 'error' ) {
							swal("Erreur !", "Merci de réessayer", "error");
						} else {
							swal("Fait !", "La porte a bien été supprimée", "success");
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
