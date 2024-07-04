<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAYCOMET RESULT</title>

    <!-- CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

</head>
<body>
<div class="container">
    @if($result = 'ok')
        <div id="operation-info2">
            <div style="text-align: center; margin-top: 50px;">
            </div>

            <div style="text-align: center;">
                       Pago completado correctamente.
            </div>


        </div>
        @else
        <div id="operation-info2">
            <div style="text-align: center; margin-top: 50px;">
            </div>

            <div style="text-align: center;">
                Error de verificación de seguridad.
                Por favor, revisa la información proporcionada y vuelve a intentarlo con la tarjeta insertada o introduce una nueva.
            </div>


        </div>
        @endif
    </div>
</body>

</html>
