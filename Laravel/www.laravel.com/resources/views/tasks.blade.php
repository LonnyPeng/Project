@extends('layouts.app')

@section('content')
    <div style="text-align: center;">
        <!-- Display Validation Errors -->
        @include('common.errors')

        <!-- New Task Form -->
        <form action="/task" method="POST">
            {{ csrf_field() }}

            <!-- Task Name -->
            <div>
                <div>
                	<label>Name ZH</label>
                    <input type="text" name="name_zhcn">
                </div>

                <div>
                	<label>Name EN</label>
                    <input type="text" name="name_enus">
                </div>

                <div>
                    <button type="submit">
                        <i class="fa fa-plus"></i> Add
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Current Tasks -->
    @if (count($tasks) > 0)
        <div style="margin: 10px auto;">
            <table>
                <thead>
                    <th>Name ZH</th>
                    <th>Name EN</th>
                    <th>Delete</th>
                </thead>

                <!-- Table Body -->
                <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <!-- Task Name -->
                        <td>
                            <div>{{ $task->name_zhcn }}</div>
                        </td>

                        <td>
                            <div>{{ $task->name_enus }}</div>
                        </td>

                        <td>
                            <form action="/baike/{{ $baike->id }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <button>Delete Task</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection