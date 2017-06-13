/**
 * Created by eliottvincent on 12/06/2017.
 */
/**
 * Created by eliottvincent on 03/06/2017.
 */

window.addEventListener('load', initialiser);

function initialiser(e) {

	var kbtns = document.getElementsByClassName('btn-delete-kc');
	if (kbtns !== null) {
		for (var i = 0; i < kbtns.length; i++) {
			kbtns[i].addEventListener('click', deleteKeychain);
		}
	}
}

function deleteKeychain() {
	var keychainId = this.getAttribute('value');
	swal({
		title: 'Êtes-vous sûr de vouloir supprimer ce trousseau ?',
		text: 'Cette action est irréversible',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Supprimer',
		showLoaderOnConfirm: true,
		preConfirm: function () {
			return new Promise(function (resolve, reject) {
				$.ajax({
					url: "/?action=deleteKeychainAjax",
					type: "POST",
					data: {
						value: encodeURIComponent(keychainId),
					},
					dataType: "json",
					success: function (data) {
						if( data.status === 'error' ) {
							swal("Erreur !", "Merci de réessayer", "error");
						} else {
							swal("Fait !", "Le trousseau a bien été supprimé", "success");
							if (data.keychains === null) {
								// S'il n'y a plus de clés --> Affichage message d'erreur
								var div = document.createElement('div');
								div.setAttribute('class', 'alert alert-danger alert-dismissable');
								var button = document.createElement('button');
								button.setAttribute('type', 'button');
								button.setAttribute('class', 'close');
								button.setAttribute('data-dismiss', 'alert');
								button.setAttribute('aria-hidden', true);
								var p = document.createElement('p');
								p.innerHTML = 'Nous n\'avons aucun trousseau d\'enregistré.';
								div.appendChild(button);
								div.appendChild(p);
								document.querySelector('.page-content').insertBefore(div, document.querySelector('.row'));

								document.querySelector('tbody').innerHTML = '';
							} else {
								var tr = document.querySelector('#' + keychainId);
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
