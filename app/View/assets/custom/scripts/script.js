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
					url: "/Ajax/delete.php",
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
							if (data.keys != null) {
								var str = '';
								for (var key in data.keys) {
									console.log (data.keys[key].key_id);

									var str_locks = '';
									for (var i = 0; i < data.keys[key].key_locks.length; i++) {
										str_locks += '<p>' + data.keys[key].key_locks[i] + '</p>';
									}

									var attr_number = '';
									if (data.keys[key].key_number < 3) {
										attr_number += "text-danger";
									} else if (data.keys[key].key_number < 6) {
										attr_number += "text-warning";
									}

									str += '<tr>' +
										'<td>' + data.keys[key].key_id + '</td>' +
										'<td>' + data.keys[key].key_name + '</td>' +
										'<td>' + data.keys[key].key_type + '</td>' +
										'<td>' + str_locks + '</td>' +
										'<td><p class ="' + attr_number + '">' + data.keys[key].key_number + '</p></td>' +
										'<td>' +
										'<form action="./?action=updatekey" method="post">' +
										'<input type="hidden" name="update" value="' + data.keys[key].key_id + '">' +
										'<button type="submit" class="btn blue btn-sm">Modifier</button>' +
										'</form>' +
										'<input type="hidden" name="delete" value="' + data.keys[key].key_id + '">' +
										'<button type="submit" class="btn red btn-sm btn-delete" value="' + data.keys[key].key_id + '">Supprimer</button>' +
										'</td>'
									'</tr>';
								}
								document.querySelector('tbody').innerHTML = str;
								var btns = document.getElementsByClassName('btn-delete');
								for (var i = 0; i < btns.length; i++) {
									btns[i].addEventListener('click', deleteKey);
								}
							} else {
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
