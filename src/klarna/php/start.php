
<html>

<header>

<title> Klarna </title>

<script>

async function testit()
{
	const resp = await fetch(
	  `https://api.klarna.com/hpp/v1/sessions`,
	  {
		method: 'POST',
		headers: {
		  'Content-Type': 'application/json',
		  'User-Agent': 'string'
		},
		body: JSON.stringify({
		  merchant_urls: {
			back: 'https://example.com/back?sid=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx&hppId={{session_id}}',
			cancel: 'https://example.com/cancel?sid=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx&hppId={{session_id}}',
			error: 'https://example.com/error?sid=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx&hppId={{session_id}}',
			failure: 'https://example.com/fail?sid=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx&hppId={{session_id}}',
			status_update: 'https://example.com/status_update?sid=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx&secret=yyyyyyyy-yyyy-yyyy-yyyy-yyyyyyyyyyyy&hppId={{session_id}}',
			success: 'https://example.com/success?sid=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx&hppId={{session_id}}&token={{authorization_token}}'
		  },
		  options: {
			additional_checkboxes: [
			  {
				checked: false,
				id: 'string',
				required: false,
				text: 'string'
			  }
			],
			background_images: [
			  {url: 'string', width: 0}
			],
			logo_url: 'https://example.com/logo.jpg',
			page_title: 'Complete your purchase',
			payment_fallback: true,
			payment_method_categories: ['DIRECT_DEBIT'],
			payment_method_category: 'DIRECT_DEBIT',
			place_order_mode: 'PLACE_ORDER',
			purchase_type: 'BUY',
			show_subtotal_detail: 'HIDE'
		  },
		  payment_session_url: 'https://api.playground.klarna.com/checkout/v3/orders/92d97f60-7a78-46a5-8f68-c56fe52dc4af',
		  profile_id: '87ab3565-5e06-4006-9ada-8eedc6926703'
		})
	  }
	);

	const data = await resp.json();
	console.log(data);
	
	obj = document.getElementById("textdiv");
	obj.innerHTML = HtmlEncode(data.toString());

}

</script>
</header>

<body>

<button onclick="testit()" >
test it
</button><br>

<div id='textdiv'>
&nbsp; &nbsp; -- to be replaced --
</div>

</body>
</html>




