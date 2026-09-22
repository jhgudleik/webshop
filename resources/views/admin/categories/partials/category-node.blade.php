<li class="category-node" data-id="{{ $category->id }}" data-parent-id="{{ $category->parent_id ?? '' }}">

    <div class="category-item {{ $isRoot ? 'parent-category' : '' }}">

        <span class="drag-handle">
            <i class="fas fa-grip-vertical"></i>
        </span>

        <span class="category-icon">
            @if($isRoot)
                <i class="fas fa-folder text-warning"></i>
            @else
                <i class="fas fa-folder-open text-secondary"></i>
            @endif
        </span>

        <span class="category-title">
            {{ $category->title }}
            @if(!$category->active)
                <span class="badge bg-secondary ms-2">Неактивна</span>
            @endif
        </span>

        <div class="category-actions">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary" title="Редактировать">
                <i class="fas fa-edit"></i>
            </a>

            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить категорию?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Удалить">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>

    </div>

    @if($category->children->isNotEmpty())
        <ul class="category-children" data-parent-id="{{ $category->id }}">
            @foreach($category->children as $child)
                @include('admin.categories.partials.category-node', [
                    'category' => $child,
                    'isRoot' => false
                ])
            @endforeach
        </ul>
    @else
        <ul class="category-children empty" data-parent-id="{{ $category->id }}">
            <li class="empty-category-message">Перетащите сюда подкатегорию</li>
        </ul>
    @endif

</li>
