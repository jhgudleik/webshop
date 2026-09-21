@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Пользователи</h1>

            <a href="{{ route('admin.users.create') }}"
               class="btn btn-primary">
                Создать пользователя
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($users->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Аватар</th>
                        <th>Создан</th>
                        <th width="300">Действия</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                {{ $user->id }}
                            </td>

                            <td>
                                {{ $user->name }}
                            </td>

                            <td>
                                {{ $user->email }}
                            </td>

                            <td>
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}"
                                         alt="avatar"
                                         class="rounded-circle"
                                         width="40"
                                         height="40"
                                         style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px; font-weight: 600;">
                                        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                {{ $user->created_at->format('d.m.Y H:i') }}
                            </td>

                            <td>
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="btn btn-sm btn-info">
                                    Просмотр
                                </a>

                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn btn-sm btn-warning">
                                    Изменить
                                </a>

                                <form action="{{ route('admin.users.destroy', $user) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Удалить пользователя?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-danger">
                                        Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        @else
            <div class="alert alert-info">
                Пользователей пока нет.
            </div>
        @endif

    </div>
@endsection
