<!-- ================= FOOTER ================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const menuBtn = document.getElementById("menuBtn");
    const mobileMenu = document.getElementById("mobileMenu");

    if(menuBtn){
        menuBtn.addEventListener("click", () => {
            mobileMenu.classList.toggle("hidden");
        });
    }

    // auto close menu on click
    document.querySelectorAll("#mobileMenu a").forEach(link => {
        link.addEventListener("click", () => {
            mobileMenu.classList.add("hidden");
        });
    });
});
</script>

<footer class="bg-gray-900 text-white py-8">

<div class="max-w-screen-xl mx-auto px-6 flex justify-between items-center">

<div class="flex items-center gap-3">
<img src="../public/img/logo.png" class="h-8">
<span>NORSU Bais Campus 1</span>
</div>

<p class="text-sm text-gray-400">
© 2026 Negros Oriental State University
</p>

</div>

</footer>

<script src="../public/assets/js/main.js"></script>

</body>
</html>
