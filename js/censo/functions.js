$('.js-withoutCertificates').on('change', function () {
  const checkbox = this;
  const exportLinks = $('.js-export-link');

  if (!exportLinks.length) return;

  exportLinks.each(function () {
    const link = $(this);
    const currentHref = link.attr('href');

    // Cria objeto URL com base no href atual
    const url = new URL(currentHref, window.location.origin);

    // Atualiza o parâmetro
    url.searchParams.set('withoutCertificates', checkbox.checked); // true ou false

    // Atualiza o href do link
    link.attr('href', url.toString());
  });
});

