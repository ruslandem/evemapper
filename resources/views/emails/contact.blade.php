<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <p>
    <table>
        <tbody>
            <tr>
                <td><b>Name:</b></td>
                <td>{{ $name }}</td>
            </tr>
            <tr>
                <td><b>Email:</b></td>
                <td>{{ $email }}</td>
            </tr>
            <tr>
                <td><b>Message:</b></td>
                <td>{{ $body }}</td>
            </tr>
        </tbody>
    </table>
    </p>
    @if ($captchaScore)
        <p><i>Captcha score: {{ $captchaScore }}</i></p>
    @endif
    <p><i>{{ $date }}</i></p>
</body>

</html>
