<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Member</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .membership-card {
            width: 380px;
            height: 240px;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6, #1e40af);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .card-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.07;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .chip {
            width: 40px;
            height: 30px;
            background: linear-gradient(135deg, #ffd700, #ffb700);
            border-radius: 6px;
            position: relative;
        }

        .chip::before {
            content: '';
            position: absolute;
            top: 5px;
            left: 5px;
            width: 30px;
            height: 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .referral-code {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 8px 12px;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            letter-spacing: 2px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .referral-code::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .referral-code:hover::before {
            left: 100%;
        }

        .gift-icon {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                background: white;
                margin: 0;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="flex flex-col items-center space-y-4">

        <!-- Card Wrapper -->
        <div id="card-container" class="membership-card relative">
            <div class="card-pattern"></div>

            <div class="relative z-10 h-full p-5 flex flex-col justify-between text-white">
                <!-- Header -->
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-lg font-bold tracking-wide">PREMIUM MEMBER</div>
                        <div class="text-blue-200 text-xs mt-1">ID: {{ $konsumen->id_konsumen }}</div>
                    </div>
                    <div class="chip"></div>
                </div>

                <!-- Member Info -->
                <div class="flex-1 flex flex-col justify-center">
                    <div class="text-xl font-semibold">{{ $konsumen->nama_konsumen }}</div>

                    <!-- Details -->
                    <div class="text-sm mt-2 space-y-0.5">
                        <div class="flex items-center">
                            <i class="fas fa-car text-blue-200 w-5"></i>
                            <span class="ml-1">{{ $konsumen->no_kendaraan ?: '-' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-blue-200 w-5"></i>
                            <span class="ml-1">{{ $konsumen->no_telp }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-star text-blue-200 w-5"></i>
                            <span class="ml-1">Points: {{ $konsumen->jumlah_point }}</span>
                        </div>
                    </div>
                </div>

                <!-- Referral Code Section -->
                @if ($konsumen->kode_referral)
                    <div class="mt-3">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center">
                                <i class="fas fa-gift text-yellow-300 gift-icon mr-1"></i>
                                <span class="text-xs font-medium text-blue-200">Kode Referral</span>
                            </div>
                            <span class="text-xs text-blue-200">Bagikan & Dapatkan Poin!</span>
                        </div>
                        <div class="referral-code">
                            <div class="text-lg font-bold">{{ $konsumen->kode_referral }}</div>
                        </div>
                    </div>
                @endif

                <!-- Address (shortened for space) -->
                <div class="text-xs text-blue-200 mt-2">
                    <i class="fas fa-map-marker-alt w-4"></i>
                    {{ Str::limit($konsumen->alamat, 40) }}
                </div>
            </div>
        </div>

        <!-- Info Referral -->
        @if ($konsumen->kode_referral)
            <div class="bg-white rounded-lg shadow-md p-4 max-w-md no-print">
                <div class="flex items-center mb-2">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    <span class="font-semibold text-gray-800">Cara Menggunakan Kode Referral</span>
                </div>
                <div class="text-sm text-gray-600 space-y-1">
                    <p>• Bagikan kode <strong>{{ $konsumen->kode_referral }}</strong> ke teman</p>
                    <p>• Teman mendapat diskon <strong>Rp 5.000</strong> saat service</p>
                    <p>• Anda mendapat <strong>+1 poin</strong> setiap kode digunakan</p>
                    <p>• Setiap orang hanya bisa pakai 1x per kode</p>
                </div>
            </div>
        @endif

        <!-- Buttons -->
        <div class="flex flex-wrap justify-center gap-4 no-print">
            <a href="{{ route('konsumen.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded shadow flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <button onclick="window.print()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow flex items-center gap-2">
                <i class="fas fa-print"></i> Cetak
            </button>

            <button onclick="downloadAsImage()"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow flex items-center gap-2">
                <i class="fas fa-download"></i> Download Gambar
            </button>

            @if ($konsumen->kode_referral)
                <button onclick="copyReferralCode()"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded shadow flex items-center gap-2">
                    <i class="fas fa-copy"></i> Copy Kode
                </button>

                <button onclick="shareReferral()"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded shadow flex items-center gap-2">
                    <i class="fas fa-share"></i> Share
                </button>
            @endif
        </div>

    </div>

    <script>
        function downloadAsImage() {
            const original = document.getElementById('card-container');
            const clone = original.cloneNode(true);
            clone.style.position = 'absolute';
            clone.style.left = '-9999px';
            clone.style.top = '0';
            document.body.appendChild(clone);

            html2canvas(clone, {
                scale: 3,
                useCORS: true,
                backgroundColor: null
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'kartu-member-{{ $konsumen->nama_konsumen }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
                document.body.removeChild(clone);
            });
        }

        @if ($konsumen->kode_referral)
            function copyReferralCode() {
                const code = '{{ $konsumen->kode_referral }}';
                const message = `Halo! Pakai kode referral ${code} untuk diskon Rp 5.000 di bengkel kami! 🎁`;

                if (navigator.clipboard) {
                    navigator.clipboard.writeText(message).then(function() {
                        showNotification('Kode referral berhasil disalin!', 'success');
                    }).catch(function(err) {
                        showNotification('Gagal menyalin kode', 'error');
                    });
                } else {
                    // Fallback untuk browser lama
                    const textArea = document.createElement('textarea');
                    textArea.value = message;
                    document.body.appendChild(textArea);
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        showNotification('Kode referral berhasil disalin!', 'success');
                    } catch (err) {
                        showNotification('Gagal menyalin kode', 'error');
                    }
                    document.body.removeChild(textArea);
                }
            }

            function shareReferral() {
                const code = '{{ $konsumen->kode_referral }}';
                const message = `Halo! Pakai kode referral ${code} untuk diskon Rp 5.000 di bengkel kami! 🎁`;

                if (navigator.share) {
                    navigator.share({
                        title: 'Kode Referral Bengkel',
                        text: message,
                        url: window.location.origin
                    }).catch(console.error);
                } else {
                    // Fallback: WhatsApp
                    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
                    window.open(whatsappUrl, '_blank');
                }
            }
        @endif

        function showNotification(message, type = 'success') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-4 py-3 rounded shadow-lg z-50 transform transition-all duration-300 ${
                type === 'success'
                    ? 'bg-green-500 text-white'
                    : 'bg-red-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()"
                            class="ml-3 text-white hover:text-gray-200">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            `;

            // Add to page
            document.body.appendChild(notification);

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => notification.remove(), 300);
                }
            }, 3000);
        }
    </script>

</body>

</html>
