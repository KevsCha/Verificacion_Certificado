document.getElementById("form").addEventListener("submit", function(e) {
    e.preventDefault();
    const formData = new FormData(this);

	fetch('./src/consulta.php',{
		method: 'POST',
		body: formData
	}).then(res => res.text())
	.then(data => {
		//!Change
		console.log(data);
	})
	.catch(err => {
		console.error(err);
	});
});