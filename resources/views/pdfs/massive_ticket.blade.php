<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Ticket de Evento</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f5f5f5;
			padding: 20px;
		}
		.ticket {
			background-color: #fff;
			padding: 20px;
			border-radius: 10px;
			box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.2);
			max-width: 500px;
			margin: auto;
		}
		h1 {
			font-size: 50px !important;
		}
		h1, h2, h3 {
			text-align: center;
		},
		h2 {
			color: red;
			font-size: 40px !important;
		},
		.event-info {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-top: 30px;
		}
		.event-info div {
			flex-grow: 1;
			text-align: center;
		}
		.qr-code {
			margin-top: 30px;
			text-align: center;
		}
		.logo {
			margin-top: 30px;
			text-align: center;
		}
        .logo > img {
            height: 150px;
		}
	</style>
</head>
<body>
	<div class="ticket">
        @foreach($qr_data as $qrCode)
            <div class="qr-code">
                <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code">
            </div>
        @endforeach
	</div>
</body>
</html>