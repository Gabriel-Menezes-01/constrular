$(document).ready(function () {
    const rssUrl = "https://g1.globo.com/rss/g1/";
    const $container = $("#conteine");
    function fetchRSS() {
        $.ajax({
            url: `https://api.rss2json.com/v1/api.json?rss_url=${encodeURIComponent(rssUrl)}`,
            method: "GET",
            success: function (response) {
                displayRSS(response);
            },
            error: function () {
                $container.html("Erro ao carregar notícias.");
            }
        });
    }

    //fazer com que esse conteudo seja visualizado na tela.
    function displayRSS(data) {
        if (data && data.items) {
            $container.empty();
            data.items.forEach(item => {
                let description = item.description;
                const imageMatch = description.match(/<img[^>]+src="([^">]+)"/); // Captura o URL da imagem
                if (imageMatch) {
                    description = description.replace(/<img[^>]*>/, "");
                }
                const $slide = $(`
                    <div class='rss-item'>
                        ${imageMatch ? `<img src='${imageMatch[1]}' alt='${item.title}' class='rss-image'>` : ""}
                        <h4><a href="${item.link}" target="_blank">${item.title}</a></h4>
                        <p>${description.slice(0, 100)}...</p>
                    </div>
                `);
                $container.append($slide);
            });
        } else {
            $container.html('Nenhuma notícia disponível.');
        }
    }

    fetchRSS();
});