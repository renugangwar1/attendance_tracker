<!DOCTYPE html>
<html>
<head>
    <title>{{ $user->name }}'s Attendance</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        #calendar {
            max-width: 900px;
            margin: 40px auto;
        }

        #attendance-popup {
            position: absolute;
            display: none;
            background: white;
            border: 1px solid #ccc;
            padding: 12px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            z-index: 999;
        }

        #attendance-popup select {
            padding: 5px;
        }

        #attendance-popup button {
            margin-top: 8px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>

<h2 style="text-align:center">{{ $user->name }}'s Attendance</h2>

<div id='calendar'></div>

<!-- Floating Attendance Popup -->
<div id="attendance-popup">
    <p id="popup-date-text"><strong>Date:</strong> <span></span></p>
    <select id="attendance-status">
        <option value="">-- Select Status --</option>
        <option value="present">Present</option>
        <option value="absent">Absent</option>
        <option value="holiday">Holiday</option>
        <option value="overtime">Overtime</option>
    </select>
    <br>
    <button onclick="submitAttendance()">Mark</button>
    <button onclick="closePopup()">Cancel</button>
</div>

<script>
    let selectedDate = '';
    let calendar;

    document.addEventListener('DOMContentLoaded', function() {
        let calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: {!! json_encode($events) !!},
            dateClick: function(info) {
                selectedDate = info.dateStr;

                const popup = document.getElementById('attendance-popup');
                const popupText = document.getElementById('popup-date-text').querySelector('span');
                popupText.innerText = selectedDate;

                // Show the popup near the mouse position
                popup.style.left = info.jsEvent.pageX + 'px';
                popup.style.top = info.jsEvent.pageY + 'px';
                popup.style.display = 'block';
            }
        });

        calendar.render();
    });

    function submitAttendance() {
        const status = document.getElementById('attendance-status').value;
        if (!['present', 'absent', 'holiday', 'overtime'].includes(status)) {
            alert("Please select a valid status.");
            return;
        }

        fetch("{{ route('attendance.mark') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_detail_id: {{ $user->id }},
                date: selectedDate,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            calendar.refetchEvents();
            closePopup();
        });
    }

    function closePopup() {
        const popup = document.getElementById('attendance-popup');
        popup.style.display = 'none';
        document.getElementById('attendance-status').value = '';
    }
</script>

</body>
</html>
