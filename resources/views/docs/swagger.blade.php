<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="AAM Employee Manager API Documentation" />
    <title>AAM API Documentation</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css" />
    <link rel="shortcut icon" href="https://laravel.com/img/logomark.min.svg" />
    <style>
        body {
            margin: 0;
            background: #0f172a; /* Slate 900 */
        }
        #swagger-ui {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            min-height: 100vh;
        }
        /* Dark mode overrides for the header if desired, but Swagger UI default is white/light.
           Let's make it look premium with a nice header. */
        .topbar {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #334155;
        }
        .topbar h1 {
            color: #f8fafc;
            margin: 0;
            font-family: 'Inter', sans-serif;
            font-size: 1.5rem;
            letter-spacing: -0.025em;
        }
        .topbar p {
            color: #94a3b8;
            margin: 5px 0 0;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <h1>Anglebert Team Employee Manager</h1>
        <p>Interactive API Console</p>
    </div>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js" charset="UTF-8"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-standalone-preset.js" charset="UTF-8"></script>
    <script>
        window.onload = () => {
            window.ui = SwaggerUIBundle({
                url: '/api/openapi.json',
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                layout: "BaseLayout",
                persistAuthorization: true,
            });
        };
    </script>
</body>
</html>
