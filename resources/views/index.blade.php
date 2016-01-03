<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{ env('APP_TITLE') }}</title>
	<link rel="stylesheet" href="{{ url('assets/css/style.css') }}">
</head>
<body>
	<div id="sidebar">
		<h3>{{ env('APP_TITLE') }}</h3>
		<ul id="types">
			@foreach($news_sources as $url => $title)
			<li>
				<a href="/{{ $url }}">{{ $title }}</a>
			</li>
			@endforeach
		</ul>
	</div>

	<div id="items-container">
		<h1>{{ $category }}</h1>
		@if(session('message'))
		<div id="message">
			{{ session('message') }}	
		</div>
		@endif
		<ul id="items">
		@foreach($items as $item)
			<li class="item">
				<a href="/publish?title={{ $item['title'] }}&url={{ $item['url'] }}">
					<span class="item-title">{{ $item['title'] }}</span>
					<span class="item-info">{{ $item['source'] }}</span>
				</a>
			</li>		
		@endforeach
		</ul>
	</div>
</body>
</html>