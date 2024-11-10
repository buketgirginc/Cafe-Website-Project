/* Scroll to Top butonu için alıntıladığım kısım*/

const toTop = document.querySelector(".to-top");

window.addEventListener("scroll", () => {
    if (window.pageYOffset > 100) {
        toTop.classList.add("active");
    } else {
        toTop.classList.remove("active");
    }
})

/* Scroll to Top butonu için alıntıladığım kısım*/
