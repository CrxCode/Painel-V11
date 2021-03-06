 <?php

 if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__))
 {
   exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
 }

 ?>
 <!-- jQuery 2.2.3 -->
 <script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
 <!-- Bootstrap 3.3.6 -->
 <script src="../../bootstrap/js/bootstrap.min.js"></script>
 <!-- DataTables -->
 <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
 <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
 <script>
  $(document).ready(function(){
    $("#myInput").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#myTable tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
</script>
<div class="container-fluid">
  <!-- ============================================================== -->
  <!-- Bread crumb and right sidebar toggle -->
  <!-- ============================================================== -->
  <div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
      <h3 class="text-themecolor"><a href="home.php">SSH<b>PLUS</a></b></h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="home.php">Início</a></li>
        <li class="breadcrumb-item active">Canceladas</li>
      </ol>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="card card-outline-danger">
        <div class="card-header">
          <h4 class="m-b-0 text-white"><i class="fas fa-file-excel"></i> Faturas Canceladas</h4>
        </div>
        <div class="col-12"><br>
          <div class="form-responsive">
            <input type="text" id="myInput" placeholder="Pesquisar..." class="form-control">
          </div>
        </div>                                   
        <div class="table-responsive m-t-40">
          <table id="myTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID Fatura</th>
                <th>Tipo</th>
                <th>Status</th>
                <th>Cliente</th>
                <th>Data</th>
                <th>Vencimento</th>
                <th>Valor</th>
                <th>Informações</th>
              </tr>
            </thead>
            <tbody id="myTable">
              <?php

              $SQLUPUser= "SELECT * FROM fatura where status='cancelado' ORDER BY id desc ";
              $SQLUPUser = $conn->prepare($SQLUPUser);
              $SQLUPUser->execute();

					// output data of each row
              if (($SQLUPUser->rowCount()) > 0) {

               while($row = $SQLUPUser->fetch())


               {

                 switch($row['tipo']){
                   case 'vpn':$tipo='Acesso VPN';break;
                   case 'revenda':$tipo='Revenda';break;
                   default:$tipo='Outros';break;
                 }


                 $datacriado=$row['data'];
                 $dataconvcriado = substr($datacriado, 0, 10);
                 $partes = explode("-", $dataconvcriado);
                 $ano = $partes[0];
                 $mes = $partes[1];
                 $dia = $partes[2];

                 $vencimento=$row['datavencimento'];
                 $datavn = substr($vencimento, 0, 10);
                 $partesven = explode("-", $datavn);
                 $anov = $partesven[0];
                 $mesv = $partesven[1];
                 $diav = $partesven[2];

                 $valor=number_format(($row['valor'])*($row['qtd']),2);


                 switch($row['status']){
                   case 'pendente':$botao='<span class="label label-warning">Pendente</span>';break;
                   case 'cancelado':$botao='<span class="label label-danger">Cancelado</span>';break;
                   case 'pago':$botao='<span class="label label-success">Pago</span>';break;
                   default:$botao='Outros';break;
                 }

                 $Susuario= "SELECT * FROM usuario where id_usuario='".$row['usuario_id']."'";
                 $Susuario = $conn->prepare($Susuario);
                 $Susuario->execute();
                 $usuario=$Susuario->fetch();

                 ?>

                 <tr >
                   <td >#<?php echo $row['id'];?></td>
                   <td><?php echo $tipo;?></td>
                   <td><?php echo $botao;?></td>
                   <td><?php echo $usuario['nome'];?></td>
                   <td><?php echo $dia;?>/<?php echo $mes;?> - <?php echo $ano;?></td>
                   <td><?php echo $diav;?>/<?php echo $mesv;?> - <?php echo $anov;?></td>
                   <td>R$<?php echo number_format($valor, 2, ',', '.');?></td>
                   <td>
                    <a href="home.php?page=faturas/verfatura&id=<?php echo $row['id'];?>" class="btn btn-block btn-success">Visualizar</a>
                  </td>
                </tr>

              <?php } } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>