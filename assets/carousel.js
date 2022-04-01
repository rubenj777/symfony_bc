document.addEventListener('DOMContentLoaded',function() {
    getData();
})

function getData() {
    fetch('http://localhost:8000/api/products')
        .then((res) => res.json())
        .then((data) => {
            data.products.forEach((product) => {
                let array = [
                    {
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        description: product.description,
                        imageName: product.imageName
                    }
                ];
                slider(array);
            })
        })
}

function slider(data) {
    const carousel = document.querySelector("#carousel");
    const datas = data.map(data => {
        const content = document.querySelector('#content');
        content.innerHTML += `
        <a href="product/${data.id}"><img src="images/products/${data.imageName}" class="item"></a>`
        return content;
    })

    carousel.append(...datas)
}

// le code en dessous va dans la fonction slider
/*<h4>${data.name}</h4>
<p>${data.price}</p>
<p>${data.description}</p>
<a href="product/${data.id}" className="btn btn-primary me-1">Voir le produit</a>
<a href="cart/add/${data.id}" className="btn btn-primary me-1">Ajouter au panier</a>*/


const gap = 16;

const carousel = document.getElementById("carousel"),
    content = document.getElementById("content"),
    next = document.getElementById("next"),
    prev = document.getElementById("prev");

next.addEventListener("click", e => {
    carousel.scrollBy(width + gap, 0);
    if (carousel.scrollWidth !== 0) {
        prev.style.display = "flex";
    }
    if (content.scrollWidth - width - gap <= carousel.scrollLeft + width) {
        next.style.display = "none";
    }
});
prev.addEventListener("click", e => {
    carousel.scrollBy(-(width + gap), 0);
    if (carousel.scrollLeft - width - gap <= 0) {
        prev.style.display = "none";
    }
    if (!content.scrollWidth - width - gap <= carousel.scrollLeft + width) {
        next.style.display = "flex";
    }
});

let width = carousel.offsetWidth;
window.addEventListener("resize", e => (width = carousel.offsetWidth));


