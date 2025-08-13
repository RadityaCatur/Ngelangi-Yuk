<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/png">
    <title>Ngelangi Yuk!</title>

    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/templatemo-training-studio.css') }}">

</head>

<body>

    <!-- ***** Preloader Start ***** -->
    <div id="js-preloader" class="js-preloader">
        <div class="preloader-inner">
            <span class="dot"></span>
            <div class="dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- ***** Preloader End ***** -->

    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="/" class="logo">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="logo-img">
                            Ngelangi<em> Yuk!</em>
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#profil">Profil</a></li>
                            <li class="scroll-to-section"><a href="#kelas">Kelas</a></li>
                            <li class="scroll-to-section"><a href="#trainers">Pelatih</a></li>
                            <li class="scroll-to-section"><a href="#penghargaan">Penghargaan</a></li>
                            <li class="scroll-to-section"><a href="#contact-us">Kontak</a></li>
                            <li class="main-button"><a href="/login">Member</a></li>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <!-- ***** Main Banner Area Start ***** -->
    <div class="main-banner">
        <video autoplay muted loop id="bg-video">
            <source src="{{ asset('assets/images/gym-video.mp4') }}" type="video/mp4">
        </video>

        <div class="video-overlay header-text">
            <div class="caption">
                <h6>Bersama Ngelangi Yuk</h6>
                <h2>Belajar Renang <em>Yuk!</em></h2>
                <div class="main-button scroll-to-section">
                    <a href="#">Gabung Ngelangi</a>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Main Banner Area End ***** -->

    <!-- ***** Features Item Start ***** -->
    <section class="section" id="profil">
        <div class="container">
            <div class="row section-heading">
                <div class="features-items-wrapper">
                    <div class="features-items single-feature">
                        <li class="feature-item">
                            <div class="icon">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="Profil Kami">
                            </div>
                            <div class="content">
                                <h4>Tentang Kami</h4>
                                <p>Ngelangi Yuk hadir sebagai komunitas dan penyedia les renang profesional yang lahir
                                    dari semangat para atlet renang dan selam Kota Batu. Berdiri sejak 2021, kami
                                    berkomitmen untuk menghadirkan pengalaman belajar renang yang menyenangkan, aman,
                                    dan dapat dinikmati oleh semua kalangan.</p>
                                <p>Kami percaya bahwa renang bukan hanya sekadar cabang olahraga, tapi juga penunjang
                                    gaya hidup sehat, dan keterampilan penting yang wajib dikuasai sejak dini.</p>
                            </div>
                        </li>
                    </div>
                    <div class="features-items single-feature right">
                        <li class="feature-item">
                            <div class="content">
                                <h4>🌟 Visi:</h4>
                                <p>Mewujudkan berenang sebagai gaya hidup sehat dan positif bagi semua kalangan, dari
                                    anak-anak hingga lansia.</p>
                            </div>
                        </li>
                        <li class="feature-item">
                            <div class="content">
                                <h4>🚀 Misi:</h4>
                                <p>✅ Menjadikan berenang sebagai keterampilan wajib bagi anak-anak sebagai bentuk
                                    pertahanan diri.</p>
                                <p>✅ Memasyarakatkan olahraga renang sebagai aktivitas yang dapat dilakukan sepanjang
                                    usia.</p>
                                <p>✅ Memberikan pengalaman belajar renang yang aman, profesional, dan menyenangkan.</p>
                            </div>
                        </li>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Features Item End ***** -->

    <!-- ***** Our Classes Start ***** -->
    <section class="section" id="kelas">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2>Program <em>Unggulan</em></h2>
                        <p>Setiap program didesain agar sesuai kebutuhan peserta, dengan bimbingan instruktur
                            berpengalaman dan suasana latihan yang suportif.</p>
                    </div>
                </div>
            </div>
            <div class="row" id="tabs">
                <div class="col-lg-4">
                    <ul>
                        <li><a href='#tabs-1'><img src="{{ asset('assets/images/line-dec.png') }}" alt="">Kelas Anak</a>
                        </li>
                        <li><a href='#tabs-2'><img src="{{ asset('assets/images/line-dec.png') }}" alt="">Kelas
                                Dewasa</a></a></li>
                        <li><a href='#tabs-3'><img src="{{ asset('assets/images/line-dec.png') }}" alt="">Kelas
                                Perempuan</a></a></li>
                        <li><a href='#tabs-4'><img src="{{ asset('assets/images/line-dec.png') }}" alt="">Kelas
                                Terapi</a></a></li>
                    </ul>
                </div>
                <div class="col-lg-8">
                    <section class='tabs-content'>
                        <article id='tabs-1'>
                            <img src="{{ asset('assets/images/kelas-anak.png') }}" alt="First Class">
                            <div class="class-description">
                                <h4>Kelas Anak (Grup & Private)</h4>
                                <ul>
                                    <li>✅ Tersedia kelas grup & private, sesuai kebutuhan anak.</li>
                                    <li>✅ Fokus pada teknik dasar renang & keberanian di air.</li>
                                    <li>✅Menggunakan metode menyenangkan agar anak betah belajar.</li>
                                </ul>
                            </div>
                        </article>
                        <article id='tabs-2'>
                            <img src="{{ asset('assets/images/kelas-dewasa.jpg') }}" alt="Second Training">
                            <div class="class-description">
                                <h4>Kelas Dewasa</h4>
                                <ul>
                                    <li>✅ Untuk pemula maupun yang ingin memperbaiki teknik renang.</li>
                                    <li>✅ Fokus pada peningkatan stamina, teknik, dan percaya diri di air.</li>
                                    <li>✅ Tersedia sesi pagi, sore, dan malam.</li>
                                </ul>
                            </div>
                        </article>
                        <article id='tabs-3'>
                            <img src="{{ asset('assets/images/kelas-perempuan.png') }}" alt="Third Class">
                            <div class="class-description">
                                <h4>Kelas Khusus Perempuan</h4>
                                <ul>
                                    <li>✅ Kelas tertutup khusus perempuan, menjaga privasi dan kenyamanan.</li>
                                    <li>✅ Pelatih perempuan profesional & ramah.</li>
                                    <li>✅ Cocok untuk semua usia (remaja hingga dewasa).</li>
                                </ul>
                            </div>
                        </article>
                        <article id='tabs-4'>
                            <img src="{{ asset('assets/images/kelas-therapy.jpg') }}" alt="Fourth Training">
                            <div class="class-description">
                                <h4>Kelas Terapi</h4>
                                <ul>
                                    <li>✅ Program renang terapi untuk membantu pemulihan kondisi fisik.</li>
                                    <li>✅ Dipandu oleh pelatih dengan pengalaman terapi air.</li>
                                    <li>✅ Materi latihan disesuaikan kondisi & kemampuan peserta.</li>
                                </ul>
                            </div>
                        </article>
                    </section>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Our Classes End ***** -->

    <!-- ***** Trainers Starts ***** -->
    <section class="section" id="trainers">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2>Pelatih <em>Hebat</em></h2>
                        <p>Setiap pelatih siap mendampingi dan membagikan pengalaman terbaik, suasana latihan
                            menyenangkan, penuh semangat, dan suportif</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="trainer-item">
                        <div class="image-thumb">
                            <img src="{{ asset('assets/images/first-trainer.jpg') }}" alt="">
                        </div>
                        <div class="down-content">
                            <h4>Salva Almayda Putri</h4>
                            <ul class="social-icons">
                                <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="trainer-item">
                        <div class="image-thumb">
                            <img src="{{ asset('assets/images/second-trainer.jpg') }}" alt="">
                        </div>
                        <div class="down-content">
                            <h4>Raditya Catur Narendra</h4>
                            <ul class="social-icons">
                                <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="trainer-item">
                        <div class="image-thumb">
                            <img src="{{ asset('assets/images/first-trainer.jpg') }}" alt="">
                        </div>
                        <div class="down-content">
                            <h4>Salva Almayda Putri</h4>
                            <ul class="social-icons">
                                <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Trainers Ends ***** -->

    <!-- ***** Achievements ***** -->
    <section class="section" id="penghargaan">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="cta-content">
                        <h2>Penghargaan</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                    </div>
                </div>
                <div class="col-lg-6">
                    <ul class="penghargaan-items">
                        <li class="penghargaan-item">
                            <div class="left-icon">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="First One">
                            </div>
                            <div class="right-content">
                                <h4>Penghargaan 1</h4>
                                <p>Nama: </p>
                                <p>Umur: </p>
                                <p>Jenis Kelas:</p>
                            </div>
                        </li>
                        <li class="penghargaan-item">
                            <div class="left-icon">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="First One">
                            </div>
                            <div class="right-content">
                                <h4>Penghargaan 2</h4>
                                <p>Nama: </p>
                                <p>Umur: </p>
                                <p>Jenis Kelas:</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <ul class="penghargaan-items">
                        <li class="penghargaan-item">
                            <div class="left-icon">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="First One">
                            </div>
                            <div class="right-content">
                                <h4>Penghargaan 3</h4>
                                <p>Nama: </p>
                                <p>Umur: </p>
                                <p>Jenis Kelas:</p>
                            </div>
                        </li>
                        <li class="penghargaan-item">
                            <div class="left-icon">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="First One">
                            </div>
                            <div class="right-content">
                                <h4>Penghargaan 4</h4>
                                <p>Nama: </p>
                                <p>Umur: </p>
                                <p>Jenis Kelas:</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
    </section>
    <!-- ***** Achievements ***** -->

    <!-- ***** Contact Us Area Starts ***** -->
    <section class="section" id="contact-us">
        <div class="container-fluid">
            <div class="row gx-3 gy-3">
                <div class="col-lg-4 col-md-12 contact-col">
                    <div class="contact-form d-flex align-items-center">
                        <div class="contact-info-box">
                            <h4>Kontak Kami</h4>
                            <ul class="contact-info-list">
                                <li><span class="label">🏢 Nama</span><span class="separator">:</span><span
                                        class="value">Ngelangi Yuk!</span></li>
                                <li><span class="label">📍 Lokasi</span><span class="separator">:</span><span
                                        class="value">Royal Hotel Villa Batu & Hotel Purnama</span></li>
                                <li><span class="label">📧 Email</span><span class="separator">:</span><span
                                        class="value">lesrenangkotabatu@gmail.com</span></li>
                                <li><span class="label">📞 Telepon</span><span class="separator">:</span><span
                                        class="value">081327915151</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 map-container">
                    <div class="map-frame">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.2887186692806!2d112.50823617476686!3d-7.864824992157161!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7f94bde5720df%3A0xfb149fce5cf80066!2sRoyal%20Hotel%20%26%20Villa%20Batu!5e0!3m2!1sen!2sid!4v1751950124124!5m2!1sen!2sid"
                            frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 map-container">
                    <div class="map-frame">
                        <iframe <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.5189940989353!2d112.52539077476665!3d-7.84062139218067!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e787e00d6230e6f%3A0xd39f52142d42a316!2sPurnama%20Hotel!5e0!3m2!1sen!2sid!4v1752033703714!5m2!1sen!2sid"
                            frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Contact Us Area Ends ***** -->

    <!-- ***** Footer Start ***** -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; 2025 Ngelangi Yuk

                        - Web Designed by <a rel="nofollow" href="#" class="tm-text-link" target="_parent">Raditya Catur
                            Narendra</a><br>

                    </p>

                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-2.1.0.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('assets/js/popper.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ asset('assets/js/scrollreveal.min.js') }}"></script>
    <script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/js/imgfix.min.js') }}"></script>
    <script src="{{ asset('assets/js/mixitup.js') }}"></script>
    <script src="{{ asset('assets/js/accordions.js') }}"></script>

    <!-- Global Init -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>

</body>

</html>