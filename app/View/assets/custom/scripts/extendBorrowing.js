/**
 * Created by eliottvincent on 03/06/2017.
 */

window.addEventListener('load', initialiser);

function initialiser(e) {

	var bbtns = document.getElementsByClassName('btn-extend-b');
	if (bbtns !== null) {
		for (var i = 0; i < bbtns.length; i++) {
			bbtns[i].addEventListener('click', extendBorrowing);
		}
	}


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
						  var tr = document.querySelector('#' + id);
						  document.querySelector('tbody').removeChild(tr);
					  }
				  },
				  error: function (xhr, ajaxOptions, thrownError) {
					  swal("Erreur !", "Merci de réessayer", "error");
				  }
			  });
		  })
	});
}
