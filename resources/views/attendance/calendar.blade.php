<!DOCTYPE html>
<html>
<head>
    <title>{{ $user->name }}'s Attendance</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f4f6f9;
            --accent-color: #28a745;
            --danger-color: #dc3545;
            --neutral-color: #6c757d;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--secondary-color);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .top-bar {
            background-color: #ffffff;
            padding: 15px 25px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .back-link {
            background-color: var(--primary-color);
            color: #fff;
            padding: 8px 14px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease-in-out;
        }

        .back-link:hover {
            background-color: #357ab8;
        }

        .attendance-heading {
            font-size: 20px;
            font-weight: 600;
        }

        #calendar {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        #attendance-popup {
            position: absolute;
            display: none;
            background: #ffffff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            z-index: 999;
            width: 240px;
            font-size: 14px;
        }

        #attendance-popup p {
            margin-bottom: 10px;
            font-weight: 500;
        }

        #attendance-popup select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 15px;
            outline: none;
            transition: 0.3s border-color;
        }

        #attendance-popup select:focus {
            border-color: var(--primary-color);
        }

        #attendance-popup button {
            margin-right: 6px;
            margin-top: 6px;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        #attendance-popup button:nth-child(3) {
            background-color: var(--accent-color);
            color: white;
        }

        #attendance-popup button:nth-child(3):hover {
            background-color: #1f7a31;
        }

        #attendance-popup button:nth-child(4) {
            background-color: var(--danger-color);
            color: white;
        }

        #attendance-popup button:nth-child(4):hover {
            background-color: #c82333;
        }

        #attendance-popup button:nth-child(5) {
            background-color: var(--neutral-color);
            color: white;
        }

        #attendance-popup button:nth-child(5):hover {
            background-color: #5a6268;
        }

        /* Smooth transition for calendar buttons and headers */
        .fc .fc-toolbar-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .fc-button {
            background-color: var(--primary-color);
            border: none;
            color: white;
            border-radius: 6px;
            padding: 5px 10px;
            transition: 0.3s;
        }

        .fc-button:hover {
            background-color: #357ab8;
        }

        .fc-button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

       
        .fc-daygrid-day-frame {
            transition: background-color 0.2s;
            cursor: pointer;
        }

        .fc-daygrid-day-frame:hover {
            background-color: #eef4fd;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <a href="{{ route('users.index') }}" class="back-link">← Back to Dashboard</a>
    <div class="attendance-heading">{{ $user->name }}'s Attendance</div>
</div>

<div id='calendar'></div>

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
    <button onclick="submitAttendance()"><i class="fas fa-check-circle"></i> Mark</button>
    <button onclick="unmarkAttendance()"><i class="fas fa-times-circle"></i> Unmark</button>
    <button onclick="closePopup()"><i class="fas fa-times"></i> Cancel</button>
</div>

<script>
    let selectedDate = '';
    let calendar;

    document.addEventListener('DOMContentLoaded', function () {
        let calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: {!! json_encode($events) !!},
            dateClick: function (info) {
                selectedDate = info.dateStr;

                const popup = document.getElementById('attendance-popup');
                const popupText = document.getElementById('popup-date-text').querySelector('span');
                popupText.innerText = selectedDate;

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
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
            calendar.addEvent({
                title: status.charAt(0).toUpperCase() + status.slice(1),
                start: selectedDate,
                color: getStatusColor(status),
                allDay: true
            });
            closePopup();
        });
    }

    function unmarkAttendance() {
        if (!confirm("Are you sure you want to unmark this date?")) return;

        fetch("{{ route('attendance.unmark') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_detail_id: {{ $user->id }},
                date: selectedDate
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            let events = calendar.getEvents();
            events.forEach(event => {
                if (event.startStr === selectedDate) {
                    event.remove();
                }
            });
            closePopup();
        });
    }

    function closePopup() {
        const popup = document.getElementById('attendance-popup');
        popup.style.display = 'none';
        document.getElementById('attendance-status').value = '';
    }

    function getStatusColor(status) {
        switch (status) {
            case 'present': return 'green';
            case 'absent': return 'red';
            case 'holiday': return 'blue';
            case 'overtime': return 'orange';
            default: return 'gray';
        }
    }
</script>

</body>
</html>
