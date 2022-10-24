<?php
$status = "";
$noupload = 0;
$path = "jpg/";
//$diretorio = dir($path);

if(isset($_GET['del'])){
    if(unlink($_GET['del'])){
        //$status = "<font color='green'>Arquivo <b>".$_GET['del']."</b> excluído com sucesso.</font>&nbsp;&nbsp;<button type='submit' id='noup'>Retomar Upload</button>";
        $status = "<font color='green'>Arquivo <b>".$_GET['del']."</b> excluído com sucesso.</font>&nbsp;&nbsp;<a href='managefiles.php'>Retomar Upload</a>";
        $noupload = 1;
    }
}

if(isset($_FILES['arquivo'])){
    $arquivo = $_FILES['arquivo'];
    //var_dump($arquivo);
    if($arquivo['error'] > 0)
        $status = "<font color='red'>Algo deu errado. Certifique-se que o arquivo não seja maior que 2MB.</font>";
    elseif($arquivo['size'] > 2097152)   //Caso o atributo "post_max_file_size" no arquivo "php.ini" esteja configurado com valor acima do padrão (2MB).
        $status = "<font color='red'>Arquivo <b>".$arquivo['name']."</b> é muito grande. Máximo de <b>2MB</b> permitido.</font>";
    else{
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        if($extensao != 'jpg')
            $status = "<font color='red'>Tipo de arquivo não permitido. Apenas formato JPG.</font>";
        else{
            $enviado = move_uploaded_file($arquivo['tmp_name'], $path.uniqid().".".$extensao);
            $status = "<font color='green'>Arquivo <b>".$arquivo['name']."</b> enviado com sucesso.</font>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="managefiles.css">
        <script src="managefiles.js"></script>
        <title>Upload de um arquivo JPG</title>
    </head>
    <body>
        <form name="form1" method="POST" enctype="multipart/form-data" action="">
            <label for="arq"><h1>Bem-vindo(a)</h1></label><p>
        <?php
        if($noupload == 0){?>
            <!--<input name="arquivo" type="file" id="arq" onchange="selecionado()"> DEPRECIADO-->
            <input name="arquivo" type="file" id="arq">
            <button name="btupload" type="submit" class="bt">Upload</button></p><?php
        }?>
            <p><?php echo $status;?></p>
        </form>
        <h1>Lista das Imagens</h1>
        <table border="1" cellpadding="10">
            <thead>
                <th>Foto</th>
                <th>Arquivo</th>
                <th>Enviado em</th>
                <th>Ação</th>
            </thead>
            <tbody>
                <?php
                    //while($img = $diretorio->read()){
                    foreach (new DirectoryIterator($path) as $img) {
                        if($img->isDot()) continue;
                        $dth_envio = date("d/m/Y H:i:s", $img->getMTime()); //Na a linha abaixo é feito o calculo de 3 horas retroativas para pegar o horário de Brasília (GMT-3)
                        $dth_envio = strftime('%d/%m/%Y %H:%M:%S', mktime(substr($dth_envio,-8,2)-3,substr($dth_envio,-5,2),substr($dth_envio,-2),substr($dth_envio,3,2),substr($dth_envio,0,2),substr($dth_envio,6,4)));
                        echo "
                        <tr>
                            <td><img height='50' src='".$path.$img."' alt=''></td>
                            <td><a href='".$path.$img."' target='_blank'>".$img."</a></td>
                            <td>".$dth_envio."</td>
                            <td><a href='managefiles.php?del=".$path.$img."'>Apagar</a></td>
                        </tr>";
                    }
                    //$diretorio->close();
                ?>
            </tbody>
        </table>
    </body>
</html>