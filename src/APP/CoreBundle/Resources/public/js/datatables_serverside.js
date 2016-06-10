function mostrarTablaServerSide(nombre,url) {    

        var oTable = $(nombre).dataTable({
            "oLanguage": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 registros",
                "sInfoFiltered": "(filtrado de _MAX_ registros en total)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sAjaxSource": url,
                "oPaginate": {
                    "sFirst": "Primero",
                    "sPrevious": "Anterior",
                    "sNext": "Siguiente",
                    "sLast": "Último"
                }
            },            
            "bJQueryUI": true,
                    "iDisplayLength": 10,
                    "bProcessing": true,
                 //   "bServerSide": true,
                    "sPaginationType": "full_numbers",
                    "sAjaxSource": url,
                    "sDom": "<'row padding-dt'<'span6'l><'span6'f>r>t<'row padding-dt'<'span6'i><'span6'p>>"
       
        });
    return oTable;            
  
}

function mostrarTablaAlertasServerSide(nombre,url) {
    alert("test");
    $(document).ready(function() {
         oTable = $(nombre).dataTable( {
                "aaSorting": [[ 0, "desc" ]],    
                "oLanguage": {
                        "sProcessing":   "Procesando...",
                        "sLengthMenu":   "Mostrar _MENU_ registros",
                        "sZeroRecords":  "No se encontraron resultados",
                        "sInfo":         "Mostrando desde _START_ hasta _END_ de _TOTAL_ registros",
                        "sInfoEmpty":    "Mostrando desde 0 hasta 0 de 0 registros",
                        "sInfoFiltered": "(filtrado de _MAX_ registros en total)",
                        "sInfoPostFix":  "",
                        "sSearch":       "Buscar:",
                        "sUrl":          "",
                        "oPaginate": {
                            "sFirst":    "Primero",
                            "sPrevious": "Anterior",
                            "sNext":     "Siguiente",
                            "sLast":     "Último"
                        }
                    },
                    "bJQueryUI": true,
                    "iDisplayLength": 10,
                    "bProcessing": true,
                    "bServerSide": true,
                    "sPaginationType": "full_numbers",
                    "sAjaxSource": url,
                    "sDom": "<'row padding-dt'<'col-xs-6'l><'col-xs-6'f>r>t<'row padding-dt'<'col-xs-6'i><'col-xs-6'p>>"
            } );
            
    });
}