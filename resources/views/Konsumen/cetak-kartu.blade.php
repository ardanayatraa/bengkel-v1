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
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .membership-card {
            width: 380px;
            height: 220px;
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
                    </div>
                    <div class="chip"></div>
                </div>

                <!-- Member Info -->
                <div>
                    <div class="text-xl font-semibold mt-2">{{ $konsumen->nama_konsumen }}</div>
                    <div class="text-blue-200 text-xs">ID: {{ $konsumen->id_konsumen }} </div>
                </div>

                <!-- Details -->
                <div class="text-sm mt-2 space-y-1">
                    <div class="flex items-center"><i class="fas fa-car text-blue-200 w-5"></i><span class="ml-1">
                            {{ $konsumen->no_kendaraan }} </span></div>
                    <div class="flex items-center"><i class="fas fa-phone text-blue-200 w-5"></i><span class="ml-1">
                            {{ $konsumen->no_telp }} </span></div>
                    <div class="flex items-center"><i class="fas fa-map-marker-alt text-blue-200 w-5"></i><span
                            class="ml-1"> {{ $konsumen->alamat }} </span></div>
                    <div class="flex items-center"><i class="fas fa-star text-blue-200 w-5"></i><span
                            class="ml-1">Points: {{ $konsumen->jumlah_point }} </span></div>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex flex-wrap justify-center gap-4 no-print">
            <button onclick="history.back()"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded shadow flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>

            <button onclick="window.print()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow flex items-center gap-2">
                <i class="fas fa-print"></i> Cetak
            </button>

            <button onclick="downloadAsImage()"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow flex items-center gap-2">
                <i class="fas fa-download"></i> Download Gambar
            </button>
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
                link.download = 'kartu-member.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
                document.body.removeChild(clone);
            });
        }
    </script>

</body>

</html>
