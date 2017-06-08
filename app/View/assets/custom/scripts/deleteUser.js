/**
 * Created by eliottvincent on 03/06/2017.
 */

window.addEventListener('load', initialiser);

function initialiser(e) {

	var ubtns = document.getElementsByClassName('btn-delete-u');
	if (ubtns !== null) {
		for (var i = 0; i < ubtns.length; i++) {
			ubtns[i].addEventListener('click', deleteUser);
		}
	}
}

function deleteUser() {
	var enssatPrimaryKey = this.getAttribute('value');
	console.log(enssatPrimaryKey);
	swal({
		title: 'Êtes-vous sûr de vouloir supprimer cet utilisateur ?',
		text: 'Cette action est irréversible',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Supprimer',
		showLoaderOnConfirm: true,
		preConfirm: function () {
			return new Promise(function (resolve, reject) {
				$.ajax({
					//url: "delete.php",
					url: "/?action=deleteUserAjax",
					type: "POST",
					data: {
						value: encodeURIComponent(enssatPrimaryKey),
					},
					dataType: "json",
					success: function (data) {
						if( data.status === 'error' ) {
							swal("Erreur !", "Merci de réessayer", "error");
						} else {
							swal("Fait !", "L'utilisateur a bien été supprimé", "success");
							var tr = document.querySelector('#u_' + enssatPrimaryKey);
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
