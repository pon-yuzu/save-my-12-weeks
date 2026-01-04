import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

let calendar;
let currentSeminarId = null;

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const eventsUrl = calendarEl.dataset.eventsUrl;

    calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        locale: 'ja',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        buttonText: {
            today: '今日',
            month: '月',
            week: '週'
        },
        events: eventsUrl,
        dateClick: function(info) {
            openModal(null, info.dateStr);
        },
        eventClick: function(info) {
            openModalForEdit(info.event);
        },
        editable: true,
        eventDrop: function(info) {
            updateSeminarDate(info.event.id, info.event.start);
        }
    });

    calendar.render();

    // フォーム送信
    const seminarForm = document.getElementById('seminar-form');
    if (seminarForm) {
        seminarForm.addEventListener('submit', function(e) {
            e.preventDefault();
            saveSeminar();
        });
    }
});

function openModal(seminarId = null, dateStr = null) {
    currentSeminarId = seminarId;
    document.getElementById('modal-title').textContent = seminarId ? 'セミナーを編集' : 'セミナーを追加';
    document.getElementById('delete-btn').style.display = seminarId ? 'block' : 'none';

    // フォームリセット
    document.getElementById('seminar-id').value = '';
    document.getElementById('seminar-title').value = '12週間プログラム説明会';
    document.getElementById('seminar-duration').value = '120';
    document.getElementById('seminar-zoom').value = '';
    document.getElementById('seminar-line').value = '';
    document.getElementById('seminar-code').value = '';
    document.getElementById('seminar-capacity').value = '';
    document.getElementById('seminar-active').checked = true;
    document.getElementById('seminar-description').value = '';

    if (dateStr) {
        // 日付クリック時はデフォルトで14:00に設定
        const datetime = dateStr.includes('T') ? dateStr : dateStr + 'T14:00';
        document.getElementById('seminar-datetime').value = datetime.slice(0, 16);
    }

    document.getElementById('seminar-modal').style.display = 'flex';
}

function openModalForEdit(event) {
    currentSeminarId = event.id;

    fetch(`/admin/seminars/${event.id}`)
        .then(res => res.json())
        .then(data => {
            const s = data.seminar;
            document.getElementById('modal-title').textContent = 'セミナーを編集';
            document.getElementById('delete-btn').style.display = 'block';
            document.getElementById('seminar-id').value = s.id;
            document.getElementById('seminar-title').value = s.title;
            document.getElementById('seminar-datetime').value = s.scheduled_at.slice(0, 16);
            document.getElementById('seminar-duration').value = s.duration_minutes;
            document.getElementById('seminar-zoom').value = s.zoom_link || '';
            document.getElementById('seminar-line').value = s.line_openchat_link || '';
            document.getElementById('seminar-code').value = s.participation_code || '';
            document.getElementById('seminar-capacity').value = s.capacity || '';
            document.getElementById('seminar-active').checked = s.is_active;
            document.getElementById('seminar-description').value = s.description || '';

            document.getElementById('seminar-modal').style.display = 'flex';
        });
}

function closeModal() {
    document.getElementById('seminar-modal').style.display = 'none';
    currentSeminarId = null;
}

function saveSeminar() {
    const id = document.getElementById('seminar-id').value;
    const data = {
        title: document.getElementById('seminar-title').value,
        scheduled_at: document.getElementById('seminar-datetime').value,
        duration_minutes: parseInt(document.getElementById('seminar-duration').value) || 120,
        zoom_link: document.getElementById('seminar-zoom').value || null,
        line_openchat_link: document.getElementById('seminar-line').value || null,
        participation_code: document.getElementById('seminar-code').value || null,
        capacity: document.getElementById('seminar-capacity').value ? parseInt(document.getElementById('seminar-capacity').value) : null,
        is_active: document.getElementById('seminar-active').checked,
        description: document.getElementById('seminar-description').value || null,
    };

    const url = id ? `/admin/seminars/${id}` : '/admin/seminars';
    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(data),
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            calendar.refetchEvents();
            closeModal();
        } else {
            alert('保存に失敗しました');
        }
    })
    .catch(err => {
        console.error(err);
        alert('エラーが発生しました');
    });
}

function deleteSeminar() {
    if (!currentSeminarId) return;

    if (!confirm('このセミナーを削除しますか？')) return;

    fetch(`/admin/seminars/${currentSeminarId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            calendar.refetchEvents();
            closeModal();
        }
    });
}

function updateSeminarDate(id, newDate) {
    fetch(`/admin/seminars/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            scheduled_at: newDate.toISOString(),
        }),
    })
    .then(res => res.json())
    .then(result => {
        if (!result.success) {
            calendar.refetchEvents();
        }
    });
}

// グローバルに公開
window.openModal = openModal;
window.closeModal = closeModal;
window.deleteSeminar = deleteSeminar;
