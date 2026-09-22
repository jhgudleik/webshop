<li class="{{ $hasChildren ? 'dropdown-submenu' : '' }}">
    <a class="dropdown-item {{ $hasChildren ? 'dropdown-toggle' : '' }}" href="#">
        {{ $category->title }}
    </a>
    @if($hasChildren)
        <ul class="dropdown-menu">
            @foreach($category->children->where('active', true) as $child)
                @include('layouts.partials.nav-category', [
                    'category' => $child,
                    'hasChildren' => $child->children->where('active', true)->isNotEmpty()
                ])
            @endforeach
        </ul>
    @endif
</li>
