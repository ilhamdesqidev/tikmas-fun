<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kopi Kenangan Senja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
      rel="stylesheet"
    />
    <!-- feather icon -->
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- CSS -->
  <style>
    :root {
    --primary: #78b65b;
    --bg: #ffffff;
    --text-dark: #333333;
}

* {
    font-family: "poppins", sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    outline: none;
    border: none;
    text-decoration: none;
}

html {
    scroll-behavior: smooth;
}

body {
    font-family: "Poppins", sans-serif;
    background: var(--bg);
    color: var(--text-dark);
}

/* Semua elemen p akan memiliki ketebalan font yang lebih */
p {
    font-weight: 400; /* Dipertebal dari nilai default (biasanya 300/400) */
}

.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9998;
    transition: opacity 0.3s ease;
}

.overlay.active {
    display: block;
    opacity: 1;
}

.navbar {
    width: 100%;
    padding: 1.4rem 7%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #ffffff;
    border-bottom: 1px solid #597336;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 9999;
}

.navbar .navbar-logo {
    font-size: 2rem;
    font-weight: 700;
    color: rgb(0, 0, 0);
    font-style: italic;
}

.navbar .navbar-logo span {
    color: var(--primary);
}

.navbar .navbar-nav a {
    color: #000000;
    display: inline-block;
    font-size: 1.3rem;
    margin-left: 0rem;
    padding: 0 1rem;
}

.navbar .navbar-nav a:hover {
    color: var(--primary);
    transition: 0.5s ease;
}

.navbar .navbar-nav a::after {
    content: "";
    display: block;
    padding-bottom: 0.5rem;
    border-bottom: 0.1rem solid var(--primary);
    transform: scaleX(0);
}

.navbar .navbar-nav a:hover::after {
    transform: scaleX(1);
    transition: transform 250ms linear;
}

.navbar .navbar-extra a {
    color: #000000;
    margin: 0 0.5rem;
}

.navbar .navbar-extra a:hover {
    color: var(--primary);
    transition: 0.5s ease;
}

.navbar .navbar-nav .close-menu {
    display: none;
    position: absolute;
    top: 1rem;
    right: 1rem;
    color: #ffffff;
    cursor: pointer;
    font-size: 2rem;
    z-index: 10000;
    background: none;
    border: none;
}

#menu-icon {
    display: none;
}

.hero {
    min-height: 100vh;
    display: flex;
    align-items: center;
    background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
        url("../img/heroimagetea.jpg");
    background-size: cover;
    background-position: center;
    position: relative;
    padding: 0 7%;
    color: #fff;
}

.hero .content {
    max-width: 60rem;
}

.hero .content h1 {
    font-size: 5em;
    color: #fff;
    text-shadow: 1px 1px 3px rgba(1, 1, 3, 0.5);
    line-height: 1.2;
}

.hero .content h1 span {
    color: var(--primary);
}

.hero .content p {
    font-size: 1.6rem;
    margin-top: 1rem;
    line-height: 1.4;
    font-weight: 500; /* Dipertebal dari sebelumnya */
    text-shadow: 1px 1px 3px rgba(1, 1, 3, 0.5);
    color: #fff;
}

.hero .content .cta {
    margin-top: 1rem;
    display: inline-block;
    padding: 1rem 3rem;
    font-size: 1.4rem;
    color: #fff;
    background-color: var(--primary);
    border-radius: 0.5rem;
    box-shadow: 1px 1px 3px rgba(1, 1, 3, 0.5);
}

.hero .content .cta:hover {
    background-color: #5a8e43;
    transition: 0.3s;
}

.about,
.menu,
.contact {
    padding: 8rem 7% 1.4rem;
}

.about h2,
.menu h2,
.contact h2 {
    text-align: center;
    font-size: 2.6rem;
    margin-bottom: 3rem;
    color: var(--text-dark);
}

.about h2 span,
.menu h2 span,
.contact h2 span {
    color: var(--primary);
}

.about .row {
    display: flex;
}

.about .row .about-img {
    flex: 1 1 45rem;
}

.about .row .about-img img {
    width: 100%;
    height: 350px;
    object-fit: cover;
    border-radius: 10px;
    background: linear-gradient(45deg, var(--primary), #a8e086);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.2rem;
}

.about .row .content {
    flex: 1 1 35rem;
    padding: 0 1rem;
}

.about .row .content h3 {
    font-size: 1.8rem;
    margin-bottom: 1rem;
    color: var(--text-dark);
}

.about .row .content p {
    margin-bottom: 0.8rem;
    font-size: 1.3rem;
    font-weight: 500; /* Dipertebal dari sebelumnya */
    line-height: 1.6;
    color: var(--text-dark);
}

.menu h2,
.contact h2 {
    margin-bottom: 1rem;
}

.menu p,
.contact p {
    text-align: center;
    max-width: 30rem;
    margin: auto;
    font-weight: 500; /* Dipertebal dari sebelumnya */
    line-height: 1.6;
    color: var(--text-dark);
}

.menu .row {
    display: flex;
    flex-wrap: wrap;
    margin-top: 5rem;
    justify-content: center;
}

.menu .row .menu-card {
    text-align: center;
    padding-bottom: 4rem;
    margin: 0 1rem;
}

.menu .row .menu-card img {
    border-radius: 50%;
    width: 150px;
    height: 150px;
    object-fit: cover;
    background: linear-gradient(45deg, var(--primary), #a8e086);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1rem;
    margin: 0 auto;
}

.menu .row .menu-card .menu-card-title {
    margin: 1rem auto 0.5rem;
    color: var(--text-dark);
}

.menu .row .menu-card .menu-card-price {
    color: var(--primary);
    font-weight: bold;
}

.contact .row {
    display: flex;
    margin-top: 2rem;
    background-color: #222;
    flex-wrap: wrap;
}

.contact .row .map {
    flex: 1 1 45rem;
    width: 100%;
    object-fit: cover;
}

.contact .row form {
    flex: 1 1 45rem;
    padding: 5rem 2rem;
    text-align: center;
}

.contact .row form .input-group {
    display: flex;
    align-items: center;
    margin-top: 2rem;
    background-color: var(--bg);
    border: 1px solid #eee;
    padding-left: 2rem;
    border-radius: 5px;
}

.contact .row form .input-group input {
    width: 100%;
    padding: 2rem;
    font-size: 1.7rem;
    background: none;
    color: var(--text-dark);
}

.contact .row form .btn {
    margin-top: 3rem;
    display: inline-block;
    padding: 1rem 3rem;
    font-size: 1.7rem;
    color: #fff;
    background-color: var(--primary);
    cursor: pointer;
    border-radius: 5px;
}

.contact .row form .btn:hover {
    background-color: #5a8e43;
    transition: 0.3s;
}

footer {
    background-color: var(--primary);
    text-align: center;
    padding: 1rem 0 3rem;
    margin-top: 3rem;
    color: #fff;
}

footer .socials {
    padding: 1rem 0;
}

footer .socials a {
    color: #fff;
    margin: 1rem;
}

footer .socials a:hover,
footer .links a:hover {
    color: var(--bg);
}

footer .links {
    margin-bottom: 1.4rem;
}

footer .links a {
    color: #fff;
    padding: 0.7rem 1rem;
}

footer .credit {
    font-size: 0.8rem;
    font-weight: 500; /* Dipertebal dari sebelumnya */
    color: #fff;
}

footer .credit a {
    color: var(--bg);
    font-weight: 700;
}

/* Media Queries */

/* For Desktop */
@media (max-width: 1366px) {
    html {
        font-size: 75%;
    }
}

/* For tablets */
@media (max-width: 758px) {
    html {
        font-size: 62.5%;
    }

    #menu-icon {
        display: inline-block;
        cursor: pointer;
        font-size: 1.5rem;
    }

    .navbar .navbar-nav {
        position: fixed;
        top: 0;
        right: -100%;
        background-color: #000000;
        width: 30rem;
        height: 100vh;
        transition: right 0.3s ease;
        z-index: 9999;
        padding-top: 4rem;
        box-shadow: -5px 0 15px rgba(0, 0, 0, 0.5);
    }

    .navbar .navbar-nav.active {
        right: 0;
    }

    .navbar .navbar-nav .close-menu {
        display: block;
        color: #ffffff;
        font-size: 2.5rem;
        top: 1.5rem;
        right: 1.5rem;
    }

    .navbar .navbar-nav a {
        display: block;
        margin: 2rem 1.5rem;
        padding: 1rem 0;
        font-size: 1.8rem;
        color: #ffffff;
        border-bottom: 1px solid #333;
        transition: all 0.3s ease;
        background: transparent;
    }

    .navbar .navbar-nav a:hover {
        color: var(--primary);
        padding-left: 1rem;
        background: transparent;
    }

    .navbar .navbar-nav a::after {
        transform-origin: 0 0;
        border-color: var(--primary);
    }

    .navbar .navbar-nav a:hover::after {
        transform: scaleX(0.8);
    }

    .hero .content h1 {
        font-size: 3.5em;
    }

    .hero .content p {
        font-weight: 500; /* Dipertebal untuk tablet */
    }

    .about .row {
        flex-wrap: wrap;
    }

    .about .row .about-img img {
        height: 24rem;
        object-fit: cover;
        object-position: center;
    }

    .about .row .content {
        padding: 0;
    }

    .about .row .content h3 {
        margin-top: 1rem;
        font-size: 2rem;
        color: var(--text-dark);
    }

    .about .row .content p {
        font-size: 2rem;
        font-weight: 500; /* Dipertebal untuk tablet */
        color: var(--text-dark);
    }

    .menu .row {
        justify-content: center;
    }

    .menu .row .menu-card {
        width: 45%;
        margin: 0 1rem;
    }

    .contact .row {
        flex-wrap: wrap;
    }

    .contact .row .map {
        height: 30rem;
    }

    .contact .row form {
        padding-top: 0;
    }
}

/* For Phone */
@media (max-width: 450px) {
    html {
        font-size: 55%;
    }

    .hero .content h1 {
        font-size: 3em;
    }

    .hero .content p {
        font-weight: 500; /* Dipertebal untuk mobile */
    }

    .navbar .navbar-nav {
        width: 25rem;
        background-color: #000000;
    }

    .navbar .navbar-nav a {
        font-size: 1.6rem;
        margin: 1.5rem;
        color: #ffffff;
        background: transparent;
    }

    .navbar .navbar-nav .close-menu {
        color: #ffffff;
        background: none;
    }

    .menu .row .menu-card {
        width: 100%;
        margin: 0;
    }

    .about .row .about-img img {
        height: 20rem;
    }

    .about .row .content p {
        font-weight: 500; /* Dipertebal untuk mobile */
    }

    .contact .row {
        flex-wrap: wrap;
    }

    .contact .row .map {
        height: 25rem;
    }

    .contact .row form {
        padding: 2rem 1rem;
    }
}
  </style>
  </head>
  <body>
    <!-- navbar start -->
    <nav class="navbar">
      <a href="#" class="navbar-logo">Line<span>Tea</span>.</a>
      <div class="navbar-nav">
        <!-- Tambahkan tombol close di dalam navbar-nav -->
        <div class="close-menu">
          <i data-feather="x"></i>
        </div>
        <a href="#home">Home</a>
        <a href="#about">Tentang Kami</a>
        <a href="#menu">Menu</a>
        <a href="#contact">Kontak</a>
      </div>
      <div class="navbar-extra">
        <a href="#" id="search-icon"><i data-feather="search"></i></a>
        <a href="#" id="shopping-cart"><i data-feather="shopping-cart"></i></a>
        <a href="#" id="menu-icon"><i data-feather="menu"></i></a>
      </div>
    </nav>

    <!-- Tambahkan overlay -->
    <div class="overlay"></div>

    <!-- navbar end -->

    <!-- hero section start -->
    <section class="hero" id="home">
      <main class="content">
        <h1>Mari Nikmati Secangkir <span>Teh</span></h1>
        <p>
          Nikmati secangkir Teh spesial di tempat kami yang nyaman dan
          bersahabat. Kami menyajikan berbagai varian Teh berkualitas tinggi
          yang akan memanjakan lidah Anda.
        </p>
        <a href="#" class="cta">Beli Sekarang</a>
      </main>
    </section>
    <!-- hero section end -->

    <!-- about section start -->
    <section id="about" class="about">
      <h2><span>Tentang</span> Kami</h2>
      <div class="row">
        <div class="about-img">
          <img src="img/tentangteh.jpg" alt="Tentang Kami" />
        </div>
        <div class="content">
          <h3>Kenapa memilih kopi kami?</h3>
          <p>
            Kenangan Senja adalah kedai kopi yang didirikan dengan cinta dan
            dedikasi untuk menghadirkan pengalaman kopi terbaik. Kami percaya
            bahwa setiap tegukan kopi dapat menciptakan kenangan indah yang akan
            diingat selamanya.
          </p>
          <p>
            Biji kopi kami dipilih langsung dari petani lokal terbaik dan diolah
            dengan teknik roasting yang sempurna. Setiap cangkir kopi yang kami
            sajikan adalah hasil dari perpaduan tradisi dan inovasi modern.
          </p>
        </div>
      </div>
    </section>
    <!-- about section end -->

    <!-- menu section start -->
    <section id="menu" class="menu">
      <h2><span>Menu</span> Kami</h2>
      <p>Nikmati berbagai pilihan kopi dan makanan pendamping yang lezat</p>
      <div class="row">
        <div class="menu-card">
          <img src="img/espresso.jpg" alt="Espresso" class="menu-card-img" />
          <h3 class="menu-card-title">- Espresso -</h3>
          <p class="menu-card-price">IDR 15K</p>
        </div>
        <div class="menu-card">
          <img
            src="img/cappuccino.jpg"
            alt="Cappuccino"
            class="menu-card-img"
          />
          <h3 class="menu-card-title">- Cappuccino -</h3>
          <p class="menu-card-price">IDR 25K</p>
        </div>
        <div class="menu-card">
          <img src="img/latte.jpg" alt="Latte" class="menu-card-img" />
          <h3 class="menu-card-title">- Latte -</h3>
          <p class="menu-card-price">IDR 22K</p>
        </div>
        <div class="menu-card">
          <img src="img/americano.jpg" alt="Americano" class="menu-card-img" />
          <h3 class="menu-card-title">- Americano -</h3>
          <p class="menu-card-price">IDR 18K</p>
        </div>
        <div class="menu-card">
          <img src="img/mocha.jpg" alt="Mocha" class="menu-card-img" />
          <h3 class="menu-card-title">- Mocha -</h3>
          <p class="menu-card-price">IDR 28K</p>
        </div>
        <div class="menu-card">
          <img src="img/crossaint.jpg" alt="Croissant" class="menu-card-img" />
          <h3 class="menu-card-title">- Croissant -</h3>
          <p class="menu-card-price">IDR 12K</p>
        </div>
      </div>
    </section>
    <!-- menu section end -->

    <!-- contact section start -->
    <section id="contact" class="contact">
      <h2><span>Kontak</span> Kami</h2>
      <p>Hubungi kami untuk informasi lebih lanjut atau reservasi</p>
      <div class="row">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d248849.84916296526!2d106.66440951640624!3d-6.229386799999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta%2C%20Daerah%20Khusus%20Ibukota%20Jakarta!5e0!3m2!1sen!2sid!4v1640021326058!5m2!1sen!2sid"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          class="map"
        ></iframe>
        <form action="">
          <div class="input-group">
            <i data-feather="user"></i>
            <input type="text" placeholder="nama" />
          </div>
          <div class="input-group">
            <i data-feather="mail"></i>
            <input type="text" placeholder="email" />
          </div>
          <div class="input-group">
            <i data-feather="phone"></i>
            <input type="text" placeholder="no hp" />
          </div>
          <div class="input-group">
            <i data-feather="message-square"></i>
            <input type="text" placeholder="pesan" />
          </div>
          <button type="submit" class="btn">kirim pesan</button>
        </form>
      </div>
    </section>
    <!-- contact section end -->

    <!-- footer start -->
    <footer>
      <div class="socials">
        <a href="https://www.instagram.com/wisataagro8/?hl=id"
          ><i data-feather="instagram"></i
        ></a>
        <a href="https://twitter.com/agrowisata_n8"
          ><i data-feather="twitter"></i
        ></a>
        <a href="https://www.facebook.com/AgrowisataN8/"
          ><i data-feather="facebook"></i
        ></a>
      </div>
      <div class="links">
        <a href="#home">Home</a>
        <a href="#about">Tentang Kami</a>
        <a href="#menu">Menu</a>
        <a href="#contact">Kontak</a>
      </div>
      <div class="credit">
        <p>Created by <a href="">Kenangan Senja</a>. | &copy; 2025.</p>
      </div>
    </footer>
    <!-- footer end -->

    <!-- feather icons -->
    <script>
      feather.replace();
    </script>

    <script>
      // Toggle navbar mobile menu
      const navbarNav = document.querySelector(".navbar-nav");
      const menuIcon = document.querySelector("#menu-icon");
      const closeMenu = document.querySelector(".close-menu");
      const overlay = document.querySelector(".overlay");

      menuIcon.addEventListener("click", (e) => {
        e.stopPropagation();
        navbarNav.classList.add("active");
        overlay.classList.add("active");
        document.body.style.overflow = "hidden"; // Mencegah scroll ketika menu terbuka
      });

      closeMenu.addEventListener("click", () => {
        navbarNav.classList.remove("active");
        overlay.classList.remove("active");
        document.body.style.overflow = "auto";
      });

      overlay.addEventListener("click", () => {
        navbarNav.classList.remove("active");
        overlay.classList.remove("active");
        document.body.style.overflow = "auto";
      });

      // Close navbar ketika klik di luar nav
      document.addEventListener("click", (e) => {
        const isClickInsideNav = e.target.closest(".navbar-nav") !== null;
        const isClickOnMenuIcon = e.target.closest("#menu-icon") !== null;

        if (
          !isClickInsideNav &&
          !isClickOnMenuIcon &&
          navbarNav.classList.contains("active")
        ) {
          navbarNav.classList.remove("active");
          overlay.classList.remove("active");
          document.body.style.overflow = "auto";
        }
      });

      // Smooth scrolling untuk navigation links dengan offset
      document
        .querySelectorAll('.navbar-nav a[href^="#"]')
        .forEach((anchor) => {
          anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const targetId = this.getAttribute("href");
            const target = document.querySelector(targetId);

            if (target) {
              // Tutup menu mobile
              navbarNav.classList.remove("active");
              overlay.classList.remove("active");
              document.body.style.overflow = "auto";

              // Hitung offset berdasarkan tinggi navbar
              const navbarHeight =
                document.querySelector(".navbar").offsetHeight;
              const targetPosition =
                target.getBoundingClientRect().top +
                window.pageYOffset -
                navbarHeight;

              // Smooth scroll ke posisi target dengan offset
              window.scrollTo({
                top: targetPosition,
                behavior: "smooth",
              });
            }
          });
        });

      // Navbar scroll effect
      // window.addEventListener("scroll", () => {
      //   const navbar = document.querySelector(".navbar");
      //   if (window.scrollY > 100) {
      //     navbar.style.backgroundColor = "rgba(1, 1, 1, 0.95)";
      //   } else {
      //     navbar.style.backgroundColor = "rgba(1, 1, 1, 0.8)";
      //   }
      // });

      // Form submission
      document.querySelector("form").addEventListener("submit", (e) => {
        e.preventDefault();
        alert("Terima kasih! Pesan Anda telah terkirim.");

        // Reset form setelah submit
        e.target.reset();
      });

      // Feather icons initialization
      feather.replace();
    </script>
  </body>
</html>