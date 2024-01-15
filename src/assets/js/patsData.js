$(document).ready(function() {
  var table = $('#patients').DataTable({
    "ajax": {
      "url": "sciezka/do/twojego/serwera.php",
      "dataSrc": "data"
    },
    "columns": [
      { "data": "id" },
      { "data": "name" },
      { "data": "name" },
      { "data": "name" },
      { "data": "name" }
    ]
  });
});