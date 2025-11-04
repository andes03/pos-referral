<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Card - {{ $pelanggan->nama }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .card-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #ff9800 0%, #ff6b00 100%);
            padding: 25px 30px;
            color: white;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .profile-image {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            background: white;
        }
        .default-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .header-text h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
            font-weight: 600;
            letter-spacing: -0.3px;
        }
        .header-text p {
            margin: 0;
            font-size: 14px;
            opacity: 0.95;
            font-weight: 400;
        }
        .card-body {
            background: #fafafa;
            padding: 40px;
        }
        .card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        .info-section h3 {
            font-size: 11px;
            font-weight: 600;
            color: #9e9e9e;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin: 0 0 20px 0;
        }
        .info-item {
            margin-bottom: 20px;
        }
        .info-label {
            font-size: 11px;
            font-weight: 500;
            color: #9e9e9e;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
        }
        .info-value {
            font-size: 15px;
            color: #212121;
            font-weight: 500;
            line-height: 1.4;
        }
        .referral-code {
            background: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #212121;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 5px;
            border: 1px solid #e0e0e0;
            font-weight: 600;
        }
        .copy-icon {
            width: 16px;
            height: 16px;
            opacity: 0.5;
        }
        .card-footer {
            background: #fafafa;
            padding: 20px 40px;
            text-align: right;
        }
        .footer-text {
            font-size: 12px;
            color: #9e9e9e;
            margin: 0;
            font-weight: 400;
        }
        .card-number {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #212121;
            background: white;
            padding: 6px 10px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
            border: 1px solid #e0e0e0;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="card-container">
        <!-- Card Header -->
        <div class="card-header">
            <div>
                @if($pelanggan->image)
                    <img src="{{ public_path('storage/' . $pelanggan->image) }}" alt="Profile Picture" class="profile-image">
                @else
                    <div class="default-avatar">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="#ff9800">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="header-text">
                <h1>Sistem manajemen pelanggan dan referral sebelas caffee</h1>
                <p>Kartu Anggota</p>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <div class="card-grid">
                <!-- Left Side - Personal Info -->
                <div class="info-section">
                    <h3>Pemegang Kartu</h3>

                    <div class="info-item">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value">{{ $pelanggan->nama }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $pelanggan->email }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Telepon</div>
                        <div class="info-value">{{ $pelanggan->no_telp ?: 'Tidak disediakan' }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Alamat</div>
                        <div class="info-value">{{ $pelanggan->alamat ?: 'Tidak disediakan' }}</div>
                    </div>
                </div>

                <!-- Right Side - Card Details -->
                <div class="info-section">
                    <h3>Anggota Sejak</h3>

                    <div class="info-item">
                        <div class="info-value">{{ $pelanggan->created_at->format('F Y') }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Kode Referral</div>
                        <div class="referral-code">
                            {{ $pelanggan->kode_referal }}
                            <svg class="copy-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Nomor Kartu</div>
                        <div class="card-number">{{ strtoupper(substr(md5($pelanggan->id_pelanggan), 0, 16)) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Footer -->
        <div class="card-footer">
            <p class="footer-text">Anggota Sistem POS</p>
        </div>
    </div>
</body>
</html>