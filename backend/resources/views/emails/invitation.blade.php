<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Uitnodiging Suriname App</title>
    <style>
        body {
            /* This sets the default font and background for the email */
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            /* This sets the maximum width and centers the container */
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
        }

        .header {
            /* This sets the background color and text styles for the header */
            background-color: #32cd32;
            color: red;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            /* This sets the padding for the content area */
            padding: 20px;
        }

        .button {
            /* This sets the background color and text styles for the button */
            background-color: #f1c40f;
            color: black;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            font-weight: bold;
        }

        .footer {
            /* This sets the padding for the footer area */
            color: #666;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Here is the header section located -->
        <div class="header">
            <h1>Suriname</h1>
        </div>
    <!--  This div class contains the most important information in regarding to receive the invitation  -->
        <div class="content">
            <h2>Hallo {{ $invitation->name }},</h2>
            <p>Je bent uitgenodigd om deel te nemen aan de Suriname takenbeheertool.</p>
            <p>Klik op de onderstaande knop om je account te activeren en een wachtwoord in te stellen:</p>
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ $acceptUrl }}" class="button">Account Activeren</a>
            </p>
            <p>Of kopieer deze link naar je browser:</p>
            <p style="word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 4px;">
                {{ $acceptUrl }}
            </p>
            <p><strong>Let op:</strong> Deze uitnodiging verloopt over 7 dagen.</p>
            <!-- Here is the footer section located -->
            <div class="footer">
                <p>Als je deze uitnodiging niet verwacht hebt, kun je deze email negeren.</p>
            </div>
        </div>
    </div>
</body>

</html>