<?php
/**
 * Regenera o index.html do Swagger a partir do openapi.json.
 * NÃO depende de nenhuma biblioteca — só PHP puro.
 *
 * USO:
 *   php docs/swagger.php
 */

$jsonPath = __DIR__ . '/openapi.json';

if (!file_exists($jsonPath)) {
    echo "Erro: openapi.json não encontrado em docs/\n";
    exit(1);
}

$json = file_get_contents($jsonPath);
$data = json_decode($json, true);

if (empty($data['paths'])) {
    echo "Aviso: openapi.json não contém rotas.\n";
}

$spec = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

$html = <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>API Produto - Swagger</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist/swagger-ui.css">
</head>
<body>

<div id="swagger-ui"></div>

<script src="https://unpkg.com/swagger-ui-dist/swagger-ui-bundle.js"></script>

<script>
const spec = $spec;

window.onload = function () {
    SwaggerUIBundle({
        spec: spec,
        dom_id: '#swagger-ui',
        presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIBundle.SwaggerUIStandalonePreset
        ],
        layout: 'BaseLayout',
        deepLinking: true
    });
};
</script>

</body>
</html>
HTML;

file_put_contents(__DIR__ . '/index.html', $html);

echo "index.html atualizado com " . count($data['paths']) . " rota(s).\n";
echo "Acesse: http://localhost/primeiroPHP/?docs\n";