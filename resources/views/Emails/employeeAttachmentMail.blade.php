<!DOCTYPE html>
<html>
<head>
    <title>from : {{ $from_email }}</title>
</head>
<body>

{{ $subject }}

<p> المستند : {{ $attachment_type }} </p>
<p> الكود الخاص بكم : {{ $employee->emp_code }} </p>
<p> رابط المستند: {{ $attachment_link }} </p>

<p>Thank you</p>
</body>
</html>
