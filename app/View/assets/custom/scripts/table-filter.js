/**
 * Created by eliottvincent on 03/06/2017.
 */
 window.addEventListener('load', initialiser);

 function initialiser(e) {
	 $('#filterTable tfoot th').each( function () {
		var title = $(this).text().replace(/\s/g, '');
		var re = new RegExp('&'+title+'=(.*?)(&|$)');
		var val = '';
		if(window.location.search.match(re) != null) {
			val = window.location.search.match(re)[1];
		}
		$(this).html( '<input type="text" style="width:90%;" placeholder="'+title+'" value="'+val+'" />');
	} );

	 var table = $('#filterTable').DataTable({
		"language": {
		 "sProcessing":     "Traitement en cours...",
		 "sSearch":         "Rechercher&nbsp;:&nbsp;",
		 "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
		 "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
		 "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
		 "sInfoFiltered":   "</br>(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
		 "sInfoPostFix":    "",
		 "sLoadingRecords": "Chargement en cours...",
		 "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
		 "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
		 "oPaginate": {
			 "sFirst":      "Premier",
			 "sPrevious":   "Pr&eacute;c&eacute;dent",
			 "sNext":       "Suivant",
			 "sLast":       "Dernier"
		 },

		 "oAria": {
			 "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
			 "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
		 }
	 }}
	);

	// Apply the search
	table.columns().every( function () {
		var that = this;

		$( 'input', this.footer() ).on( 'keyup change', function () {
			if ( that.search() !== this.value ) {
				that
					.search( this.value )
					.draw();
			}
		} );
		$( 'input', this.footer() ).trigger('change');
	} );
 }
