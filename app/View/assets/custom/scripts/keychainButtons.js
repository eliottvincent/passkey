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

	var kbtnsBis = document.getElementsByClassName('btn-duplicate-kc');
	if (kbtnsBis !== null) {
		for (var i = 0; i < kbtnsBis.length; i++) {
			kbtnsBis[i].addEventListener('click', duplicateKeychain);
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

function duplicateKeychain() {
	var id = this.getAttribute('value');
	swal({
		title: 'Quel sera le nouveau nom du trousseau dupliqué ?',
		type: 'info',
		showCancelButton: true,
		confirmButtonText: 'Dupliquer',
		showLoaderOnConfirm: true,
		input: 'text',
		inputPlaceholder: 'Mon trousseau dupliqué',
		inputValidator: function (value) {
			return new Promise(function (resolve, reject) {
				if (true) {
					resolve()
				} else {
					reject('Vous devez specifier un nombre de jour entier.')
				}
			})
		},
		allowOutsideClick: false
	}).then(function (result) {
		return new Promise(function (resolve, reject) {
			$.ajax({
				url: "/?action=duplicateKeychainAjax",
				type: "POST",
				data: {
					value: encodeURIComponent(id),
					name: encodeURIComponent(result)
				},
				dataType: "json",
				success: function (data) {
					if( data.status === 'error' ) {
						swal("Erreur !", "Merci de réessayer", "error");
					} else {
						swal("Fait !", "Le trousseau a bien été dupliqué", "success");

						var trToDuplicate = document.querySelector('#' + id).cloneNode(true);
						$(trToDuplicate).attr('id', 'kc_' + result.replace(' ', '_').toLowerCase());

						//$(trToDuplicate).find(".td-kc-id").innerText = "test";
						//$(trToDuplicate).find(".td-kc-name").innerText = "test";
						//ne marche pas
						// reste à changer l'id et le nom

						document.querySelector('tbody').appendChild(trToDuplicate);
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					swal("Erreur !", "Merci de réessayer", "error");
				}
			});
		})
	});
}


function myFunction() {

	console.log("test");
}
