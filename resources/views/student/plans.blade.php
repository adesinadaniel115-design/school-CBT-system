<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plans & Tokens - School CBT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 2px 0 30px rgba(0, 0, 0, 0.1);
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            transform: translateX(0);
            z-index: 1040;
        }

        body.sidebar-collapsed .sidebar {
            transform: translateX(-100%);
        }

        body.sidebar-collapsed .sidebar-brand,
        body.sidebar-collapsed .sidebar-menu {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .sidebar-brand span,
        .sidebar-brand p,
        .menu-label {
            transition: opacity 0.2s ease, transform 0.2s ease;
            white-space: nowrap;
        }

        body.sidebar-collapsed .sidebar-brand span,
        body.sidebar-collapsed .sidebar-brand p {
            opacity: 0;
            transform: translateX(-8px);
            pointer-events: none;
        }

        body.sidebar-collapsed .menu-item {
            justify-content: center;
        }

        body.sidebar-collapsed .menu-item:hover {
            transform: none;
        }

        .sidebar-brand {
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 2rem;
        }

        .sidebar-brand h4 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
            font-size: 1.5rem;
        }

        .sidebar-brand p {
            color: #6b7280;
            margin: 0.5rem 0 0;
            font-size: 0.875rem;
        }

        .sidebar-menu {
            padding: 0 1rem;
        }

        /* Sidebar visibility defaults */
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0) !important;
                z-index: 1040;
            }
        }

        @media (max-width: 991px) {
            .sidebar {
                /* Span full height and width on mobile */
                top: 0 !important;
                bottom: 0 !important;
                height: 100vh !important;
                background: rgba(255, 255, 255, 0.98) !important;
                transform: translateX(-100%) !important;
                z-index: 1050;
            }
            .sidebar.show {
                transform: translateX(0) !important;
            }
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #374151;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .menu-item:hover {
            background: #f3f4f6;
            transform: translateX(4px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .menu-item.logout {
            color: var(--danger);
        }

        .menu-item.logout:hover {
            background: lightyellow;
        }

        .menu-icon i {
            font-size: 1.2rem;
        }

        .menu-label {
            transition: opacity 0.2s ease, transform 0.2s ease;
            white-space: nowrap;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
            transition: padding 0.25s ease;
            width: 100%;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .top-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 100;
        }

        /* extra toggle container shown near the bottom of the page (before motivational quote) */
        .quote-actions {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 1rem;
        }

        .quote-actions .sidebar-toggle {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            color: #374151;
        }

        .sidebar-toggle {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            color: #374151;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            transition: all 0.2s;
            cursor: pointer;
            position: relative;
            z-index: 101;
            flex-shrink: 0;
        }

        .sidebar-toggle:active {
            background: #f3f4f6;
        }

        .sidebar-toggle i {
            transition: transform 0.25s ease;
        }

        body.sidebar-collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        .sidebar-toggle:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        /* Page Content */
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .page-title i {
            margin-right: 0.75rem;
            font-size: 2.5rem;
        }

        .content-wrapper {
            flex: 1;
        }

        /* Plan Cards */
        .plan-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .plan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .plan-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .plan-badge.popular {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
        }

        .plan-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .plan-subtitle {
            color: #6b7280;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }

        .plan-price {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .plan-features {
            list-style: none;
            padding: 0;
            margin: 1.5rem 0;
            flex: 1;
        }

        .plan-features li {
            padding: 0.75rem 0;
            color: #374151;
            display: flex;
            align-items: center;
        }

        .plan-features li:before {
            content: "✓";
            color: var(--success);
            font-weight: bold;
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        .plan-description {
            color: #6b7280;
            font-size: 0.9rem;
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        /* Payment Section */
        .payment-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        .payment-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .payment-info-card {
            background: #f9fafb;
            border-left: 4px solid var(--primary);
            padding: 1.5rem;
            border-radius: 10px;
        }

        .payment-info-label {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .payment-info-value {
            color: #1f2937;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            word-break: break-all;
        }

        .payment-action-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .payment-action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
            color: white;
            text-decoration: none;
        }

        .copy-button {
            padding: 0.5rem 0.75rem;
            background: #e5e7eb;
            color: #374151;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .copy-button:hover {
            background: #d1d5db;
        }

        .copy-button.copied {
            background: var(--success);
            color: white;
            border-color: var(--success);
        }

        .instructions {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid var(--warning);
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
        }

        .instructions h6 {
            color: #92400e;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .instructions ol {
            color: #78350f;
            margin-bottom: 0;
        }

        .instructions li {
            margin-bottom: 0.75rem;
        }

        .instructions ul {
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .note {
            background: #f0fdf4;
            border-left: 4px solid var(--success);
            padding: 1rem;
            border-radius: 8px;
            color: #15803d;
            font-size: 0.9rem;
            margin-top: 1.5rem;
        }

        /* Motivational Quote */
        .motivation-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 2rem;
            color: white;
            margin: 2rem 0; /* add top and bottom space to separate from preceding content */
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .motivation-text {
            font-size: 1.3rem;
            font-weight: 600;
            line-height: 1.8;
            margin-bottom: 1rem;
            letter-spacing: 0.5px;
        }

        .motivation-text .line1 {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: none;
        }

        .motivation-text .line2 {
            display: block;
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            opacity: 0.95;
        }

        .motivation-signature {
            font-size: 0.95rem;
            font-weight: 600;
            margin-top: 1.5rem;
            opacity: 0.9;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .motivation-card {
                padding: 1.5rem;
            }
            .motivation-text .line1 {
                font-size: 1.25rem;
            }
            .motivation-text .line2 {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .motivation-card {
                padding: 1rem;
            }
            .motivation-text {
                font-size: 1.1rem;
            }
            .motivation-text .line1 {
                font-size: 1.1rem;
            }
            .motivation-text .line2 {
                font-size: 0.95rem;
            }
        }

        /* Footer */
        .footer-spacer {
            flex: 1;
        }

        footer {
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 1rem 2rem;
            text-align: center;
            margin-top: 2rem;
        }

        footer p {
            margin: 0;
            color: #6b7280;
            font-size: 0.875rem;
        }

        footer strong {
            color: #1f2937;
        }

        /* Desktop - Sidebar always visible, toggle shown for recovery */
        @media (min-width: 992px) {
            .sidebar-toggle {
                display: flex !important;
            }
        }

        /* Tablet & Below - Sidebar hideable via toggle */
        @media (max-width: 991px) {
            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: flex !important;
            }
        }

        /* Mobile Responsive (max-width: 768px) */
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }

            .top-actions {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .page-title i {
                font-size: 1.75rem;
            }

            .plan-card {
                padding: 1.5rem;
            }

            .plan-title {
                font-size: 1.3rem;
            }

            .plan-price {
                font-size: 1.75rem;
            }

            .payment-section {
                padding: 1.5rem;
            }

            .payment-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .payment-info-card {
                padding: 1rem;
            }

            .instructions {
                padding: 1rem;
            }

            footer {
                padding: 1rem;
            }
        }

        /* Small Mobile (max-width: 480px) */
        @media (max-width: 480px) {
            .main-content {
                padding: 0.75rem;
            }

            .page-title {
                font-size: 1.25rem;
                margin-bottom: 1rem;
            }

            .page-title i {
                font-size: 1.5rem;
                margin-right: 0.5rem;
            }

            .plan-card {
                padding: 1rem;
            }

            .plan-title {
                font-size: 1.1rem;
            }

            .plan-price {
                font-size: 1.5rem;
            }

            .plan-features li {
                font-size: 0.9rem;
                padding: 0.5rem 0;
            }

            .payment-section {
                padding: 1rem;
            }

            .payment-title {
                font-size: 1.3rem;
            }

            .payment-info-value {
                font-size: 1rem;
            }

            .payment-action-button {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }

            .instructions {
                padding: 0.75rem;
            }

            .instructions ol {
                padding-left: 1.25rem;
                font-size: 0.9rem;
            }

            footer p {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <!-- Sidebar -->
    @include('student.partials.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-actions">
            <button class="sidebar-toggle" id="sidebarToggle" type="button" style="display: flex !important;">
                <i class="bi bi-list"></i>
            </button>
        </div>

        <div class="content-wrapper">
            <div class="page-title">
                <i class="bi bi-credit-card-fill"></i>
                Plans & Tokens
            </div>

            <!-- Plan Cards -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="plan-card">
                        <h4 class="plan-title">Basic Plan</h4>
                        <p class="plan-subtitle">Perfect for quick practice</p>
                        <div class="plan-price">₦500</div>
                        <ul class="plan-features">
                            <li>180 JAMB-standard questions</li>
                            <li>2-hour exam timer</li>
                            <li>3 full attempts</li>
                            <li>Basic performance tracking</li>
                        </ul>
                        <p class="plan-description">Ideal for students who want to test their readiness under standard exam conditions.</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="plan-card">
                        <span class="plan-badge popular"><i class="bi bi-star-fill"></i> Most Popular</span>
                        <h4 class="plan-title">Smart Plan</h4>
                        <p class="plan-subtitle">Designed for serious improvement</p>
                        <div class="plan-price">₦1,000</div>
                        <ul class="plan-features">
                            <li>Everything in Basic</li>
                            <li>Question reshuffling on every attempt</li>
                            <li>Detailed answer explanations</li>
                            <li>Performance streak tracking</li>
                            <li>7 full attempts</li>
                        </ul>
                        <p class="plan-description">Reshuffling prevents memorization and forces real understanding.</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="plan-card">
                        <span class="plan-badge"><i class="bi bi-gem-fill"></i> Elite Package</span>
                        <h4 class="plan-title">Premium Plan</h4>
                        <p class="plan-subtitle">Elite Performance Package</p>
                        <div class="plan-price">₦2,000</div>
                        <ul class="plan-features">
                            <li>Everything in Smart</li>
                            <li>National-style leaderboard</li>
                            <li>Downloadable PDF reports</li>
                            <li>15 full attempts</li>
                            <li>Advanced performance analytics</li>
                        </ul>
                        <p class="plan-description">Competing on a leaderboard increases motivation and tracks progress.</p>
                    </div>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="payment-section">
                <h5 class="payment-title"><i class="bi bi-wallet-fill"></i> Activate Your Plan</h5>

                <div class="instructions">
                    <h6><i class="bi bi-info-circle-fill"></i> How to Activate</h6>
                    <ol>
                        <li>Make payment to the account details below</li>
                        <li>Send a WhatsApp message with:
                            <ul>
                                <li>Your Full Name</li>
                                <li>Registered Email Address</li>
                                <li>Selected Plan</li>
                                <li>Proof of Payment (screenshot)</li>
                            </ul>
                        </li>
                        <li>Your request will be verified within 24 hours</li>
                        <li>Your token will be issued and activated</li>
                    </ol>
                </div>

                <h6 class="mt-4 mb-3"><i class="bi bi-bank"></i> Payment Details</h6>
                <div class="payment-grid">
                    <div class="payment-info-card">
                        <div class="payment-info-label">Account Name</div>
                        <div class="payment-info-value">DANIEL ADESINA OLUWAJUWON</div>
                    </div>
                    <div class="payment-info-card">
                        <div class="payment-info-label">Bank</div>
                        <div class="payment-info-value">OPAY</div>
                    </div>
                    <div class="payment-info-card">
                        <div class="payment-info-label">Account Number</div>
                        <div class="payment-info-value" id="account-number">9069644323</div>
                        <button class="copy-button" id="copy-account" type="button">
                            <i class="bi bi-files"></i> Copy
                        </button>
                    </div>
                    <div class="payment-info-card">
                        <div class="payment-info-label">WhatsApp (Text Only)</div>
                        <a href="https://wa.me/2349076464347" target="_blank" class="payment-action-button">
                            <i class="bi bi-whatsapp"></i> Message +234 907 646 4347
                        </a>
                    </div>
                </div>

                <div class="note">
                    <i class="bi bi-exclamation-circle-fill"></i> <strong>Important:</strong> This page does not activate plans automatically. Follow the instructions above to request activation through WhatsApp only.
                </div>
            </div>
        </div>

        <!-- Motivational Quote -->
        <div class="motivation-card">
            <div class="motivation-text">
                <span class="line1">Work work work!!!<br>Now</span>
                <span class="line2">And ask God to grace it and you rest tomorrow</span>
            </div>
            <div class="motivation-signature">~ D.O Adesina</div>
        </div>

        <div class="footer-spacer"></div>

        <!-- Footer -->
        <footer>
            <p><strong>CBT Platform</strong> © 2026 El-Bethel Digital Learning Systems.</p>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar Toggle (multi-button support)
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.querySelector('.sidebar');
        const toggles = document.querySelectorAll('.sidebar-toggle');

        if (sidebar && toggles.length) {
            toggles.forEach(function (toggle) {
                toggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('show');
                });
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function (e) {
                if (window.innerWidth < 992) {
                    const clickedToggle = Array.from(toggles).some(t => t.contains(e.target));
                    if (!sidebar.contains(e.target) && !clickedToggle) {
                        sidebar.classList.remove('show');
                    }
                }
            });

            // Close sidebar on resize to desktop
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 992) {
                    sidebar?.classList.remove('show');
                }
            });
        }
    });

    // Copy Account Number
    document.getElementById('copy-account')?.addEventListener('click', function(){
        const text = document.getElementById('account-number')?.innerText || '';
        if(!text) return;
        navigator.clipboard?.writeText(text).then(function(){
            const btn = document.getElementById('copy-account');
            const prev = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Copied!';
            btn.classList.add('copied');
            setTimeout(function(){
                btn.innerHTML = prev;
                btn.classList.remove('copied');
            }, 2000);
        });
    });
</script>
</body>
</html>
