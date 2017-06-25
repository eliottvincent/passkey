/**
 * Created by eliottvincent on 03/06/2017.
 */

window.addEventListener('load', initialiser);

function initialiser(e) {

	var deleteButtons = document.getElementsByClassName('btn-delete-b');
	if (deleteButtons !== null) {
		for (var i = 0; i < deleteButtons.length; i++) {
			deleteButtons[i].addEventListener('click', deleteBorrowing);
		}
	}

	var extendButtons = document.getElementsByClassName('btn-extend-b');
	if (extendButtons !== null) {
		for (var i = 0; i < extendButtons.length; i++) {
			extendButtons[i].addEventListener('click', extendBorrowing);
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


function extendBorrowing() {
	var id = this.getAttribute('value');
	swal({
		title: 'De combien voulez-vous prolonger cet emprunt ?',
		text: 'Cette action est irréversible',
		type: 'info',
		showCancelButton: true,
		confirmButtonText: 'Prolonger',
		showLoaderOnConfirm: true,
		input: 'number',
		inputPlaceholder: 'Jour(s)',
		inputAttributes: {min : 1},
		inputValidator: function (value) {
			return new Promise(function (resolve, reject) {
				if (true) {//check is integer and > 1
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
				url: "/?action=extendBorrowingAjax",
				type: "POST",
				data: {
					value: encodeURIComponent(id),
					number: result
				},
				dataType: "json",
				success: function (data) {
					if( data.status === 'error' ) {
						swal("Erreur !", "Merci de réessayer", "error");
					} else {
						swal("Fait !", "L'emprunt a bien été prolongé", "success");
						var tr = document.querySelector('#' + id + ' #dueDate');
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					swal("Erreur !", "Merci de réessayer", "error");
				}
			});
		})
	});
}
