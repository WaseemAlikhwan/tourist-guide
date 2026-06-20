@extends('admin.layouts.app')

@section('title', 'إدارة التعليقات | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span>إدارة التعليقات</span>
    </div>

    <div class="page-header">
        <div>
            <h1>إدارة التعليقات 💬</h1>
            <p class="subtitle">عرض وإدارة جميع تعليقات المستخدمين على الأنشطة</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $totalComments = \App\Models\Comment::count();
        $approvedComments = \App\Models\Comment::where('is_approved', true)->count();
        $pendingComments = \App\Models\Comment::where('is_approved', false)->count();
    @endphp

    <!-- بطاقات الإحصائيات -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">إجمالي التعليقات</span>
                    <span class="badge rounded-pill bg-primary-subtle text-primary">💬</span>
                </div>
                <div class="stat-number">{{ $totalComments }}</div>
                <div class="stat-label">تعليق إجمالي</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">معتمدة</span>
                    <span class="badge rounded-pill bg-success-subtle text-success">✅</span>
                </div>
                <div class="stat-number">{{ $approvedComments }}</div>
                <div class="stat-label">تعليق معتمد</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">قيد المراجعة</span>
                    <span class="badge rounded-pill bg-warning-subtle text-warning">⏳</span>
                </div>
                <div class="stat-number">{{ $pendingComments }}</div>
                <div class="stat-label">تعليق يحتاج مراجعة</div>
            </div>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-list-ul"></i> قائمة التعليقات
                </h5>
                <span class="badge bg-primary-subtle text-primary">{{ $comments->total() }} تعليق</span>
            </div>

            @if($comments->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 60px;">#</th>
                                <th scope="col">المستخدم</th>
                                <th scope="col">النشاط</th>
                                <th scope="col">التعليق</th>
                                <th scope="col" class="text-center">الحالة</th>
                                <th scope="col">التاريخ</th>
                                <th scope="col" class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comments as $comment)
                                <tr id="comment-row-{{ $comment->id }}" class="{{ $comment->is_approved ? 'table-success' : 'table-warning' }}">
                                    <td class="text-muted">{{ $loop->iteration + ($comments->currentPage() - 1) * $comments->perPage() }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $comment->user?->name ?? 'مستخدم محذوف' }}</div>
                                        <small class="text-muted">{{ $comment->user?->email ?? '-' }}</small>
                                    </td>
                                    <td>
                                        @if($comment->activity)
                                            <a href="{{ route('admin.activities.show', $comment->activity) }}" 
                                               class="text-decoration-none fw-semibold">
                                                <i class="bi bi-activity"></i> {{ $comment->activity->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">نشاط محذوف</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div id="comment-form-{{ $comment->id }}">
                                            @if(!$comment->is_approved)
                                                <form action="{{ route('admin.comments.update', $comment) }}" 
                                                      method="POST" 
                                                      class="comment-form d-flex flex-column gap-2" 
                                                      data-comment-id="{{ $comment->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <textarea name="comment" 
                                                              class="form-control" 
                                                              rows="3" 
                                                              style="min-width: 100%; max-width: 400px;">{{ $comment->comment }}</textarea>
                                                    <button type="submit" class="btn btn-sm btn-primary align-self-start">
                                                        <i class="bi bi-check-circle"></i> حفظ واعتماد
                                                    </button>
                                                </form>
                                            @else
                                                <div class="text-dark mb-1">{{ $comment->comment }}</div>
                                                <span class="badge bg-success-subtle text-success small">
                                                    <i class="bi bi-check-circle-fill"></i> معتمد
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($comment->is_approved)
                                            <span class="badge bg-success-subtle text-success fs-6">
                                                <i class="bi bi-check-circle"></i> معتمد
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning fs-6">
                                                <i class="bi bi-clock"></i> قيد المراجعة
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $comment->created_at?->format('Y-m-d') ?? '-' }}</div>
                                        <small class="text-muted">{{ $comment->created_at?->diffForHumans() ?? '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            @if(!$comment->is_approved)
                                                <form action="{{ route('admin.comments.toggle-approval', $comment) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success" 
                                                            title="اعتماد">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.comments.toggle-approval', $comment) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-warning" 
                                                            title="إلغاء الاعتماد">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.comments.destroy', $comment) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا التعليق؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="حذف">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($comments->hasPages())
                    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted">
                            عرض {{ $comments->firstItem() ?? 0 }} إلى {{ $comments->lastItem() ?? 0 }} من {{ $comments->total() }} تعليق
                        </div>
                        <div>
                            {{ $comments->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-chat-left-text" style="font-size: 64px; color: #cbd5e1;"></i>
                    </div>
                    <h5 class="text-muted">لا توجد تعليقات</h5>
                    <p class="text-muted">لا توجد تعليقات مضافة حتى الآن</p>
                </div>
            @endif
        </div>
    </div>

    @push('head')
    <style>
        .table-success {
            background-color: rgba(16, 185, 129, 0.1) !important;
        }
        .table-warning {
            background-color: rgba(245, 158, 11, 0.1) !important;
        }
        .comment-form textarea {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .comment-form textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }
    </style>
    <script>
        // معالجة حفظ التعليقات
        document.querySelectorAll('.comment-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const commentId = this.dataset.commentId;
                const submitBtn = this.querySelector('button[type="submit"]');
                const commentText = formData.get('comment');
                
                submitBtn.disabled = true;
                submitBtn.textContent = 'جاري الحفظ...';

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || formData.get('_token')
                    }
                })
                .then(response => response.json().catch(() => ({ success: true })))
                .then(data => {
                    if (data.success !== false) {
                        // إخفاء النموذج وإظهار التعليق المعتمد
                        const formContainer = document.getElementById('comment-form-' + commentId);
                        formContainer.innerHTML = `
                            <div class="text-muted">${commentText}</div>
                            <span class="badge bg-success small">معتمد</span>
                        `;
                        
                        // تحديث صف الجدول
                        const row = document.getElementById('comment-row-' + commentId);
                        row.classList.add('table-success');
                        
                        // تحديث الحالة
                        const statusCell = row.querySelector('td:nth-child(4)');
                        statusCell.innerHTML = '<span class="badge bg-success">معتمد</span>';
                        
                        // تحديث أزرار الإجراءات
                        const actionsCell = row.querySelector('td:last-child');
                        actionsCell.innerHTML = `
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.comments.toggle-approval', ':id') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" title="إلغاء الاعتماد">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.comments.destroy', ':id') }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا التعليق؟')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                                </form>
                            </div>
                        `.replace(/:id/g, commentId);

                        // إظهار رسالة النجاح
                        showSuccessMessage('تم حفظ واعتماد التعليق بنجاح');
                        
                        // إعادة تحميل الصفحة بعد ثانيتين
                        setTimeout(() => window.location.reload(), 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'حفظ';
                    alert('حدث خطأ أثناء حفظ التعليق');
                });
            });
        });

        function showSuccessMessage(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.setAttribute('role', 'alert');
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.content-shell').insertBefore(alertDiv, document.querySelector('.content-shell').firstChild);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
    @endpush
@endsection
