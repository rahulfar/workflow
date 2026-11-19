<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../helpers/functions.php';

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? sanitize($pageTitle) . ' - ' : '' ?><?= APP_NAME ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    
    <!-- Custom Light Theme CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/css/style.css') ?: time() ?>">
    
    <!-- SweetAlert2 JS in Head for Instant Availability -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
        const BASE_URL = '<?= BASE_URL ?>';
        const CSRF_TOKEN = '<?= getCsrfToken() ?>';

        window.resolveApiUrl = function(endpoint) {
            if (!endpoint) return '';
            let ep = endpoint.trim();
            if (!ep.startsWith('/')) ep = '/' + ep;
            const qIndex = ep.indexOf('?');
            const path = qIndex !== -1 ? ep.substring(0, qIndex) : ep;
            const query = qIndex !== -1 ? ep.substring(qIndex) : '';
            if (path.startsWith('/api/') && !path.endsWith('.php')) {
                ep = path + '.php' + query;
            }
            return (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + ep;
        };

        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function escapeJs(str) {
            if (str === null || str === undefined) return '';
            return String(str).replace(/\\/g, "\\\\").replace(/'/g, "\\'").replace(/"/g, '\\"');
        }

        // Global Universal Shift Clock-In / Clock-Out Handler
        window.handleClockInOut = function(action) {
            const isStart = (action === 'clock_in' || action === 'start_shift' || action === 'start');
            const apiAction = isStart ? 'clock_in' : 'clock_out';
            const csrf = (typeof CSRF_TOKEN !== 'undefined' && CSRF_TOKEN) ? CSRF_TOKEN : '';

            const executeAction = () => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: isStart ? 'Starting Shift...' : 'Ending Shift...',
                        text: 'Please wait while your attendance is recorded.',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                }

                const formData = new URLSearchParams();
                formData.append('action', apiAction);
                formData.append('csrf_token', csrf);

                fetch(resolveApiUrl('/api/attendance'), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData.toString()
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: isStart ? 'Shift Started!' : 'Shift Ended!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            alert(res.message);
                            location.reload();
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Shift Notice', res.message, 'warning');
                        } else {
                            alert(res.message);
                        }
                    }
                })
                .catch(err => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', 'Shift action failed: ' + err.message, 'error');
                    } else {
                        alert('Shift action failed: ' + err.message);
                    }
                });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: isStart ? 'Start Shift Now?' : 'End Shift Now?',
                    text: isStart ? 'Record current Indian Standard Time (IST) and start live shift timer.' : 'Record end time in IST and calculate total shift duration.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: isStart ? 'Yes, Start Shift' : 'Yes, End Shift',
                    confirmButtonColor: isStart ? '#10b981' : '#ef4444',
                    cancelButtonColor: '#64748b'
                }).then(result => {
                    if (result.isConfirmed) {
                        executeAction();
                    }
                });
            } else {
                if (confirm(isStart ? 'Start Shift Now?' : 'End Shift Now?')) {
                    executeAction();
                }
            }
        };
        window.handleWorkdayShift = window.handleClockInOut;

        function toggleMobileSidebar(e) {
            if (e) {
                if (e.preventDefault) e.preventDefault();
                if (e.stopPropagation) e.stopPropagation();
            }
            var sidebar = document.getElementById('appSidebar') || document.querySelector('.sidebar');
            var backdrop = document.getElementById('sidebarBackdrop');
            if (!sidebar) return;

            var isShow = sidebar.classList.contains('show') || sidebar.classList.contains('active');
            if (isShow) {
                sidebar.classList.remove('show');
                sidebar.classList.remove('active');
                if (backdrop) {
                    backdrop.classList.remove('show');
                    backdrop.classList.remove('active');
                }
                document.body.classList.remove('sidebar-open');
            } else {
                sidebar.classList.add('show');
                sidebar.classList.add('active');
                if (backdrop) {
                    backdrop.classList.add('show');
                    backdrop.classList.add('active');
                }
                document.body.classList.add('sidebar-open');
            }
        }

        function closeMobileSidebar(e) {
            if (e && e.stopPropagation) e.stopPropagation();
            var sidebar = document.getElementById('appSidebar') || document.querySelector('.sidebar');
            var backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) {
                sidebar.classList.remove('show');
                sidebar.classList.remove('active');
            }
            if (backdrop) {
                backdrop.classList.remove('show');
                backdrop.classList.remove('active');
            }
            document.body.classList.remove('sidebar-open');
        }
    </script>
</head>
<body>
<div class="app-wrapper">
