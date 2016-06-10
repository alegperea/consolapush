function mostrarTabla(nombre)
{
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
      "sUrl": "",
      "oPaginate": {
        "sFirst": "Primero",
        "sPrevious": "Anterior",
        "sNext": "Siguiente",
        "sLast": "Último"
      }
    },
    "iDisplayLength": 10,
    "sDom": "<'row padding-dt'<'col-xs-6'lT><'col-xs-6'f>r>t<'row padding-dt'<'col-xs-6'i><'col-xs-6'p>>",
    /*"tableTools": {
      "sSwfPath": "/assets/swf/copy_csv_xls_pdf.swf",
      "aButtons": [
        {
          "sExtends": "collection",
          "sButtonText": "<div>Grabar</div>",
          "aButtons": ["csv", "xls", "pdf"]
        }
      ]
    }*/
  });
  return oTable;
}

function mostrarTabla3(nombre)
{
  $(document).ready(function () {
    oTable = $(nombre).dataTable({
      "oLanguage": {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 registros",
        "sInfoFiltered": "(filtrado de _MAX_ registros en total)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "oPaginate": {
          "sFirst": "Primero",
          "sPrevious": "Anterior",
          "sNext": "Siguiente",
          "sLast": "Último"
        }
      },
      "iDisplayLength": 50,
      "sPaginationType": "bootstrap",
      "sDom": "<'row padding-dt'<'span6'l><'span6'f>r>t<'row padding-dt'<'span6'i><'span6'p>>"
    });
  });
}


/*
 function addTagFormDeleteLink($tagFormLi, value) {
 //var $removeFormA = $('<a class="boton borrar" href="#"> <img id="img_semaforo" class="borrar clear-parent" src="/images/delete.png" style="padding-left : 1em; cursor: pointer"></a>');
 var $removeFormA = $('<a class="boton borrar" href="#"><label style="color:red; line-height: 28px;font-weight:bold">Eliminar </label><img id="img_semaforo" class="borrar clear-parent" src="/images/delete.png" style="padding-left : 1em; cursor: pointer"></a>');
 $tagFormLi.append($removeFormA);
 
 $removeFormA.on('click', function(e) {
 // evita crear el enlace con una "#" en la URL
 e.preventDefault();
 
 // quita el li de la etiqueta del formulario
 $tagFormLi.remove();
 });
 }
 
 function addTagForm(collectionHolder, $newLinkLi, value) {
 // Get the data-prototype we explained earlier
 var prototype = collectionHolder.attr('data-prototype');
 
 // Replace '$$name$$' in the prototype's HTML to
 // instead be a number based on the current collection's length.
 var newForm = prototype.replace(/\$\$name\$\$/g, collectionHolder.children().length);
 
 // Display the form in the page in an li, before the "Add a tag" link li
 var $newFormLi = $('<ol class="campos"></ol>').append(newForm);
 $newLinkLi.before($newFormLi);
 addTagFormDeleteLink($newFormLi, value);
 
 }
 
 
 function agregarBorrar(collectionHolder, value)
 {
 // Get the div that holds the collection of tags
 //var collectionHolder = $(".direcciones");
 
 // setup an "add a tag" link
 var $addTagLink = $('<a href="#" class="add_tag_link boton">Agregar ' + value + '</a>');
 var $newLinkLi = $('<ol></ol>').append($addTagLink);
 
 jQuery(document).ready(function() {
 // Añade un enlace para borrar todas las etiquetas existentes
 // en elementos li del formulario
 collectionHolder.find('ol.campos').each(function() {
 addTagFormDeleteLink($(this), value);
 });
 // add the "add a tag" anchor and li to the tags ul
 collectionHolder.append($newLinkLi);
 
 $addTagLink.on('click', function(e) {
 // prevent the link from creating a "#" on the URL
 e.preventDefault();
 
 // add a new tag form (see next code block)
 addTagForm(collectionHolder, $newLinkLi, value);
 });
 });
 }
 */
function iniciarTiny2(idtiny) {
  new TINY.editor.edit(idtiny, {
    id: idtiny,
    width: "100%",
    height: "300px",
    cssclass: 'tinyeditor',
    controlclass: 'tinyeditor-control',
    rowclass: 'tinyeditor-header',
    dividerclass: 'tinyeditor-divider',
    controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'subscript', 'superscript', '|',
      'orderedlist', 'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign',
      'centeralign', 'rightalign', 'blockjustify', '|', 'unformat', '|', 'undo', 'redo', 'n',
      'font', 'size', 'style', '|', 'image', 'hr', 'link', 'unlink', '|', 'print'],
    footer: true,
    fonts: ['Verdana', 'Arial', 'Georgia', 'Trebuchet MS'],
    xhtml: true,
    bodyid: 'editor',
    footerclass: 'tinyeditor-footer'
  });
}

function post(idtiny) {
  idtiny.post();
}

function eliminarRestaurar(url_delete, url_restore) {
  $(document).ready(function () {

    $(".bootbox-confirm").each(function () {
      $(this).on('click', function () {
        var entity_id = $(this).attr('entity_id');
        var eliminado = $(this).attr('eliminado');
        if (eliminado == 1) {
          bootbox.confirm("¿Está seguro que lo desea restaurar?", function (result) {
            if (result) {
              $.ajax({
                type: 'post',
                url: url_restore,
                data: {
                  id: entity_id
                },
                success: function () {
                  $("td#" + entity_id).html("<span class=\"color-green\"><i class=\"fa fa-eye bigger-130\"></i></span>");
                  $("a[entity_id=" + entity_id + "]").html("<span class=\"red\"><i class=\"fa fa-trash-o bigger-130\"></i></span>");
                  $("a[entity_id=" + entity_id + "]").attr('eliminado', 0);
                  bootbox.alert("Restauración éxitosa");
                }
              });
            }
          });
        }
        else {

          bootbox.confirm("¿Está seguro que desea eliminar?", function (result) {
            if (result) {
              $.ajax({
                type: 'post',
                url: url_delete,
                data: {
                  id: entity_id
                },
                success: function () {
                  var close = "<span class=\"color-red\"><i class=\"fa fa-eye-slash bigger-130\"></i></span>";
                  $("td#" + entity_id).html(close);
                  var restore = "<span class=\"green\"><i class=\"fa fa-refresh bigger-130\"></i></span>";
                  $("a[entity_id=" + entity_id + "]").html(restore);
                  $("a[entity_id=" + entity_id + "]").attr('eliminado', 1);
                  bootbox.alert("La eliminación se realizo con éxito");
                }
              });
            }
          });
        }
      });
    });
  });
}

function eliminarRestaurarDatatables(url_delete, url_restore, tabla) {
  var rows = tabla.fnGetNodes();
  for (var i = 0; i < rows.length; i++) {
    boton = $(rows[i]).find("td a.bootbox-confirm");
    $(boton).on('click', function () {
      var entity_id = $(this).attr('entity_id');
      var eliminado = $(this).attr('eliminado');
      if (eliminado == 1) {
        bootbox.confirm("¿Está seguro que lo desea restaurar?", function (result) {
          if (result) {
            $.ajax({
              type: 'post',
              url: url_restore,
              data: {
                id: entity_id
              },
              success: function () {
                $("td#" + entity_id).html("<span class=\"color-green\"><i class=\"fa fa-eye bigger-130\"></i></span>");
                $("a[entity_id=" + entity_id + "]").html("<span class=\"red\"><i class=\"fa fa-trash-o bigger-130\"></i></span>");
                $("a[entity_id=" + entity_id + "]").attr('eliminado', 0);
                bootbox.alert("Restauración éxitosa");
              }
            });
          }
        });
      }
      else {
        bootbox.confirm("¿Está seguro que desea eliminar?", function (result) {
          if (result) {
            $.ajax({
              type: 'post',
              url: url_delete,
              data: {
                id: entity_id
              },
              success: function () {
                var close = "<span class=\"color-red\"><i class=\"fa fa-eye-slash bigger-130\"></i></span>";
                $("td#" + entity_id).html(close);
                var restore = "<span class=\"green\"><i class=\"fa fa-refresh bigger-130\"></i></span>";
                $("a[entity_id=" + entity_id + "]").html(restore);
                $("a[entity_id=" + entity_id + "]").attr('eliminado', 1);
                bootbox.alert("La eliminación se realizo con éxito");
              }
            });
          }
        });
      }
    });
  }
}

function eliminar(url_delete) {
  $(document).ready(function () {
    $(".bootbox-confirm").each(function () {
      $(this).on('click', function () {
        var entity_id = $(this).attr('entity_id');
        bootbox.confirm("¿Está seguro que desea eliminar?", function (result) {
          if (result) {
            $.ajax({
              type: 'post',
              url: url_delete,
              data: {
                id: entity_id
              },
              success: function () {
                var close = "<span class=\"color-red\"><i class=\"fa fa-eye-slash bigger-130\"></i></span>";
                $("td#" + entity_id).html(close);
                var restore = "<span class=\"color-red\"><i class=\"fa fa-times bigger-130\"></i></span>";
                $("a[entity_id=" + entity_id + "]").parent().html(restore);
              }
            });
          }
        });
      });
    });
  });
}


function readOnlyElement(value, options) {
  return $('<span></span>', {text: value});
}

function readOnlyValue(elem, operation, value) {
  if (operation === 'get') {
    return $(elem).text();
  } else if (operation === 'set') {
    $('span', elem).text(value);
  }
}

//enable search/filter toolbar
//jQuery(grid_selector).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true})

//switch element when editing inline
function aceSwitch(cellvalue, options, cell) {
  setTimeout(function () {
    $(cell).find('input[type=checkbox]')
            .wrap('<label class="inline" />')
            .addClass('ace ace-switch ace-switch-5')
            .after('<span class="lbl"></span>');
  }, 0);
}
//enable datepicker
function pickDate(cellvalue, options, cell) {
  setTimeout(function () {
    $(cell).find('input[type=text]')
            .datepicker({format: 'yyyy-mm-dd', autoclose: true});
  }, 0);
}

function agregarIconosJqgrid(grid_selector, pager_selector) {
  //navButtons
  jQuery(grid_selector).jqGrid('navGrid', pager_selector,
          {//navbar options
            edit: true,
            editicon: 'icon-pencil blue',
            add: false,
            addicon: 'icon-plus-sign purple',
            del: false,
            elicon: 'icon-trash red',
            search: true,
            searchicon: 'icon-search orange',
            refresh: true,
            refreshicon: 'icon-refresh green',
            view: true,
            viewicon: 'icon-zoom-in grey',
          },
          {
            //edit record form
            //closeAfterEdit: true,
            recreateForm: true,
            beforeShowForm: function (e) {
              var form = $(e[0]);
              form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
              style_edit_form(form);
            },
            closeAfterEdit: true,
            reloadAfterSubmit: false,
            afterSubmit: function () {
              location.reload();
            }
          },
  {
    //new record form
    closeAfterAdd: true,
    recreateForm: true,
    viewPagerButtons: false,
    beforeShowForm: function (e) {
      var form = $(e[0]);
      form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
      style_edit_form(form);
    }
  },
  {
    //delete record form
    recreateForm: true,
    beforeShowForm: function (e) {
      var form = $(e[0]);
      if (form.data('styled'))
        return false;

      form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
      style_delete_form(form);

      form.data('styled', true);
    },
    onClick: function (e) {
      alert(1);
    }
  },
  {
    //search form
    recreateForm: true,
    afterShowSearch: function (e) {
      var form = $(e[0]);
      form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
      style_search_form(form);
    },
    afterRedraw: function () {
      style_search_filters($(this));
    }
    ,
    multipleSearch: true,
    /**
     multipleGroup:true,
     showQuery: true
     */
  },
          {
            //view record form
            recreateForm: true,
            beforeShowForm: function (e) {
              var form = $(e[0]);
              form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
            }
          }
  )
}

function style_edit_form(form) {
  //enable datepicker on "sdate" field and switches for "stock" field
  form.find('input[name=sdate]').datepicker({format: 'yyyy-mm-dd', autoclose: true})
          .end().find('input[name=stock]')
          .addClass('ace ace-switch ace-switch-5').wrap('<label class="inline" />').after('<span class="lbl"></span>');

  //update buttons classes
  var buttons = form.next().find('.EditButton .fm-button');
  buttons.addClass('btn btn-sm').find('[class*="-icon"]').remove();//ui-icon, s-icon
  buttons.eq(0).addClass('btn-primary').prepend('<i class="icon-ok"></i>');
  buttons.eq(1).prepend('<i class="icon-remove"></i>')

  buttons = form.next().find('.navButton a');
  buttons.find('.ui-icon').remove();
  buttons.eq(0).append('<i class="icon-chevron-left"></i>');
  buttons.eq(1).append('<i class="icon-chevron-right"></i>');
}


function style_delete_form(form) {
  var buttons = form.next().find('.EditButton .fm-button');
  buttons.addClass('btn btn-sm').find('[class*="-icon"]').remove();//ui-icon, s-icon
  buttons.eq(0).addClass('btn-danger').prepend('<i class="icon-trash"></i>');
  buttons.eq(1).prepend('<i class="icon-remove"></i>')
}

function style_search_filters(form) {
  form.find('.delete-rule').val('X');
  form.find('.add-rule').addClass('btn btn-xs btn-primary');
  form.find('.add-group').addClass('btn btn-xs btn-success');
  form.find('.delete-group').addClass('btn btn-xs btn-danger');
}
function style_search_form(form) {
  var dialog = form.closest('.ui-jqdialog');
  var buttons = dialog.find('.EditTable')
  buttons.find('.EditButton a[id*="_reset"]').addClass('btn btn-sm btn-info').find('.ui-icon').attr('class', 'icon-retweet');
  buttons.find('.EditButton a[id*="_query"]').addClass('btn btn-sm btn-inverse').find('.ui-icon').attr('class', 'icon-comment-alt');
  buttons.find('.EditButton a[id*="_search"]').addClass('btn btn-sm btn-purple').find('.ui-icon').attr('class', 'icon-search');
}

function beforeDeleteCallback(e) {
  var form = $(e[0]);
  if (form.data('styled'))
    return false;

  form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
  style_delete_form(form);

  form.data('styled', true);
}

function beforeEditCallback(e) {
  var form = $(e[0]);
  form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
  style_edit_form(form);
}



//it causes some flicker when reloading or navigating grid
//it may be possible to have some custom formatter to do this as the grid is being created to prevent this
//or go back to default browser checkbox styles for the grid
function styleCheckbox(table) {
  /**
   $(table).find('input:checkbox').addClass('ace')
   .wrap('<label />')
   .after('<span class="lbl align-top" />')
   
   
   $('.ui-jqgrid-labels th[id*="_cb"]:first-child')
   .find('input.cbox[type=checkbox]').addClass('ace')
   .wrap('<label />').after('<span class="lbl align-top" />');
   */
}


//unlike navButtons icons, action icons in rows seem to be hard-coded
//you can change them like this in here if you want
function updateActionIcons(table) {
  /**
   var replacement = 
   {
   'ui-icon-pencil' : 'icon-pencil blue',
   'ui-icon-trash' : 'icon-trash red',
   'ui-icon-disk' : 'icon-ok green',
   'ui-icon-cancel' : 'icon-remove red'
   };
   $(table).find('.ui-pg-div span.ui-icon').each(function(){
   var icon = $(this);
   var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
   if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
   })
   */
}

//replace icons with FontAwesome icons like above
function updatePagerIcons(table) {
  var replacement =
          {
            'ui-icon-seek-first': 'icon-double-angle-left bigger-140',
            'ui-icon-seek-prev': 'icon-angle-left bigger-140',
            'ui-icon-seek-next': 'icon-angle-right bigger-140',
            'ui-icon-seek-end': 'icon-double-angle-right bigger-140'
          };
  $('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function () {
    var icon = $(this);
    var $class = $.trim(icon.attr('class').replace('ui-icon', ''));

    if ($class in replacement)
      icon.attr('class', 'ui-icon ' + replacement[$class]);
  })
}

function enableTooltips(table) {
  $('.navtable .ui-pg-button').tooltip({container: 'body'});
  $(table).find('.ui-pg-div').tooltip({container: 'body'});
}

//var selr = jQuery(grid_selector).jqGrid('getGridParam','selrow');

function cargarIconos(table) {
  styleCheckbox(table);
  updateActionIcons(table);
  updatePagerIcons(table);
  enableTooltips(table);
}

function addTagForm($collectionHolder, $newLinkLi) {
  // Get the data-prototype explained earlier
  var prototype = $collectionHolder.data('prototype');

  // get the new index
  var index = $collectionHolder.data('index');

  // Replace '__name__' in the prototype's HTML to
  // instead be a number based on how many items we have
  var newForm = prototype.replace(/__name__/g, index);

  // increase the index with one for the next item
  $collectionHolder.data('index', index + 1);

  // Display the form in the page in an li, before the "Add a tag" link li
  var $newFormLi = $('<li></li>').append(newForm);

  $newLinkLi.before($newFormLi);
  addTagFormDeleteLink($newFormLi);
  var div = $('#' + $collectionHolder.attr('id') + "_" + index).parent();
  $(div).children().first().remove();

}

function addTagFormDeleteLink($tagFormLi) {
  var $removeFormA = $('<a class="btn-u btn-brd rounded-3x btn-u-red btn-u-xs" style="color:black;"><i class="fa fa-trash"></i> Eliminar</a>');
  $tagFormLi.append($removeFormA);

  $removeFormA.on('click', function (e) {
    // prevent the link from creating a "#" on the URL
    e.preventDefault();

    // remove the li for the tag form
    $tagFormLi.remove();
  });
}

function addEditTagsCollection(id) {
  var $collectionHolder;

  // setup an "add a tag" link
  //var $addTagLink = $('<a href="#" class="add_tag_link">Add a tag</a>');
  $(id).parent().children().first().append("<a id=\"add-tag-link\" class=\"jskink\"><i style=\"margin-left:10px;\"class=\"fa fa-plus\"></i></a>");
  var $addTagLink = $('#add-tag-link')
  var $newLinkLi = $('<li></li>');



  jQuery(document).ready(function () {
    // Get the ul that holds the collection of tags
    $collectionHolder = $(id);

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagLink.on('click', function (e) {
      // prevent the link from creating a "#" on the URL
      e.preventDefault();

      // add a new tag form (see next code block)
      addTagForm($collectionHolder, $newLinkLi);
    });

    if ($collectionHolder.length > 1) {
      $collectionHolder.find('li').each(function () {
        addTagFormDeleteLink($(this));
      });
    }
  });
}

function agregarEliminar(id) {
  /*    $( id + " section > label.label" ).each(function( index ) {
   $(this).remove();
   });*/
  $(id + " section").each(function (index) {
    $(this).remove();
  });
}

function resolucionCollection() {
  var $collectionHolder;

  var $addResolucionLink = $('<a href="#" class="add_resolucion_link btn btn-info" style="color:white" ><i class="fa fa-plus-square"></i> Agregar Resolucion</a>');
  var $newLinkLi = $('<li class="pull-right li-resolucion"></li>').append($addResolucionLink);

  jQuery(document).ready(function () {
    $collectionHolder = $('ul#resoluciones');

    $collectionHolder.find('li.li-resolucion').each(function () {
      addResolucionFormDeleteLink($(this));
    });

    $collectionHolder.append($newLinkLi);

    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addResolucionLink.on('click', function (e) {
      e.preventDefault();

      addResolucionForm($collectionHolder, $newLinkLi);
    });
  });
}

function addResolucionForm($collectionHolder, $newLinkLi) {
  var prototype = $collectionHolder.data('prototype');

  var index = $collectionHolder.data('index');

  var newForm = prototype.replace(/__name__/g, index);

  $collectionHolder.data('index', index + 1);

  var $newFormLi = $('<li></li>').append(newForm);
  $newLinkLi.before($newFormLi);

  addResolucionFormDeleteLink($newFormLi);

  $(".date").datepicker({
    dateFormat: 'dd/mm/yy',
    prevText: '<i class="fa fa-angle-left"></i>',
    nextText: '<i class="fa fa-angle-right"></i>'
  });
}

function addResolucionFormDeleteLink($resolucionFormLi) {
  var $removeFormA = $('<a href="#" class="btn btn-danger" style="color:white"><i class="fa  fa-times-circle"></i> Eliminar Resolucion</a>');
  $resolucionFormLi.append($removeFormA);

  $removeFormA.on('click', function (e) {
    // prevent the link from creating a "#" on the URL
    e.preventDefault();

    // remove the li for the tag form
    $resolucionFormLi.remove();
  });
}
