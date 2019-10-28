$(function () {
     var fecha = '';
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

        var popupresumenlpnd = $("#POPUP_Resumen_LpnD");
        popupresumenlpnd.data("kendoWindow").title('Resumen LPNs a liberar: '+fecha);
        popupresumenlpnd.data("kendoWindow").open().maximize();
        var grid = $("#gridResLpnD");
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
        url: baseURL + 'LPNDemora/totales',
        dataType: 'json',
        success: function(result){
          result.forEach(function(element){ 
            $('#calendar').fullCalendar('renderEvent',{
              title: "LPN a liberar: "+element.TOTAL,
              start: new Date(element.INCUB_DATE.replace(/\//g, "-")),
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

    var dataSourceDetLpnDemora = new kendo.data.DataSource({
        transport: {
            read: onReadDetLpnDemora
        },
        schema: {
            model: {
                id: "ASN",
                fields: {
                        ASN: {type: "string"}, // number - string - date
                        ESTADO_ASN: {type: "string"},
                        DESC_ESTADO_ASN: {type: "string"}, // number - string - date
                        LPN: {type: "string"},
                        FEC_LIBERACION: {type: "string"},
                        ESTADO_LPN: {type: "string"},
                        DESC_ESTADO_LPN: {type: "string"},
                        UBICACION_LPN: {type: "string"},
                        CARTON: {type: "string"},
                        ESTADO_CARTON: {type: "string"},
                        DESC_ESTADO_CARTON: {type: "string"},
                        UBICACION_CARTON: {type: "string"}
                    }
            }
        },
        pageSize: 50
    });
    var ventana_resumen_lpnd = $("#POPUP_Resumen_LpnD");
    ventana_resumen_lpnd.kendoWindow({
        width: "100%",
        height: "100%",
        visible: false,
        actions: [
            "Close"     
        ]
    }).data("kendoWindow").center();
    $("#gridResLpnD").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetLpnDemora,
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
            {field: "ASN",title: "ASN",width: 70, resizable:false, filterable: {multi: true, search: true}},
            {field: "ESTADO_ASN",title: "ESTADO ASN",width:70,filterable: {multi: true, search: true}},
            {field: "DESC_ESTADO_ASN",title: "DESC ESTADO ASN",width:70,filterable: false},
            {field: "LPN",title: "LPN",width:100,filterable: false},
            {field: "FEC_LIBERACION",title: "FECHA LIBERACION",width:70,filterable: false},
            {field: "ESTADO_LPN",title: "ESTADO LPN",width:60,filterable: {multi: true, search: true}},
            {field: "DESC_ESTADO_LPN",title: "DESC ESTADO LPN",width:70,filterable: false},
            {field: "UBICACION_LPN",title: "UBICACION LPN",width:70,filterable: {multi: true, search: true}},
            {field: "CARTON",title: "CARTON",width:90,filterable: false},
            {field: "ESTADO_CARTON",title: "ESTADO CARTON",width:60,filterable: {multi: true, search: true}},
            {field: "DESC_ESTADO_CARTON",title: "DESC ESTADO CARTON",width:60, filterable: false},
            {field: "UBICACION_CARTON",title: "UBICACION CARTON",width:60,filterable: {multi: true, search: true}}
        ]
    });
    function onReadDetLpnDemora(e){
      $.ajax({
          type: "POST",
          url: baseURL + 'LPNDemora/resumen',
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
  });