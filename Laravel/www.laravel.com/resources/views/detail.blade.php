@extends('layouts.app')

@section('style')
<style type="text/css">
	#content span {
		display: inline-block;
	}
	#content span:hover {
		font-size: 1.2em;
		font-weight: bold;
	}
	.fa-snowflake-o {
		display: inline-block;
		-webkit-animation: circle 4s infinite linear;
	}
	@-webkit-keyframes circle{
		0% {
			transform:rotate(0deg);
			font-size: 1em;
			color: #fff;
		}
		50% {
			transform:rotate(180deg);
			font-size: 1.5em;
			color: #000;
		}
		100% {
			transform:rotate(360deg);
			font-size: 1em;
			color: #fff;
		}
	}
</style>
@endsection

@section('content')
	<div>
		<center><i class="fa fa-snowflake-o"></i></center>
	</div>
	<div id="content" style="margin: 10px auto; text-align: center;">
		<span>{!! preg_replace("/。/i", "。</span><br/><span>", $baike->baike_content) !!}</span>
	</div>
@endsection

@section('script')
<script type="text/javascript">
	window.onload = function () {
		console.log("lonny.p@eyebuydirect.com");
	};
</script>
@endsection