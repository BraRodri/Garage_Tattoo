

var arrayAtributos = [];
var combinaciones = [];
$('#losAttributos').change(function() {
    var val = $("#losAttributos option:selected").val();
    console.log(val);
    var idAtt = val.split('-');
    $.ajax(
        {
          url : 'http://127.0.0.1:8000/api/attribute-values/'  + idAtt[0],
          type: "GET"
        })
          .done(function(data) {
            console.log(data);
            let dataParse = '';
            for (let i = 0; i < data.length; i++) {
                const element = data[i];
                dataParse += `
                <input class="form-check-input" type="checkbox" value="${idAtt[1]+'-'+element.id+'-'+element.title}" id="${element.id}" name="checks[]">
                <label class="form-check-label" for="${element.id}">
                 ${element.title}
                </label>`;
            }
            document.getElementById("attributoEspec").innerHTML = `
            <h3>Selecciona los atributos</h3>
            <div class="form-check">
            ${dataParse}
            <br>
            <button id="btnSaveAtt" type="button" class="btn btn-info">Elegir atributos</button>
            </div>
            `;
            elementosSeleccionados();
          });
});
function elementosSeleccionados() {
    var arr;
    $('[name="checks[]"]').click(function() {
        
        arr = $('[name="checks[]"]:checked').map(function(){
          return this.value;
        }).get();
        
      });
      $('#btnSaveAtt').click(()=> {
        arrayAtributos.push(arr);
        document.getElementById("attributosSelect").innerHTML = '<h3>Atributos seleccionados</h3>';
        for (let i = 0; i < arrayAtributos.length; i++) {
            const element = arrayAtributos[i];
            document.getElementById("attributosSelect").innerHTML += `
            <span class="label label-success">${element}</span>
            <span> - </span>
            `;
        }
        imprimirCampos();
        $('#guardar').click(function() { 
          imprimirCombinacion(arrayAtributos,$('#sku1').val(),$('#price1').val(),$('#stock1').val());
         });
        $('#limpiar').click(()=> {
            limpiar();
        });
    });
}
function limpiar() {
  arrayAtributos = [];
  $('#sku').value = "";
  $('#price').value = "";
  $('#stock').value = "";
  imprimirCampos();
}
function imprimirCampos() {
    document.getElementById("attributosSelect").innerHTML += `
    <hr>
    <div class="form-group">
        <label class="control-label">SKU</label>
        <input type="number" class="form-control" name="sku1" id="sku1" required/>

    </div>

    <div class="form-group">
        <label class="control-label">Precio</label>
        <input type="number" class="form-control" name="price1" id="price1" required/>

    </div>

    <div class="form-group">
        <label class="control-label">Cantidad</label>
        <input type="number" class="form-control" name="stock1" id="stock1" required/>
    </div>
    <button id="guardar" type="button" class="btn btn-success">Generar Combinacion</button>
   `;
}
function imprimirCombinacion(att ,sku, price, stock) {
  combinaciones.push({att, sku, price, stock});
  var attString = arrayAtributos.join('//');
  arrayAtributos = [];
  var json = JSON.stringify(combinaciones);
  console.log(combinaciones);
    document.getElementById("listaCom").innerHTML += `
    <div class="row">
      <hr>
      <div class="col-sm-3">
      <label class="control-label">Atributos Combinados</label>
      <div class="card">
      <div class="card-body">
        ${attString}
        <input type="hidden" class="form-control" name="combinacion[]" id="attString" value="${attString}" />
      </div>
    </div>
      </div>
      <div class="col-sm-3">
      <label class="control-label">sku</label>
          <input type="number" class="form-control" name="combinacion[]" id="sku" value="${sku}" />
      </div>
      <div class="col-sm-3">
      <label class="control-label">price</label>
          <input type="number" class="form-control" name="combinacion[]" id="price" value="${price}" />
      </div>
      <div class="col-sm-3">
      <label class="control-label">Cantidad</label>
          <input type="number" class="form-control" name="combinacion[]" id="stock" value="${stock}" />
      </div>
      <hr>
    </div>
    
    `;
    
}
{/* <button id="limpiar" type="button" class="btn btn-primary">Limpiar</button> */}