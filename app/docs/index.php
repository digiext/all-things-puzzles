<!DOCTYPE html>
<html lang="en">
<!--<redoc spec-url="../api/api-spec.yaml"></redoc>-->
<!--<script src="https://cdn.redoc.ly/redoc/latest/bundles/redoc.standalone.js"> </script>-->

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>All Things Puzzle DOCS</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css" />
</head>
<body>
<div id="swagger-ui"></div>
<script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js" crossorigin></script>
<script>
    window.onload = () => {
        window.ui = SwaggerUIBundle({
            url: '../api/api-spec.yaml',
            dom_id: '#swagger-ui',
        });
    };
</script>
</body>
</html>