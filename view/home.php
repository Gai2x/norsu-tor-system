<?php include __DIR__ . '/../public/includes/landing/Header.php'; ?>

<section class="bg-gradient-to-r from-blue-700 to-blue-500 pt-28 pb-20">
    <div class="max-w-screen-xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h1 class="text-5xl font-bold text-white leading-tight mb-6">
                Welcome to NORSU Bais Campus 1 Academic Services
            </h1>
            <p class="text-blue-100 text-lg mb-8">
                Streamline your academic document requests, schedule consultations, and book appointments all in one convenient platform.
            </p>
            <div class="flex gap-4">
                <a href="../public/Login.php" class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-3 rounded-lg">
                    Student Login
                </a>
                <a href="../public/OneTimeRequest.php"
                class="inline-flex bg-white hover:bg-yellow-500 text-black font-semibold px-6 py-3 rounded-lg items-center hover:text-white transition-colors group">
                    One-Time Request
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>

            </div>
            <p class="text-blue-200 mt-4 text-sm">
                No account? Make a one-time request or register as a student
            </p>
        </div>

        <div class="flex justify-center">
            <img src="../public/img/logo.png" class="w-80 bg-white/10 p-8 rounded-xl shadow-lg" alt="NORSU Logo">
        </div>
    </div>
</section>

<section id="about" class="py-20 bg-gray-100">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">About NORSU Bais Campus</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Negros Oriental State University is committed to delivering global excellence in education,
                research, and community service while developing responsible leaders for the future.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-xl shadow">
                <h3 class="text-xl font-bold text-blue-900 mb-4">Vision Statement</h3>
                <p class="text-gray-700 mb-4 font-medium">A globally recognized state university.</p>
                <p class="text-sm text-gray-500 italic">
                    HON. NOEL MARJON E. YASI, Psy. D. <br>
                    University President <br>
                    BOR Resolution No. 133 s. 2024
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow">
                <h3 class="text-xl font-bold text-blue-900 mb-4">Mission Statement</h3>
                <p class="text-gray-700 mb-4">
                    Negros Oriental State University delivers global excellence through advanced
                    instruction, impactful research, and sustainable extension, with strategic
                    partnerships and modern infrastructure shaping effective leaders to serve
                    the Philippine society and the world.
                </p>
                <p class="text-sm text-gray-500 italic">
                    HON. NOEL MARJON E. YASI, Psy. D. <br>
                    University President <br>
                    BOR Resolution No. 133 s. 2024
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow">
                <h3 class="text-xl font-bold text-blue-900 mb-4">Quality Policy</h3>
                <p class="text-gray-700 mb-4">
                    Negros Oriental State University commits to delivering quality instruction,
                    research, extension and production. We ensure compliance with all statutory
                    and regulatory requirements and continuously work to improve our management
                    system to meet our quality objectives.
                </p>
                <p class="text-sm text-gray-500 italic">
                    HON. NOEL MARJON E. YASI, Psy. D. <br>
                    University President <br>
                    BOR Resolution No. 133 s. 2024
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow">
                <h3 class="text-xl font-bold text-blue-900 mb-4">Core Values</h3>
                <ul class="list-disc pl-6 text-gray-700 space-y-1">
                    <li>Spirituality</li>
                    <li>Honesty</li>
                    <li>Innovation</li>
                    <li>Nurturance</li>
                    <li>Excellence</li>
                </ul>
                <p class="text-sm text-gray-500 italic mt-4">
                    HON. NOEL MARJON E. YASI, Psy. D. <br>
                    University President <br>
                    BOR Resolution No. 133 s. 2024
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow md:col-span-2">
                <h3 class="text-xl font-bold text-blue-900 mb-4">Strategic Goals</h3>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Achieve global recognition by program excellence</li>
                    <li>Strengthen research through impactful innovation</li>
                    <li>Promote enhanced community extension services</li>
                    <li>Integrate partnerships and international relations</li>
                    <li>Revitalize infrastructure with operational systems</li>
                    <li>Enrich student life and leadership opportunities</li>
                </ul>
                <p class="text-sm text-gray-500 italic mt-4">
                    HON. NOEL MARJON E. YASI, Psy. D. <br>
                    University President <br>
                    BOR Resolution No. 133 s. 2024
                </p>
            </div>
        </div>
    </div>
</section>

<section id="services" class="py-20 bg-gray-100">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Services</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Access a comprehensive range of academic services designed to support your educational journey
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($services as $service): ?>
            <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100 hover:shadow-lg transition">
                <div class="bg-blue-900 text-white w-14 h-14 flex items-center justify-center rounded-lg mb-4 text-xl">
                    <?php echo $service['title'] === 'Document Requests' ? 'D' : ($service['title'] === 'Grade Consultations' ? 'G' : 'O'); ?>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($service['title']); ?></h3>
                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($service['desc']); ?></p>
                <p class="text-yellow-500 text-sm font-medium"><?php echo htmlspecialchars($service['time']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="contact" class="py-20 bg-white">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="text-center mb-14">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Contact Us</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Have questions? We're here to help. Reach out to us through any of the following channels.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-10">
            <div class="bg-gray-100 rounded-xl p-8 shadow">
                <h3 class="text-2xl font-bold mb-8">Get in Touch</h3>

                <div class="space-y-6">
                    <div class="flex items-start gap-4 p-3 rounded-lg hover:bg-gray-200 transition">
                        <div class="bg-blue-900 text-white w-12 h-12 flex items-center justify-center rounded-lg text-lg flex-shrink-0">A</div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Address</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                NORSU Bais Campus 1 <br>
                                Negros Oriental State University <br>
                                Bais City, Negros Oriental, Philippines
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-3 rounded-lg hover:bg-gray-200 transition">
                        <div class="bg-blue-900 text-white w-12 h-12 flex items-center justify-center rounded-lg text-lg flex-shrink-0">P</div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Phone</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Main Office: (035) 123-4567 <br>
                                Registrar: (035) 123-4568
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-3 rounded-lg hover:bg-gray-200 transition">
                        <div class="bg-blue-900 text-white w-12 h-12 flex items-center justify-center rounded-lg text-lg flex-shrink-0">E</div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Email</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                registrar@norsu.edu.ph <br>
                                bais.campus@norsu.edu.ph
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-900 to-blue-600 rounded-xl p-8 text-white shadow">
                <h3 class="text-2xl font-bold mb-6">Office Hours</h3>

                <div class="space-y-4">
                    <div class="bg-white/10 p-4 rounded-lg flex justify-between text-white">
                        <span>Monday - Friday</span>
                        <span class="text-yellow-300">8:00 AM - 5:00 PM</span>
                    </div>
                    <div class="bg-white/10 p-4 rounded-lg flex justify-between text-white">
                        <span>Saturday</span>
                        <span class="text-yellow-300">8:00 AM - 12:00 PM</span>
                    </div>
                    <div class="bg-white/10 p-4 rounded-lg flex justify-between text-white">
                        <span>Sunday & Holidays</span>
                        <span class="text-yellow-300">Closed</span>
                    </div>
                    <div class="bg-black/10 p-4 rounded-lg text-white">
                        <p><span class="font-bold">Note:</span> Online request submissions are available 24/7. Processing times apply during office hours.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-screen-xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Why Choose Our Platform?</h2>
            <p class="text-gray-600 mb-6">
                Our modern, user-friendly system makes managing your academic needs simple and efficient.
            </p>

            <ul class="space-y-3 text-gray-700">
                <li>Real-time status tracking</li>
                <li>Secure document handling</li>
                <li>Multiple request types</li>
                <li>Email notifications</li>
                <li>24/7 request submission</li>
                <li>One-time request option</li>
            </ul>
        </div>

        <div class="bg-blue-700 text-white p-8 rounded-xl">
            <h3 class="text-xl font-semibold mb-6">Document Processing Times</h3>
            <div class="space-y-4">
                <div class="bg-blue-600 p-4 rounded-lg flex justify-between">
                    <span>Good Moral Certificate</span>
                    <span class="text-yellow-300">1-2 days</span>
                </div>
                <div class="bg-blue-600 p-4 rounded-lg flex justify-between">
                    <span>Certificates</span>
                    <span class="text-yellow-300">3-5 days</span>
                </div>
                <div class="bg-blue-600 p-4 rounded-lg flex justify-between">
                    <span>Transcript of Records</span>
                    <span class="text-yellow-300">5-10 days</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-gradient-to-r from-blue-700 to-blue-500 py-20 text-center">
    <h2 class="text-3xl font-bold text-white mb-4">Ready to Get Started?</h2>
    <p class="text-blue-100 mb-8">Join hundreds of students already using our platform</p>

    <div class="flex justify-center gap-4">
        <a href="../public/Signup.php" class="bg-yellow-400 text-black font-semibold px-6 py-3 rounded-lg">Create Account</a>
        <a href="../public/Login.php" class="bg-white text-blue-700 font-semibold px-6 py-3 rounded-lg">Sign In</a>
    </div>
</section>

<?php include __DIR__ . '/../public/includes/landing/Footer.php'; ?>
