const resp = document.getElementById("result_form");

document.getElementById("form").addEventListener("submit", function(e) {
	e.preventDefault();
    const formData = new FormData(this);
	
	fetch('./src/consulta.php',{
		method: 'POST',
		body: formData
	}).then(res => res.text())
	.then(data => {
		//!Change
		//console.log(data);
		console.log("RESPUESTA ENVIADA AL HTML DESDE PHP");

		console.log(resp);
		resp.innerHTML = data;
		resp.classList.add('visible');
		
	})
	.catch(err => {
		console.error(err);
	});
});