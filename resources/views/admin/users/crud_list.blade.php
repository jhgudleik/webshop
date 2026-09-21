@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>{{ $crud->entity_name_plural }}</h1>

            @if($crud->hasAccess('create'))
                <a href="{{ backpack_url('users/create') }}"
                   class="btn btn-primary">
                    Создать пользователя
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @php
            $entries = \App\Models\User::orderBy('id')->paginate(20);
        @endphp

        @if($entries->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        @foreach($crud->columns() as $column)
                            <th>{{ $column['label'] }}</th>
                        @endforeach
                        <th>Аватар</th>
                        @if($crud->hasAccess('update') || $crud->hasAccess('delete') || $crud->hasAccess('show'))
                            <th width="300">Действия</th>
                        @endif
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($entries as $entry)
                        <tr>
                            @foreach($crud->columns() as $column)
                                <td>
                                    @if($column['name'] === 'id')
                                        {{ $entry->id }}
                                    @elseif($column['name'] === 'name')
                                        {{ $entry->name }}
                                    @elseif($column['name'] === 'email')
                                        {{ $entry->email }}
                                    @elseif($column['name'] === 'created_at')
                                        {{ $entry->created_at->format('d.m.Y H:i') }}
                                    @endif
                                </td>
                            @endforeach

                            <td>
                                @if($entry->avatar)
                                    <img src="{{ asset('storage/' . $entry->avatar) }}"
                                         alt="avatar"
                                         class="rounded-circle"
                                         width="40"
                                         height="40"
                                         style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px; font-weight: 600;">
                                        {{ mb_strtoupper(mb_substr($entry->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                @if($crud->hasAccess('show'))
                                    <a href="{{ backpack_url('users/'.$entry->getKey().'/show') }}"
                                       class="btn btn-sm btn-info">
                                        Просмотр
                                    </a>
                                @endif

                                @if($crud->hasAccess('update'))
                                    <a href="{{ backpack_url('users/'.$entry->getKey().'/edit') }}"
                                       class="btn btn-sm btn-warning">
                                        Изменить
                                    </a>
                                @endif

                                @if($crud->hasAccess('delete'))
                                    <form action="{{ backpack_url('users/'.$entry->getKey()) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Удалить пользователя?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Удалить
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                Пользователей пока нет.
            </div>
        @endif

    </div>
@endsection
