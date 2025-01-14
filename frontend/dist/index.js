$(document).ready(function () {
    $("#navbar-placeholder").load("nav.html", function () {
        console.log("Navbar loaded successfully.");
    });
});
$(document).ready(function () {
    $("#footer-placeholder").load("footer.html", function () {
        console.log("Footer loaded successfully.");
    });
});