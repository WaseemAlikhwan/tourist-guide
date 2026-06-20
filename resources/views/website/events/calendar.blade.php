@extends('website.layouts.app')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

@section('title', $isEn ? 'Events Calendar - Wander Point in Syria' : 'تقويم الفعاليات - Wander Point in Syria')

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css">
<style>
    .calendar-hero {
        background: linear-gradient(135deg, rgba(139, 69, 19, 0.9) 0%, rgba(160, 82, 45, 0.9) 100%);
        color: white;
        padding: 60px 20px;
        text-align: center;
        border-radius: 24px;
        margin-bottom: 3rem;
    }
    #calendar {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        position: relative;
        min-height: 500px;
    }
    .fc-event {
        border-radius: 6px;
        padding: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .fc-event:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .event-info {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-top: 2rem;
    }
    .filters-section {
        background: white;
        border-radius: 16px;
    }
    .filters-section .form-label {
        font-size: 14px;
        margin-bottom: 8px;
    }
    .events-list-card {
        position: sticky;
        top: 20px;
        max-height: calc(100vh - 40px);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .events-list {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        padding: 0;
    }
    .event-item {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .event-item:hover {
        background-color: #f8f9fa;
        transform: translateX(-5px);
    }
    .event-item.active {
        background-color: #e7f3ff;
        border-right: 4px solid var(--primary, #0d6efd);
    }
    .event-item-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 0.5rem;
    }
    .event-item-title {
        font-weight: 600;
        font-size: 14px;
        color: #212529;
        margin: 0;
        flex: 1;
    }
    .event-item-date {
        font-size: 12px;
        color: #6c757d;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 4px;
        white-space: nowrap;
        margin-right: 0.5rem;
    }
    .event-item-meta {
        font-size: 12px;
        color: #6c757d;
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .event-item-meta span {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .event-item-type {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        color: white;
    }
    .event-tooltip {
        position: absolute;
        z-index: 1000;
        background: rgba(0, 0, 0, 0.9);
        color: #fff;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 13px;
        pointer-events: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        max-width: 250px;
        line-height: 1.6;
    }
    .event-tooltip strong {
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
    }
    #calendar-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 999;
    }
    .no-events-message {
        text-align: center;
        padding: 3rem 2rem;
        color: #6c757d;
    }
    .no-events-message i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    @media (max-width: 992px) {
        .events-list-card {
            position: relative;
            top: 0;
            margin-top: 2rem;
            max-height: 600px;
        }
        .events-list {
            max-height: 500px;
        }
    }
</style>
@endpush

@section('content')
<div class="calendar-hero">
    <h1 class="display-4 fw-bold mb-3">{{ $isEn ? 'Events Calendar in Syria' : 'تقويم الفعاليات في سوريا' }}</h1>
    <p class="lead">{{ $isEn ? 'Discover upcoming events and occasions across Syria' : 'اكتشف الفعاليات والمناسبات القادمة في جميع أنحاء سوريا' }}</p>
</div>

<!-- Search filters -->
<div class="filters-section mb-4">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label fw-bold">
                        <i class="fas fa-search me-2"></i>{{ $isEn ? 'Search' : 'البحث' }}
                    </label>
                    <input type="text" class="form-control" id="search" placeholder="{{ $isEn ? 'Search for an event...' : 'ابحث عن فعالية...' }}">
                </div>
                <div class="col-md-3">
                    <label for="filter-destination" class="form-label fw-bold">
                        <i class="fas fa-map-marker-alt me-2"></i>{{ $isEn ? 'Destination' : 'الوجهة' }}
                    </label>
                    <select class="form-select" id="filter-destination">
                        <option value="">{{ $isEn ? 'All destinations' : 'جميع الوجهات' }}</option>
                        @foreach($destinations as $destination)
                            <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter-type" class="form-label fw-bold">
                        <i class="fas fa-tag me-2"></i>{{ $isEn ? 'Event type' : 'نوع الفعالية' }}
                    </label>
                    <select class="form-select" id="filter-type">
                        <option value="">{{ $isEn ? 'All types' : 'جميع الأنواع' }}</option>
                        @foreach($eventTypes as $eventType)
                            <option value="{{ $eventType }}">{{ $eventType }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" id="clear-filters">
                        <i class="fas fa-times me-2"></i>{{ $isEn ? 'Reset' : 'إعادة التعيين' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Calendar -->
    <div class="col-lg-8 mb-4">
        <div id="calendar"></div>
    </div>
    
    <!-- Events list -->
    <div class="col-lg-4">
        <div class="events-list-card card shadow-sm border-0 h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>{{ $isEn ? 'Events List' : 'قائمة الفعاليات' }}
                </h5>
            </div>
            <div class="card-body p-0">
                <div id="events-list" class="events-list">
                    <div class="text-center p-4 text-muted">
                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                        <p>{{ $isEn ? 'Loading events...' : 'جاري تحميل الفعاليات...' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="event-info mt-4">
    <h3 class="mb-3">
        <i class="fas fa-info-circle me-2" style="color: var(--primary);"></i>
        {{ $isEn ? 'Events Information' : 'معلومات الفعاليات' }}
    </h3>
    <p class="text-muted">
        {{ $isEn ? 'The events calendar is updated regularly. For more information about upcoming events, please visit the destinations page or contact us.' : 'يتم تحديث تقويم الفعاليات بشكل دوري. للمزيد من المعلومات حول الفعاليات القادمة، يرجى زيارة صفحة الوجهات أو التواصل معنا.' }}
    </p>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/locales/ar.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isEnglish = @json($isEn);
        const calendarLocale = isEnglish ? 'en' : 'ar';
        const calendarDirection = isEnglish ? 'ltr' : 'rtl';
        const dateLocale = isEnglish ? 'en-US' : 'ar-SA';
        const labels = {
            loadError: isEnglish ? 'An error occurred while loading events' : 'حدث خطأ أثناء تحميل الفعاليات',
            noEventsMatch: isEnglish ? 'No events match your search' : 'لا توجد فعاليات مطابقة للبحث',
            noEventsMonth: isEnglish ? 'No events this month' : 'لا توجد فعاليات في هذا الشهر',
            loadingEvents: isEnglish ? 'Loading events...' : 'جاري تحميل الفعاليات...',
            type: isEnglish ? 'Type' : 'النوع',
            destination: isEnglish ? 'Destination' : 'الوجهة',
            currency: isEnglish ? 'SYP' : 'ل.س'
        };

        var calendarEl = document.getElementById('calendar');
        var calendar;
        var allEvents = [];
        
        // متغيرات الفلترة
        var currentFilters = {
            destination_id: '',
            type: '',
            search: ''
        };
        
        // جلب الفعاليات مع الفلترة
        function loadEvents(start, end, successCallback, failureCallback) {
            var params = new URLSearchParams();
            params.append('start', start.toISOString().split('T')[0]);
            params.append('end', end.toISOString().split('T')[0]);
            
            if (currentFilters.destination_id) {
                params.append('destination_id', currentFilters.destination_id);
            }
            if (currentFilters.type) {
                params.append('type', currentFilters.type);
            }
            if (currentFilters.search) {
                params.append('search', currentFilters.search);
            }
            
            fetch('{{ route("api.events") }}?' + params.toString())
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    allEvents = data;
                    successCallback(data);
                    renderEventsList(data);
                })
                .catch(error => {
                    console.error('Error loading events:', error);
                    if (failureCallback) {
                        failureCallback(error);
                    }
                    showError(labels.loadError);
                });
        }
        
        // عرض قائمة الفعاليات
        function renderEventsList(events) {
            var eventsListEl = document.getElementById('events-list');
            
            if (!events || events.length === 0) {
                eventsListEl.innerHTML = `
                    <div class="no-events-message">
                        <i class="fas fa-calendar-times"></i>
                        <p class="mb-0">${labels.noEventsMatch}</p>
                    </div>
                `;
                return;
            }
            
            // ترتيب الفعاليات حسب التاريخ
            events.sort((a, b) => new Date(a.start) - new Date(b.start));
            
            var html = events.map((event, index) => {
                var date = new Date(event.start);
                var dateStr = date.toLocaleDateString(dateLocale, {
                    day: 'numeric', 
                    month: 'long',
                    year: 'numeric'
                });
                
                return `
                    <div class="event-item" data-event-id="${event.id}" onclick="window.location.href='${event.url}'">
                        <div class="event-item-header">
                            <h6 class="event-item-title">${event.title}</h6>
                            <span class="event-item-date">
                                <i class="fas fa-calendar"></i> ${dateStr}
                            </span>
                        </div>
                        <div class="event-item-meta">
                            ${event.extendedProps.type ? `
                                <span>
                                    <span class="event-item-type" style="background-color: ${event.color}">
                                        ${event.extendedProps.type}
                                    </span>
                                </span>
                            ` : ''}
                            ${event.extendedProps.destination ? `
                                <span>
                                    <i class="fas fa-map-marker-alt"></i>
                                    ${event.extendedProps.destination}
                                </span>
                            ` : ''}
                            ${event.price ? `
                                <span>
                                    <i class="fas fa-tag"></i>
                                    ${event.price} ${labels.currency}
                                </span>
                            ` : ''}
                        </div>
                    </div>
                `;
            }).join('');
            
            eventsListEl.innerHTML = html;
        }
        
        // تهيئة التقويم
        function initCalendar() {
            calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: calendarLocale,
            direction: calendarDirection,
            firstDay: isEnglish ? 0 : 6,
            headerToolbar: {
                right: 'prev,next today',
                center: 'title',
                left: 'dayGridMonth,listWeek'
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                // جلب الفعاليات مع الفلترة
                loadEvents(fetchInfo.start, fetchInfo.end, successCallback, failureCallback);
            },
            noEventsContent: function() {
                return {
                    html: `<div class="text-center py-4 text-muted"><i class="fas fa-calendar-times fa-3x mb-3 d-block"></i><p class="mb-0">${labels.noEventsMonth}</p></div>`
                };
            },
            eventClick: function(info) {
                // الانتقال إلى صفحة النشاط عند النقر على الفعالية
                if (info.event.url) {
                    window.location.href = info.event.url;
                }
                info.jsEvent.preventDefault();
            },
            eventDidMount: function(info) {
                // إضافة tooltip عند التمرير على الفعالية
                var tooltip = null;
                info.el.addEventListener('mouseenter', function(e) {
                    tooltip = document.createElement('div');
                    tooltip.className = 'event-tooltip';
                    tooltip.innerHTML = '<strong>' + info.event.title + '</strong>' +
                        (info.event.extendedProps.type ? '<br>' + labels.type + ': ' + info.event.extendedProps.type : '') +
                        (info.event.extendedProps.destination ? '<br>' + labels.destination + ': ' + info.event.extendedProps.destination : '');
                    document.body.appendChild(tooltip);
                    updateTooltipPosition(e);
                });
                info.el.addEventListener('mousemove', function(e) {
                    if (tooltip) {
                        updateTooltipPosition(e);
                    }
                });
                info.el.addEventListener('mouseleave', function() {
                    if (tooltip && tooltip.parentNode) {
                        tooltip.parentNode.removeChild(tooltip);
                        tooltip = null;
                    }
                });
                
                function updateTooltipPosition(e) {
                    if (!tooltip) return;
                    var x = e.pageX + 15;
                    var y = e.pageY + 15;
                    var windowWidth = window.innerWidth;
                    var windowHeight = window.innerHeight;
                    var tooltipWidth = tooltip.offsetWidth || 250;
                    var tooltipHeight = tooltip.offsetHeight || 100;
                    
                    // التحقق من حدود الشاشة
                    if (x + tooltipWidth > windowWidth) {
                        x = e.pageX - tooltipWidth - 15;
                    }
                    if (y + tooltipHeight > windowHeight) {
                        y = e.pageY - tooltipHeight - 15;
                    }
                    
                    tooltip.style.left = x + 'px';
                    tooltip.style.top = y + 'px';
                }
            },
            loading: function(isLoading) {
                if (isLoading) {
                    // إظهار مؤشر التحميل
                    var loadingEl = document.createElement('div');
                    loadingEl.id = 'calendar-loading';
                    loadingEl.className = 'text-center py-4';
                    loadingEl.innerHTML = `<i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2 text-muted">${labels.loadingEvents}</p>`;
                    calendarEl.appendChild(loadingEl);
                } else {
                    // إخفاء مؤشر التحميل
                    var loadingEl = document.getElementById('calendar-loading');
                    if (loadingEl && loadingEl.parentNode) {
                        loadingEl.parentNode.removeChild(loadingEl);
                    }
                }
            },
            eventDisplay: 'block',
            height: 'auto',
            aspectRatio: 1.8
            });
            calendar.render();
        }
        
        // تطبيق الفلترة
        function applyFilters() {
            calendar.refetchEvents();
        }
        
        // إعادة تعيين الفلترات
        function resetFilters() {
            document.getElementById('search').value = '';
            document.getElementById('filter-destination').value = '';
            document.getElementById('filter-type').value = '';
            currentFilters = {
                destination_id: '',
                type: '',
                search: ''
            };
            applyFilters();
        }
        
        // معالجة الأحداث للفلترات
        document.getElementById('search').addEventListener('input', debounce(function() {
            currentFilters.search = this.value;
            applyFilters();
        }, 500));
        
        document.getElementById('filter-destination').addEventListener('change', function() {
            currentFilters.destination_id = this.value;
            applyFilters();
        });
        
        document.getElementById('filter-type').addEventListener('change', function() {
            currentFilters.type = this.value;
            applyFilters();
        });
        
        document.getElementById('clear-filters').addEventListener('click', resetFilters);
        
        // دالة debounce لتأخير البحث
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        
        // عرض رسالة الخطأ
        function showError(message) {
            var errorMsg = document.createElement('div');
            errorMsg.className = 'alert alert-danger mt-3';
            errorMsg.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>' + message;
            calendarEl.appendChild(errorMsg);
            setTimeout(function() {
                if (errorMsg.parentNode) {
                    errorMsg.parentNode.removeChild(errorMsg);
                }
            }, 5000);
        }
        
        // تهيئة التقويم عند تحميل الصفحة
        initCalendar();
    });
</script>
@endpush
@endsection

