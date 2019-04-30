// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable( {
	"language": {
            "lengthMenu": "Mostrar _MENU_ resultados por página",
            "zeroRecords": "No se han encontrado resultados",
            "info": "Página _PAGE_ de _PAGES_",
            "infoEmpty": "No se han encontrado resultados",
            "infoFiltered": "(fltrado de _MAX_ resultados totales)"
        }
  });
});
