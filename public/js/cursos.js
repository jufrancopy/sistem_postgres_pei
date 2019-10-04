//codigo que trabaja con modulo materias
$("#ciclo_ids").change(event => {
	$.get(`selectcursos/${event.target.value}`, function(res, sta){
		$("#curso").empty();
		res.forEach(element => {
			$("#curso").append(`<option value=${element.id}> ${element.nombre} </option>`);
		});
	});
});

//codigo de prueba
$("#state").change(event => {
	$.get(`towns/${event.target.value}`, function(res, sta){
		$("#town").empty();
		res.forEach(element => {
			$("#town").append(`<option value=${element.id}> ${element.name} </option>`);
		});
	});
});


