@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1">Категории</h1>
                <p class="text-muted mb-0">Перетаскивайте категории для изменения порядка и перемещайте подкатегории между категориями.</p>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="saving-indicator" id="savingIndicator" style="display:none;">
                    <span class="spinner-border spinner-border-sm"></span>
                    Сохранение...
                </div>

                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Добавить категорию
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($parentCategories->isEmpty())
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5>Категорий пока нет</h5>
                    <p class="text-muted">Создайте первую категорию.</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Создать категорию
                    </a>
                </div>
            </div>
        @else
            <div class="card shadow-sm">
                <div class="card-body">
                    <ul class="category-tree" id="categoryTree" data-parent-id="">
                        @foreach($parentCategories as $parentCategory)
                            @include('admin.categories.partials.category-node', [
                                'category' => $parentCategory,
                                'isRoot' => true
                            ])
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

    </div>
@endsection

@push('styles')
<style>
    .category-tree { list-style: none; padding: 0; margin: 0; }
    .category-children { list-style: none; margin: 8px 0 0 35px; padding: 0 0 0 20px; border-left: 2px solid #e9ecef; min-height: 10px; }
    .category-children.empty { min-height: 45px; }
    .category-node { margin-bottom: 8px; }
    .category-item { display: flex; align-items: center; min-height: 52px; padding: 10px 14px; background: #fff; border: 1px solid #dee2e6; border-radius: 8px; transition: border-color .2s, box-shadow .2s, background-color .2s; }
    .category-item:hover { border-color: #adb5bd; box-shadow: 0 3px 10px rgba(0,0,0,.06); }
    .category-item.parent-category { background: #f8f9fa; font-weight: 600; }
    .drag-handle { width: 30px; color: #adb5bd; cursor: grab; text-align: center; flex-shrink: 0; }
    .drag-handle:active { cursor: grabbing; }
    .category-icon { width: 25px; flex-shrink: 0; }
    .category-title { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .category-actions { display: flex; gap: 5px; margin-left: 15px; }
    .sortable-ghost { opacity: .35; }
    .sortable-drag { opacity: .9; }
    .category-drop-target { border-color: #e94560 !important; background: rgba(233,69,96,.05); }
    .empty-category-message { display: flex; align-items: center; justify-content: center; min-height: 45px; color: #adb5bd; font-size: 14px; }
    .saving-indicator { display: none; align-items: center; gap: 8px; font-size: 14px; color: #6c757d; }
    .saving-indicator.show { display: flex; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const moveUrl = @json(route('admin.categories.move'));
    const savingIndicator = document.getElementById('savingIndicator');
    const lists = document.querySelectorAll('#categoryTree, .category-children');

    lists.forEach(function (list) {
        new Sortable(list, {
            group: { name: 'categories', pull: true, put: true },
            animation: 180,
            handle: '.drag-handle',
            draggable: '.category-node',
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            fallbackOnBody: true,
            swapThreshold: 0.65,

            onStart: function () {
                document.querySelectorAll('.category-children').forEach(function (el) {
                    el.classList.add('category-drop-target');
                });
            },

            onEnd: async function (event) {
                document.querySelectorAll('.category-children').forEach(function (el) {
                    el.classList.remove('category-drop-target');
                });

                const categoryId = event.item.dataset.id;
                const targetList = event.to;
                let parentId = targetList.dataset.parentId || null;
                const position = event.newIndex;

                const isRootCategory = !event.item.dataset.parentId || event.item.dataset.parentId === '';
                const isMovingIntoSubcategory = targetList.classList.contains('category-children');

                if (isRootCategory && isMovingIntoSubcategory) {
                    alert('Главную категорию нельзя переместить внутрь подкатегории.');
                    window.location.reload();
                    return;
                }

                await moveCategory(categoryId, parentId, position);
            }
        });
    });

    async function moveCategory(categoryId, parentId, position) {
        savingIndicator.classList.add('show');

        try {
            const response = await fetch(moveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    category_id: categoryId,
                    parent_id: parentId,
                    position: position
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Не удалось переместить категорию.');
            }

            const element = document.querySelector(`.category-node[data-id="${categoryId}"]`);
            if (element) {
                element.dataset.parentId = parentId ?? '';
            }
        } catch (error) {
            console.error(error);
            alert(error.message || 'Не удалось сохранить перемещение категории.');
            window.location.reload();
        } finally {
            savingIndicator.classList.remove('show');
        }
    }
});
</script>
@endpush
