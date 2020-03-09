$(function () {
     var fecha = '';
     var codigo = 0;
    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    init_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
    $('#calendar').fullCalendar({
      locale: "es",
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'month, year'
      },
      buttonText: {
        today: 'today',
        month: 'month',
        year: 'year'
      },
      //Random default event
      editable  : true,
      eventClick: function(info){
        var date = info.start._i;
        var day = date.getDate();
        var month = date.getMonth()+1;
        var year = date.getFullYear();

        fecha = year+"/"+month+"/"+day;

        var popupestadospkt = $("#POPUP_Estados_PKT");
        popupestadospkt.data("kendoWindow").title('PKT Pendientes: '+fecha);
        popupestadospkt.data("kendoWindow").open();
        var grid = $("#gridEstadosPKT");
        grid.data("kendoGrid").dataSource.read();
      },
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function (date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject')

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject)

        // assign it the date that was reported
        copiedEventObject.start           = date
        copiedEventObject.allDay          = allDay
        copiedEventObject.backgroundColor = $(this).css('background-color')
        copiedEventObject.borderColor     = $(this).css('border-color')

        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
          // if so, remove the element from the "Draggable Events" list
          $(this).remove()
        }

      }
    })

    $.ajax({
        type: "POST",
        url: baseURL + 'calendario/read',
        dataType: 'json',
        success: function(result){
          result.forEach(function(element){ 
            $('#calendar').fullCalendar('renderEvent',{
              title: "PKT Pendientes: "+element.TOTAL,
              start: new Date(element.ADVT_DATE.replace(/\//g, "-")),
              allDay: true,
              backgroundColor: "#cc0099"
            }, true);
          });  
        },
        error: function(result){
            alert(JSON.stringify(result));
        }
    });

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      //Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.html(val)
      $('#external-events').prepend(event)

      //Add draggable funtionality
      init_events(event)

      //Remove event from text input
      $('#new-event').val('')
    })

    var dataSourceEstadosPKT = new kendo.data.DataSource({
        transport: {
            read: onReadEstadosPKT
        },
        schema: {
            model: {
                id: "STAT_CODE",
                fields: {
                        STAT_CODE: {type: "string"}, // number - string - date
                        DESC_ESTADO: {type: "string"},
                        TOTAL: {type: "string"} // number - string - date
                    }
            }
        },
        pageSize: 50
    });

     var dataSourceDetallePKT = new kendo.data.DataSource({
        transport: {
            read: onReadDetallePKT
        },
        schema: {
            model: {
                id: "PKT_CTRL_NBR",
                fields: {
                        PKT_CTRL_NBR: {type: "string"}, // number - string - date
                        RTE_ID: {type: "string"}
                    }
            }
        },
        pageSize: 50
    });

    var ventana_Estados_PKT = $("#POPUP_Estados_PKT");
    ventana_Estados_PKT.kendoWindow({
        width: "700PX",
        height: "500PX",
        visible: false,
        actions: [
            "Close"     
        ]
    }).data("kendoWindow").center();

    var ventana_Detalle_PKT = $("#POPUP_Detalle_PKT");
    ventana_Detalle_PKT.kendoWindow({
        width: "700PX",
        height: "500PX",
        visible: false,
        actions: [
            "Close"     
        ]
    }).data("kendoWindow").center();

    $("#gridEstadosPKT").kendoGrid({
        selectable: "cell",
        autoBind: false,
        dataSource: dataSourceEstadosPKT,
        height: "100%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "STAT_CODE",title: "ESTADO",width: 70, resizable:false, filterable: {multi: true, search: true}},
            {field: "DESC_ESTADO",title: "DESCRIPCION",width:70,filterable: {multi: true, search: true}},
            {field: "TOTAL",title: "CANTIDAD",width:70,filterable: false}
        ]
    }).on("click", "tbody td", function(e) {
        var cell = $(e.currentTarget);
        var cellIndex = cell[0].cellIndex;
        var grid = $("#gridEstadosPKT").data("kendoGrid");
        var column = grid.columns[cellIndex];
        var dataItem = grid.dataItem(cell.closest("tr"));
        codigo = dataItem[grid.columns[0].field];

        var popupdetallepkt = $("#POPUP_Detalle_PKT");
        popupdetallepkt.data("kendoWindow").title('Detalle PKT Pendientes');
        popupdetallepkt.data("kendoWindow").open();
        var grid = $("#gridDetallePKT");
        grid.data("kendoGrid").dataSource.read();

    });

    $("#gridDetallePKT").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetallePKT,
        height: "100%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "PKT_CTRL_NBR",title: "PKT",width: 70, resizable:false, filterable: {multi: true, search: true}},
            {field: "RTE_ID",title: "RUTA",width:70,filterable: {multi: true, search: true}}
        ]
    })

    function onReadEstadosPKT(e){
      $.ajax({
          type: "POST",
          url: baseURL + 'calendario/estados',
          data: {fecha: fecha},
          dataType: 'json',
          success: function(result){
              e.success(result);
          },
          error: function(result){
              console.log(JSON.stringify(result));
          }
      });
    }

    function onReadDetallePKT(e){
      $.ajax({
          type: "POST",
          url: baseURL + 'calendario/detalle',
          data: {fecha: fecha, codigo: codigo},
          dataType: 'json',
          success: function(result){
              e.success(result);
          },
          error: function(result){
              console.log(JSON.stringify(result));
          }
      });
    }
  });