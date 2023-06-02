<!DOCTYPE html>
<html>
<head>
    <title>from : {{ $from_email }}</title>
</head>
<body>

{{ $subject }}

<p>نوع المستند: {{$attachment_type}}</p>
<p>اسم الموظف : {{ $employee->emp_name_full_ar }} </p>
<p> الرقم الوظيفي: {{ $employee->emp_code }} </p>
<p> رابط المستند: {{ $attachment_link }} </p>


<p>شـكرا</p>
</body>
</html>
