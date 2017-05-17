/**
 * Created by chloecorfmat on 16/05/2017.
 */

window.addEventListener('load', initialiser);

function initialiser(e) {
	var btns = document.getElementsByClassName('btn-delete-k');
	for (var i = 0; i < btns.length; i++) {
		btns[i].addEventListener('click', deleteKey);
	}
}

function deleteKey() {
	var value = this.getAttribute('value');
	swal({
		title: 'Êtes-vous sûr de vouloir supprimer cette clé ?',
		text: 'Cette action est irréversible',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Supprimer',
		showLoaderOnConfirm: true,
		preConfirm: function () {
			return new Promise(function (resolve, reject) {
				$.ajax({
					//url: "delete.php",
					url: "delete.php",
					type: "POST",
					data: {
						value: value,
					},
					dataType: "json",
					success: function (data) {
						if( data.status == 'error' ) {
							swal("Erreur !", "Merci de réessayer", "error");
						} else {
							swal("Fait !", "La clé a bien été supprimée", "success");
							if (data.keys == null) {
								// S'il n'y a plus de clés --> Affichage message d'erreur
								var div = document.createElement('div');
								div.setAttribute('class', 'alert alert-danger alert-dismissable');
								var button = document.createElement('button');
								button.setAttribute('type', 'button');
								button.setAttribute('class', 'close');
								button.setAttribute('data-dismiss', 'alert');
								button.setAttribute('aria-hidden', true);
								var p = document.createElement('p');
								p.innerHTML = 'Nous n\'avons aucune clé d\'enregistrée.';
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
