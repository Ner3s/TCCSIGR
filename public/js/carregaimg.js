var carregar;

function loadImagem(img) {
    carregar = new Image();
    carregar.src = img;
    document.getElementById("renderiza_imagem").innerHTML = "Carregando...";
    setTimeout("verificaCarregamento()", 1);
}

function verificaCarregamento() {
    if (carregar.complete) {
        document.getElementById("renderiza_imagem").innerHTML = "<img class='img-thumbnail' src=" +
            carregar.src + " style='max-width:50%; height:auto;' />";
    } else {
        setTimeout("verificaCarregamento()", 1);
    }
}