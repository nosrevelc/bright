    var qrcode_replace = document.getElementById('qrcode');

	var qrcode = new QRCode(qrcode_replace);

	var data = qrcodedata.home_url+'?superpwa-non-amp-install=true&non-amp=true';


 	qrcode.makeCode(data);

	function downloadQRCode(event){
		let qrcode_img = document.querySelectorAll('#qrcode img');
		let qrcode_src = qrcode_img[0].getAttribute('src');
		document.getElementById("download-qr").setAttribute('href',qrcode_src)
	}