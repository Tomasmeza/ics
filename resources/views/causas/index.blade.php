@extends('layouts.admin')
@section('contenido')

<div class="row">
<div class="col-lg-6 col-xs-12">
  <a href="causas/create"><button class="btn btn-success">Nuevo +</button></a>
</div>
  </div>

<div class="row">
  <div class="col-xs-12">
    <h3 class="header smaller lighter blue">Listado de Categorias</h3>
    <div class="clearfix">
      <div class="pull-right tableTools-container"></div>
    </div>

    <div class="table-header">
      Lista de Areas"
    </div>

      <table class="table table-bordered text-center table-striped table-hover" id="table-causas">
        <thead>
          <th>Id</th>
          <th>Nombre</th>
          <th>Opciones</th>
        </thead>

        @foreach ($causas as $causa)
        <tr>
          <td>{{$causa->id}}</td>
          <td>{{$causa->nombre}}</td>
          <td>
            <div class="hidden-sm hidden-xs action-buttons">
              <a class="blue" href="#">
                <i class="ace-icon fa fa-search-plus bigger-200"></i>
              </a>

              <a class="green" href="{{URL::action('CausasController@edit',$causa->id)}}">
                <i class="ace-icon fa fa-pencil bigger-200"></i>
              </a>

              <a class="red" href="" data-target="#modal-delete-{{$causa->id}}" data-toggle="modal">
                <i class="ace-icon fa fa-trash-o bigger-200"></i>
              </a>
            </div>

          </td>
        </tr>
        @include('causas.modal')
        @endforeach
      </table>
    </div>
  </div>

@endsection

@section('scripts')
<script type="text/javascript">
  jQuery(function($) {
    //initiate dataTables plugin
    var oTable1 =
    $('#table-causas')
    //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
    .dataTable( {

      } );
    //oTable1.fnAdjustColumnSizing();


    //TableTools settings
    TableTools.classes.container = "btn-group btn-overlap";
    TableTools.classes.print = {
      "body": "DTTT_Print",
      "info": "tableTools-alert gritter-item-wrapper gritter-info gritter-center white",
      "message": "tableTools-print-navbar"
    }

    //initiate TableTools extension
    var tableTools_obj = new $.fn.dataTable.TableTools( oTable1, {
      "sSwfPath": "assets/swf/copy_csv_xls_pdf.swf",

      "sRowSelector": "td:not(:last-child)",
      "sRowSelect": "multi",
      "fnRowSelected": function(row) {
        //check checkbox when row is selected
        try { $(row).find('input[type=checkbox]').get(0).checked = true }
        catch(e) {}
      },
      "fnRowDeselected": function(row) {
        //uncheck checkbox
        try { $(row).find('input[type=checkbox]').get(0).checked = false }
        catch(e) {}
      },

      "sSelectedClass": "success",
          "aButtons": [
        {
          "sExtends": "copy",
          "sToolTip": "Copy to clipboard",
          "sButtonClass": "btn btn-white btn-primary btn-bold",
          "sButtonText": "<i class='fa fa-copy bigger-110 pink'></i>",
          "fnComplete": function() {
            this.fnInfo( '<h3 class="no-margin-top smaller">Table copied</h3>\
              <p>Copied '+(oTable1.fnSettings().fnRecordsTotal())+' row(s) to the clipboard.</p>',
              1500
            );
          }
        },

        {
          "sExtends": "csv",
          "sToolTip": "Export to CSV",
          "sButtonClass": "btn btn-white btn-primary  btn-bold",
          "sButtonText": "<i class='fa fa-file-excel-o bigger-110 green'></i>"
        },

        {
          "sExtends": "pdf",
          "sToolTip": "Export to PDF",
          "sButtonClass": "btn btn-white btn-primary  btn-bold",
          "sButtonText": "<i class='fa fa-file-pdf-o bigger-110 red'></i>"
        },

        {
          "sExtends": "print",
          "sToolTip": "Print view",
          "sButtonClass": "btn btn-white btn-primary  btn-bold",
          "sButtonText": "<i class='fa fa-print bigger-110 grey'></i>",

          "sMessage": "<div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Optional Navbar &amp; Text</small></a></div></div>",

          "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                <p>Please use your browser's print function to\
                print this table.\
                <br />Press <b>escape</b> when finished.</p>",
        }
          ]
      } );
    //we put a container before our table and append TableTools element to it
      $(tableTools_obj.fnContainer()).appendTo($('.tableTools-container'));

    //also add tooltips to table tools buttons
    //addding tooltips directly to "A" buttons results in buttons disappearing (weired! don't know why!)
    //so we add tooltips to the "DIV" child after it becomes inserted
    //flash objects inside table tools buttons are inserted with some delay (100ms) (for some reason)
    setTimeout(function() {
      $(tableTools_obj.fnContainer()).find('a.DTTT_button').each(function() {
        var div = $(this).find('> div');
        if(div.length > 0) div.tooltip({container: 'body'});
        else $(this).tooltip({container: 'body'});
      });
    }, 200);



    //ColVis extension
    var colvis = new $.fn.dataTable.ColVis( oTable1, {
      "buttonText": "<i class='fa fa-search'></i>",
      "aiExclude": [0, 6],
      "bShowAll": true,
      //"bRestore": true,
      "sAlign": "right",
      "fnLabel": function(i, title, th) {
        return $(th).text();//remove icons, etc
      }

    });

    //style it
    $(colvis.button()).addClass('btn-group').find('button').addClass('btn btn-white btn-info btn-bold')

    //and append it to our table tools btn-group, also add tooltip
    $(colvis.button())
    .prependTo('.tableTools-container .btn-group')
    .attr('title', 'Show/hide columns').tooltip({container: 'body'});

    //and make the list, buttons and checkboxed Ace-like
    $(colvis.dom.collection)
    .addClass('dropdown-menu dropdown-light dropdown-caret dropdown-caret-right')
    .find('li').wrapInner('<a href="javascript:void(0)" />') //'A' tag is required for better styling
    .find('input[type=checkbox]').addClass('ace').next().addClass('lbl padding-8');



    /////////////////////////////////
    //table checkboxes
    $('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);

    //select/deselect all rows according to table header checkbox
    $('#dynamic-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
      var th_checked = this.checked;//checkbox inside "TH" table header

      $(this).closest('table').find('tbody > tr').each(function(){
        var row = this;
        if(th_checked) tableTools_obj.fnSelect(row);
        else tableTools_obj.fnDeselect(row);
      });
    });

    //select/deselect a row when the checkbox is checked/unchecked
    $('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
      var row = $(this).closest('tr').get(0);
      if(!this.checked) tableTools_obj.fnSelect(row);
      else tableTools_obj.fnDeselect($(this).closest('tr').get(0));
    });




      $(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
      e.stopImmediatePropagation();
      e.stopPropagation();
      e.preventDefault();
    });


    //And for the first simple table, which doesn't have TableTools or dataTables
    //select/deselect all rows according to table header checkbox
    var active_class = 'active';
    $('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
      var th_checked = this.checked;//checkbox inside "TH" table header

      $(this).closest('table').find('tbody > tr').each(function(){
        var row = this;
        if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
        else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
      });
    });

    //select/deselect a row when the checkbox is checked/unchecked
    $('#simple-table').on('click', 'td input[type=checkbox]' , function(){
      var $row = $(this).closest('tr');
      if(this.checked) $row.addClass(active_class);
      else $row.removeClass(active_class);
    });



    /********************************/
    //add tooltip for small view action buttons in dropdown menu
    $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});

    //tooltip placement on right or left
    function tooltip_placement(context, source) {
      var $source = $(source);
      var $parent = $source.closest('table')
      var off1 = $parent.offset();
      var w1 = $parent.width();

      var off2 = $source.offset();
      //var w2 = $source.width();

      if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
      return 'left';
    }

  })
</script>
@endsection
