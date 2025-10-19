<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>E-Ticket {{ $order->event->title }}</title>
  <style>
    /* Menggunakan font modern yang umum tersedia */
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      margin: 0;
      padding: 0;
      /* Menghilangkan padding body agar ticket-container bisa full di tengah */
      /* background-color: #f4f7f6; */
      color: #333;
      display: flex;
      /* Menggunakan flexbox untuk memposisikan tiket di tengah halaman */
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      /* Memastikan body mengambil seluruh tinggi viewport */
    }

    .ticket-container {
      width: 700px;
      /* Sedikit lebih lebar untuk logo dan spasi */
      height: 280px;
      /* Menyesuaikan tinggi */
      margin: 0 auto;
      /* Tengah secara horizontal, tapi flexbox di body sudah menangani ini */
      background-color: #ffffff;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      border-radius: 16px;
      border-top: 20px solid #2563eb;
      border: 1px solid #e5e7eb;
      display: table;
      table-layout: fixed;
      position: relative;
      /* Penting untuk penempatan logo di tengah */
      overflow: hidden;
      /* Memastikan tidak ada yang meluber keluar dari container */
    }

    .logo-watermark {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 60%;
      /* Ukuran logo */
      height: 60%;
      opacity: 0.05;
      /* Transparansi logo */
      /* GANTI DENGAN PATH LOGO ASLI ANDA */
      background-image: url('/img/Logo-PD.png');
      /* PATH RELATIF KE PUBLIC/IMAGE */
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center;
      z-index: 0;
      /* Pastikan di belakang konten */
    }

    .main-content {
      display: table-cell;
      width: 65%;
      padding: 25px 30px;
      vertical-align: top;
      position: relative;
      z-index: 1;
      /* Pastikan konten di depan logo */
    }

    .stub {
      display: table-cell;
      width: 35%;
      padding: 20px;
      text-align: center;
      vertical-align: middle;
      border-left: 2px dashed #cccccc;
      background-color: #f9fafb;
      border-bottom-right-radius: 15px;
      z-index: 1;
      /* Pastikan konten di depan logo */
    }

    .event-title {
      font-size: 28px;
      font-weight: bold;
      color: #111827;
      margin: 0 0 8px 0;
    }

    .attendee-name {
      font-size: 20px;
      font-weight: 600;
      color: #374151;
      margin: 0 0 30px 0;
    }

    .detail-item {
      margin-bottom: 20px;
      display: table;
      width: 100%;
    }

    .detail-icon {
      display: table-cell;
      width: 30px;
      vertical-align: top;
      padding-top: 2px;
    }

    .detail-text {
      display: table-cell;
      vertical-align: top;
    }

    .detail-item .label {
      font-size: 11px;
      font-weight: bold;
      text-transform: uppercase;
      color: #6b7280;
      letter-spacing: 0.5px;
      margin: 0 0 2px 0;
    }

    .detail-item .value {
      font-size: 16px;
      color: #1f2937;
      margin: 0;
    }

    .qr-code {
      margin-bottom: 12px;
    }

    .ticket-code {
      font-family: 'Courier New', Courier, monospace;
      font-weight: bold;
      font-size: 16px;
      letter-spacing: 1px;
      background-color: #e5e7eb;
      padding: 5px 10px;
      border-radius: 6px;
      display: inline-block;
      color: #1f2937;
    }

    .scan-text {
      font-size: 11px;
      margin-top: 8px;
      color: #6b7280;
    }

    .footer {
      position: absolute;
      bottom: 20px;
      left: 30px;
      font-size: 10px;
      color: #9ca3af;
    }
  </style>
</head>

<body>
  <div class="ticket-container">
    <div class="logo-watermark"></div>
    <div class="main-content">
      <h1 class="event-title">{{ $order->event->title }}</h1>
      <p class="attendee-name">{{ $order->customer_name }}</p>

      <div class="details-section">
        <div class="detail-item">
          <div class="detail-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
          </div>
          <div class="detail-text">
            <p class="label">Tanggal Acara</p>
            <p class="value">{{ $order->event->starts_at->format('l, d F Y') }}</p>
          </div>
        </div>
        <div class="detail-item">
          <div class="detail-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
          </div>
          <div class="detail-text">
            <p class="label">Lokasi</p>
            <p class="value">{{ $order->event->location }}</p>
          </div>
        </div>
      </div>

      <p class="footer">E-Tick PD &copy; {{ date('Y') }}</p>
    </div>

    <div class="stub">
      <div class="qr-code">
        <img src="data:image/svg+xml;base64,{!! base64_encode(QrCode::format('svg')->size(140)->generate($order->ticket_code)) !!}">
      </div>
      <p class="ticket-code">{{ $order->ticket_code }}</p>
      <p class="scan-text">Pindai kode ini untuk Check-in</p>
    </div>
  </div>
</body>

</html>
