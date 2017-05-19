@extends('layouts.app')

@section('content')
	@if (count($baikes) > 0)
	    <div style="margin: 10px auto;">
	        <table>
	            <thead>
	                <th>Name</th>
	                <th>Detail</th>
	            </thead>

	            <!-- Table Body -->
	            <tbody>
	            @foreach ($baikes as $baike)
	                <tr>
	                    <!-- Task Name -->
	                    <td>
	                        <div>{{ $baike->baike_name }}</div>
	                    </td>

	                    <td>
	                        <form action="/detail/{{ $baike->baike_id }}" method="GET">
	                            <button>Detail</button>
	                        </form>
	                    </td>
	                </tr>
	            @endforeach
	            </tbody>
	        </table>
	    </div>
	@endif
@endsection