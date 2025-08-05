<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Contact Us Email</title>
</head>
<body>
	<h1>Hello</h1>
	{{-- contact us email template --}}

	<div style="border:1px solid #ccc; padding:16px; background:#f9f9f9;">
		<strong>Title:</strong> {{ $contactData['title'] }}<br><br>
		<strong>Description:</strong><br>
		{{ $contactData['description'] }}<br><br>
		<strong>User Name:</strong> {{ $contactData['user_name'] }}<br>
		<strong>User Email:</strong> {{ $contactData['user_email'] }}
	</div>
</body>
</html>