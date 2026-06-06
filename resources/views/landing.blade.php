<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/png">
    <title>Ngelangi Yuk!</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/templatemo-training-studio.css?v=1.1.1') }}">

    <style>
        #profil .features-items-wrapper {
            justify-content: center;
        }

        #profil .features-items.single-feature {
            margin-top: 60px;
        }

        .typography-container {
            text-align: center;
            margin-top: 30px;
        }

        .typography-container img {
            max-width: 200px;
            margin: 0 10px;
        }
        
        
        .background-header .main-nav .logo h2 {
            color: #232d39 !important;
        }
        .background-header .main-nav .logo em {
            color: #019db2 !important;
        }

        @media (max-width: 768px) {
            .typography-container {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .typography-container img {
                margin: 10px 0;
                max-width: 80%;
            }

            .typography-container img:last-child {
                max-width: 50%;
            }
        }
        
        .mobile-tab-content {
            display: none !important;
        }
        @media (max-width: 991px) {
            #tabs ul .ui-tabs-active .mobile-tab-content {
                display: block !important;
                padding: 15px 10px;
                text-align: left;
                border-top: 1px dashed #eee;
                margin-top: 15px;
            }
            #tabs ul .ui-tabs-active a {
                border-bottom-left-radius: 0;
                border-bottom-right-radius: 0;
            }
        }
    </style>

</head>

<body>

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
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <a href="/" class="logo">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="logo-img">
                            <h2>Ngelangi</h2><em> Yuk!</em>
                        </a>
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#profil">Profil</a></li>
                            <li class="scroll-to-section"><a href="#kelas">Kelas</a></li>
                            <li class="scroll-to-section"><a href="#trainers">Pelatih</a></li>
                            <li class="scroll-to-section"><a href="#contact-us">Kontak</a></li>
                            <li class="main-button"><a href="/login">Member</a></li>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <div class="main-banner">
        <div class="video-overlay header-text">
            <div class="caption">
                <h6>Bersama Ngelangi Yuk</h6>
                <h2>Belajar Renang <em>Yuk!</em></h2>
                <div class="main-button scroll-to-section">
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSeHdI-qJxs_CdkHBQWEu2X6ohZqX2QDRbV70wKP5RW59M8rQQ/viewform">Gabung Ngelangi</a>
                </div>
                <p class="already-member">
                  Sudah jadi member? <a href="/login">Login di sini</a>
                </p>
            </div>
        </div>
    </div>
    <section class="section" id="profil">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="features-items-wrapper">
                        <div class="features-items single-feature">
                            <li class="feature-item">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="Profil Kami">
                                </div>
                                <div class="content">
                                    <h4>Tentang Kami</h4>
                                    <p>Ngelangi Yuk hadir sebagai komunitas dan penyedia les renang profesional yang
                                        lahir
                                        dari semangat para atlet renang dan selam Kota Batu. Berdiri sejak 2021, kami
                                        berkomitmen untuk menghadirkan pengalaman belajar renang yang menyenangkan,
                                        aman,
                                        dan dapat dinikmati oleh semua kalangan.</p>
                                    <p>Kami percaya bahwa renang bukan hanya sekadar cabang olahraga, tapi juga
                                        penunjang
                                        gaya hidup sehat, dan keterampilan penting yang wajib dikuasai sejak dini.</p>
                                </div>
                            </li>
                            <li class="typography-container">
                                <img src="{{ asset('assets/images/typography.png') }}" alt="Gambar 1">
                                <img src="{{ asset('assets/images/typography2.png') }}" alt="Gambar 2"
                                    style="max-width:100px;">
                            </li>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
                        <li>
                            <a href='#tabs-1'><img src="{{ asset('assets/images/line-dec.png') }}" alt="">Kelas Anak</a>
                            <div class="mobile-tab-content d-lg-none">
                                <img class="class-img mobile-img" src="{{ asset('assets/images/square_MG_6122.png') }}" alt="First Class">
                                <div class="class-description mt-3">
                                    <h4>Kelas Anak (Grup & Private)</h4>
                                    <ul>
                                        <li>✅ Tersedia kelas grup & private, sesuai kebutuhan anak.</li>
                                        <li>✅ Fokus pada teknik dasar renang & keberanian di air.</li>
                                        <li>✅ Menggunakan metode menyenangkan agar anak betah belajar.</li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href='#tabs-2'><img src="{{ asset('assets/images/line-dec.png') }}" alt="">Kelas Dewasa</a>
                            <div class="mobile-tab-content d-lg-none">
                                <img class="class-img mobile-img" src="{{ asset('assets/images/square_MG_6105.png') }}" alt="Second Class">
                                <div class="class-description mt-3">
                                    <h4>Kelas Dewasa</h4>
                                    <ul>
                                        <li>✅ Untuk pemula maupun yang ingin memperbaiki teknik renang.</li>
                                        <li>✅ Fokus pada peningkatan stamina, teknik, dan percaya diri di air.</li>
                                        <li>✅ Tersedia sesi pagi, sore, dan malam.</li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href='#tabs-3'><img src="{{ asset('assets/images/line-dec.png') }}" alt="">Kelas Perempuan</a>
                            <div class="mobile-tab-content d-lg-none">
                                <img class="class-img mobile-img" src="{{ asset('assets/images/square_MG_6074.png') }}" alt="Third Class">
                                <div class="class-description mt-3">
                                    <h4>Kelas Khusus Perempuan</h4>
                                    <ul>
                                        <li>✅ Kelas tertutup khusus perempuan, menjaga privasi dan kenyamanan.</li>
                                        <li>✅ Pelatih perempuan profesional & ramah.</li>
                                        <li>✅ Cocok untuk semua usia (remaja hingga dewasa).</li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href='#tabs-4'><img src="{{ asset('assets/images/line-dec.png') }}" alt="">Kelas Terapi</a>
                            <div class="mobile-tab-content d-lg-none">
                                <img class="class-img mobile-img" src="{{ asset('assets/images/square_MG_6161.png') }}" alt="Fourth Class">
                                <div class="class-description mt-3">
                                    <h4>Kelas Terapi</h4>
                                    <ul>
                                        <li>✅ Program renang terapi untuk membantu pemulihan kondisi fisik.</li>
                                        <li>✅ Dipandu oleh pelatih dengan pengalaman terapi air.</li>
                                        <li>✅ Materi latihan disesuaikan kondisi & kemampuan peserta.</li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div class="col-lg-8 d-none d-lg-block">
                    <section class='tabs-content'>
                        <article id='tabs-1'>
                            <img class="class-img desktop-img" src="{{ asset('assets/images/full_MG_6122.png') }}"
                                alt="First Class">
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
                            <img class="class-img desktop-img" src="{{ asset('assets/images/full_MG_6105.png') }}"
                                alt="Second Class">
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
                            <img class="class-img desktop-img" src="{{ asset('assets/images/full_MG_6074.png') }}"
                                alt="Third Class">
                            <div class="class-description">
                                <div class="class-description">
                                    <h4>Kelas Khusus Perempuan</h4>
                                    <ul>
                                        <li>✅ Kelas tertutup khusus perempuan, menjaga privasi dan kenyamanan.</li>
                                        <li>✅ Pelatih perempuan profesional & ramah.</li>
                                        <li>✅ Cocok untuk semua usia (remaja hingga dewasa).</li>
                                    </ul>
                                </div>
                            </div>
                        </article>
                        <article id='tabs-4'>
                            <img class="class-img desktop-img" src="{{ asset('assets/images/full_MG_6161.png') }}"
                                alt="Fourth Class">
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
    <section class="section" id="trainers">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2>Our <em>Coach</em></h2>
                    </div>
                </div>
            </div>
            
            <div class="row">
                
                <div class="col-lg-4 col-12 mb-4 order-2 order-lg-1">
                    <div class="trainer-item">
                        <div class="image-thumb">
                            <img src="{{ asset('assets/images/IMG_Pelatih_1.png') }}" alt="Coach 1">
                        </div>
                    </div>
                </div>
    
                <div class="col-lg-4 col-12 mb-4 order-1 order-lg-2">
                    <div class="trainer-item">
                        <div class="image-thumb">
                            <img src="{{ asset('assets/images/IMG_Pelatih_2.png') }}" alt="Coach 2">
                        </div>
                    </div>
                </div>
    
                <div class="col-lg-4 col-12 mb-4 order-3 order-lg-3">
                    <div class="trainer-item">
                        <div class="image-thumb">
                            <img src="{{ asset('assets/images/IMG_Pelatih_3.png') }}" alt="Coach 3">
                        </div>
                    </div>
                </div>
    
            </div>
        </div>
    </section>
    <section class="section" id="contact-us">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2>Temukan <em>Kami</em></h2>
                        </div>
                </div>
            </div>
        </div>
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
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright © 2025 Ngelangi Yuk
                        <a rel="nofollow" href="#" class="tm-text-link" target="_parent">
                            - Web Designed by Raditya Catur
                            Narendra
                        </a><br>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/jquery-2.1.0.min.js') }}"></script>

    <script src="{{ asset('assets/js/popper.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('assets/js/scrollreveal.min.js') }}"></script>
    <script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/js/imgfix.min.js') }}"></script>
    <script src="{{ asset('assets/js/mixitup.js') }}"></script>
    <script src="{{ asset('assets/js/accordions.js') }}"></script>

    <script src="{{ asset('assets/js/custom.js') }}"></script>

</body>

</html>