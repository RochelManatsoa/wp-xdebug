window.addEventListener('scroll', function() {
    var scrollPosition = window.scrollY;
    var stickyFooter = document.getElementById('sticky-footer');
    var menu = document.getElementById('mysticky-nav');  

    if (scrollPosition > 2000) {
        stickyFooter.classList.add("d-sm-none");
        stickyFooter.classList.add("d-block");
        stickyFooter.classList.remove("d-none");
        menu.style.display = 'none';  
    } else {
        stickyFooter.classList.remove("d-sm-none");
        stickyFooter.classList.remove("d-block");
        stickyFooter.classList.add("d-none");
        menu.style.display = 'block';  
    }
});