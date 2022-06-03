<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name='robots' content='max-image-preview:large'>

<?php

//DEPENDENCIAS DO ARQUIVO
use \App\WebService\Correios\Rastreio;

//ITERA OS OBJETOS RETORNADOS
foreach ($response['objetos'] as $objeto) {
    //CÓDIGO DO OBJETO
    echo '<h2 class="mt-3">' .$objeto['codObjeto'].'</h2>';

    /*
    //INICIAR DEBUG
    echo "<pre>";
    print_r($objeto);
    echo "</pre>"; exit;
    */

    //VERIFICA OS EVENTOS DO OBJETO
    if(!isset($objeto['eventos'])){
        //MENSAGEM DE ERRO
        $mensagem = $objeto['mensagem'] ?? 'Problemas ao buscar
        dados da API dos Correios';

        //ALERTA NO HTML
        echo '<div class="alert alert-warning">
                '.$mensagem.'
             </div>';

        //PULA PARA O PROXIMO ÍNDICE
        continue;
    }

        //ITERA OS EVENTOS DO OBJETO
        foreach ($objeto['eventos'] as $evento) {

            //IMAGEM
            $imagem = isset($evento['urlIcone']) ?
           '<div style="width:40px;height:10px;">
                <img src="'.Rastreio::URL_BASE.$evento['urlIcone'].'" class="w-100">
            </div>' :
            '';

            //CIDADE
            $cidade = isset($evento['unidade']['endereco']['cidade']) ?
                      $evento['unidade']['endereco']['cidade'] .'/'.$evento['unidade']['endereco']['uf'] : null;

            //NOME DE ORIGEM
            $nome = $evento['unidade']['nome'] ?? null;

            //DADOS DESCRITIVOS DO EVENTO
            $dados = [
                //Converte em Negrito e MAIUSCULO!
                $replacementNegrito = '<b>'.strtoupper($evento['descricao']).'</b>',
                $cidade,
                $nome
            ];

            //HTML COMPLETO
            echo '<div class="alert alert-light d-flex align-items-center">
                    '.$imagem.'

                    <div style="flex:1;">
                        '.implode('<br>',array_filter($dados)).'
                    </div>

                    <div class="d-flex flex-column">
                        <div style="width:auto;">
                            <span class="badge bg-dark">
                                '.date('d/m/Y',strtotime($evento['dtHrCriado'])).'
                            </span>
                        </div>
                        
                        <div style="width:auto;">
                            <span class="badge bg-dark">
                                '.date('à\s H:i:s',strtotime($evento['dtHrCriado'])).'
                            </span>
                        </div>
                    </div>

                    
                </div>';
        }
}