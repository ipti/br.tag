$('.js-withoutCertificates').on('change', function () {
  const checkbox = this;
  const exportLink = $('.js-export-link');

  if (!exportLink.length) return;

  // Pega o valor atual do href
  const currentHref = exportLink.attr('href');

  // Cria objeto de URL com base no href atual
  const url = new URL(currentHref, window.location.origin);

  // Atualiza o parâmetro
  url.searchParams.set('withoutCertificates', checkbox.checked); // true ou false

  // Atualiza o href do link
  exportLink.attr('href', url.toString());
});
