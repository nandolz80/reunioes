
var viewmodel = {
    login: null,
    senha: null,
    
    entrar: function() {
        ko.notification.info("Realizando login...");
        
        $.ajax({
            type: "post",
            url: rootUrl.concat("usuario/login"),
            dataType: "json",
            data: ko.toJSON(this),
            success: this.onSuccessLogin,
            error: this.onErrorLogin
        });
    },
    
    onSuccessLogin: function(data) {
        $.cookie.json = true;
        $.cookie('usuario', data.result, {expires: 1});
        
        goPage("calender");
    },
    
    onErrorLogin: function(error) {
        ko.notification.error(getErrorMessage(error.responseText));
    }
};

ko.applyBindings(viewmodel);

$(function(){
    var currentDate; // Holds the day clicked when adding a new event
    var currentEvent; // Holds the event object when editing an event
    $('#color').colorpicker(); // Colopicker
    $('#time').timepicker({
        minuteStep: 5,
        showInputs: false,
        disableFocus: true,
        showMeridian: false
    });  // Timepicker
    // Fullcalendar
    $('#calendar').fullCalendar({
        timeFormat: 'H(:mm)',
        lang: 'pt-br',
        header: {
            left: 'prev, next, today',
            center: 'title',
            right: 'month, basicWeek, basicDay'
        },
        // Get all events stored in database
//        events: rootUrl + 'calender/listAll',
        events: function(start, end, timezone, callback) {
            $.ajax({
                type: "get",
                dataType: "json",
                url: rootUrl + "calender/listAll",
                success: function(doc) {
                    var events = [];
                    $.map(doc.result, function(r) {
                        events.push({
                            id: r.id,
                            title: r.nome,
                            description: r.description,
                            color: r.color,
                            usuario: r.usuario,
                            nome: r.nome,
                            date: r.date
                        });
                    });
                    callback(events);
                }
            });
        },
        // Event Mouseover
        eventMouseover: function(calEvent, jsEvent, view){
            var tooltip = '<div class="event-tooltip">' + calEvent.usuario + '</div>';
            $("body").append(tooltip);
            $(this).mouseover(function(e) {
                $(this).css('z-index', 10000);
                $('.event-tooltip').fadeIn('500');
                $('.event-tooltip').fadeTo('10', 1.9);
            }).mousemove(function(e) {
                    $('.event-tooltip').css('top', e.pageY + 10);
                    $('.event-tooltip').css('left', e.pageX + 20);
                });
        },
        eventMouseout: function(calEvent, jsEvent) {
            $(this).css('z-index', 8);
            $('.event-tooltip').remove();
        }
    });
    // Prepares the modal window according to data passed
    function modal(data) {
        // Set modal title
        $('.modal-title').html(data.title);
        // Clear buttons except Cancel
        $('.modal-footer button:not(".btn-default")').remove();
        // Set input values
        $('#title').val(data.event ? data.event.title : '');
        if( ! data.event) {
            // When adding set timepicker to current time
            var now = new Date();
            var time = now.getHours() + ':' + (now.getMinutes() < 10 ? '0' + now.getMinutes() : now.getMinutes());
        } else {
            // When editing set timepicker to event's time
            var time = data.event.date.split(' ')[1].slice(0, -3);
            time = time.charAt(0) === '0' ? time.slice(1) : time;
        }
        $('#time').val(time);
        $('#description').val(data.event ? data.event.description : '');
        $("#id_sala option:selected").val(data.event ? data.event.id_sala : '');
        $('#color').val(data.event ? data.event.color : '#3a87ad');
        // Create Butttons
        $.each(data.buttons, function(index, button){
            $('.modal-footer').prepend('<button type="button" id="' + button.id  + '" class="btn ' + button.css + '">' + button.label + '</button>')
        });
        //Show Modal
        $('.modal').modal('show');
    }
    
    function getTime() {
        var time = $('#time').val();
        return (time.indexOf(':') == 1 ? '0' + time : time) + ':00';
    }
});