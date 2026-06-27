# Correções do Projeto PHP + Swagger

## Problemas encontrados e corrigidos

### 1. Swagger não se atualizava automaticamente

**Problema:** O projeto gerava `docs/openapi.json` e `docs/index.html` apenas quando você rodava `php docs/gerarSwagger.php` no terminal. Adicionar novas rotas não refletia no Swagger sem esse passo manual.

**Solução:** O Swagger agora é **100% dinâmico via HTTP**. Não há mais arquivos `.json` ou `.html` estáticos — tudo é gerado em tempo real pelo servidor.

---

## Novas URLs do Swagger

| URL | O que faz |
|-----|-----------|
| `http://localhost/swagger/ui` | Abre o Swagger UI no navegador |
| `http://localhost/swagger/openapi` | Retorna o JSON OpenAPI gerado na hora |
| `http://localhost/?docs` | Redireciona para `/swagger/ui` (compatibilidade) |

---

## Arquivos alterados

### `index.php`
- Adicionada rota `swagger` que serve o UI e o JSON dinamicamente
- O `?docs` agora redireciona para `/swagger/ui`

### `docs/swagger.php`
- Reescrito: agora é um **endpoint HTTP** (não mais um script de terminal)
- Usa `Generator::scan()` para escanear as pastas `docs/` e `route/` em tempo real
- Retorna o JSON com `Content-Type: application/json`
- Cabeçalho `Cache-Control: no-store` garante que sempre retorna a versão atualizada

### `docs/index.php` *(novo — substitui o index.html estático)*
- Serve o HTML do Swagger UI dinamicamente
- Aponta para `/swagger/openapi` como fonte do spec (sempre atualizado)

### `docs/openApi.php`
- Corrigido: removidos `\r` (CRLF → LF)
- Namespace mantido como `Docs\`

### `docs/categoriaSwagger.php` e `docs/produtoSwagger.php`
- Corrigidos: CRLF → LF
- Adicionados `example` nos campos para melhor visualização no Swagger UI

### `composer.json`
- Adicionado `autoload.psr-4` para o namespace `Docs\` (necessário para o scanner funcionar)
- Versão do swagger-php ajustada para `^4.10` (compatível com PHP 8.0+)

---

## Como adicionar novas rotas

1. Crie o arquivo de anotações em `docs/` (ex: `docs/pedidoSwagger.php`) com namespace `Docs`
2. Crie a rota em `route/pedidoRoute.php`
3. Registre o recurso no `switch` do `index.php`
4. **Pronto!** Acesse `/swagger/ui` e a nova rota já aparece — sem rodar nenhum comando.

---

## Instalação

```bash
# Instalar/atualizar dependências
composer install

# Testar o Swagger
php -S localhost:80 -t .
# Acesse: http://localhost/swagger/ui
```

> **Nota:** Se usar Apache, o `.htaccess` já está configurado corretamente.
