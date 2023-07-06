<!DOCTYPE html>
<html lang="{{{ $lang }}}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {% yield meta %}
        <title>{{{ $title }}}</title>
    </head>

    <body>
        {% yield content %}
    </body>
</html>
