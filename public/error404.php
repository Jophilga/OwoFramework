<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="This is OWO error page">
        <title><?= $data['error_title'] ?? 'Error 404' ?></title>

        <style>
            html,body {
                max-width: 100%;
                max-height: 100%;
                width: 100%;
                height: 100%;
                margin: 0;
                text-align: center;
                font-family: system-ui;
                font-size: 12px;
                background: #102030;
                color: #ffffff;
            }
            section#header {
                height: 70px;
                background: #ffcc00;
                color: #000000;
                margin-bottom: 1em;
            }
            section#main {
                padding: 10px;
            }
            section#footer {
                padding: 10px;
                background: #ffcc00;
                color: #000000;
            }
            p.catch {
                padding: 10px;
            }
            textarea {
                width: 80%;
                    padding: 10px;
                text-align: center;
                background: #ffffff;
                color: #333333;
            }
        </style>
    </head>

    <body>
        <section id="header"></section>

        <section id="main">
            <h2><?= $data['error_title'] ?? 'Error 404' ?></h2>
            <p>We could not found what you are looking for :(</p>

            <?php if (true === isset($data['error_debug'])): ?>
            <p><?= $data['error_debug'] ?></p>
            <?php endif; ?>
        </section>

        <section id="footer">
            <p>Copyright © <?= \date('Y') ?> All Rights Reserved.</p>
        </section>
    </body>
</html>
