<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Sintegra;
use DB;
use Auth;

class SintegraRestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $sintegras = Sintegra::where('idusuario', '=', Auth::user()->id)
                        ->orderBy('id', 'desc')
                        ->get();

            return json_encode(['status' => 'success', 'message' => 'Dados retornados.', 'data' => $sintegras]);
        } catch (\Exception $e) {
            return json_encode(['status' => 'error', 'message' => 'Falha ao tentar listar dados.']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function create()
     {
         try {
             $cnpj = \Input::get('cnpj');

             if (!$cnpj) {
                   return json_encode([
                      'status' => 'error',
                      'message' => 'O campo CNPJ não pode estar vazio.'
                    ]);
             }

             if (strlen(preg_replace('/[0-9]/', '', $cnpj)) > 0) {
                 return json_encode([
                      'status' => 'error',
                      'message' => 'O campo CNPJ deve conter somente números.'
                  ]);
             }

             $resultado_json = $this->getJsonFromSintegraSite($cnpj);

             DB::table('sintegra')->insert([
                 'idusuario' => Auth::user()->id,
                 'cnpj' => $cnpj,
                 'resultado_json' => !is_null($resultado_json) && !empty($resultado_json) ? $resultado_json : "Não encontrado."
             ]);

             return json_encode([
                  'status' => 'success',
                  'message' => 'Dados obtidos.',
                  'data' => $resultado_json
              ]);

         } catch (\Exception $e) {
              return json_encode([
                  'status' => 'error',
                  'message' => 'Falha ao tentar buscar CNPJ.'
              ]);
         }
     }

     private function getJsonFromSintegraSite($cnpj)
     {
         $post = [
             'num_cnpj' => $cnpj,
             'botao' => 'Consultar'
         ];

         $userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36';
         $curl = curl_init ( 'http://www.sintegra.es.gov.br/resultado.php' );
         curl_setopt ( $curl, CURLOPT_HEADER, 0);
         curl_setopt ( $curl, CURLOPT_USERAGENT, $userAgent );
         curl_setopt ( $curl, CURLOPT_POST, true );
         curl_setopt ( $curl, CURLOPT_POSTFIELDS, (is_array ( $post ) ? http_build_query ( $post, '', '&' ) : $post) );
         curl_setopt ( $curl, CURLOPT_HEADER, 0 );
         curl_setopt ($curl, CURLOPT_ENCODING, "" );
         curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1 );
         $result = curl_exec ( $curl );
         curl_close($curl);

         if (!$result) {
            return json_encode(array());
         }

         $result = $this->htmlToArrayOfValues($result);

         $result = [
             'cnpj' => $result[3],
             'ie' => $result[5],
             'razao_social' => $result[7],
             'endereco' => "{$result[10]} nº {$result[12]} {$result[14]} {$result[16]} {$result[18]} - {$result[20]}",
             'cep' => $result[22],
             'telefone' => $result[24],
             'atividade_economica' => $result[27],
             'dt_inicio_atividade' => $result[29],
             'situacao_cadastral_vigente' => $result[31],
             'dt_situacao_cadastral' => $result[33],
             'regime_apuracao' => $result[35],
             'dt_inicio_emitente_nota_fiscal' => $result[37],
             'dt_obrigada_nota_fiscal' => $result[39]
         ];

         $result = json_encode($result);

         return $result;
      }

      public function destroy(Request $request)
      {
          try {
              $id = \Input::get('id');

              if (!$id) {
                  return json_encode(['status' => 'error', 'message' => 'É necessário indicar um id para excluir.']);
              }

              $sintegra = Sintegra::find($id);

              if (!$sintegra) {
                  return json_encode(['status' => 'error', 'message' => 'O registro especificado não existe.']);
              }

              if ($sintegra->idusuario != Auth::user()->id) {
                  return json_encode(['status' => 'error', 'message' => 'É necessário que o registro pertença à seu usuário para excluí-lo.']);
              }

              if (count($sintegra) > 0)
                  $success = $sintegra->delete();

              if ($success) {
                  return json_encode(['status' => 'success', 'message' => 'Registro Sintegra removido.']);
              }

              return json_encode(['status' => 'error', 'message' => 'Falha ao tentar remover registro.']);

          } catch (\Exception $e) {
              echo $e->getMessage(); echo $e->getLine(); die;
              return json_encode(['status' => 'error', 'message' => 'Falha ao tentar remover registro.']);
          }
      }

      private function htmlToArrayOfValues($html) {
          $dom = new \DOMDocument();
          $dom->loadHTML($html);
          $array = $this->getTableData($this->htmlToArray($dom));

          return $array;
      }

      private function getTableData(array $array)
      {
          $iterator  = new \RecursiveArrayIterator($array);
          $recursive = new \RecursiveIteratorIterator(
              $iterator,
              \RecursiveIteratorIterator::SELF_FIRST
          );
          foreach ($recursive as $key => $value) {
              if ($key === 'table') {
                  return $this->getValuesFromTableData($value);
              }
          }
      }

      private function getValuesFromTableData(array $array)
      {
          $iterator  = new \RecursiveArrayIterator($array);
          $recursive = new \RecursiveIteratorIterator(
              $iterator,
              \RecursiveIteratorIterator::SELF_FIRST
          );
          foreach ($recursive as $key => $value) {
              if ($key === '_value') {
                  $return[] = $value;
              }
          }
          return $return;
      }

      private function htmlToArray($root) {
          $result = array();

          if ($root->hasAttributes()) {
              $attrs = $root->attributes;
              foreach ($attrs as $attr) {
                  $result['attributes'][$attr->name] = $attr->value;
              }
          }

          if ($root->hasChildNodes()) {
              $children = $root->childNodes;
              if ($children->length == 1) {
                  $child = $children->item(0);
                  if ($child->nodeType == XML_TEXT_NODE) {
                      $result['_value'] = $child->nodeValue;
                      return count($result) == 1
                          ? $result['_value']
                          : $result;
                  }
              }
              $groups = array();
              foreach ($children as $child) {
                  if (!isset($result[$child->nodeName])) {
                      $result[$child->nodeName] = $this->htmlToArray($child);
                  } else {
                      if (!isset($groups[$child->nodeName])) {
                          $result[$child->nodeName] = array($result[$child->nodeName]);
                          $groups[$child->nodeName] = 1;
                      }
                      $result[$child->nodeName][] = $this->htmlToArray($child);
                  }
              }
          }

          return $result;
      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
