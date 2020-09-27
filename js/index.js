document.querySelectorAll('.items').forEach(function (slider) {
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.classList.add('active');
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });
    slider.addEventListener('mouseleave', (e) => {
        isDown = false;
        slider.classList.remove('active');
    });
    slider.addEventListener('mouseup', (e) => {
        isDown = false;
        slider.classList.remove('active');
    });
    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 2; //scroll-fast
        slider.scrollLeft = scrollLeft - walk;
        console.log(walk);
    });
});

$(document).ready(function () {
    $(".container").css("padding-top", $(".navbar-fixed-top").css("height"));
    $(window).resize(function () {
        $(".container").css("padding-top", $(".navbar-fixed-top").css("height"));
    });
});
