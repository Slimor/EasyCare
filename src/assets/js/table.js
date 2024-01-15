$(document).ready(function() {
  $('#patients').DataTable( {
      "language": {
        "decimal":        "",
        "emptyTable":     "Brak wyników",
        "info":           "_START_ do _END_ z _TOTAL_",
        "infoEmpty":      "0 do 0 z 0",
        "infoFiltered":   "(filtered from _MAX_ total entries)",
        "infoPostFix":    "",
        "thousands":      ",",
        "lengthMenu":     "Pokaż _MENU_ pacjentów",
        "loadingRecords": "Ładowanie",
        "processing":     "",
        "search":         "Wyszukaj:",
        "zeroRecords":    "Brak wyników",
        "paginate": {
            "first":      "Pierwsza",
            "last":       "Ostatna",
            "next":       "Następna",
            "previous":   "Poprzedna"
        },
        "aria": {
            "sortAscending":  ": activate to sort column ascending",
            "sortDescending": ": activate to sort column descending"
        }
    }
  } );
} );