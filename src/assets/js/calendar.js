document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      locale: 'pl',
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      editable: true,
      selectable: true,
      events: [],
      select: function (info) {
        var title = prompt('Podaj nazwę wydarzenia:');
        if (title) {
          calendar.addEvent({
            title: title,
            start: info.startStr,
            end: info.endStr,
            allDay: info.allDay
          });
        }
      },
      eventClick: function (info) {
        var deleteEvent = confirm("Czy na pewno chcesz usunąć to wydarzenie?");
        if (deleteEvent) {
          info.event.remove();
        }
      }
    });

    calendar.render();
  });