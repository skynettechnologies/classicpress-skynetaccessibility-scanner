<?php
/*
Plugin Name: SkynetAccessibility Scanner
Description: Scan, monitor, and identify website accessibility issues across WCAG 2.0, 2.1, 2.2, ADA, and EAA.
Version: 1.0
Author: Skynet Technologies USA LLC
Requires PHP: 8.0
Requires CP: 2.2
Author URI: https://www.skynettechnologies.com
Plugin URI: https://github.com/skynettechnologies/classicpress-skynetaccessibility-scanner
Text Domain: skynetaccessibilityscanner
Domain Path: /languages
 ------------------------------------------------------------------------------
 Scan, monitor, and identify website accessibility issues across WCAG 2.0, 2.1, 2.2, ADA, Section 508, EN 301 549, UK Equality Act, Australian DDA, and Canada ACA. Get simple issue highlights with recommended fixes.
 ------------------------------------------------------------------------------
*/

if (!defined('ABSPATH')) { exit; }

add_action(
    'admin_menu',
    'skynetaccessibility_scanner_admin_menu'
);

function skynetaccessibility_scanner_admin_menu() {

    add_menu_page(
        __( 'SkynetAccessibility Scanner', 'skynetaccessibilityscanner' ),
        __( 'SkynetAccessibility Scanner', 'skynetaccessibilityscanner' ),
        'manage_options',
        'skynetaccessibility-scanner',
        'skynetaccessibility_scanner_settings_page',
        'dashicons-search'
    );
}

add_action(
    'admin_enqueue_scripts',
    function () {

        wp_enqueue_style(
            'skynetaccessibilityscanner-style',
            plugin_dir_url( __FILE__ ) .
            'assets/css/scanning-and-monitoring-app.css',
            array(),
            '1.0'
        );

       wp_enqueue_script(
            'skynetaccessibilityscanner-admin',
            plugin_dir_url(__FILE__) . 'assets/js/skynetaccessibilityscanner.js',
            array('jquery'),
            '1.0',
            false
        );

        wp_localize_script(
            'skynetaccessibilityscanner-admin',
            'skynetScanner',
            array(
                'pluginUrl' => plugin_dir_url( __FILE__ ),
            )
        );
    }
);

function skynetaccessibility_scanner_settings_page() {

    $current_user = wp_get_current_user();

    $username = $current_user->user_login;
    $email    = $current_user->user_email;
  $consent = get_option( 'skynetaccessibilityscanner_consent', false );

if (
    isset( $_POST['skynet_give_consent'] ) &&
    isset( $_POST['_wpnonce'] ) &&
    wp_verify_nonce(
        sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ),
        'skynet_consent'
    )
) {
    update_option(
        'skynetaccessibilityscanner_consent',
        1
    );

    $consent = true;
}

if ( ! $consent ) {
    ?>
    <div class="notice notice-warning">
        <p>
            <?php esc_html_e(
                'By clicking "I Agree", you consent to this plugin sending your website URL, administrator username, and administrator email address to Skynet Technologies servers in order to provide accessibility scanning services.',
    'skynetaccessibilityscanner'
            ); ?>
        </p>

        <ul>
            <li><?php esc_html_e( 'Website URL', 'skynetaccessibilityscanner' ); ?></li>
            <li><?php esc_html_e( 'Administrator username', 'skynetaccessibilityscanner' ); ?></li>
            <li><?php esc_html_e( 'Administrator email address', 'skynetaccessibilityscanner' ); ?></li>
        </ul>

        <form method="post">
            <?php wp_nonce_field( 'skynet_consent' ); ?>

            <input
                type="submit"
                name="skynet_give_consent"
                class="button button-primary"
                value="<?php esc_attr_e( 'I Agree', 'skynetaccessibilityscanner' ); ?>"
            />
        </form>
    </div>
    <?php

    return;
}
    ?>
<div class="wrap">
<div id="container">
<div class="main">
<div id="page-loader" class="page-loader">
<div class="loader-box">
<div class="spinner"></div>
<p>Loading scan results..</p>
</div>
</div>

<!-- End User register -->
<!-- Section 1 -->
<div id="section1">
<div class="dialog-off-canvas-main-canvas" data-off-canvas-main-canvas>
<div id="page-wrapper">
<div id="page">
    <div id="main-wrapper" class="layout-main-wrapper clearfix">
        <div id="main" class="container">
            <div class="row row-offcanvas row-offcanvas-left clearfix">
                <main class="main-content col" id="content" role="main">
                    <section class="section">
                        <div id="main-content" tabindex="-1"></div>
                        <div id="block-skynettechnologies-content"
                                class="block block-system block-system-main-block">
                            <div class="content">
                                <article data-history-node-id="529"
                                            class="node node--type-page node--view-mode-full clearfix">
                                    <div class="node__content clearfix">
                                        <div class="scanning-monitoring-app">
                                            <div class="scans">
                                                <p class="title">My Scans</p>
                                                <!-- Status Section -->
                                                <section class="skynet-status" >
                                                    <div class="page-background"></div>

                                                    <div class="status-card">
                                                            <span class="status-title">Scan
                                                                Score</span>
                                                        <span
                                                                class="status-value status-progress"
                                                                id="scan-score"
                                                                style="cursor:pointer;">
                                                                Loading...
                                                            </span>
                                                    </div>

                                                    <div class="status-card">
                                                            <span class="status-title">Last
                                                                Scanned</span>
                                                        <span
                                                                class="status-value status-inactive"
                                                                id="last-scanned">
                                                                Loading...
                                                            </span>
                                                    </div>
                                                </section>
                                                <hr class="divider">
                                                <!-- Plan Section -->
                                                <section class="plan" >
                                                    <div class="page-background"></div>
                                                    <div class="plan-info">
                                                        <div class="plans-left">
                                                                <span class="plan-type free">
                                                                    <div class="icon-circle">
                                                                                                                                                                                                                                        <img src="<?php echo esc_url(
                                                                                                                                                                                                                                                plugin_dir_url( __FILE__ ) . 'assets/images/round.svg'
                                                                                                                                                                                                                                            ); ?>"
                                                                                                                                                                                                                                            alt=""
                                                                                                                                                                                                                                            height="20"
                                                                                                                                                                                                                                            width="20">
                                                                    </div>
                                                                    <span
                                                                            id="plan-name">Loading...</span>
                                                                    <span class="plan-desc">
                                                                        <ul>
                                                                            <li id="plan-pages"
                                                                                style="list-style: initial;">
                                                                            </li>
                                                                        </ul>
                                                                    </span>
                                                                    <span class="plan-badge"
                                                                            id="plan-badge"
                                                                            style="color: green; background: #D1FFD3;">
                                                                        Current Plan
                                                                    </span>
                                                                </span>
                                                        </div>
                                                        <div class="plans-right">
                                                                <span class="plan-renewal"
                                                                        id="plan-renewal">
                                                                    <!-- Renewal/Expiry date will be populated here --></span>
                                                            <button class="cancel-btn"
                                                                    id="cancel-subscription-btn">
                                                                Loading...
                                                            </button>
                                                        </div>
                                                    </div>
                                                </section>
                                                <!-- Pricing Section -->
                                                <section class="pricing" >
                                                    <div class="page-background"></div>
                                                    <div class="billing-toggle">
                                                            <span class="label active"
                                                                    id="monthly-label">Pay
                                                                Monthly</span>
                                                        <label class="switch">
                                                            <input type="checkbox"
                                                                    id="billing-toggle">
                                                            <span class="slider"></span>
                                                        </label>
                                                        <span class="label" id="annual-label">
                                                                Pay
                                                                Annually
                                                            </span>
                                                        <span class="save">Save 20%</span>
                                                    </div>
                                                    <!-- Monthly Plans -->
                                                    <div id="monthlyclass" class="monthlyclass">
                                                        <div class="pricing-tiers"
                                                                id="monthly-plans">
                                                            <!-- Plans will be dynamically inserted here -->
                                                        </div>
                                                    </div>
                                                    <!-- Annual Plans -->
                                                    <div id="annualclass" class="annualclass">
                                                        <div class="pricing-tiers"
                                                                id="annual-plans">
                                                            <!-- Plans will be dynamically inserted here -->
                                                        </div>
                                                    </div>
                                                    <p class="pricing-contact" >
                                                        Are you looking for a custom plan or
                                                        Enterprise plan? Contact us
                                                        <a href="mailto:hello@skynettechnologies.com">hello@skynettechnologies.com</a>
                                                    </p>
                                                </section>
                                                <hr class="divider">
                                                <!-- Help Section -->
                                                <section class="help">
                                                    <p class="help-text">
                                                        <strong>
                                                            Facing any issues with
                                                            SkynetAccessibility Scanner?
                                                        </strong>
                                                        Report a problem, we will get back to
                                                        you
                                                        very soon!
                                                    </p>
                                                    <a href="https://www.skynettechnologies.com/report-accessibility-problem"
                                                        class="help-btn" target="_blank" rel="noopener noreferrer">
                                                        Report a
                                                        problem
                                                    </a>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<!-- End Section 1 -->
<!-- Section 2 - Violation Report -->
<div id="section2" style="display:none;">
<div class="dialog-off-canvas-main-canvas" data-off-canvas-main-canvas>
<div id="page-wrapper">
<div id="page">
    <div id="main-wrapper" class="layout-main-wrapper clearfix">
        <div id="main" class="container">
            <div class="row row-offcanvas row-offcanvas-left clearfix">
                <main class="main-content col" id="content" role="main">
                    <section class="section">
                        <div id="main-content" tabindex="-1"></div>
                        <div id="block-skynettechnologies-content"
                                class="block block-system block-system-main-block">
                            <div class="content">
                                <article data-history-node-id="529"
                                            class="node node--type-page node--view-mode-full clearfix">
                                    <div class="node__content clearfix">
                                        <div class="scanning-monitoring-app">
                                            <div class="accessibility-report">
                                                <div class="report-date">
                                                    <label for="report-date">Report
                                                        Date:</label>
                                                    <span id="report-date-value" style="font-weight: 500;display:none;
                                                        margin-left: 5px;"></span>
                                                        <select id="report-date"></select>
                                                </div>
                                                <section class="top-section">
                                                    <div class="card score-card">
                                                        <h3>Accessibility Score</h3>
                                                        <div class="accessibility-score">
                                                            <div class="score-value"
                                                                    id="accessibility-score">0%
                                                            </div>
                                                            <span
                                                                    class="status-text not-compliant"
                                                                    id="compliance-status">
                                                                    Not
                                                                    Compliant
                                                                </span>
                                                        </div>
                                                        <div class="progress-bar">
                                                            <div class="progress-fill"
                                                                    id="accessibility-progress"
                                                                    style="width: 0%;"></div>
                                                        </div>
                                                        <p class="note">
                                                            Automated Accessibility score has
                                                            limitations.
                                                            We recommend Manual Accessibility
                                                            Audit.
                                                        </p>
                                                    </div>
                                                    <!-- Web Pages Scanned -->
                                                    <div class="card pages-card">
                                                        <h3>Web Pages Scanned</h3>
                                                        <div class="pages-value"
                                                                id="pages-scanned">
                                                            0
                                                        </div>
                                                        <div class="progress-bar">
                                                            <div class="progress-fill"
                                                                    id="pages-progress"
                                                                    style="width: 0%;"></div>
                                                        </div>
                                                        <p class="note" id="pages-note">
                                                            0 pages scanned out of 0
                                                        </p>
                                                    </div>
                                                </section>
                                                <!-- WCAG Section -->
                                                <section class="wcag-section">
                                                    <div class="wcag-header">
                                                        <h3>WCAG 2.1/2.2</h3>
                                                        <button id="view-all-violations"
                                                                class="view-btn">
                                                            View all Violations
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                    width="6" height="10"
                                                                    viewBox="0 0 6 10" fill="none">
                                                                <path
                                                                        d="M6 5.00002C6 5.17924 5.92797 5.35843 5.78422 5.49507L1.25832 9.79486C0.970413 10.0684 0.503627 10.0684 0.21584 9.79486C-0.0719468 9.52145 -0.0719468 9.07807 0.21584 8.80452L4.22061 5.00002L0.21598 1.19549C-0.0718073 0.921968 -0.0718073 0.478632 0.21598 0.205242C0.503767 -0.0684128 0.970553 -0.0684128 1.25846 0.205242L5.78436 4.50496C5.92814 4.64166 6 4.82086 6 5.00002Z"
                                                                        fill="white" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <!-- Checks Grid -->
                                                    <div class="checks-grid">
                                                        <div class="check-card failed">
                                                                <span class="check-value"
                                                                        id="failed-checks">0</span>
                                                            <span class="check-label">
                                                                    Failed
                                                                    Checks
                                                                </span>
                                                        </div>
                                                        <div class="check-card passed">
                                                                <span class="check-value"
                                                                        id="passed-checks">0</span>
                                                            <span class="check-label">
                                                                    Passed
                                                                    Checks
                                                                </span>
                                                        </div>
                                                        <div class="check-card na">
                                                                <span class="check-value"
                                                                        id="na-checks">0</span>
                                                            <span class="check-label">
                                                                    N/A
                                                                    Checks
                                                                </span>
                                                        </div>
                                                    </div>
                                                    <hr class="divider">
                                                    <!-- Violations Grid -->
                                                    <div class="violations-grid" style="color: black;">
                                                        <div class="violation-card">
                                                                <span class="violation-title">
                                                                    Level
                                                                    A
                                                                </span>
                                                            <span class="violation-count">
                                                                    <span
                                                                            id="level-a-violations">0</span>
                                                                    violations
                                                                </span>
                                                        </div>
                                                        <div class="violation-card">
                                                                <span class="violation-title">
                                                                    Level
                                                                    AA
                                                                </span>
                                                            <span class="violation-count">
                                                                    <span
                                                                            id="level-aa-violations">0</span>
                                                                    violations
                                                                </span>
                                                        </div>
                                                        <div class="violation-card">
                                                                <span class="violation-title">
                                                                    Level
                                                                    AAA
                                                                </span>
                                                            <span class="violation-count">
                                                                    <span
                                                                            id="level-aaa-violations">0</span>
                                                                    violations
                                                                </span>
                                                        </div>
                                                    </div>
                                                </section>
                                                <div>
                                                    <button class="back-btn"
                                                            id="back-btn">Back</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<!-- End Section 2 -->
</div>
</div>
<script>
 
    function showLoader() {
        document.getElementById("page-loader")?.classList.remove("hidden");
    }
    function hideLoader() {
        document.getElementById("page-loader")?.classList.add("hidden");
    }
 
            function pluginImage(fileName) {
            return skynetScanner.pluginUrl + 'assets/images/' + fileName;
        }

    console.log(pluginImage('not-shared.svg'));
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize the application
        function initializeApp() {
            showLoader(); //
            var url = window.location.hostname; //window.location.hostname;

            console.log("No URL parameter provided");
            if (!url) {
                console.error("No URL parameter provided");
                hideLoader();
                return;
            }

            // Register domain
            checkAndRegisterDomain(url)
                .then(function () {
                    // Fetch scan details
                    return fetchScanDetails(url);
                })
               
                .then(function () {
                    // Fetch scan count
                    return fetchScanCount(url);
                })
                .then(function () {
                    // Fetch packages
                    return fetchPackages(url);
                })
                .then(function () {
                    renderUI();
                    console.log("Interval:", appData.subscrInterval);
                    // Set toggle correctly AFTER data is ready
                    if (appData.subscrInterval === "Y") {
                        showAnnual();
                    } else {
                        showMonthly();
                    }

                    hideLoader();
                })
                .catch(function (error) {
                    console.error("Error initializing app:", error);
                    hideLoader(); //  ALWAYS hide on error
                });
        }

        // Global data object to store API responses
        var appData = {
            websiteId: "",
            packageId: "",
            subscrInterval: "",
            paypalSubscrId: "",
            dashboardLink: "",
            violationLink: "",
            endDate: "",
            cancelDate: "",
            isExpired: false,
            plans: [],
        };
        var skynetScannerUser = {
                    username: "<?php echo esc_js( $current_user->user_login ); ?>",
                    email: "<?php echo esc_js( $current_user->user_email ); ?>"
                };
    console.log(skynetScannerUser.email);
  
        // Register domain
        function registerDomain(url) {
            console.log("registerDomain called with:", url);
            var domain = url.replace(/^www\./, "");
            var formData = new URLSearchParams({
                website: btoa(url),
                platform: "ClassicPress",
                is_trial_period: "1",
                name: skynetScannerUser.username,
                email: skynetScannerUser.email,
                company_name: domain,
                package_type: "25-pages",
            });

            return fetch(
                "https://skynetaccessibilityscan.com/api/register-domain-platform",
                {
                    method: "POST",
                    body: formData,
                },
            ).then(function (response) {
                return response.json();
            });
        }

        function checkAndRegisterDomain(url) {
            var storageKey = "skynet_domain_registered_" + url;

            // Skip API if already registered
            if (localStorage.getItem(storageKey)) {
                console.log("Domain already registered. Skipping API.");
                return Promise.resolve();
            }

            // First time only
            return registerDomain(url)
                .then(function (response) {
                    console.log("Domain registered successfully");

                    // Save flag in browser
                    localStorage.setItem(storageKey, "1");

                    return response;
                })
                .catch(function (err) {
                    console.error("Register domain failed:", err);
                    throw err;
                });
        }

        // Fetch scan details
        function fetchScanDetails(url) {
            var formData = new URLSearchParams({ website: btoa(url) });

            return fetch(
                "https://skynetaccessibilityscan.com/api/get-scan-detail",
                {
                    method: "POST",
                    body: formData,
                },
            )
                .then(function (response) {
                    return response.json();
                })
                .then(function (result) {
                    var data = (result.data && result.data[0]) || {};

                    // Existing mappings
                    appData.domain = data.domain || "";
                    appData.favIcon = data.fav_icon || "";
                    appData.urlScanStatus = data.url_scan_status || 0;
                    appData.scanStatus = data.scan_status || 0;
                    appData.totalSelectedPages = data.total_selected_pages || 0;
                    appData.totalLastScanPages = data.total_last_scan_pages || 0;
                    appData.totalPages = data.total_pages || 0;
                    appData.lastUrlScan = data.last_url_scan || 0;
                    appData.totalScanPages = data.total_scan_pages || 0;
                    appData.lastScan = data.last_scan || null;
                    appData.nextScanDate = data.next_scan_date || null;
                    appData.successPercentage = data.success_percentage || "0";
                    appData.scanViolationTotal = data.scan_violation_total || "0";
                    appData.totalViolations = data.total_violations || 0;
                    appData.packageName = data.name || "";
                    appData.packageId = data.package_id || "";
                    appData.pageViews = data.page_views || "";
                    appData.packagePrice = data.package_price || "";
                    appData.subscrInterval = data.subscr_interval || "";
                    appData.endDate = data.end_date || "";
                    appData.cancelDate = data.cancel_date || "";
                    appData.websiteId = data.website_id || "";
                    appData.paypalSubscrId = data.paypal_subscr_id || "";
                    appData.isTrialPeriod = data.is_trial_period || "";
                    appData.dashboardLink = result.dashboard_link || "";
                    appData.totalFailSum = data.total_fail_sum || "";
                    appData.isExpired = data.is_expired || "";

                    // NEW: get user info
                    appData.userId = result.userData?.id || "";
                    appData.userEmail = result.userData?.email || "";

                    // console.log("User ID:", appData.userId);
                    // console.log("User Email:", appData.userEmail);
                });
        }

        // Fetch scan count
        function fetchScanCount(url) {
            var formData = new URLSearchParams({
                website: btoa(url),
            });

            return fetch(
                "https://skynetaccessibilityscan.com/api/get-scan-count",
                {
                    method: "POST",
                    body: formData,
                },
            )
                .then(function (response) {
                    return response.json();
                })
                .then(function (result) {
                    var widgetPurchased = result.widget_purchased || false;
                    appData.scanDetails = {
                        withRemediation: widgetPurchased
                            ? result.scan_details.with_remediation || {}
                            : result.scan_details.without_remediation || {},
                    };
                });
        }

        // Fetch packages
        function fetchPackages(url) {
            return fetch(
                "https://skynetaccessibilityscan.com/api/packages-list",
                {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ website: btoa(url) }),
                },
            )
                .then(function (response) {
                    return response.json();
                })
                .then(function (decoded) {
                    var packageData = {};
                    if (
                        decoded.current_active_package &&
                        decoded.current_active_package[appData.websiteId]
                    ) {
                        packageData = decoded.current_active_package[appData.websiteId];
                    } else if (
                        decoded.expired_package_detail &&
                        decoded.expired_package_detail[appData.websiteId]
                    ) {
                        packageData = decoded.expired_package_detail[appData.websiteId];
                    }

                    appData.finalPrice = packageData.final_price || 0;
                    appData.packageId = packageData.package_id || appData.packageId;
                    appData.subscrInterval =
                        packageData.subscr_interval || appData.subscrInterval;

                    // Generate violation link
                    return fetch(
                        "https://skynetaccessibilityscan.com/api/generate-plan-action-link",
                        {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: new URLSearchParams({
                                website_id: appData.websiteId,
                                current_package_id: appData.packageId,
                                action: "violation",
                            }),
                        },
                    )
                        .then(function (response) {
                            return response.json();
                        })
                        .then(function (violationData) {
                            appData.violationLink = violationData.action_link || "#";
                            // Process plans
                            appData.plans = [];
                            var today = new Date();

                            var plans = decoded.Data || [];
                            for (var i = 0; i < plans.length; i++) {
                                var plan = plans[i];
                                if (!plan.platforms || plan.platforms.toLowerCase() !== "scanner")
                                    continue;

                                var planId = plan.id;
                                if (!planId) continue;

                                var action = "upgrade";
                                if (planId == appData.packageId) {
                                    plan.interval = appData.subscrInterval;
                                    if (appData.endDate) {
                                        var endDate = new Date(appData.endDate);
                                        action = today <= endDate ? "cancel" : "upgrade";
                                    } else {
                                        action = "cancel";
                                    }
                                }
                                plan.action = action;
                                appData.plans.push(plan);
                            }
                        });
                });
        }

        // Render UI with fetched data
        function renderUI() {
            renderScanScore();
            renderLastScanned();
            renderPlanInfo();
            renderPlans();
            renderViolationReport();
        }

        // Render scan score
        function renderScanScore() {
            var scanScoreElement = document.getElementById("scan-score");

            if (appData.isExpired == 1) {
                scanScoreElement.innerHTML =
                    '<span class="status-value status-inactive">N/A</span>';
            } else if (appData.scanViolationTotal == 0) {
                scanScoreElement.innerHTML =
                    '<span class="status-value status-inactive">N/A</span>';
            } else {
                scanScoreElement.innerHTML =
                    appData.successPercentage +
                    "%" +
                    '<div class="progress-bar">' +
                    '<div class="progress-fill" style="width: ' +
                    appData.successPercentage +
                    '%;"></div>' +
                    "</div>" +
                    '<div class="violations">' +
                    'Violations: <span class="status-value" style="font-size: 15px;">' +
                    appData.totalFailSum +
                    "</span>" +
                    "</div>";
            }
        }
        // Render last scanned
        function renderLastScanned() {
            var lastScannedElement = document.getElementById("last-scanned");
            var imgNotStarted = pluginImage("not-shared.svg");
            if (appData.urlScanStatus < 2 || appData.scanStatus == 0) {
                lastScannedElement.innerHTML =
                    '<img src="' +
                    imgNotStarted +
                    '" alt="" title="Not Started"> Not Started';
                lastScannedElement.className = "status-value status-inactive";
            } else if (appData.scanStatus == 1 || appData.scanStatus == 2) {
                lastScannedElement.innerHTML =
                    '<img src="' +
                    imgNotStarted +
                    '" alt="" title="Scanning in process"> Scanning<br>' +
                    appData.totalScanPages +
                    "/" +
                    appData.totalSelectedPages;
                lastScannedElement.className = "status-value status-inactive";
            } else if (appData.scanStatus == 3) {
                var formattedDate = "";
                if (appData.lastScan) {
                    var date = new Date(appData.lastScan);
                    formattedDate = date.toLocaleDateString("en-US", {
                        year: "numeric",
                        month: "long",
                        day: "numeric",
                    });
                }

                lastScannedElement.innerHTML =
                    appData.totalScanPages + " Pages<br>" + formattedDate;
                lastScannedElement.className = "status-value status-active";
            }
        }

        // Render plan info
        function renderPlanInfo() {
            var planNameElement = document.getElementById("plan-name");
            var planPagesElement = document.getElementById("plan-pages");
            var planBadgeElement = document.getElementById("plan-badge");
            var planRenewalElement = document.getElementById("plan-renewal");
            var cancelBtnElement = document.getElementById("cancel-subscription-btn");

            // Plan badge
            var today = new Date().toISOString().split("T")[0];
            var cancelDate = appData.cancelDate
                ? appData.cancelDate.substring(0, 10)
                : null;
            var endDateStr = appData.endDate ? appData.endDate.substring(0, 10) : null;
            // TRUE expired condition
            var isExpired =
                appData.isExpired == 1 || (endDateStr && endDateStr < today);
            // TRUE cancelled condition (but not expired yet)
            var isCancelled = cancelDate && cancelDate <= today && !isExpired;

            // Plan name
            if (isExpired) {
                planNameElement.innerHTML =
                    '<span style="color: #9F0000; font-weight: 700;">Your Plan has Expired</span>';
            } else {
                planNameElement.textContent =
                    appData.isTrialPeriod == 1
                        ? "Free Plan"
                        : appData.packageName + " Plan";
            }

            // Plan pages
            if (!isExpired) {
                planPagesElement.textContent =
                    "Scan up to " + appData.pageViews + " Pages";
            } else {
                planPagesElement.textContent = "";
            }

            if (isExpired) {
                planBadgeElement.style.display = "none";
            } else if (isCancelled) {
                planBadgeElement.style.display = "inline-block";
                planBadgeElement.style.color = "#940000";
                planBadgeElement.style.background = "#ffd1d1";
                planBadgeElement.textContent = "Cancelled Plan";
            } else {
                planBadgeElement.style.display = "inline-block";
                planBadgeElement.style.color = "green";
                planBadgeElement.style.background = "#D1FFD3";
                planBadgeElement.textContent = "Current Plan";
            }

            // Plan renewal
            var isTrial = appData.isTrialPeriod == 1;
            // Plan renewal
            if (!isExpired && appData.endDate) {
                var endDate = new Date(appData.endDate);
                var formattedDate = endDate.toLocaleDateString("en-US", {
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                });

                // Trial OR Cancelled → Expires on
                if (isTrial || isCancelled) {
                    planRenewalElement.innerHTML =
                        '<span style="color:#9F0000;">Expires on:</span> <strong>' +
                        formattedDate +
                        "</strong>";
                }
                // Active paid plan
                else {
                    planRenewalElement.innerHTML =
                        "Renews on: <strong>" + formattedDate + "</strong>";
                }
            } else if (isExpired && appData.endDate) {
                var endDate = new Date(appData.endDate);
                var formattedDate = endDate.toLocaleDateString("en-US", {
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                });
                planRenewalElement.innerHTML =
                    "Expired on: <strong>" + formattedDate + "</strong>";
            }

            // Cancel/Renew button
            // var showRenew = cancelDate <= today || isExpired;
            var showRenew = isExpired || isCancelled || isTrial;

            cancelBtnElement.setAttribute("data-action", "cancel");

            if (isTrial) {
                cancelBtnElement.style.backgroundColor = "#420083";
                cancelBtnElement.style.color = "#fff";
                cancelBtnElement.textContent = "Activate Now";
                cancelBtnElement.setAttribute("data-action", "upgrade");
            } else if (showRenew) {
                cancelBtnElement.style.backgroundColor = "#420083";
                cancelBtnElement.style.color = "#fff";
                cancelBtnElement.textContent = "Renew Plan";
                cancelBtnElement.setAttribute("data-action", "upgrade");
            } else {
                cancelBtnElement.style.backgroundColor = "";
                cancelBtnElement.style.color = "";
                cancelBtnElement.textContent = "Cancel Subscription";
                cancelBtnElement.setAttribute("data-action", "cancel");
            }
        }
        // Render plans
        function renderPlans() {
            var monthlyPlansContainer = document.getElementById("monthly-plans");
            var annualPlansContainer = document.getElementById("annual-plans");

            var icons = ["diamond.svg", "pentagon.svg", "hexagon.svg", "hexagon.svg"];

            monthlyPlansContainer.innerHTML = "";
            annualPlansContainer.innerHTML = "";

            for (var i = 0; i < appData.plans.length; i++) {
                var plan = appData.plans[i];
                var icon = icons[i] || "default.svg";
                var iconPath = pluginImage(icon);
                var today = new Date().toISOString().split("T")[0];
                var cancelDate = appData.cancelDate
                    ? appData.cancelDate.substring(0, 10)
                    : null;
                var endDateStr = appData.endDate
                    ? appData.endDate.substring(0, 10)
                    : null;
                var isExpired = appData.endDate && new Date(appData.endDate) < new Date();
                var isTrial = appData.isTrialPeriod == 1;
                var isCancelled = cancelDate && cancelDate <= today && !isExpired;
                var isCurrentMonthly =
                    appData.packageId == plan.id && plan.interval == "M" && !isTrial;

                // FORCE upgrade if trial
                var isActiveCurrentMonthly =
                    isCurrentMonthly && !isCancelled && !isExpired;
                var monthlyAction =
                    isExpired || isTrial || isCancelled
                        ? "upgrade"
                        : isActiveCurrentMonthly
                        ? "cancel"
                        : "upgrade";

                var monthlyButtonClass =
                    "upgrade-btn" + (isActiveCurrentMonthly ? " cancel-btnn" : "");

                var monthlyButtonText =
                    isExpired || isTrial || isCancelled
                        ? "Upgrade"
                        : isActiveCurrentMonthly
                        ? "Cancel"
                        : "Upgrade";
                var monthlyCard =
                    '<div class="tier" data-plan-id="' +
                    plan.id +
                    '">' +
                    '<div class="pricing-top">' +
                    '<div class="pricing-header">' +
                    '<div class="icon-circle">' +
                    '<img src="' +
                    iconPath +
                    '" alt="" height="20" width="20">' +
                    "</div>" +
                    "</div>" +
                    '<div class="pricing-info">' +
                    '<h3 class="tier-title">' +
                    plan.name +
                    "</h3>" +
                    '<p class="tier-pages">' +
                    plan.page_views +
                    " Pages</p>" +
                    "</div>" +
                    "</div>" +
                    '<hr class="pricing-divider">' +
                    '<div class="pricing-body">' +
                    '<p class="old-price">$' +
                    plan.strick_monthly_price +
                    "</p>" +
                    '<p class="new-price">$' +
                    plan.monthly_price +
                    '<span class="per-year">/Monthly</span></p>' +
                    "</div>" +
                    '<button type="button" class="' +
                    monthlyButtonClass +
                    '" data-action="' +
                    monthlyAction +
                    '" data-planid="' +
                    plan.id +
                    '" data-interval="M" >' +
                    monthlyButtonText +
                    "</button>" +
                    "</div>";

                var isCurrentAnnual =
                    appData.packageId == plan.id && plan.interval == "Y";
                var isActiveCurrentAnnual = isCurrentAnnual && !isCancelled && !isExpired;
                var annualAction =
                    isExpired || isTrial || isCancelled
                        ? "upgrade"
                        : isActiveCurrentAnnual
                        ? "cancel"
                        : "upgrade";

                var annualButtonClass = isActiveCurrentAnnual
                    ? "upgrade-btn cancel-btnn"
                    : "upgrade-btn";
                var annualCard =
                    '<div class="tier" data-plan-id="' +
                    plan.id +
                    '">' +
                    '<div class="pricing-top">' +
                    '<div class="pricing-header">' +
                    '<div class="icon-circle">' +
                    '<img src="' +
                    iconPath +
                    '" alt="" height="20" width="20">' +
                    "</div>" +
                    "</div>" +
                    '<div class="pricing-info">' +
                    '<h3 class="tier-title">' +
                    plan.name +
                    "</h3>" +
                    '<p class="tier-pages">' +
                    plan.page_views +
                    " Pages</p>" +
                    "</div>" +
                    "</div>" +
                    '<hr class="pricing-divider">' +
                    '<div class="pricing-body">' +
                    '<p class="old-price">$' +
                    plan.strick_price +
                    "</p>" +
                    '<p class="new-price">$' +
                    plan.price +
                    '<span class="per-year">/Year</span></p>' +
                    "</div>" +
                    '<button type="button" class="' +
                    annualButtonClass +
                    '" data-action="' +
                    annualAction +
                    '" data-planid="' +
                    plan.id +
                    '" data-interval="Y" >' +
                    (isExpired || isTrial || isCancelled
                        ? "Upgrade"
                        : isActiveCurrentAnnual
                            ? "Cancel"
                            : "Upgrade") +
                    "</button>" +
                    "</div>";
                monthlyPlansContainer.innerHTML += monthlyCard;
                annualPlansContainer.innerHTML += annualCard;
            }
        }

        // Render violation report
        function renderViolationReport() {
            // Report date
            if (appData.lastScan) {
                var reportDate = new Date(appData.lastScan);
                var formattedDate = reportDate.toLocaleDateString("en-US", {
                    day: "numeric",
                    month: "long",
                    year: "numeric",
                });
                document.getElementById("report-date-value").textContent = formattedDate;
                var reportDate = document.getElementById("report-date-value").textContent.trim();

                var select = document.getElementById("report-date");

                select.innerHTML =
                    '<option selected value="' + reportDate + '">' +
                    reportDate +
                    '</option>';
            }

            // Accessibility score
            document.getElementById("accessibility-score").textContent =
                appData.successPercentage + "%";
            document.getElementById("accessibility-progress").style.width =
                appData.successPercentage + "%";

            // Compliance status
            var percentage = parseInt(appData.successPercentage);
            var statusClass = "";
            var statusText = "";

            if (percentage >= 0 && percentage < 50) {
                statusClass = "not-compliant";
                statusText = "Not Compliant";
            } else if (percentage >= 50 && percentage < 85) {
                statusClass = "semi-compliant";
                statusText = "Semi Compliant";
            } else if (percentage >= 85) {
                statusClass = "compliant";
                statusText = "Compliant";
            }

            var complianceElement = document.getElementById("compliance-status");
            complianceElement.className = "status-text " + statusClass;
            complianceElement.textContent = statusText;

            // Pages scanned
            document.getElementById("pages-scanned").textContent =
                appData.totalScanPages;
            var pagesProgress =
                appData.totalPages > 0
                    ? (appData.totalScanPages / appData.totalPages) * 100
                    : 0;
            document.getElementById("pages-progress").style.width = pagesProgress + "%";
            document.getElementById("pages-note").textContent =
                appData.totalScanPages + " pages scanned out of " + appData.totalPages;

            // WCAG checks
            var scanDetails =
                appData.scanDetails && appData.scanDetails.withRemediation
                    ? appData.scanDetails.withRemediation
                    : {};
            document.getElementById("failed-checks").textContent =
                scanDetails.total_fail || 0;
            document.getElementById("passed-checks").textContent =
                scanDetails.total_success || 0;
            document.getElementById("na-checks").textContent =
                scanDetails.severity_counts && scanDetails.severity_counts.Not_Applicable
                    ? scanDetails.severity_counts.Not_Applicable
                    : 0;

            // Violation levels
            document.getElementById("level-a-violations").textContent =
                scanDetails.criteria_counts && scanDetails.criteria_counts.A
                    ? scanDetails.criteria_counts.A
                    : 0;
            document.getElementById("level-aa-violations").textContent =
                scanDetails.criteria_counts && scanDetails.criteria_counts.AA
                    ? scanDetails.criteria_counts.AA
                    : 0;
            document.getElementById("level-aaa-violations").textContent =
                scanDetails.criteria_counts && scanDetails.criteria_counts.AAA
                    ? scanDetails.criteria_counts.AAA
                    : 0;
        }

        // Handle upgrade/cancel button clicks
        document.addEventListener("click", function (e) {
            if (e.target.classList.contains("upgrade-btn")) {
                e.preventDefault();
                e.stopPropagation();
                console.log("update clickk", e.target.dataset);

                var planId = e.target.getAttribute("data-planid");
                var actionType = e.target.getAttribute("data-action");
                var interval = e.target.getAttribute("data-interval");
                var paypalSubscrId = appData.paypalSubscrId;

                if (!paypalSubscrId || paypalSubscrId === "null") {
                    actionType = "upgrade";
                }

                var payload = {
                    website_id: appData.websiteId,
                    current_package_id: appData.packageId,
                    action: actionType,
                };

                if (actionType === "upgrade") {
                    payload.package_id = planId;
                    payload.interval = interval;
                }
                getOpenLink(payload);
            }
        });

        var cancel_subscription_btn = document.getElementById(
            "cancel-subscription-btn",
        );
        if (cancel_subscription_btn) {
            cancel_subscription_btn.addEventListener("click", function (e) {
                e.preventDefault();
                var newTab = window.open("", "_blank");
                var actionType = cancel_subscription_btn.getAttribute("data-action");
                var payload = {
                    website_id: appData.websiteId,
                    current_package_id: appData.packageId,
                    action: actionType,
                };
                if (actionType === "upgrade") {
                    payload.package_id = appData.packageId;
                    payload.interval = appData.subscrInterval || "M";
                }
                console.log("FINAL PAYLOAD:", payload);
                getOpenLink(payload, newTab); // pass tab
            });
        }

        function getOpenLink(payload, existingWindow = null) {
            var newWindow = existingWindow || window.open("", "_blank");

            console.log("SENDING:", payload);

            fetch(
                "https://skynetaccessibilityscan.com/api/generate-plan-action-link",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: new URLSearchParams(payload),
                },
            )
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error("HTTP " + response.status);
                    }

                    return response.json();
                })
                .then(function (data) {
                    console.log("API RESPONSE:", data);

                    var redirectUrl = data.action_link || data.url;

                    if (redirectUrl) {
                        if (newWindow) {
                            newWindow.location.href = redirectUrl;
                        } else {
                            window.open(redirectUrl, "_blank");
                        }
                    } else {
                        console.error("No redirect URL returned", data);

                        if (newWindow && !newWindow.closed) {
                            newWindow.close();
                        }
                    }
                })
                .catch(function (err) {
                    console.error("API Error:", err);

                    if (newWindow && !newWindow.closed) {
                        newWindow.close();
                    }
                });
        }
        // Handle violation link
        var view_all_violations = document.getElementById("view-all-violations");
        if (view_all_violations) {
            view_all_violations.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();

                if (appData.violationLink) {
                    window.open(appData.violationLink, "_blank");
                }
            });
        }

        // Toggle between monthly and annual plans
        var toggle = document.getElementById("billing-toggle");
        var monthlyLabel = document.getElementById("monthly-label");
        var annualLabel = document.getElementById("annual-label");
        var monthlyclass = document.getElementById("monthlyclass");
        var annualclass = document.getElementById("annualclass");

        function showMonthly() {
            toggle.checked = false;
            monthlyLabel.classList.add("active");
            annualLabel.classList.remove("active");
            monthlyclass.style.display = "block";
            annualclass.style.display = "none";
        }

        function showAnnual() {
            toggle.checked = true;
            monthlyLabel.classList.remove("active");
            annualLabel.classList.add("active");
            monthlyclass.style.display = "none";
            annualclass.style.display = "block";
        }

        toggle.addEventListener("change", function () {
            if (toggle.checked) {
                showAnnual();
            } else {
                showMonthly();
            }
        });

        // Initialize the application
        initializeApp();
        // Show violation details

        var show_scan_details = document.getElementById("scan-score");
        show_scan_details.addEventListener("click", function () {
            document.getElementById("section1").style.display = "none";
            document.getElementById("section2").style.display = "block";
        });

        // Go back to main view
        var go_back = document.getElementById("back-btn");
        go_back.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById("section2").style.display = "none";
            document.getElementById("section1").style.display = "block";
        });
    });
</script>
    </div>
    <?php
}


