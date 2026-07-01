<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel · Catálogo</title>
<link rel="stylesheet" href="assets/css/categoria.css">
</head>
<body>

<div class="wrap">

  <span class="eyebrow"><span class="eyebrow__dot"></span>Painel do sistema</span>

  <div class="hero">
    <h1 class="hero-title">Organize seu <em>catálogo</em>.</h1>
    <p class="hero-sub">Gerencie categorias, produtos e clientes a partir de um único lugar. Comece pelo módulo abaixo.</p>
  </div>

  <div class="hub-grid">

    <a href="categoria/listar.php" class="resource-card resource-card--active">
      <div class="resource-card__top">
        <span class="resource-card__icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="M4 10h16M10 4v16"/></svg>
        </span>
        <span class="resource-card__badge resource-card__badge--live">Disponível</span>
      </div>
      <h2 class="resource-card__title">Categorias</h2>
      <p class="resource-card__text">Cadastre, edite e organize as categorias usadas para classificar os produtos.</p>
      <span class="resource-card__cta">
        Acessar categorias
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </span>
    </a>

    <a href="produto/listar.php" class="resource-card resource-card--active">
      <div class="resource-card__top">
        <span class="resource-card__icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m20.5 7.3-8.5 5-8.5-5M12 12.3V21M3.5 7.3v9.4L12 21l8.5-4.3V7.3L12 3z"/></svg>
        </span>
        <span class="resource-card__badge resource-card__badge--live">Disponível</span>
      </div>
      <h2 class="resource-card__title">Produtos</h2>
      <p class="resource-card__text">Cadastro de produtos vinculados às categorias — telas ainda em construção.</p>
      <span class="resource-card__cta">
        Acessar produtos
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </span>
    </a>

    <div class="resource-card resource-card--disabled">
      <div class="resource-card__top">
        <span class="resource-card__icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/></svg>
        </span>
        <span class="resource-card__badge resource-card__badge--soon">Em breve</span>
      </div>
      <h2 class="resource-card__title">Clientes</h2>
      <p class="resource-card__text">Cadastro e gestão da base de clientes — telas ainda em construção.</p>
    </div>

  </div>

</div>

</body>
</html>
