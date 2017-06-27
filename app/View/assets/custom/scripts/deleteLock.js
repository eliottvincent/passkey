/**
 * Created by eliottvincent on 03/06/2017.
 */

window.addEventListener('load', initialiser);

function initialiser(e) {

	var lbtns = document.getElementsByClassName('btn-delete-l');
	if (lbtns !== null) {
		for (var i = 0; i < lbtns.length; i++) {
			lbtns[i].addEventListener('click', deleteLock);
		}
	}
}

function deleteLock() {
	var value = this.getAttribute('value');
	swal({
		title: 'Êtes-vous sûr de vouloir supprimer ce canon ?',
		text: 'Cette action est irréversible',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Supprimer',
		showLoaderOnConfirm: true,
		preConfirm: function () {
			return new Promise(function (resolve, reject) {
				$.ajax({
					//url: "delete.php",
					url: "/?action=deleteLockAjax",
					type: "POST",
					data: {
						value: encodeURIComponent(value),
					},
					dataType: "json",
					success: function (data) {
						if( data.status === 'error' ) {
							swal("Erreur !", "Merci de réessayer", "error");
						} else {
							swal("Fait !", "Le canon a bien été supprimé", "success");
							if (data.locks === null) {
								// S'il n'y a plus de canon --> Affichage message d'erreur
								var div = document.createElement('div');
								div.setAttribute('class', 'alert alert-danger alert-dismissable');
								var button = document.createElement('button');
								button.setAttribute('type', 'button');
								button.setAttribute('class', 'close');
								button.setAttribute('data-dismiss', 'alert');
								button.setAttribute('aria-hidden', true);
								var p = document.createElement('p');
								p.innerHTML = 'Nous n\'avons aucun canon d\'enregistré.';
								div.appendChild(button);
								div.appendChild(p);
								document.querySelector('.page-content').insertBefore(div, document.querySelector('.row'));

								document.querySelector('tbody').innerHTML = '';
							} else {
								var tr = document.querySelector('#' + value);
								document.querySelector('tbody').removeChild(tr);
							}

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
