<?php
	include('config.php');

	$sqlCreate = 'INSERT INTO cadastro (nome, email) VALUES (:nome, :email)';
	$sqlRead = 'SELECT * FROM cadastro';
	$sqlUpdate = 'UPDATE cadastro SET nome = :nome, email = :email WHERE id = :id';
	$sqlDelete = 'DELETE FROM cadastro WHERE id = :id';
	$sqlSelect = 'SELECT * FROM cadastro WHERE id = :id';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>PDO</title>

		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			<header class="masthead">
				<h1 class="text-muted">C.R.U.D</h1>
				<nav class="navbar navbar-default" role="navigation">
					<div class="container-fluid">
						<a class="navbar-brand" href="index.php">PDO</a>
					</div>
				</nav>

				<?php
					if(isset($_POST['enviar'])){
						try {
							$create = $db->prepare($sqlCreate);
							$create->bindValue(':nome', $_POST['nome'], PDO::PARAM_STR);
							$create->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
							if ($create->execute()) {
								echo "<p class=\"alert alert-success\"> Inserido com sucesso! </p>";
							}
						} catch(PDOException $e){
							echo "<p class=\"alert alert-danger\"> Erro ao inserir dados! " . $e->errorInfo[2] . " </p>";
						}
					}

					#UPDATE
					if(isset($_POST['atualizar'])){
						try{
							$update = $db->prepare($sqlUpdate);
							$update->bindValue(':id', (int)$_GET['id'], PDO::PARAM_INT);
							$update->bindValue(':nome', $_POST['nome'], PDO::PARAM_STR);
							$update->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
							if($update->execute()) {
								echo "<p class=\"alert alert-success\"> Atualizado com sucesso! </p>";
							}
						}
						catch (PDOException $e){
							echo "<p class=\"alert alert-danger\"> Erro ao atualizar dados! " . $e->errorInfo[2] . " </p>";
						}
					}

					# DELETE
					if(isset($_GET['action']) && $_GET['action'] == 'delete'){
						try{
							$delete = $db->prepare($sqlDelete);
							$delete->bindValue(':id', (int)$_GET['id'], PDO::PARAM_INT);
							if($delete->execute()){
								echo "<p class=\"alert alert-success\"> Deletado com sucesso! </p>";
							}
						} catch (PDOException $e) {
							echo "<p class=\"alert alert-danger\"> Erro ao deletar dados! <br  /> Erro: " . $e->errorInfo[2] . " </p>";
						}
					}
				?>
			</header>

			<article>
				<section class="jumbotron">
					<?php
						if(isset($_GET['action']) && $_GET['action'] == 'update'){
							try{
								$select = $db->prepare($sqlSelect);
								$select->bindValue(':id', (int)$_GET['id'], PDO::PARAM_INT);
								$select->execute();
							} catch (PDOException $e){
								echo $e->errorInfo[2];
							}

							$result = $select->fetch(PDO::FETCH_OBJ);
					?>

						<ul class="breadcrumb">
							<li><a href="index.php">Página Inicial</a> <span class="divider"></span> </li>
							<li>Atualizar</li>
						</ul>

						<form method="post" action="">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<input type="text" name="nome" class="form-control" value="<?= $result->nome; ?>"/>
							</div>

							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
								<input type="text" name="email" class="form-control"  value="<?= $result->email; ?>" />
							</div>

							<input type="submit" class="btn-primary btn" value="Atualizar Dados" name="atualizar"/>
						</form>
					<?php } else { ?>
						<form method="post" action="">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<input type="text" name="nome" class="form-control" placeholder="Nome: "/>
							</div>

							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
								<input type="text" name="email" class="form-control" placeholder="E-mail: "/>
							</div>

							<input type="submit" class="btn-primary btn" value="Cadastrar Dados" name="enviar"/>
						</form>
					<?php } ?>
				</section>
			<article>

			<article>
				<section class="jumbotron">
					<?php
						try{
							$read = $db->prepare($sqlRead);
							$read->execute();
							$result = $read->fetchAll(PDO::FETCH_OBJ);
						} catch (PDOException $e){
							exit($e->errorInfo[2]);
						}
					?>
					<table class="table table-hover">
						<thead>
							<tr>
								<th> # </th>
								<th> Nome </th>
								<th> Ordem </th>
								<th> Data Nascimento </th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($result)): ?>
								<tr><td>Nenhum Registro Localizado</td></tr>
							<?php else: ?>
								<?php foreach ($result as $row) { ?>
									<tr>
										<td><?=$row->id;?></td>
										<td><?=$row->nome;?></td>
										<td><?=$row->ordem;?></td>
										<td><?=$row->dtnasc;?></td>
										<td>
											<div class="btn-group" role="group" aria-label="Editar ou Deletar Registro">
												<a href="index.php?action=update&id=<?= $row->id; ?>" class="btn btn-default" aria-label="Editar">
													<i aria-hidden="true" class="glyphicon-pencil glyphicon"></i>
												</a>
												<a href="index.php?action=delete&id=<?= $row->id; ?>" class="btn btn-danger" aria-label="Apagar" onclick="return confirm('Deseja deletar este registro?')">
													<i aria-hidden="true" class="glyphicon-remove glyphicon"></i>
												</a>
											</div>
										</td>
									</tr>
								<?php } ?>
							<?php endif ?>
						</tbody>
					</table>
				</section>
			</article>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
